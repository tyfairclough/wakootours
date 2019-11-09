<?php

$link = $props['link'] ? $this->el('a', [
    'href' => ['{link}'],
    'target' => ['_blank {@link_target}'],
    'uk-scroll' => strpos($props['link'], '#') === 0,
]) : null;

if ($link && $props['link_type'] == 'element') {

    $container->attr($link->attrs + [

        'class' => [
            'uk-link-reset',
        ],

    ]);

    $props['title'] = $this->striptags($props['title']);
    $props['meta'] = $this->striptags($props['meta']);
    $props['content'] = $this->striptags($props['content']);

} elseif ($link && $props['link_type'] == 'content') {

    if ($props['title']) {
        $props['title'] = $link($props, ['class' => ['uk-link-reset']], $this->striptags($props['title']));
    }

} elseif ($link) {

    $link->attr([

        'class' => [
            'el-link',
            'uk-{link_style: link-(muted|text)}',
            'uk-button uk-button-{!link_style: |link-muted|link-text} [uk-button-{link_size}]',
            'uk-transition-{link_transition} {@overlay_hover}',
        ],

    ]);

}

return $link;
