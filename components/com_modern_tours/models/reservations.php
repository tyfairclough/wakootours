<?php
/**
 * @version     1.0.0
 * @package     com_modern_tours
 * @copyright   modernjoomla.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      modernjoomla.com <support@modernjoomla.com>
 */
defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');
include_once(JPATH_SITE . '/components/com_modern_tours/helpers/simple_html_dom.php');
include_once(JPATH_SITE . '/components/com_modern_tours/helpers/modern_tours.php');
include_once(JPATH_SITE . '/components/com_modern_tours/helpers/google.php');

/**
 * Methods supporting a list of modern_tours records.
 */
class modern_toursModelReservations extends JModelList
{

	/**
	 * Used for email, to show whole price {price}
	 * @var $totalPrice
	 */
	public $regex_matches = array('/{status}/' => 'status', '/{starttime([^}]*)}/' => 'starttime', '/{endtime([^}]*)}/' => 'endtime', '/{link([^}]*)}/' => 'link');
	public $priceFields = array('price', 'discount', 'childPrice', 'adultPrice', 'childrenPrice', 'adultsPrice');
	public $variables = array('price', 'unique_id', 'coupon', 'deposit', 'email', 'people', 'paid_deposit', 'assets_id', 'adults', 'children', 'travellersData', 'registered', 'date', 'userData', 'status', 'user_id', 'fields_id', 'endtime');
	public $excludedPrices = array(), $deposit, $dataParams = array(), $input, $view, $multiply, $slots, $client, $status, $table, $fullDiscount = false, $fields_id, $tax, $pdf_path, $pdf_file, $firstDay, $firstDayEndHour, $lastDay, $lastDayEndHour, $serviceTimes, $order, $assets_id, $html, $timeFormat = 'H:i', $save = true;

	public function __construct()
	{
		$this->parameters = MTHelper::getParams();
		$this->input = JFactory::getApplication()->input;
		$this->assets_id = $this->input->getInt('assets_id');
		$this->tax = $this->parameters->get('tax');
		$this->deposit = $this->input->getInt('deposit');
	}

	public function prepareDateFormats()
	{
		$parts = explode(' ', $this->dataParams['starttime']);
		$this->firstDay = $parts[0];
		$this->firstDayEndHour = $parts[1];

		$parts = explode(' ', $this->dataParams['endtime']);
		$this->lastDay = $parts[0];
		$this->lastDayEndHour = $parts[1];
	}

	public function getColumns()
	{
		$cols = JFactory::getDbo()->setQuery("SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE TABLE_NAME= '$this->table'")->loadRowList();

		$databaseCols = array_flip(array_map(function ($e) {
			return $e[0];
		}, $cols));

		return $databaseCols;
	}

	public function operators($type, $a, $b)
	{
		$operators = array("equal" => function ($a, $b) {
			return $a == $b;
		}, "not_equal" => function ($a, $b) {
			return $a != $b;
		}, "less" => function ($a, $b) {
			return $a < $b;
		}, "less_or_equal" => function ($a, $b) {
			return $a <= $b;
		}, "greater" => function ($a, $b) {
			return $a > $b;
		}, "greater_or_equal" => function ($a, $b) {
			return $a >= $b;
		}, "between" => function ($a, $b) {
			return $a + $b;
		}, "not_between" => function ($a, $b) {
			return $a + $b;
		});
		return $operators[$type]($a, $b);
	}

	/**
	 * Sets order to status
	 * @param $id
	 */
	public function setStatus($order_id, $status)
	{
		$item = JFactory::getDbo()->setQuery('SELECT * FROM #__modern_tours_reservations WHERE id = "' . $order_id . '"')->loadObject();

		if($item)
		{
			$item->status = $status;
			return JFactory::getDbo()->updateObject('#__modern_tours_reservations', $item, 'id');
		}
		else
		{
			MTHelper::sendError(JText::_('COULDNT_FIND_RESERVATION'));
		}

	}

	public function addExlcluded($element)
	{
		if($element->exclude) {
			$this->excludedPrices[] = $element->name;
		}
	}

	public function excludePrices($prices)
	{
		$price = 0;

		foreach($this->excludedPrices as $name)
		{
			$price += $prices[$name];
		}

		return [$price, $price * $this->multiply];
	}

