<?php

// Options
$layout = $theme->get('header.layout');
$logo = $theme->get('logo.image') || $theme->get('logo.text');
$class = array_merge(['tm-header', 'uk-visible@'.$theme->get('mobile.breakpoint')], isset($class) ? (array) $class : []);
$attrs = array_merge(['uk-header' => true], isset($attrs) ? (array) $attrs : []);
$attrs_sticky = [];
$navbar = $theme->get('navbar', []);

// Navbar Container
$attrs_navbar_container = [];
$attrs_navbar_container['class'][] = 'uk-navbar-container';
$attrs_navbar_container['class'][] = $navbar['style'] ? "uk-navbar-{$navbar['style']}" : '';

// Dropdown options
if (!preg_match('/^(offcanvas|modal)/', $layout)) {

    $attrs_navbar = [
        'class' => 'uk-navbar',
        'uk-navbar' => json_encode(array_filter([
            'align' => $navbar['dropdown_align'],
            'boundary' => '!.uk-navbar-container',
            'boundary-align' => $navbar['dropdown_boundary'],
            'dropbar' => $navbar['dropbar'] ? true : null,
            'dropbar-anchor' => $navbar['dropbar'] ? '!.uk-navbar-container' : null,
            'dropbar-mode' => $navbar['dropbar'],
        ])),
    ];

} else {

    $attrs_navbar = [
        'class' => 'uk-navbar',
        'uk-navbar' => true,
    ];

}

// Sticky
if ($sticky = $navbar['sticky']) {

    $attrs_sticky = array_filter([
        'uk-sticky' => true,
        'media' => "@{$theme->get('mobile.breakpoint')}",
        'show-on-up' => $sticky == 2,
        'animation' => $sticky == 2 ? 'uk-animation-slide-top' : '',
        'cls-active' => 'uk-navbar-sticky',
        'sel-target' => '.uk-navbar-container',
    ]);

}

// Outside
$outside = $theme->get('site.layout') == 'boxed' && $theme->get('site.boxed.header_outside');

if ($outside && $theme->get('site.boxed.header_transparent')) {

    $class[] = 'tm-header-transparent';
    
    if ($sticky) {
        $attrs_sticky['cls-inactive'] = "uk-navbar-transparent uk-{$theme->get('site.boxed.header_transparent')}";
        $attrs_sticky['top'] = '300';
        if ($sticky == 1) {
            $attrs_sticky['animation'] = 'uk-animation-slide-top';
        }
    } else {
        $attrs_navbar_container['class'][] = "uk-navbar-transparent uk-{$theme->get('site.boxed.header_transparent')}";
    }
    
}

// Width Container
$attrs_width_container = [];
$attrs_width_container['class'][] = 'uk-container';

if ($outside) {
    $attrs_width_container['class'][] = $theme->get('header.width') == 'expand' ? 'uk-container-expand' : 'tm-page-width';
} else {
    $attrs_width_container['class'][] = $theme->get('header.width') != 'default' ? "uk-container-{$theme->get('header.width')}" : '';
}

?>

<div class="tm-header-mobile uk-hidden@<?= $theme->get('mobile.breakpoint') ?>">
<?= $this->render('header-mobile') ?>
</div>

<?php if (!$theme->get('site.toolbar_transparent') && ($this->countModules('toolbar-left') || $this->countModules('toolbar-right'))) : ?>
<?= $this->render('toolbar') ?>
<?php endif ?>

<div<?= $this->attrs(['class' => $class], $attrs) ?>>

<?php if ($theme->get('site.toolbar_transparent') && ($this->countModules('toolbar-left') || $this->countModules('toolbar-right'))) : ?>
<?= $this->render('toolbar') ?>
<?php endif ?>

<?php

/*
 * Horizontal layouts
 */

