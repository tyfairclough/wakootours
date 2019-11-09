<?php

$link = $props['link'] ? $this->el('a', [
    'href' => $props['link'],
]) : null;

// Lightbox
if ($link && $element['lightbox']) {

    if ($type = $this->isImage($props['link'])) {

        if ($type !== 'svg' && ($element['lightbox_image_width'] || $element['lightbox_image_height'])) {
            $props['link'] = "{$props['link']}#thumbnail={$element['lightbox_image_width']},{$element['lightbox_image_height']},{$element['lightbox_image_orientation']}";
        }

        $link->attr([
            'href' => $app['image']->getUrl($props['link']),
            'data-alt' => $props['image_alt'],
            'data-type' => 'image',
        ]);

    } elseif ($this->isVideo($props['link'])) {
        $link->attr('data-type', 'video');
    } elseif (!$this->iframeVideo($props['link'])) {
        $link->attr('data-type', 'iframe');
    } else {
        $link->attr('data-type', true);
    }

    // Caption
    $caption = '';

    if ($props['title'] && $element['title_display'] != 'item') {

        $caption .= "<h4 class='uk-margin-remove'>{$props['title']}</h4>";

        if ($element['title_display'] == 'lightbox') {
            $props['title'] = '';
        }
    }

    if ($props['content'] && $element['content_display'] != 'item') {

        $caption .= $props['content'];

        if ($element['content_display'] == 'lightbox') {
            $props['content'] = '';
        }
    }

    if ($caption) {
        $link->attr('data-caption', $caption);
    }

} elseif ($link) {

    $link->attr([
        'target' => ['_blank {@link_target}'],
        'uk-scroll' => strpos($props['link'], '#') === 0,
    ]);

}

if ($link && $element['link_type'] == 'element') {

    $el->attr($link->attrs + [

        'class' => [
            'uk-link-reset',
        ],

    ]);

    $props['title'] = $this->striptags($props['title']);
    $props['meta'] = $this->striptags($props['meta']);
    $props['content'] = $this->striptags($props['content']);

} elseif ($link && $element['link_type'] == 'content') {

    if ($props['title']) {
        $props['title'] = $link($element, ['class' => ['uk-link-reset']], $this->striptags($props['title']));
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
