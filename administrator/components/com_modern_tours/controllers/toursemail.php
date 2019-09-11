<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Modern_tours
 * @author     Jonas Jovaišas <support@modernjoomla.com>
 * @copyright  2018 Jonas Jovaišas
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Toursemail controller class.
 *
 * @since  1.6
 */
class Modern_toursControllerToursemail extends JControllerForm
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'modernforms';
		parent::__construct();
	}
}
