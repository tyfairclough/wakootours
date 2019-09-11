<?php
/**
 * @version    CVS: 1.0.0
 * @package    com_modern_tours
 * @author     modernjoomla.com <support@modernjoomla.com>
 * @copyright  modernjoomla.com
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;
?>
<?php if($this->params->get('show_page_heading')): ?>
    <div class="row">
        <div class="col-12">
            <h2><?php echo $this->params->get('page_title'); ?></h2>
        </div>
    </div>
<?php endif; ?>

<?php include (JPATH_COMPONENT . '/views/assets/tmpl/default.php'); ?>