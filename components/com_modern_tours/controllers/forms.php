<?php
/**
 * @version     1.0.0
 * @package     com_modern_tours
 * @copyright   modernjoomla.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      modernjoomla.com <support@modernjoomla.com>
 */
// No direct access.
defined('_JEXEC') or die;
require_once JPATH_COMPONENT . '/controller.php';
require_once JPATH_COMPONENT . '/helpers/modern_tours.php';

/**
 * Fields list controller class.
 */
class Modern_toursControllerForms extends Modern_toursController
{
    public function delete($count = array())
    {
        $user = JFactory::getUser();
        if(array_key_exists('7', $user->groups) OR array_key_exists('8', $user->groups)) {
            $this->id = MTHelper::getCategory();
            $input = JFactory::getApplication()->input;
            $cid = $input->get('cid',array(),'','array');

            foreach($cid as $item) {
                $count[] = JFactory::getDbo()->setQuery('DELETE FROM #__modern_tours_forms_' . $this->id . ' WHERE id = "' . $item . '"')->query();
            }
            $url = JRoute::_('index.php?option=com_modern_tours&view=forms&category=' . $this->id);
            $message = count($count) . ' reservation(s) deleted.';

            $this->setRedirect($url, $message, 'Message');
        } else {
            MTHelper::sendError('Access not allowed');
        }
    }

    public function getCSV()
    {
        require JPATH_COMPONENT_SITE . '/models/forms.php';
        $model = JModelList::getInstance('Forms', 'Modern_toursModel');
        $model->generateCSV();
    }
}