<?php
/**
 * @version     1.0.0
 * @package     com_modern_tours
 * @copyright   modernjoomla.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      modernjoomla.com <support@modernjoomla.com>
 */

defined('_JEXEC') or die;

/**
 * Class MTHelper
 */
class MTHelper
{

    /**
     * Gets forms category id
     * @return string
     */
    public static function getCategory()
    {
        if (JFactory::getApplication()->isSite()) {
            $app = JFactory::getApplication();
            $itemid = $app->input->get('Itemid');
            $menu = $app->getMenu();
            $link = $menu->getItem($itemid);

            return $link->query['category'];
        }
        else {
            $id = JFactory::getApplication()->input->get('id');
            if(!$id) {
                $id = JFactory::getApplication()->input->cookie->get($name = 'category', $defaultValue = null);
            }

            return $id;
        }
    }

    /**
     * Gets timeslots category id
     * @return string
     */
    public static function getTimeCategory()
    {
        if (JFactory::getApplication()->isSite()) {
            $app = JFactory::getApplication();
            $itemid = $app->input->get('Itemid');
            $menu = $app->getMenu();
            $link = $menu->getItem($itemid);

            return $link->query['timecategory'];
        }
        else {
            return JFactory::getApplication()->input->get('timecategory');
        }
    }

    /**
     * Creates params object
     * @return string
     */
    public static function getParams()
    {
	    $app  = JFactory::getApplication();
	    $params = $app->getParams('com_modern_tours');

        return $params;
    }

