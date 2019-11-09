<?php

namespace YOOtheme\Theme;

use YOOtheme\EventSubscriber;

class AnalyticsListener extends EventSubscriber
{
    /**
     * @var string
     */
    public $path;

    /**
     * @var array
     */
    public $inject = [
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
    }

    public function onSite($theme)
    {
        $keys = [
            'google_analytics',
            'google_analytics_anonymize',
        ];

        if ($theme->get($keys[0])) {

            foreach ($keys as $key) {
                $theme->data->set($key, $theme->get($key));
            }

            $this->scripts->add('analytics', "{$this->path}/app/analytics.min.js", 'theme-script', ['defer' => true]);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'theme.site' => 'onSite',
        ];
    }
}