	function discount($price = 0, $onlyDiscount = false)
	{
		$discounts = json_decode(JFactory::getDbo()->setQuery('SELECT discount FROM #__modern_tours_times WHERE id = ' . $this->timecat)->loadResult(), true);

		if($discounts) {
			foreach ($discounts as $discount) {
				foreach ($discount['rules'] as $disc) {
					$type = $disc['field'];

					if ($type == 'minutes') {
						$first_date = explode(',', $this->dataParams['starttime']);
						$amount = $this->toMinutes($first_date[0], $this->dataParams['end_time']);
					} elseif ($type == 'days') {
						$amount = count(explode(',', $this->dataParams['starttime']));
						krsort($discounts);
					} elseif ($type == 'price') {
						$amount = $price;
					}

					if ($this->operators($disc['operator'], $amount, $disc['value'])) {
						$price = self::calcType($price, $disc['amount'], $disc['discount'], $onlyDiscount);
					}
				}
			}
		}

		return $price;
	}

	/**
	 * Apply discount to total sum
	 * @param $total
	 * @return float
	 */
	public function applyCoupon($total)
	{
		$code = JFactory::getApplication()->input->get('coupon');
		$db = JFactory::getDbo();

		if ($code) {
			$item = JFactory::getDbo()->setQuery('SELECT pricepercent, pricetype FROM #__modern_tours_coupons WHERE code = ' . $db->quote($code) . ' AND couponsnumber > 0 AND CURDATE() between start and end')->loadObject();
			if ($item) {
				$total = self::calcType($total, $item->pricepercent, $item->pricetype, false);
				// SET RESERVATION TO PAID IF 100% DISCOUNT
				if($item->pricepercent == 100) {
					$this->dataParams['paid'] = 'paid';
					$this->fullDiscount = true;
				}
			}
		}

		return ($total <= 0 ? 0 : $total);
	}

	public function calcType($total, $value, $type, $onlyDiscount = true)
	{
		if ($onlyDiscount == true) {
			if ($type == 'percentage') {
				$total = $total * ($value / 100);
			} else {
				$total = $value;
			}
		} else {
			if ($type == 'percentage') {
				$total = $total * (100 - $value) / 100;
			} else {
				$total = $total - $value;
			}
		}

		return $total;
	}

	/**
	 * Adds user timezone (hour) offset to reservation date
	 * @param $date
	 * @return int
	 */
	protected function fixTimezone($date)
	{
		if (strpos($date, '-')) {
			$date = strtotime($date);
		}

		return $date;
	}

	public function getPrice() {
		return $this->dataParams['price'];
	}

	private function setDates()
	{
		if($this->view == 'calendarday') {
			$multiplyTimes = explode(',', $this->dataParams['start']);
			$this->dataParams['end_time'] = end($multiplyTimes);
			$this->dataParams['first_day'] = $multiplyTimes[0];
		}
	}

	public function toJSON($array, $name, $html = '') {
		$html = '<div class="sect-name">' . ucfirst($name) . '</div>';
		if($array[$name]) {
			foreach($array[$name] as $value) {
				$html .= '<div class="list">Name: ' . $value['name'] . ' Surname: ' . $value['surname'] . '</div>';
			}
		}
		return $html;
	}

	public function fieldsToHTML()
	{
		$input = JFactory::getApplication()->input->getArray();
		$travellers = $this->toJSON($input, 'travellers');
		return $travellers;
	}

	public function createOrder()
	{
		$order = JFactory::getApplication()->input->getArray();
		return $order;
	}

	public function toArray($fields)
	{
		foreach(json_decode($this->dataParams[$fields]) as $label => $value)
		{
			$this->dataParams[$fields . '_' . $label] = $value;
		}
	}

	/**
	 * Generates single or multiple objects to be saved to database
	 * @param array $orderDetails
	 * @param int $i
	 * @return mixed
	 */

