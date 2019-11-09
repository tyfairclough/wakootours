<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Plugin\PluginHelper;
use YOOtheme\EventSubscriber;
use YOOtheme\Util\Collection;

class ModulesListener extends EventSubscriber
{
    /**
     * @var string
     */
    public $path;

    /**
     * @var ModulesHelper
     */
    public $helper;

    /**
     * @var array
     */
    public $inject = [
        'db' => 'app.db',
        'view' => 'app.view',
        'admin' => 'app.admin',
        'config' => 'app.config',
        'styles' => 'app.styles',
        'scripts' => 'app.scripts',
    ];

    /**
     * Constructor.
     *
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
        $this->helper = new ModulesHelper();
    }

    public function onSite()
    {
        $renderer = version_compare(JVERSION, '3.8', '>=') ? 'Joomla\CMS\Document\Renderer\Html\ModulesRenderer' : 'JDocumentRendererHtmlModules';

        class_alias('YOOtheme\Theme\Joomla\ModulesRenderer', $renderer);
    }

    public function onAdmin()
    {
        $user = Factory::getUser();
        $component = PluginHelper::isEnabled('system', 'advancedmodules') ? 'com_advancedmodules' : 'com_modules';

        // add config
        $this->config->add('customizer', [

            'module' => [
                'types' => $this->helper->getTypes(),
                'modules' => $this->helper->getModules(),
                'positions' => $this->helper->getPositions(),
                'canDelete' => $user->authorise('core.edit.state', 'com_modules'),
                'canEdit' => $user->authorise('core.edit', 'com_modules'),
                'canCreate' => $user->authorise('core.create', 'com_modules'),
            ],

        ]);

        if ($user->authorise('core.manage', 'com_modules')) {
            $this->config->addFile('customizer', "{$this->path}/config/customizer.json", compact('component'));
        }
    }

    public function onModules(&$modules)
    {
        if ($this->admin) {
            return;
        }

        $this->view['sections']->add('breadcrumbs', function () {
            return ModuleHelper::renderModule($this->createModule([
                'name' => 'yoo_breadcrumbs',
                'module' => 'mod_breadcrumbs',
            ]));
        });

        if ($position = $this->theme->get('header.search')) {

            $search = $this->createModule([
                'name' => 'yoo_search',
                'module' => $this->theme->get('search_module'),
                'position' => $position,
            ]);

            array_push($modules, $search);

            $search = $this->createModule([
                'name' => 'yoo_search',
                'module' => 'mod_search',
                'position' => 'mobile',
            ]);

            array_push($modules, $search);
        }

        if ($position = $this->theme->get('header.social')) {

            $social = $this->createModule([
                'name' => 'yoo_socials',
                'module' => 'mod_custom',
                'position' => $position,
                'content' => $this->view->render('socials'),
            ]);

            strpos($position, 'left') ? array_unshift($modules, $social) : array_push($modules, $social);
        }

        $temp = $this->theme->params->get('module');

        foreach ($modules as $module) {

            if ($temp && $temp['id'] == $module->id) {
                $module->content = $temp['content'];
            }

            $params = json_decode($module->params);

            if (!isset($params->yoo_config) && isset($params->config)) {
                $params->yoo_config = $params->config;
            }

            $config = json_decode(isset($params->yoo_config) ? $params->yoo_config : '{}', true);

            $module->type = str_replace('mod_', '', $module->module);
            $module->attrs = ['id' => "module-{$module->id}", 'class' => []];
            $module->config = (new Collection($config))->merge([
                'class' => [isset($params->moduleclass_sfx) ? $params->moduleclass_sfx : ''],
                'showtitle' => $module->showtitle,
                'title_tag' => isset($params->header_tag) ? $params->header_tag : 'h3',
                'is_list' => in_array($module->type, ['articles_archive', 'articles_categories', 'articles_latest', 'articles_popular', 'tags_popular', 'tags_similar']),
            ]);
        }
    }

    public function createModule($module)
    {
        static $id = 0;

        $module = (object) array_merge(['id' => 'tm-' . (++$id), 'title' => '', 'showtitle' => 0, 'position' => '', 'params' => '{}'], (array) $module);

        if (is_array($module->params)) {
            $module->params = json_encode($module->params);
        }

        return $module;
    }

    public function editModule($form, $data)
    {
        if (!in_array($form->getName(), ['com_modules.module', 'com_advancedmodules.module', 'com_config.modules'])) {
            return;
        }

        // don't show theme settings in builder module
        if (isset($data->module) && $data->module == 'mod_yootheme_builder') {
            return;
        }

        $config = $this->config->load("{$this->path}/config/modules.json", ['theme' => $this->theme]);

        if (isset($this->app['translator'])) {
            $config['locales'] = $this->app['translator']->getResources();
        }

        if (!isset($data->params['yoo_config']) && isset($data->params['config'])) {
            $data->params['yoo_config'] = $data->params['config'];
        }

        $this->styles->add('module-styles', '@assets/css/admin.css');
        $this->scripts->add('module-edit', "{$this->path}/app/module-edit.min.js");
        $this->scripts->add('module-data', sprintf('var $module = %s;', json_encode($config)), '', 'string');

        $form->load('<form><fields name="params"><fieldset name="template" label="Template"><field name="yoo_config" type="hidden" default="{}" /></fieldset></fields></form>');
    }

    public static function getSubscribedEvents()
    {
        return [
            'theme.site' => 'onSite',
            'theme.admin' => 'onAdmin',
            'modules.load' => ['onModules', -10],
            'content.form' => 'editModule',
        ];
    }
}
