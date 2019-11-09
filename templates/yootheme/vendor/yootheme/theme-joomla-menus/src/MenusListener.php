<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Factory;
use YOOtheme\EventSubscriber;

class MenusListener extends EventSubscriber
{
    /**
     * @var string
     */
    public $path;

    /**
     * @var MenusHelper
     */
    public $helper;

    /**
     * @var array
     */
    public $inject = [
        'admin' => 'app.admin',
        'config' => 'app.config',
    ];

    /**
     * Constructor.
     *
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
        $this->helper = new MenusHelper();
    }

    public function onAdmin($theme)
    {
        $user = Factory::getUser();

        // add config
        $this->config->add('customizer', [

            'menu' => [
                'menus' => $this->helper->getMenus(),
                'items' => $this->helper->getItems(),
                'positions' => $theme->options['menus'],
                'canDelete' => $user->authorise('core.edit.state', 'com_menus'),
                'canEdit' => $user->authorise('core.edit', 'com_menus'),
                'canCreate' => $user->authorise('core.create', 'com_menus'),
            ],

        ]);

        if ($user->authorise('core.manage', 'com_menus')) {
            $this->config->addFile('customizer', "{$this->path}/config/customizer.json");
        }
    }

    public function onModules(&$modules)
    {
        if ($this->admin) {
            return;
        }

        // create menu modules when assigned in theme settings
        foreach ($this->theme->get('menu.positions') as $position => $menu) {

            if (!$menu) {
                continue;
            }

            array_unshift($modules, (object) [
                'id' => 0,
                'name' => 'menu',
                'module' => 'mod_menu',
                'title' => '',
                'showtitle' => 0,
                'position' => $position,
                'params' => json_encode(['menutype' => $menu, 'showAllChildren' => true]),
            ]);
        }

        $mods = [];

        foreach ($modules as $id => $module) {

            $mods[] = $module;

            if ($module->module != 'mod_menu' || $module->position != 'navbar') {
                continue;
            }

            $params = is_string($module->params) ? json_decode($module->params, true) : $module->params;
            $params['split'] = true;
            $module->params = json_encode($params);

            $clone = clone $module;
            $clone->id = '';
            $clone->position = 'navbar-split';
            $mods[] = $clone;
        }

        $modules = $mods;
    }

    public static function getSubscribedEvents()
    {
        return [
            'theme.admin' => 'onAdmin',
            'modules.load' => 'onModules',
        ];
    }
}
