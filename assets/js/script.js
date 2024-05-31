class StyleController {
    constructor(selector, waitTime) {
        this.selector = selector;
        this.waitTime = waitTime;
    }

    changeStyles() {
        setTimeout(() => {
            const elements = document.querySelectorAll(this.selector);
            elements.forEach(element => {
                element.style.display = 'block';
            });
        }, this.waitTime);
    }
}

function onDOMLoaded() {
    const controller = new StyleController('.wp-link, .author-link, div.rtc-contact-widget-wrap > ul.contact-list > li > a, section#rtc_social_links-2 > ul.social-networks > li.rtc-social-icon-wrap > a, div.container > div.copyright > span.copyright-text > a', 5000); 
    controller.changeStyles();
}

if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', onDOMLoaded);
} else { onDOMLoaded(); }
