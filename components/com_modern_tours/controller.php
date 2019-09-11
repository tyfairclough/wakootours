<?php
/**
 * @version     1.0.0
 * @package     com_modern_tours
 * @copyright   modernjoomla.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      modernjoomla.com <support@modernjoomla.com>
 */
// No direct access
defined('_JEXEC') or die;
jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT . '/helpers/modern_tours.php';
require_once JPATH_COMPONENT . '/helpers/maintenance.php';
include_once JPATH_COMPONENT . '/helpers/google.php';

/**
 * Class Modern_toursController
 */
class Modern_toursController extends JControllerLegacy
{

	public $params, $view;

	/**
	 * Method to display a view.
	 * @param    boolean $cachable If true, the view output will be cached
	 * @param    array $urlparams An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 * @return    JController        This object to support chaining.
	 * @since    1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		$this->view = JFactory::getApplication()->input->getCmd('view', 'fields');
		JFactory::getApplication()->input->set('view', $this->view);

		Maintenance::displayTest();

		if(!$this->existUserFields()) {
//			MTHelper::sendError(JText::_('PLEASE_CREATE_USER_FIELDS'));
//			exit();
		}

		if(self::checkLibraries()) {
			parent::display($cachable, $urlparams);
		}

		return $this;
	}

	public function checkLibraries()
	{
		if(JFactory::getApplication()->input->get('view') != 'forms') {
			$libraries = array(
				'send_pdf' => array(
					'url' => '/mpdf/mpdf.php',
					'message' => 'mPDF library is missing. Please download it from <a href="https://www.modernjoomla.com/login">https://www.modernjoomla.com/login</a> or disable Send PDF invoice parameter.'
				),
				'pdf' => array(
					'url' => '/mpdf/mpdf.php',
					'message' => 'mPDF library is missing. Please download it from <a href="https://www.modernjoomla.com/login">https://www.modernjoomla.com/login</a> or disable Send PDF invoice parameter.'
				),
				'google_events' => array(
					'url' => '/google/vendor/autoload.php',
					'message' => ' Google calendar library is missing. Please download it from <a href="https://www.modernjoomla.com/login">https://www.modernjoomla.com/login</a> or disable `Google calendar save` parameter.'
				),
			);

			foreach ($libraries as $library => $details) {
				if (MTHelper::getComponentParams($library)) {
					if (!file_exists(JPATH_LIBRARIES . $details['url'])) {
						JFactory::getApplication()->enqueueMessage($details['message'], 'error');
						return false;
					}
				}
			}
		}

		return true;
	}

	public function existUserFields()
	{
		$userFields = MTHelper::getComponentParams('user_data_fields');

		if($userFields)
		{
			return true;
		}
	}

	/**
	 * Saves user reservation information and sends email to admin & user
	 * @throws Exception
	 */
	public function saveReservation()
	{
		$model = JModelList::getInstance('Reservations', 'Modern_toursModel');
		$input = JFactory::getApplication()->input;
		$params = MTHelper::getParams();

		$payment = $input->get('payment', 'cash');
		$spam = $input->get('emailmessages');

		if(!$spam) {

			$model->prepareParams();
			$model->fields_id = $params->get('user_data_fields');
			$model->dataParams['status'] = $params->get('status'); //($payment == 'cash' ? $params->get('cash_status') : $model->dataParams['status']);
			$model->user_id = JFactory::getUser()->id;

			if($params->get('send_pdf') && $payment != 'cash' || $params->get('send_pdf_cash') && $payment == 'cash') {
				$model->generatePDF();
			}

			$userEmail = $model->dataParams['email'];
			$adminEmail = $model->parseEmail($params->get('admin_email'));

			$emailParams = $model->getEmailParameters();
			$userMessageTemplate = $emailParams->{'user_'.$model->dataParams['status'].'_message'};
			$adminMessageTemplate = $emailParams->{'admin_'.$model->dataParams['status'].'_message'};

			$userMessage = $model->setMessage($userMessageTemplate);
			$adminMessage = $model->setMessage($adminMessageTemplate);

			$model->sendEmail($userMessage, $userEmail, $model->dataParams['status']);
			$model->sendEmail($adminMessage, $adminEmail, $model->dataParams['status']);
			$order_id = $model->saveToDB();

			$details = array('payment' => $payment, 'table' => MTHelper::getTable(), 'order_id' => $order_id);

			$payment = self::getPayment($details);

			if(json_decode($payment)->action != 'noAjax') {
				ob_clean();

				echo $payment;

				JFactory::getApplication()->close();
			}
		}
	}

	/**
	 * Checks if coupon exists
	 * @throws Exception
	 */
	public function checkcoupon()
	{
		header('Content-Type: application/json');
		$code = JFactory::getApplication()->input->getVar('code');
		$db = JFactory::getDbo();

		$success = JText::_('SUCCESS_COUPON');
		$fail = JText::_('FAIL_COUPON');

		$item = $db->setQuery('SELECT pricepercent, pricetype FROM #__modern_tours_coupons WHERE code = ' . $db->quote($code) . ' AND couponsnumber > 0 AND CURDATE() between start and end')->loadObject();

		echo ($item ? new JResponseJson(array('type' => $item->pricetype, 'price' => $item->pricepercent), '<span class="green notif">' . $success . '</span>') : new JResponseJson('', '<span class="red notif">' . $fail . '</span>', true));

		JFactory::getApplication()->close();
	}