	public function prepareParams( $order = array() )
	{
		$user = JFactory::getUser();
		$this->order = $order;
		$this->dataParams = $this->order ? (array) $this->order : $this->createOrder();
		$this->assets_id = $this->assets_id ? $this->assets_id : $this->order->assets_id;
		$this->serviceTimes = $this->getServicesTimes();
		$orderID = $this->order ? $this->order->id : self::lastId() + 1;
		$tourID = $this->assets_id ? $this->assets_id : $this->order->assets_id;

		$this->prepareDateFormats();
		$this->prepareUserFields($tourID);

		if(!isset($this->dataParams['email']))
		{
			$this->dataParams['email'] = MTHelper::getUserEmail('', $tourID);
		}

		if(!isset($this->dataParams['starttime']))
		{
			$this->dataParams['starttime'] = $this->dataParams['date'];
		}

		$this->dataParams['pdf_date'] = date('Y/m/d');
		$this->dataParams['status'] = $this->parameters->get('status');
		$this->dataParams['unique_id'] = MTHelper::generateRandomString();
		$this->dataParams['user_id'] = $user->id ? $user->id : '';
		$this->dataParams['fields_id'] = MTHelper::getComponentParams('user_data_fields');
		$this->dataParams['usergroup'] = $user->id ? implode(',', $user->groups) : 0;
        $this->dataParams['invoice_number'] = str_pad($orderID, 4, '0', STR_PAD_LEFT);
		$this->dataParams['tour'] = $this->order ? MTHelper::getTour($this->order->assets_id) : MTHelper::getTour($tourID);
		$this->dataParams['userData'] = !$this->order ? json_encode($this->getUserData($tourID)) : $this->dataParams['userData'];
		$this->dataParams['userDataText'] = $this->toText(json_decode($this->dataParams['userData']));
		$this->dataParams['travellersData'] = is_array($this->dataParams['travellersData']) ? MTHelper::jsonToText(json_encode($this->dataParams['travellersData'])) : $this->dataParams['travellersData'];
		$this->dataParams['registered'] = date('Y-m-d H:i:s');
		$this->dataParams['discount'] = '0.00';
		$this->dataParams['date'] = $this->dataParams['starttime'];
		$this->dataParams['deposit'] = $this->deposit;

		$this->setParticipantsPrices();
		$this->addVAT();
		$this->onlyVAT();
		$this->addFormats();

		$this->toArray('userData');

        self::debug();

		return $this->dataParams;
	}

	public function prepareUserFields($id)
	{
		$fields = $this->order ? json_decode($this->dataParams['userData']) : (object) $this->getUserData($id);

		foreach($fields as $field => $value)
		{
			$this->dataParams[$field] = $value;
		}
	}

	public function toText($userData)
	{
		$info = '';
		foreach($userData as $field => $value)
		{
			$info .= '<div class="info-line"><span class="high-text">' . MTHelper::convertToLabel($field) . ':</span> ' . $value . '</div>';
		}
		return $info;
	}

	public function getUserData($id)
	{
		$values = array();
		$fields = MTHelper::getUserFields($id);
		$input = JFactory::getApplication()->input;

		foreach($fields as $field) {
			$values[$field] = $input->getVar($field);
		}

		return $values;
	}

	public function saveToDB()
	{
		$object = (object) array_intersect_key($this->dataParams, array_flip($this->variables));
		
		$db = JFactory::getDbo();
		$db->insertObject('#__modern_tours_reservations', $object);

		return $db->insertid();
	}

	public function toMinutes($d1, $d2)
	{
		return abs(strtotime($d1) - strtotime($d2)) / 60;
	}

	public function generatePDF()
	{
		require_once JPATH_LIBRARIES . '/mpdf/mpdf.php';
		self::setInvoice();
		$mpdf = new mPDF('UTF-8');
		$mpdf->SetHTMLFooter('<div class="term">' . JText::_('PDF_FOOTER_TEXT') . '</div>');
		$mpdf->WriteHTML($this->html);
		$this->pdf_file = 'invoice' . $this->dataParams['invoice_number'] . '.pdf';
		$this->pdf_path = JPATH_SITE . '/components/com_modern_tours/invoice/' . $this->pdf_file;
		$mpdf->Output($this->pdf_path, 'F');
	}

	public function getTemplate()
	{
		return JFactory::getDbo()->setQuery('SELECT template FROM #__modern_tours_invoice WHERE id = 9999')->loadResult();
	}

	private function setInvoice()
	{
		$invoice = self::invoiceTemplate();

		if($invoice)
		{
			$this->html = $this->setMessage($invoice);
		}
	}

	private function invoiceTemplate()
	{
		if($this->fields_id)
		{
			$invoiceTemplate = JFactory::getDbo()->setQuery('SELECT template FROM #__modern_tours_invoice WHERE id = ' . $this->fields_id)->loadResult();
			if($invoiceTemplate)
			{
				$css_path = 'media/com_modern_tours/css/invoice.css';
				return '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>Invoice</title><link rel="stylesheet" href="' . $css_path . '" media="all" /></head><body>' . $invoiceTemplate . '</body></html>';
			}
		}

	}

