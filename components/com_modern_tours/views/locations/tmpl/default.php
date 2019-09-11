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
$doc = JFactory::getDocument();
$doc->addStyleSheet('media/com_modern_tours/css/style.css');
$doc->addStyleSheet('media/com_modern_tours/css/bootstrap-min.css');
$doc->addStyleSheet( 'https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css' );
?>
<?php if($this->params->get('show_page_heading')): ?>
    <div class="row">
        <div class="col-12">
            <h2><?php echo $this->params->get('page_title'); ?></h2>
        </div>
    </div>
<?php endif; ?>
<div class="row">
    <?php foreach($this->items as $category): ?>
        <?php
            $layout = new JLayoutFile('location', JPATH_ROOT .'/components/com_modern_tours/layouts');
            echo $layout->render($category);
        ?>
    <?php endforeach; ?>
</div>

<?php echo $this->pagination->getPagesLinks(); ?>