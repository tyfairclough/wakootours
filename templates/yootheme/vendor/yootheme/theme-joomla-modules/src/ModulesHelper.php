<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use YOOtheme\ContainerTrait;

class ModulesHelper
{
    use ContainerTrait;

    /**
     * @var array
     */
    public $inject = [
        'db' => 'app.db',
    ];

    public function getTypes()
    {
        $language = Factory::getLanguage();
        $types = $this->db->fetchAll("SELECT name, element FROM @extensions WHERE client_id = 0 AND type = 'module'");

        foreach ($types as $type) {
            $language->load("{$type['element']}.sys", JPATH_SITE, null, false, true);
            $data[$type['element']] = Text::_($type['name']);
        }

        natsort($data);

        return $data;
    }

    public function getModules()
    {
        return $this->db->fetchAll('SELECT id, title, module, position, ordering FROM @modules WHERE client_id = 0 AND published != -2 ORDER BY position, ordering');
    }

    public function getPositions()
    {
        return array_values(
            array_unique(
                array_merge(
                    array_keys($this->theme->options['positions']),
                    Factory::getDbo()->setQuery('SELECT DISTINCT(position) FROM #__modules WHERE client_id = 0 ORDER BY position')->loadColumn()
                )
            )
        );
    }
}