	/**
	 * Generates message from user input, replaces {} brackets with order data
	 * @param bool|FALSE $admin
	 * @return string
	 */
	public function setMessage($message, $params = '')
	{
		$lang = JFactory::getLanguage()->getlocale();
		setlocale(LC_ALL, $lang[1]);

		foreach ($this->regex_matches as $match => $string) {
			preg_match_all($match, $message, $matches);
			if ($matches) {
				foreach ($matches[0] as $i => $val) {
					preg_match_all('/\[(.*?)\]/', $val, $date);
					$val = str_replace("[", "\[", $val);
					$val = str_replace("]", "\]", $val);
					$replace[] = '/' . $val . '/';
					$with[] = self::filterString($string, $date);
				}
			}
		}

		if(!$params) { $params = $this->dataParams; }

		foreach ($params as $key => $param) {
			$replace[] = '/{' . $key . '}/';
			$with[] = $param;
		}

		foreach ($with as $i => $val) {
			if (is_array($val)) {
				unset($with[$i]);
				unset($replace[$i]);
			}
		}


		$message = preg_replace($replace, $with, $message);

		return $message;
	}

	public function generateLink($order_id, $data)
	{
		$input = JFactory::getApplication()->input;
		$Itemid = $input->get('Itemid');

		$parts = explode('_', $data);
		$link = $parts[0];
		$status = self::getStatus($parts[1]);

		if(!$status)
		{
			echo 'Such status do not exist. You provided not existing status in email {link} variable. Possible statuses are: paid, canceled, waiting, reserved. Status example code {link[click here_paid]}';
			JFactory::getApplication()->close();
		}

		$url = JURI::base() . 'index.php?option=com_modern_tours&Itemid=' . $Itemid . '&task=regstate&reservation=' . $order_id . '_' . $status;

		return '<a target="_blank" href="' . $url . '">' . $link . '</a>';
	}

	public function getStatus($status)
	{
		$statuses = array('J73Xcb2' => 'paid', 'KI9CJ1x' => 'canceled', 'J0IJFDRCv' => 'reserved', '1O5rEvB' => 'waiting');
		$status_code = array_search($status, $statuses);

		if($status_code)
		{
			return $status_code;
		}

		if(array_key_exists($status, $statuses))
		{
			return $statuses[$status];
		}

		return false;
	}

	/**
	 * Last inserted ID to form table
	 * @return mixed
	 */
	public function lastId()
	{
		return JFactory::getDbo()->setQuery('SELECT id FROM #__modern_tours_reservations ORDER by id DESC')->loadResult();
	}

	/**
	 * Send email
	 * @param $body
	 * @param null $recipient
	 * @return true/false
	 * @throws Exception
	 */
	public function sendEmail($body, $recipient, $status = 'reserved')
	{
		if($body) {
			$input = JFactory::getApplication()->input;
			$payment = $input->get('payment', 'cash');
			$assets_id = $input->get('assets_id', $this->assets_id);
			$mailer = JFactory::getMailer();
			$mainframe = JFactory::getApplication();
			$sender = array($mainframe->getCfg('mailfrom'), $mainframe->getCfg('fromname'));
			$replyTo = MTHelper::getUserEmail(array(), $assets_id);
			$mailer->addReplyTo($replyTo);
			$mailer->setSender($sender);
			$mailer->addRecipient($recipient);
			$mailer->setSubject(JText::_($status . '_EMAIL_SUBJECT'));
			$mailer->isHTML(true);

			if(($payment == 'cash' && MTHelper::getComponentParams('send_pdf_cash')) || ($payment != 'cash' && MTHelper::getComponentParams('send_pdf'))) {
				$invoiceName = 'invoice' . $this->dataParams['invoice_number'];
				$mailer->addAttachment(JPATH_COMPONENT . '/invoice/' . $invoiceName . '.pdf');
			}

			$mailer->setBody($body);

			return $mailer->Send();
		}
	}


	/**
	 * Send email
	 * @param $body
	 * @param null $recipient
	 * @return true/false
	 * @throws Exception
	 */
	public function enquiryEmail($body, $recipient)
	{
		$mailer = JFactory::getMailer();
		$mainframe = JFactory::getApplication();
		$sender = array($mainframe->getCfg('mailfrom'), $mainframe->getCfg('fromname'));
		$mailer->addReplyTo($recipient);
		$mailer->setSender($sender);
		$mailer->addRecipient($recipient);
		$mailer->setSubject(JText::_('ENQUIRY_EMAIL_SUBJECT'));
		$mailer->isHTML(true);
		$mailer->setBody($body);
		return $mailer->Send();
	}

