<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;
use YOOtheme\ContainerTrait;
use YOOtheme\Util\Collection;

class CustomizerController
{
    use ContainerTrait;

    /**
     * @var array
     */
    public $inject = [
        'db' => 'app.db',
        'config' => 'app.config',
    ];

    public function index($return = false, $response)
    {
        $config = Factory::getConfig();
        $document = Factory::getDocument();

        $this->app->trigger('theme.admin', [$this->theme]);
        $this->config->add('customizer', [
            'config' => $this->theme->config->all(),
            'return' => $return ?: $this->app->url('administrator/index.php'),
        ]);

        HTMLHelper::_('bootstrap.tooltip');
        HTMLHelper::_('behavior.keepalive');

        return $document->setTitle("Website Builder - {$config->get('sitename')}")
            ->addFavicon(Uri::root(true) . '/administrator/templates/isis/favicon.ico')
            ->render(false, [
                'file' => 'component.php',
                'template' => 'system',
            ]);
    }

    public function save($config, $response)
    {
        $user = Factory::getUser();
        $config = new Collection($config);

        if (!$user->authorise('core.edit', 'com_templates')) {
            $this->app->abort(403, 'Insufficient User Rights.');
        }

        $this->app->trigger('theme.save', [$config]);

        // alter custom_data type to MEDIUMTEXT only in MySQL database
        if (strpos($this->db->driver, 'mysql') !== false) {
            foreach (['extensions' => 'custom_data', 'template_styles' => 'params'] as $table => $field) {

                $query = "SHOW FIELDS FROM @{$table} WHERE Field = '{$field}'";
                $alter = "ALTER TABLE @{$table} CHANGE `{$field}` `{$field}` MEDIUMTEXT NOT NULL";

                if ($this->db->fetchObject($query)->Type == 'text') {
                    $this->db->executeQuery($alter);
                }
            }
        }

        // update template style params
        $params = array_replace($this->theme->params->toArray(), ['config' => json_encode($config)]);
        $this->db->update('@template_styles', ['params' => json_encode($params)], ['id' => $this->theme->id]);

        return 'success';
    }
}
