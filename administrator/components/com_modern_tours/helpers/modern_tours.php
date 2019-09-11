<?php

/**
 * @version     1.0.0
 * @package     com_modern_tours
 * @copyright   Copyright (C) 2015. All rights reserved. Unikalus team.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Jonas Jovaisas <jonasjov2@gmail.com> - http://modernjoomla.com
 */
// No direct access
defined('_JEXEC') or die;

/**
 * Modern_tours helper.
 */
class Modern_toursHelper
{

    /**
     * Configure the Linkbar.
     */
    public static function addSubmenu($vName = '')
    {

        JHtmlSidebar::addEntry(
            '<i class="fa fa-dashboard"></i>' . JText::_('Dashboard'),
            "index.php?option=com_modern_tours&view=dashboard",
            $vName == 'dashboard'
        );
        JHtmlSidebar::addEntry(
            '<i class="fa fa-align-left"></i>' . JText::_('COM_MODERN_TOURS_FIELDS_CATEGORY'),
            "index.php?option=com_modern_tours&view=modernforms",
            $vName == 'modernforms'
        );
        JHtmlSidebar::addEntry(
            '<i class="fa fa-calendar-o"></i>' . JText::_('COM_MODERN_TOURS_FIELDS_PRICE'),
            "index.php?option=com_modern_tours&view=assets",
            $vName == 'assets'
        );
	    JHtmlSidebar::addEntry(
		    '<i class="fa fa-barcode"></i>' . JText::_('RESERVATIONS'),
		    "index.php?option=com_modern_tours&view=reservations",
		    $vName == 'reservations'
	    );
	    JHtmlSidebar::addEntry(
		    '<i class="fa fa-barcode"></i>' . JText::_('Coupon Generator'),
		    "index.php?option=com_modern_tours&view=coupons",
		    $vName == 'coupons'
	    );
	    JHtmlSidebar::addEntry(
		    '<i class="fa fa-barcode"></i>' . JText::_('RESERVATIONS'),
		    "index.php?option=com_modern_tours&view=reservations",
		    $vName == 'reservations'
	    );
    }

    /**
     * Gets a list of the actions that can be performed.
     *
     * @return    JObject
     * @since    1.6
     */
    public static function getActions()
    {
        $user = JFactory::getUser();
        $result = new JObject;

        $assetName = 'com_modern_tours';

        $actions = array(
            'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
        );

        foreach ($actions as $action) {
            $result->set($action, $user->authorise($action, $assetName));
        }

        return $result;
    }

    public static function getForms()
    {
        return JFactory::getDbo()->setQuery('SELECT id, title FROM #__modern_tours_forms where state=1')->loadObjectList();
    }

    public static function getAllReservations($limit = 5)
    {
        foreach (self::getForms() as $item) {
            $all_forms[] = JFactory::getDbo()->setQuery('SELECT registered FROM #__modern_tours_forms_' . $item->id . '  LIMIT 0, ' . $limit)->loadColumn();
        }

        return $all_forms;
    }

    public static function reservationCount($paid = '', $where = '')
    {
        if ($paid && is_int($paid) && $paid == 1) {
            $where = 'where paid = 1';
        }
        if ($paid && is_int($paid) && $paid == 0) {
            $where = 'where paid = 0';
        }

        foreach (self::getForms() as $item) {
            $count[] = JFactory::getDbo()->setQuery('SELECT COUNT(id) FROM #__modern_tours_forms_' . $item->id . ' ' . $where)->loadColumn();
        }

        $count = array_sum($count);

        return $count;
    }

    public static function getReservations($limit = 5, $reservations = array())
    {
        foreach (self::getAllReservations() as $form) {
            foreach ($form as $reservation) {
                $reservations[strtotime($reservation)] = $reservation;
            }
        }

        if ($reservations) {
            $reservations = array_slice($reservations, 0, $limit);
        }

        return $reservations;
    }