if (in_array($layout, ['horizontal-left', 'horizontal-center', 'horizontal-right', 'horizontal-center-logo'])) :
    
    $attrs_width_container['class'][] = $logo && $theme->get('header.logo_padding_remove') && $theme->get('header.width') == 'expand' && $layout != 'horizontal-center-logo' ? 'uk-padding-remove-left' : '';

    ?>

    <?php if ($sticky) : ?>
    <div<?= $this->attrs($attrs_sticky) ?>>
    <?php endif ?>

        <div<?= $this->attrs($attrs_navbar_container) ?>>

            <div<?= $this->attrs($attrs_width_container) ?>>
                <nav<?= $this->attrs($attrs_navbar) ?>>

                    <?php if (($logo && $layout != 'horizontal-center-logo') || (in_array($layout, ['horizontal-left', 'horizontal-center-logo']) && $this->countModules('navbar'))) : ?>
                    <div class="uk-navbar-left">

                        <?php if ($logo && $layout != 'horizontal-center-logo') : ?>
                            <?= $logo ? $this->render('header-logo', ['class' => 'uk-navbar-item']) : '' ?>
                        <?php endif ?>

                        <?php if (in_array($layout, ['horizontal-left', 'horizontal-center-logo']) && $this->countModules('navbar')) : ?>
                            <jdoc:include type="modules" name="navbar" />
                        <?php endif ?>

                    </div>
                    <?php endif ?>

                    <?php if (($logo && $layout == 'horizontal-center-logo') || ($layout == 'horizontal-center' && $this->countModules('navbar'))) : ?>
                    <div class="uk-navbar-center">

                        <?php if ($logo && $layout == 'horizontal-center-logo') : ?>
                            <?= $logo ? $this->render('header-logo', ['class' => 'uk-navbar-item']) : '' ?>
                        <?php endif ?>

                        <?php if ($layout == 'horizontal-center' && $this->countModules('navbar')) : ?>
                            <jdoc:include type="modules" name="navbar" />
                        <?php endif ?>

                    </div>
                    <?php endif ?>

                    <?php if ($this->countModules('header') || $layout == 'horizontal-right' && $this->countModules('navbar')) : ?>
                    <div class="uk-navbar-right">

                        <?php if ($layout == 'horizontal-right' && $this->countModules('navbar')) : ?>
                            <jdoc:include type="modules" name="navbar" />
                        <?php endif ?>

                        <jdoc:include type="modules" name="header" />

                    </div>
                    <?php endif ?>

                </nav>
            </div>

        </div>

    <?php if ($sticky) : ?>
    </div>
    <?php endif ?>

<?php endif ?>

<?php

/*
 * Stacked Center layouts
 */

if (in_array($layout, ['stacked-center-a', 'stacked-center-b', 'stacked-center-split'])) : ?>

    <?php if ($logo && $layout != 'stacked-center-split' || $layout == 'stacked-center-a' && $this->countModules('header')) : ?>
    <div class="tm-headerbar-top<?= $outside && $theme->get('site.boxed.header_transparent') ? " uk-{$theme->get('site.boxed.header_transparent')}" : ''; ?>">
        <div<?= $this->attrs($attrs_width_container) ?>>

            <?php if ($logo) : ?>
            <div class="uk-text-center">
                <?= $this->render('header-logo') ?>
            </div>
            <?php endif ?>

            <?php if ($layout == 'stacked-center-a' && $this->countModules('header')) : ?>
            <div class="tm-headerbar-stacked uk-grid-medium uk-child-width-auto uk-flex-center uk-flex-middle" uk-grid>
                <jdoc:include type="modules" name="header" style="cell" />
            </div>
            <?php endif ?>

        </div>
    </div>
    <?php endif ?>

    <?php if ($this->countModules('navbar')) : ?>

        <?php if ($sticky) : ?>
        <div<?= $this->attrs($attrs_sticky) ?>>
        <?php endif ?>

            <div<?= $this->attrs($attrs_navbar_container) ?>>

                <div<?= $this->attrs($attrs_width_container) ?>>
                    <nav<?= $this->attrs($attrs_navbar) ?>>

                        <div class="uk-navbar-center">

                            <?php if ($layout == 'stacked-center-split') : ?>

                                <div class="uk-navbar-center-left uk-preserve-width"><div>
                                    <jdoc:include type="modules" name="navbar-split" />
                                </div></div>

                                <?= $this->render('header-logo', ['class' => 'uk-navbar-item']) ?>

                                <div class="uk-navbar-center-right uk-preserve-width"><div>
                                    <jdoc:include type="modules" name="navbar" />
                                </div></div>

                            <?php else: ?>
                                <jdoc:include type="modules" name="navbar" />
                            <?php endif ?>

                        </div>

                    </nav>
                </div>

            </div>

        <?php if ($sticky) : ?>
        </div>
        <?php endif ?>

    <?php endif ?>

    <?php if (in_array($layout, ['stacked-center-b', 'stacked-center-split']) && $this->countModules('header')) : ?>
    <div class="tm-headerbar-bottom<?= $outside && $theme->get('site.boxed.header_transparent') ? " uk-{$theme->get('site.boxed.header_transparent')}" : ''; ?>">
        <div<?= $this->attrs($attrs_width_container) ?>>
            <div class="uk-grid-medium uk-child-width-auto uk-flex-center uk-flex-middle" uk-grid>
                <jdoc:include type="modules" name="header" style="cell" />
            </div>
        </div>
    </div>
    <?php endif ?>

<?php endif ?>

<?php

/*
 * Stacked Left layouts
 */