	private function filterString($string, $date, $n = 2)
	{
		switch ($string) {
			case 'registered':
				return strftime($date[1][0], strtotime($this->dataParams['registered']));
				break;
			case 'starttime':
				return strftime($date[1][0], strtotime($this->dataParams['starttime']));
				break;
			case 'endtime':
				return strftime($date[1][0], strtotime($this->dataParams['endtime']));
				break;
			case 'start':
				return strftime($date[1][0], strtotime($this->dataParams['starttime']));
				break;
			case 'end':
				return strftime($date[1][0], strtotime($this->dataParams['endtime']));
				break;
			case 'link':
				return self::generateLink($this->dataParams['id'], $date[1][0]);
				break;
			case 'status':
				return JText::_($this->dataParams['status']);
				break;
		}
	}

	public function calculatePrice($times = array(), $total = false, $prices = '')
	{
		$timeArray = explode(',', $times);
		foreach ($timeArray as $time) {
			$prices = $prices + $this->generateCost($this->fixTimezone($time));
		}

		if($total) {
			$prices = $prices  * $this->multiply;
		}

		return $prices;
	}

	public function getServicesTimes()
	{
		$options = JFactory::getDbo()->setQuery('SELECT times FROM #__modern_tours_assets WHERE id = ' . $this->assets_id)->loadResult();
		return json_decode($options, true);
	}

	public function setParticipantsPrices()
	{
		$serviceObject = $this->getHourObject($this->dataParams['starttime']);

		$this->dataParams['childPrice'] = $this->applyCoupon($serviceObject['child-price']);
		$this->dataParams['adultPrice'] = $this->applyCoupon($serviceObject['adult-price']);

		$this->dataParams['childrenPrice'] = $this->dataParams['childPrice'] * $this->dataParams['children'];
		$this->dataParams['adultsPrice'] = $this->dataParams['adultPrice'] * $this->dataParams['adults'];
		$this->dataParams['price'] = $this->dataParams['childrenPrice'] + $this->dataParams['adultsPrice'];
	}

	public function countParticipants()
	{
		$adults = $this->input->getInt('quantity');
		$children = $this->input->getInt('children');

		return $adults * $children;
	}

	/**
	 * @param $date
	 *
	 * @return object()
	 */
	public function getHourObject($date)
	{
		$date = strtotime($date);
		$dayofweek = date('N', $date);
		$dateFormat = date('Y-m-d', $date);
		$hour = date('Hi', $date);
		return isset($this->serviceTimes[$dateFormat]) ? $this->serviceTimes[$dateFormat][$hour] : $this->serviceTimes[$dayofweek][$hour];
	}

	private function distractCoupon($coupon)
	{
		$db = JFactory::getDbo();

		$count = JFactory::getDbo()->setQuery('SELECT couponsnumber FROM #__modern_tours_coupons WHERE code = ' . $db->quote($coupon))->loadResult();

		if ($count) {
			$object = new stdClass();
			$object->code = $coupon;
			$object->couponsnumber = $count - 1;
			JFactory::getDbo()->updateObject('#__modern_tours_coupons', $object, 'code');
		}
	}

	private function getCurrency()
	{
		return JFactory::getDbo()->setQuery('SELECT currency FROM #__modern_tours_invoice WHERE id = ' . $this->cat)->loadResult();
	}


	public function addFormats()
	{
		foreach($this->priceFields as $field) {
			$this->dataParams[$field] = number_format($this->dataParams[$field], 2);
		}
	}

	public function modifyPrice($int)
	{
		foreach($this->priceFields as $field) {
			$this->dataParams[$field] = number_format($this->dataParams[$field] / $int, 2);
		}
	}

	public function addVAT()
	{
		foreach($this->priceFields as $field) {
			$this->dataParams['vat_' . $field] = number_format($this->dataParams[$field] * (100-$this->tax)/100, 2);
		}
	}

	public function onlyVAT()
	{
		foreach($this->priceFields as $field) {
			$this->dataParams['only_vat_' . $field] = number_format($this->dataParams[$field] * ($this->tax/100), 2);
		}
	}

	public function getStartDate()
	{
		$dateParts = explode(' ', $this->dataParams['start']);
		return $dateParts[0];
	}

	public function getEmailParameters()
	{
		if($this->fields_id)
		{
			$emailParams = JFactory::getDbo()->setQuery('SELECT params FROM #__modern_tours_emails WHERE id = ' . $this->fields_id)->loadResult();
			return json_decode($emailParams);
		}
	}

	public function parseEmail($email)
	{
		$multiple = str_replace(' ', '', $email);
		$email = explode(',', $multiple);
		return $email;
	}

	public function debug()
	{
		$debug = JFactory::getApplication()->input->get('debug');

		if($debug)
		{
			$this->dataParams = $this->createOrder();
			echo '<pre>';
			print_r($this->dataParams);
			echo '</pre>';
			exit();
		}
	}
}