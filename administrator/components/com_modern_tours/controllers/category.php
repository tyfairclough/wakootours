<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Modern_tours
 * @author     Jonas <jonasjov2@gmail.com>
 * @copyright  2016 Jonas
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Category controller class.
 *
 * @since  1.6
 */
class Modern_toursControllerCategory extends JControllerForm
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'categories';
		parent::__construct();
	}
}
