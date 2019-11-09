<?php

// Display
foreach (['title', 'meta', 'content', 'image', 'link', 'label', 'thumbnail'] as $key) {
    if (!$element["show_{$key}"]) { $props[$key] = ''; }
}

// Image
$image = $this->render("{$__dir}/template-image", compact('props'));

// Image Align
$grid = $this->el('div', [

    'class' => [
        'uk-child-width-expand',
        'uk-grid-{image_gutter}',
        'uk-flex-middle {@image_vertical_align}',
    ],

    'uk-grid' => true,
]);

$cell_image = $this->el('div', [

    'class' => [
        'uk-width-{image_grid_width}@{image_breakpoint}',
        'uk-flex-last@{image_breakpoint} {@image_align: right}',
    ],

]);

$cell_content = $this->el('div', [

    'class' => [
        'uk-margin-remove-first-child',
    ],

]);

?>

<?php if ($image && in_array($element['image_align'], ['left', 'right'])) : ?>

    <?= $grid($element) ?>
        <?= $cell_image($element, $image) ?>
        <?= $cell_content($element) ?>
            <?= $this->render("{$__dir}/template-content", compact('props')) ?>
        <?= $cell_content->end() ?>
    </div>

<?php else : ?>

    <?= $element['image_align'] == 'top' ? $image : '' ?>
    <?= $this->render("{$__dir}/template-content", compact('props')) ?>
    <?= $element['image_align'] == 'bottom' ? $image : '' ?>

<?php endif ?>
