<?php

/**
 * @version    CVS: 1.0.0
 * @package    com_modern_tours
 * @author      modernjoomla.com <support@modernjoomla.com>
 * @copyright  modernjoomla.com
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View to edit
 *
 * @since  1.6
 */
class Modern_toursViewAsset extends JViewLegacy
{
	protected $state;

	protected $item;

	protected $form;

	protected $params;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$user = JFactory::getUser();
		$model = JModelList::getInstance('Asset', 'modern_toursModel');
		$this->item   = $model->getItem();
		$this->params = MTHelper::getParams();
		$this->state  = $this->get('State');
		$this->services = $this->get('Services');
		$this->stripeKey = $this->get('Key');
		$bandates = $this->item->bandates ? $this->item->bandates : '{}';
		$this->hours = '{"bandates" : ' . $bandates . ', "times" : ' . $this->item->times . '}';
		$this->item->imageFiles = json_decode($this->item->imageFiles, true);
		$this->reservations = json_encode($this->get('Reservations'));
		$this->userData = $this->setFielsData('user_data_fields');
		$this->travellersData = $this->setFielsData('travellers_data_fields');
		$this->emailFields = $this->setFielsData('email_fields');
		$this->payments = MTHelper::getPaymentMethods();
		$this->deposit = MTHelper::depositParams($this->item->id);
		MTHelper::addColors($this->item, $this->params->get('color'));
		$this->addCashPayment();

		if (!empty($this->item))
		{
			$this->form = $this->get('Form');
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		if ($this->_layout == 'edit')
		{
			$authorised = $user->authorise('core.create', 'com_modern_tours');

			if ($authorised !== true)
			{
				throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
			}
		}

		$this->_prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function _prepareDocument()
	{
		$app   = JFactory::getApplication();
		$menus = $app->getMenu();
		$title = null;

		// Because the application sets a default page title,
		// We need to get it from the menu item itself
		$menu = $menus->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', JText::_('COM_MODERN_TOURS_DEFAULT_PAGE_TITLE'));
		}

		$title = $this->params->get('page_title', $this->item->title);

		if (empty($title))
		{
			$title = $app->get('sitename');
		}
		elseif ($app->get('sitename_pagetitles', 0) == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		}
		elseif ($app->get('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
		}

		$title = $this->item->title . ' | ' . $app->get('sitename');

		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	}

	protected function setFielsData($field)
	{
		$id = $this->item->params->{$field} ? $this->item->params->{$field} : $this->params->get($field);

		if($id)
		{
			return JFactory::getDbo()->setQuery('SELECT formdata FROM #__modern_tours_forms WHERE id = ' . $id)->loadResult();
		}
	}

	protected function addCashPayment()
	{
		if($this->params->get('cash_payment'))
		{
			array_unshift($this->payments, 'cash');
		}
	}

}
