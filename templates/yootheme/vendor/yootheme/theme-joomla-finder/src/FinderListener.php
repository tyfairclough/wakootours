<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Factory;
use YOOtheme\EventSubscriber;

class FinderListener extends EventSubscriber
{
    /**
     * @var array
     */
    public $inject = [
        'config' => 'app.config',
    ];

    public function onAdmin()
    {
        $user = Factory::getUser();

        // add config
        $this->config->add('customizer', [

            'media' => [
                // TODO
                // 'path' => ComponentHelper::getParams('com_media')->get('file_path'),
                'canCreate' => $user->authorise('core.manage', 'com_media') || $user->authorise('core.create', 'com_media'),
                'canDelete' => $user->authorise('core.manage', 'com_media') || $user->authorise('core.delete', 'com_media'),
            ],

        ]);
    }

    public static function getSubscribedEvents()
    {
        return [
            'theme.admin' => 'onAdmin',
        ];
    }
}
