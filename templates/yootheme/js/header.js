import {getComponent} from 'uikit';
import {$, $$, addClass, attr, before, css, each, hasAttr, offset, removeClass} from 'uikit-util';

const selector = '.tm-header ~ [class*="uk-section"], .tm-header ~ :not(.tm-page) > [class*="uk-section"]';

export default {

    update: [

        {
            read() {

                const section = $(selector);
                const modifier = attr(section, 'tm-header-transparent');

                if (!modifier || !section) {
                    return false;
                }

                const sticky = findComponent(this.$el, 'sticky');

                if (sticky) {

                    const options = sticky.$options.data;

                    if (options.animation !== 'uk-animation-slide-top') {
                        each({
                            top: selector,
                            animation: 'uk-animation-slide-top',
                            clsInactive: `uk-navbar-transparent uk-${modifier}`
                        }, (value, key) => options[key] = sticky[key] = sticky.$props[key] = value);
                    }

                    sticky.$props.top = section.offsetHeight <= window.innerHeight ? selector : offset(section).top + 300;
                }

                return {
                    section,
                    modifier,
                    height: this.$el.offsetHeight
                };

            },

            write({height, modifier, section}) {

                if (!this.placeholder) {

                    addClass(this.$el, 'tm-header-transparent tm-header-overlay');
                    addClass($$('.tm-headerbar-top, .tm-headerbar-bottom, .tm-toolbar-transparent'), `uk-${modifier}`);
                    removeClass($('.tm-toolbar-transparent.tm-toolbar-default'), 'tm-toolbar-default');
                    addClass(findComponent(this.$el, 'navbar').dropbar, 'uk-navbar-dropbar-slide');

                    if (!$('[uk-sticky]', this.$el)) {
                        addClass($('.uk-navbar-container', this.$el), `uk-navbar-transparent uk-${modifier}`);
                    }

                    const anchor = $('[uk-grid]', section);
                    this.placeholder = anchor && hasAttr(section, 'tm-header-transparent-placeholder')
                        && before(anchor, '<div class="tm-header-placeholder uk-margin-remove-adjacent"></div>');

                }

                css(this.placeholder, {height});
            },

            events: ['resize']
        }

    ]

};

function findComponent(el, component) {
    return getComponent($(`[uk-${component}]`, el), component);
}