    /**
     * @return string
     */
    public static function getTable()
    {
        return '#__modern_tours_reservations';
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function getOrder($id)
    {
        return JFactory::getDbo()->setQuery('SELECT * FROM #__modern_tours_reservations where id = "' . $id . '"')->loadObject();
    }

	/**
	 * @param $id
	 * @return mixed
	 */
	public static function getAsset($id)
	{
		return JFactory::getDbo()->setQuery('SELECT * FROM #__modern_tours_assets where id = "' . $id . '"')->loadObject();
	}

    /**
     * @param int $length
     * @return string
     */
    public static function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public static function getUserFieldsID($field, $id)
    {
    	if($id)
	    {
		    $params = JFactory::getDbo()->setQuery('SELECT params FROM #__modern_tours_assets WHERE id = ' . $id)->loadResult();

		    if($params)
		    {
			    $item = json_decode($params);
			    return $item->{$field} ? $item->{$field} : MTHelper::getComponentParams($field);
		    }
	    }
    }

    public static function getHTML($id)
    {
    	$id = JFactory::getDbo()->setQuery('SELECT formdata FROM #__modern_tours_forms WHERE id = ' . $id)->loadResult();
    	if($id)
	    {
		    return str_get_html($id);
	    }
    }

    public static function getUserEmail( $order = array(), $id )
    {
    	$user_fields = MTHelper::getUserFieldsID('user_data_fields', $id);
        $email = MTHelper::getHTML($user_fields)->find('field[type=email]');

        if($email)
        {
	        $email = $email[0]->name;
	        return $order ? $order->email : JFactory::getApplication()->input->getVar($email, '', 'raw');
        }
    }

	public static function getContactFormEmail( $order = array(), $id )
	{
		$user_fields = MTHelper::getUserFieldsID('email_fields', $id);
		$email = MTHelper::getHTML($user_fields)->find('field[type=email]');

		if($email)
		{
			$email = $email[0]->name;
			return $order ? $order->{$email} : JFactory::getApplication()->input->getVar($email, '', 'raw');
		}
	}

	public static function getUserFields($id)
	{
		$ignorableFields = array_flip(array('paragraph', 'header'));
		$names = array();
		$userFieldsID = MTHelper::getUserFieldsID('user_data_fields', $id);
		if($userFieldsID)
		{
			$fields = MTHelper::getHTML($userFieldsID)->find('field');

			foreach ($fields as $field) {
				if(!isset($ignorableFields[$field->type]))
				{
					$names[] = $field->name;
				}
			}

			return $names;
		}
	}

	public static function multiArray( $result = array(), $array, $value )
	{
		if(!$result) {
			$count = count( $array );

			for ( $x = $count - 1; $x >= 0; $x -- ) {
				if ( $x == $count - 1 ) {
					$result[ $array[ $x ] ] = $value;//setting value for last index
				} else {
					$tempArray              = $result;//storing value temporarily
					$result                 = array();//creating empty array
					$result[ $array[ $x ] ] = $tempArray;//overriting values.
				}
			}

			return $result;
		} else {
			return $result;
		}
	}

	public static function sendError($message)
	{
		echo '<h1>' . $message . '</h1>';
		exit();
	}

	public static function displayRating($rating, $count) {

		$stars = MTHelper::generateStars($rating, $count);

		if($count == 1) {
			$reviewText = JText::sprintf( 'REVIEW', $count );
		} else if($count > 1) {
			$reviewText = JText::sprintf( 'REVIEWS', $count );
		} else {
			$reviewText =  JText::_( 'NO_REVIEWS_YET' );
		}

		return $stars . '<span class="review-text">' . $reviewText . '</span>';
	}

	public static function generateStars($rating, $count, $showText = false)
	{
		$ratingHTML = '';
		$positive = round($rating);
		$negative = 5 - $positive;

		if($count)
		{
			for($i=0;$i < $positive; $i++) {
				$ratingHTML .= '<i class="fa fa-star"></i>';
			}

			for($i=0;$i < $negative; $i++) {
				$ratingHTML .= '<i class="fa fa-star blank"></i>';
			}

			$stars = '<span class="stars">' . $ratingHTML . '</span>';
		}
		else
		{
			$stars = '<span class="stars"><i class="fa fa-star-o blank"></i><i class="fa fa-star-o blank"></i><i class="fa fa-star-o blank"></i><i class="fa fa-star-o blank"></i><i class="fa fa-star-o blank"></i></span>';
		}

		if($showText)
		{
			$stars .= JText::sprintf('STARS_RATING_TEXT', $rating);
		}

		return $stars;
	}

    public static function defineTranslate($digit, $string)
    {
	    $text = $digit != 1 ? $string. 'S' : 'SINGLE_' . $string;
	    return JText::sprintf( $text , $digit );
    }

	public static function addSymbol($currency) {
    	$currencies = array(
		    'EUR' => '€',
		    'USD' => '$',
		    'GBP' => '£'
	    );
		return isset($currencies[$currency]) ? $currencies[$currency] : $currency;
	}

	public static function getComponentParams($option)
	{
		$params = JComponentHelper::getParams('com_modern_tours');
		return $params->get($option);
	}

	public static function getOptionItems($table, $text)
	{
		$items = JFactory::getDbo()->setQuery('SELECT id, title FROM ' . $table . ' WHERE state = 1')->loadObjectList();
		$options[] = JHTML::_('select.option', '', JText::_($text));

		foreach($items as $item)
		{
			$options[] = JHTML::_('select.option', $item->title, $item->title);
		}

		return $options;
	}

	public static function generateOptions($options)
	{
		$newOptions = array();

		foreach($options as $key => $value)
		{
			$newOptions[] = JHTML::_('select.option', $key, $value);
		}

		return $newOptions;
	}

	public static function newField($options, $name)
	{
		$default = JFactory::getApplication()->input->getVar($name);
		$options = MTHelper::generateOptions($options);

		return JHTML::_('select.genericlist', $options, $name, 'class="inputbox"', 'value', 'text', $default);
	}

	public static function getLocations()
	{
		MTHelper::loadLanguage();
		$default = JFactory::getApplication()->input->getVar('location');
		$options = MTHelper::getOptionItems('#__modern_tours_locations', JText::_('SELECT_LOCATIONS'));

		return JHTML::_('select.genericlist', $options, 'location', 'class="inputbox"', 'value', 'text', $default);
	}

	public static function getCategories()
	{
		$default = JFactory::getApplication()->input->getVar('category');
		$options = MTHelper::getOptionItems('#__modern_tours_categories', JText::_('SELECT_CATEGORY_SEARCH'));

		return JHTML::_('select.genericlist', $options, 'category', 'class="inputbox"', 'value', 'text', $default);
	}

	public static function getRatingField()
	{
		$options     = array(
			'' => JText::_( 'SELECT_RATING' ),
			1  => JText::_( '1_RATING' ),
			2  => JText::_( '2_RATING' ),
			3  => JText::_( '3_RATING' ),
			4  => JText::_( '4_RATING' ),
			5  => JText::_( '5_RATING' )
		);
		return MTHelper::newField( $options, 'rating' );
	}

	public static function getDurationField()
	{
		$options       = array(
			''    => JText::_( 'SELECT_DURATION' ),
			1     => JText::_( '1_DAY_DURAITON' ),
			'2-3' => JText::_( '2_3_DAY_DURAITON' ),
			'4-5' => JText::_( '4_5_DAY_DURAITON' ),
			6     => JText::_( '6+_DAY_DURAITON' )
		);

		return MTHelper::newField( $options, 'duration' );
	}

	public static function getAlias()
	{
		$app  = JFactory::getApplication('com_modern_tours');
		$params       = $app->getParams();
		$params_array = $params->toArray();
		$itemAlias = str_replace(':', '-', JFactory::getApplication()->input->getVar('alias'));

		$alias = isset($params_array['item_id']) ? $params_array['item_id'] : $itemAlias;
		$view = JFactory::getApplication()->input->get('view');

		if($view == 'asset')
		{
			$alias = $itemAlias;

			if(!JFactory::getApplication()->input->getVar('alias'))
			{
				$alias = $params_array['item_id'];
			}
		}

		return $alias;
	}

	public static function loadLanguage($admin = false)
	{
		$lang = JFactory::getLanguage();
		$extension = 'com_modern_tours';
		$base_dir = !$admin ? JPATH_SITE : JPATH_ADMINISTRATOR;
		$language_tag = 'en-GB';
		$reload = true;
		$lang->load($extension, $base_dir);
	}
	
	public static function getLanguage()
	{
		$lang = JFactory::getLanguage();
		$parts = explode('-', $lang->getTag());
		return $parts[0];
	}
	
	public static function getMenuLang()
	{
		$active = JFactory::getApplication()->getMenu()->getActive();

		if(isset($active))
		{
			return JFactory::getApplication()->getMenu()->getActive()->language;
		}
	}

	public static function convertToLabel($name)
	{
		return str_replace('-', ' ', ucfirst($name));
	}

	public static function jsonToText($json, $text = '')
	{
		$objects = json_decode($json);

		if($objects)
		{
			foreach($objects as $object)
			{
				foreach($object as $fieldname => $value)
				{
					$text .= '<div class="single-data">' . MTHelper::convertToLabel($fieldname) . ': ' . $value . '</div>';
				}
				$text .= '<hr>';
			}
		}

		return $text;
	}

	public static function getTour($id)
	{
		return JFactory::getDbo()->setQuery('SELECT title FROM #__modern_tours_assets WHERE id = ' . (int) $id)->loadResult();
	}

	/**
	 * Gets payment plugins list for display in frontend of calendar
	 * @return mixed
	 */
	public static function getPaymentMethods()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery( true )->select( 'element AS value' )->from( '#__extensions' )->where( 'folder = "tourpayment"' )->where( 'enabled = 1' )->order( 'ordering, name' );
		$db->setQuery( $query );

		return $db->loadColumn();
	}