    public static function getSidebar()
    {
        $view = JFactory::getApplication()->input->get('view');
        ?>
        <div id="j-sidebar-container" class="j-sidebar-container j-sidebar-visible">
            <img src="<?php echo JURI::base() . '../media/com_modern_tours/img/logo.png'; ?>" id="logo"/>
            <h3><?php echo JText::_('MAIN_MENU'); ?></h3>
            <div id="sidebar" class="sidebar">
                <div class="sidebar-nav">
                    <ul id="submenu" class="nav nav-list">
                        <li <?php if ($view == 'dashboard') { echo 'class="active"'; } ?>>
                            <a href="index.php?option=com_modern_tours&amp;view=dashboard"><i class="fa fa-dashboard"></i><?php echo JText::_('Dashboard'); ?></a>
                        </li>
                        <li <?php if ($view == 'assets' OR $view == 'asset') { echo 'class="active"'; } ?>>
                            <a href="index.php?option=com_modern_tours&amp;view=assets"><i class="fa fa-calendar-o"></i><?php echo JText::_('ASSETS'); ?></a>
                        </li>
                        <li <?php if ($view == 'locations' OR $view == 'location') { echo 'class="active"'; } ?>>
                            <a href="index.php?option=com_modern_tours&amp;view=locations"><i class="fa fa-map-marker"></i><?php echo JText::_('LOCATIONS'); ?></a>
                        </li>
                        <li <?php if ($view == 'categories' OR $view == 'category') { echo 'class="active"'; } ?>>
                            <a href="index.php?option=com_modern_tours&amp;view=categories"><i class="fa fa-bars"></i><?php echo JText::_('CATEGORIES'); ?></a>
                        </li>
                        <li <?php if ($view == 'modernforms' OR $view == 'modernform') { echo 'class="active"'; } ?>>
                            <a href="index.php?option=com_modern_tours&amp;view=modernforms"><i class="fa fa-pencil-square"></i><?php echo JText::_('REGISTRATION_FIELDS'); ?></a>
                        </li>
                        <li <?php if ($view == 'ex' OR $view == 'ex') { echo 'class="active"'; } else {  echo 'class="extra-options"'; } ?>>
                            <a href="index.php?option=com_modern_tours&amp;view=modernforms"><i class="fa fa-usd"></i><?php echo JText::_('EXTRA_OPTIONS'); ?></a>
                        </li>
                        <li <?php if ($view == 'coupons' OR $view == 'coupon') { echo 'class="active"'; } ?>>
                            <a href="index.php?option=com_modern_tours&amp;view=coupons"><i class="fa fa-barcode"></i><?php echo JText::_('COUPONS'); ?></a>
                        </li>
                        <li <?php if ($view == 'reviews' OR $view == 'review') { echo 'class="active"'; } ?>>
                            <a href="index.php?option=com_modern_tours&amp;view=reviews"><i class="fa fa-star"></i><?php echo JText::_('REVIEWS'); ?></a>
                        </li>
                        <li <?php if ($view == 'reservations' OR $view == 'reservation') { echo 'class="active"'; } ?>>
                            <a href="index.php?option=com_modern_tours&amp;view=reservations"><i class="fa fa-ticket"></i><?php echo JText::_('RESERVATIONS'); ?></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <?php
    }

	public static function getVariables($id, $explanations = true, $fieldshtml = '')
	{
		if($id)
		{
			include_once(JPATH_SITE . '/components/com_modern_tours/helpers/simple_html_dom.php');
			$fieldsTemplate = JFactory::getDbo()->setQuery('SELECT formdata FROM #__modern_tours_forms WHERE id = ' . $id)->loadResult();
			$html = str_get_html($fieldsTemplate);
			$ignorableFields = array_flip(array('paragraph', 'header'));

			foreach ($html->find('field') as $field) {
				if(!isset($ignorableFields[$field->type]))
				{
					$fieldshtml .= '<span class="param hasTip" title="' . JText::sprintf( 'TOOLTIP_FIELD', $field->name ) . '">{' . $field->name . '}</span>';
				}
			}
			$fieldshtml .= '<span class="param hasTip" title="' . JText::_( 'TOOLTIP_PAYMENT_TYPE' ) . '">{payment}</span> <span class="param hasTip" title="' . JText::_( 'TOOLTIP_TOTAL_PRICE' ) . '">{price}</span> <span class="param hasTip" title="' . JText::_( 'TOOLTIP_ADULTS' ) . '">{adults}</span> <span class="param hasTip" title="' . JText::_( 'TOOLTIP_CHILDREN' ) . '">{children}</span> <span class="param hasTip" title="' . JText::_( 'TOOLTIP_PARTICIPANTS' ) . '">{people}</span> <span class="param hasTip" title="' . JText::_( 'TOOLTIP_STARTTIME' ) . '">{starttime[%Y %m %d %H:%M]}</span> <span class="param hasTip" title="' . JText::_( 'TOOLTIP_ENDTIME' ) . '">{endtime[%Y %m %d %H:%M]}</span> <span class="param hasTip" title="' . JText::_( 'TOOLTIP_RESERVATION_DATE' ) . '">{pdf_date}</span> <span class="param hasTip" title="' . JText::_( 'TOOLTIP_BOOKING_STATUS' ) . '">{status}</span> <span class="param hasTip" title="' . JText::_( 'TOOLTIP_RESERVATION_ID' ) . '">{id}</span> <span class="param hasTip" title="' . JText::_( 'TOOLTIP_USER_ID' ) . '">{user_id}</span> <span class="param hasTip" title="' . JText::_( 'TOOLTIP_INVOICE_NUMBER' ) . '">{invoice_number}</span> <span class="param hasTip" title="' . JText::_( 'TOOLTIP_TOUR_TITLE' ) . '">{tour}</span> <span class="param hasTip" title="' . JText::_( 'TOOLTIP_USER_DATA' ) . '">{userDataText}</span> <span class="param hasTip" title="' . JText::_( 'TOOLTIP_TRAVELLERS_DATA' ) . '">{travellersData}</span> <span class="param hasTip" title="' . JText::_( 'TOOLTIP_REGISTERED' ) . '">{registered}</span>';
			if($explanations)
			{
				$fieldshtml .= '<div class="show-explanation">' . JText::_( 'SHOW_EXPLANATION' ) . '</div> ' . JText::_( 'VARIABLES_EXPLANATION' );
			}
		}

		return $fieldshtml;
	}

}