	public function getPayment($details)
	{
		$details = json_encode($details);
		JPluginHelper::importPlugin('tourpayment');
		$dispatcher = JEventDispatcher::getInstance();

		$event = $dispatcher->trigger('displayForm', $details);
		$action = $dispatcher->trigger('getAction', $details);

		$content = ($event[0] ? $event[0] : $event[1]);

		return json_encode(array('action' => $action[0], 'content' => $content));
	}

	public function regstate()
	{
		$model = JModelList::getInstance('Reservations', 'Modern_toursModel');
		$input = JFactory::getApplication()->input;
		$data = $input->get('reservation');

		// reservation consists of 2 parts, first contains order_id, second passphrase of status code ($statuses)
		$parts = explode('_', $data);
		$status = $model->getStatus($parts[1]);

		if(count($parts) != 2 OR !$status)
		{
			$this->miniView(MTHelper::getParams()->email_confirm_error);
		}

		$order_id = $parts[0];

		if($model->setStatus($order_id, $status))
		{
			$this->miniView(MTHelper::getParams()->email_confirm_success);
		}
		else
		{
			$this->miniView(MTHelper::getParams()->email_confirm_error);
		}
	}

	public function miniView($text)
	{
		echo '<script>setTimeout(function () { location.href = "' . JURI::base() . '"; }, 3000);</script><div style="text-align: center; margin: auto; top: 0; left: 0; right: 0; bottom: 0; position: fixed; width: 100%; height: 77px; font-size: 28px; font-family: Verdana;">' . $text . '<a href="' . JURI::base() . '" style="display: block; font-size: 11px; text-decoration: none; line-height: 26px; color: blue;">' . MTHelper::getParams()->email_redirect_text . '</a></div>';

		JFactory::getApplication()->close();
	}

	public function verifier()
	{
		GoogleCalendar::getToken();
	}

	public function runTest()
	{
//		Maintenance::runTest();
	}

	public function getInvoice()
	{
		$id = JFactory::getApplication()->input->get('id');
		$model = JModelList::getInstance('Reservations', 'Modern_toursModel');
		$order = MTHelper::getOrder($id);
		$model->prepareParams($order);
		$model->fields_id = $order->fields_id;
		$model->generatePDF();
		$this->readPDF($model->pdf_file, $model->pdf_path);
		header('Content-Type: application/pdf');
		header('Content-disposition: attachment;filename=' . $model->pdf_file);
		readfile(JURI::base() . 'components/com_modern_tours/invoice/invoice0001.pdf');
	}

	public function readPDF($file, $path)
	{
		$fp = fopen($path, "r") ;

		header("Cache-Control: maxage=1");
		header("Pragma: public");
		header("Content-type: application/pdf");
		header("Content-Disposition: inline; filename=".$file."");
		header("Content-Description: PHP Generated Data");
		header("Content-Transfer-Encoding: binary");
		header('Content-Length:' . filesize($path));
		ob_clean();
		flush();
		while (!feof($fp)) {
			$buff = fread($fp, 1024);
			print $buff;
		}
		exit;
	}

	public function cancelBooking()
	{
		$id = JFactory::getApplication()->input->get('id');
		MTHelper::checkUser(MTHelper::getOrder($id)->user_id);
		$model = JModelList::getInstance('Reservations', 'Modern_toursModel');
		echo $model->setStatus($id, 'canceled');
		exit();
	}


	public function saveReview()
	{
		$user_id = JFactory::getUser()->id;
		$input = JFactory::getApplication()->input;
		$title = $input->getVar('title');
		$userReview = $input->getVar('review');
		$rating = $input->get('rating');
		$asset_id = $input->get('assets_id');

		if($title && $userReview && $rating)
		{
			$review = new stdClass();
			$review->title = $title;
			$review->review = $userReview;
			$review->rating = $rating;
			$review->user_id = $user_id;
			$review->assets_id = $asset_id;
			$review->state = MTHelper::getComponentParams('review_status');
			$review->date = date('Y-m-d H:i');
			JFactory::getDbo()->insertObject('#__modern_tours_reviews', $review);
		}
	}

	public function leaveReview()
	{
		$guest = JFactory::getUser()->get('guest');
		$guestCanLeaveReview = MTHelper::getComponentParams('guest_reviews');

		if($guest && !$guestCanLeaveReview)
		{
			include (JPATH_COMPONENT . '/views/leavereview/tmpl/default_login.php');
		}
		else
		{
			$id = JFactory::getApplication()->input->get('id');
			$this->item = JFactory::getDbo()->setQuery('SELECT id, title FROM #__modern_tours_assets WHERE id = ' . $id)->loadObject();

			include (JPATH_COMPONENT . '/views/leavereview/tmpl/default.php');
		}

	}

	public function deleteReview()
	{
		$id = JFactory::getApplication()->input->getInt('id');
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);

		$conditions = array(
			$db->quoteName('user_id') . ' = ' . JFactory::getUser()->id,
			$db->quoteName('id') . ' = ' . $db->quote($id)
		);

		$query->delete($db->quoteName('#__modern_tours_reviews'));
		$query->where($conditions);

		$db->setQuery($query);

		return $db->execute();
	}

	public function enquiryEmail()
	{
		$id = JFactory::getApplication()->input->getInt('id');
		$userEmail = MTHelper::getContactFormEmail('', $id);

		$model = JModelList::getInstance('Reservations', 'Modern_toursModel');
		$model->dataParams = JFactory::getApplication()->input->getArray();

		$message = '{text-1551612062256} asdasdasd {text-1551612062256}';
		$body = $model->setMessage($message);
		$model->enquiryEmail($body, $userEmail);
	}
}