	public static function getCategoriesColumns()
	{
		return MTHelper::setParam('list_col_xs') . ' ' . MTHelper::setParam('list_col_sm') . ' ' . MTHelper::setParam('list_col_md') . ' ' . MTHelper::setParam('list_col_lg');
	}

	public static function getColumns()
	{
		return MTHelper::setParam('col_xs') . ' ' . MTHelper::setParam('col_sm') . ' ' . MTHelper::setParam('col_md') . ' ' . MTHelper::setParam('col_lg');
	}

	public static function setParam($name)
	{
		$app = JFactory::getApplication();
		$params = $app->getParams('com_modern_tours');
		$menuParameter = $params->get($name);
		return $menuParameter;
	}

	public static function checkUser($id)
	{
		if($id === JFactory::getUser()->id)
		{
			return true;
		}
		else
		{
			MTHelper::sendError('SORRY_ERROR');
		}
	}

	/**
	 * Increases or decreases the brightness of a color by a percentage of the current brightness.
	 *
	 * @param   string  $hexCode        Supported formats: `#FFF`, `#FFFFFF`, `FFF`, `FFFFFF`
	 * @param   float   $adjustPercent  A number between -1 and 1. E.g. 0.3 = 30% lighter; -0.4 = 40% darker.
	 *
	 * @return  string
	 */
	public static function adjustBrightness($hexCode, $adjustPercent) {
		$hexCode = ltrim($hexCode, '#');

		if (strlen($hexCode) == 3) {
			$hexCode = $hexCode[0] . $hexCode[0] . $hexCode[1] . $hexCode[1] . $hexCode[2] . $hexCode[2];
		}

		$hexCode = array_map('hexdec', str_split($hexCode, 2));

		foreach ($hexCode as & $color) {
			$adjustableLimit = $adjustPercent < 0 ? $color : 255 - $color;
			$adjustAmount = ceil($adjustableLimit * $adjustPercent);

			$color = str_pad(dechex($color + $adjustAmount), 2, '0', STR_PAD_LEFT);
		}

		return '#' . implode($hexCode);
	}