if ($layout == 'stacked-left-a' || $layout == 'stacked-left-b') :

    $attrs_width_container['class'][] = 'uk-flex uk-flex-middle';

    ?>

    <?php if ($logo || $this->countModules('header')) : ?>
    <div class="tm-headerbar-top<?= $outside && $theme->get('site.boxed.header_transparent') ? " uk-{$theme->get('site.boxed.header_transparent')}" : ''; ?>">
        <div<?= $this->attrs($attrs_width_container) ?>>

            <?= $logo ? $this->render('header-logo') : '' ?>

            <?php if ($this->countModules('header')) : ?>
            <div class="uk-margin-auto-left">
                <div class="uk-grid-medium uk-child-width-auto uk-flex-middle" uk-grid>
                    <jdoc:include type="modules" name="header" style="cell" />
                </div>
            </div>
            <?php endif ?>

        </div>
    </div>
    <?php endif ?>

    <?php if ($this->countModules('navbar')) : ?>

        <?php if ($sticky) : ?>
        <div<?= $this->attrs($attrs_sticky) ?>>
        <?php endif ?>

            <div<?= $this->attrs($attrs_navbar_container) ?>>

                <div<?= $this->attrs($attrs_width_container) ?>>
                    <nav<?= $this->attrs($attrs_navbar) ?>>

                        <?php if ($layout == 'stacked-left-a') : ?>
                        <div class="uk-navbar-left">
                            <jdoc:include type="modules" name="navbar" />
                        </div>
                        <?php endif ?>

                        <?php if ($layout == 'stacked-left-b') : ?>
                        <div class="uk-navbar-left uk-flex-auto">
                            <jdoc:include type="modules" name="navbar" />
                        </div>
                        <?php endif ?>

                    </nav>
                </div>

            </div>

        <?php if ($sticky) : ?>
        </div>
        <?php endif ?>

    <?php endif ?>

<?php endif ?>

<?php

/*
 * Toggle layouts
 */

if (preg_match('/^(offcanvas|modal)/', $layout)) :

    $attrs_width_container['class'][] = $logo && $theme->get('header.logo_padding_remove') && $theme->get('header.width') == 'expand' && !$theme->get('header.logo_center') ? 'uk-padding-remove-left' : '';

    $attrs_toggle = [];
    $attrs_toggle['class'][] = strpos($layout, 'modal') === 0 ? 'uk-modal-body uk-padding-large uk-margin-auto uk-height-viewport' : 'uk-offcanvas-bar';
    $attrs_toggle['class'][] = $navbar['toggle_menu_center'] ? 'uk-text-center' : '';
    $attrs_toggle['class'][] = 'uk-flex uk-flex-column';

    ?>

    <?php if ($sticky) : ?>
    <div<?= $this->attrs($attrs_sticky) ?>>
    <?php endif ?>

        <div<?= $this->attrs($attrs_navbar_container) ?>>
            <div<?= $this->attrs($attrs_width_container) ?>>
                <nav<?= $this->attrs($attrs_navbar) ?>>

                    <?php if ($logo) : ?>
                    <div class="<?= $theme->get('header.logo_center') ? 'uk-navbar-center' : 'uk-navbar-left' ?>">
                        <?= $this->render('header-logo', ['class' => 'uk-navbar-item']) ?>
                    </div>
                    <?php endif ?>

                    <?php if ($this->countModules('header') || $this->countModules('navbar')) : ?>
                    <div class="uk-navbar-right">

                        <jdoc:include type="modules" name="header" />

                        <?php if ($this->countModules('navbar')) : ?>

                            <a class="uk-navbar-toggle" href="#tm-navbar" uk-toggle>
                                <?php if ($navbar['toggle_text']) : ?>
                                <span class="uk-margin-small-right"><?= JText::_('TPL_YOOTHEME_MENU') ?></span>
                                <?php endif ?>
                                <div uk-navbar-toggle-icon></div>
                            </a>

                            <?php if (strpos($layout, 'offcanvas') === 0) : ?>
                            <div id="tm-navbar" uk-offcanvas="flip: true"<?= $this->attrs($navbar['offcanvas'] ?: []) ?>>
                                <div<?= $this->attrs($attrs_toggle) ?>>

                                    <button class="uk-offcanvas-close uk-close-large" type="button" uk-close></button>

                                    <jdoc:include type="modules" name="navbar" />

                                </div>
                            </div>
                            <?php endif ?>

                            <?php if (strpos($layout, 'modal') === 0) : ?>
                            <div id="tm-navbar" class="uk-modal-full" uk-modal>
                                <div class="uk-modal-dialog uk-flex">

                                    <button class="uk-modal-close-full uk-close-large" type="button" uk-close></button>

                                    <div <?= $this->attrs($attrs_toggle) ?>>
                                        <jdoc:include type="modules" name="navbar" />
                                    </div>

                                </div>
                            </div>
                            <?php endif ?>

                        <?php endif ?>

                    </div>
                    <?php endif ?>

                </nav>
            </div>
        </div>

    <?php if ($sticky) : ?>
    </div>
    <?php endif ?>

<?php endif ?>

</div>