	public static function getDeposit($price, $assets_id) {
		$deposit = MTHelper::depositParams($assets_id);
		return number_format($price * ($deposit->deposit_percentage/100), 2);
	}

	public static function depositParams($assets_id) {
		$order = MTHelper::getAsset($assets_id);
		$params = json_decode($order->params);
		$compParams = MTHelper::getParams();
		$deposit = new stdClass();
		$deposit->deposit_booking = $params->deposit_booking == 1 || $params->deposit_booking == '0' ? $params->deposit_booking : $compParams->get('deposit_booking');
		$deposit->deposit_booking_choose = $params->deposit_booking_choose == 1 || $params->deposit_booking_choose == '0' ? $params->deposit_booking_choose : $compParams->get('deposit_booking_choose');
		$deposit->deposit_percentage = $params->deposit_percentage ? $params->deposit_percentage : $compParams->get('deposit_percentage');
		return $deposit;
	}

	public static function hasAvailability($item)
	{
		if(isset($item->params->{'from-month'}) && isset($item->params->{'from-day'}) && isset($item->params->{'to-month'}) && isset($item->params->{'to-day'}))
		{
			if ( $item->params->{'from-month'} && $item->params->{'from-day'} && $item->params->{'to-month'} && $item->params->{'to-day'} )
			{
				return true;
			}
		}

		return false;
	}

	public static function addColors($item, $color)
	{
		$app = JFactory::getApplication();
//		if($app->getTemplate() != 'shaper_helixultimate')
//		{
			$doc = JFactory::getDocument();
			$color = $item->params->color ? $item->params->color : $color;
			$lightcolor = MTHelper::adjustBrightness($color, 0.3);
			$doc->addStyleDeclaration('.clndr .clndr-controls, .clndr td.day:not(.blocked) .day-contents, button#book-now, .next-btn, .content-block .ticket:before, #stripe-pay { background: ' .$color. '; } button.swal2-cancel.swal2-styled, .swal2-icon.swal2-error .line, .itenary-block i.fa.fa-calendar-o, #stripe-pay:hover { background: ' .$color. ' !important; } body .box-input:focus, .swal2-icon.swal2-error.animate-error-icon, .swal2-icon.swal2-error .line, .swal2-icon.swal2-info, #stripe-pay, .stripe-fields .form-control:focus { border-color: ' .$color. '; } .tour-body .fa, .itenary-block .itenary-title b, .time-list i, .content-block-menu .menu li a:hover, .content-block-menu .menu li a:hover i, .swal2-icon.swal2-info, .swal2-modal .swal2-close:hover, #deposit .deposit-button i { color: ' .$color. '; } .content-block .ticket:before { background: -moz-linear-gradient(left, ' .$color. ' 0%, ' .$lightcolor. ' 100%); background: -webkit-linear-gradient(left, ' .$color. ' 0%, ' .$lightcolor. ' 100%); background: linear-gradient(to right, ' .$color. ' 0%, ' .$lightcolor. ' 100%); }');
//		}
	}

}

