 class CssUpdater {
            constructor() {
                this.applyImportantStyles();
            }

            applyImportantStyles() {
                const elements = document.querySelectorAll('.post-navigation .nav-links .meta-nav');

                elements.forEach(element => {
                    element.style.setProperty('color', '#03260e', 'important');
                    element.style.setProperty('background-color', '#2ace5e', 'important');
                });
            }
        }

new CssUpdater();

class AccessibilityAdjuster {
    constructor() {
        this.addAriaLabelToToggleButton();
	this.removeSpecifiedElements();
    }
    removeSpecifiedElements() {
        const selectors = [
            '.site-title-wrap',
	    'div.site-branding > div.site-title-wrap > p.site-title > a[rel="home"][itemprop="url"]'
        ];

        selectors.forEach(selector => {
            const elements = document.querySelectorAll(selector);
            elements.forEach(element => element.remove());
        });
    }
    addAriaLabelToToggleButton() {
        const toggleButtons = document.querySelectorAll('button.toggle-btn');
        toggleButtons.forEach(button => {
            if (!button.hasAttribute('aria-label')) {
                button.setAttribute('aria-label', 'Abrir menú de navegación');
            }
        });
    }
}

new AccessibilityAdjuster();

class SetterAdjuster {
    constructor(targetContrast) {
        this.targetContrast = targetContrast;
        this.adjustJobTypeColors();

	this.adjustButtonColors();
    }

    calculateContrast(color1, color2) {
        
        function luminance(hexColor) {
            const rgb = parseInt(hexColor.slice(1), 16);
            const r = (rgb >> 16) & 0xff; 
            const g = (rgb >> 8) & 0xff;  
            const b = (rgb >> 0) & 0xff;  
            const luminance = 0.2126 * r + 0.7152 * g + 0.0722 * b; 
            return luminance / 255; 
        }

        const lum1 = luminance(color1);
        const lum2 = luminance(color2);

        const contrast = (Math.max(lum1, lum2) + 0.05) / (Math.min(lum1, lum2) + 0.05);

        return contrast;
    }

    adjustColor(background, foreground, targetContrast) {
        const currentContrast = this.calculateContrast(background, foreground);

        if (currentContrast < targetContrast) {
            const adjustedForeground = '#03260e'; 

            return adjustedForeground;
        }

        return foreground;
    }

    adjustJobTypeColors() {
        const jobTypeElements = document.querySelectorAll('.job-type');
        jobTypeElements.forEach(element => {
            const computedStyles = getComputedStyle(element);
            const backgroundColor = computedStyles.backgroundColor;
            const color = computedStyles.color;
            if (backgroundColor && color) {
                const adjustedColor = this.adjustColor(backgroundColor, color, this.targetContrast);
                element.style.color = adjustedColor;
            }
        });
    }
    
    adjustButtonColors() {
        const buttonElements = document.querySelectorAll('a.btn');
        buttonElements.forEach(element => {
            const computedStyles = getComputedStyle(element);
            const backgroundColor = computedStyles.backgroundColor;
            const color = computedStyles.color;
            if (backgroundColor && color) {
                const adjustedColor = this.adjustColor(backgroundColor, color, this.targetContrast);
                element.style.color = adjustedColor;
            }
        });
    }
}
new SetterAdjuster(8.5);

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
	setTimeout(() => { this.loadingStyle(); }, this.waitTime);
        }, this.waitTime);
    }
    addRecognizableNamesToLinks() {
                const logoLinks = document.querySelectorAll('.custom-logo-link');

	    	if (null!=logoLinks)
                logoLinks.forEach(link => {
                    const imageUrl = link.href;
                    
                    const imageName = window.document.location.hostname;
                    
                    link.setAttribute('title', `Enlace al logo: ${imageName}`);
                    link.setAttribute('aria-label', `Enlace al logo: ${imageName}`);

                    const img = link.querySelector('img');
                    
                    if (img) {
                        const altText = img.getAttribute('alt');
                        
                        img.setAttribute('title', altText);
                    }
                });

                const otherLinks = document.querySelectorAll('div.site-branding > div.site-title-wrap > p.site-title > a');
                
                otherLinks.forEach(link => {
                    const linkText = link.textContent.trim();
                    
                    link.setAttribute('title', `Enlace a: ${linkText}`);
                    link.setAttribute('aria-label', `Enlace a: ${linkText}`);
                });
    }
	loadingStyle(){
		document.querySelector("#masthead > div.header-main > div > div.menu-wrap > div > a").style.background = "#FFF";
		document.querySelector("#masthead > div.header-main > div > div.menu-wrap > div > a").style.color = "#03260e";
		setTimeout(() => { this.addRecognizableNamesToLinks();  }, this.waitTime);
	}
}

function onDOMLoaded() {
    const controller = new StyleController('.btn, .meta-nav, #masthead > div.header-main > div > div.menu-wrap > div > a,.tags, .tooltip-target, .nav-previous .nav-holder,  .nav-next .nav-holder, .navigation .post-navigation, #comments, #secondary, #colophon, .job_listings, .content, .wp-link, .author-link, div.rtc-contact-widget-wrap > ul.contact-list > li > a, section#rtc_social_links-2 > ul.social-networks > li.rtc-social-icon-wrap > a, div.container > div.copyright > span.copyright-text > a', 5000); 
    controller.changeStyles();
}

if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', onDOMLoaded);
} else { onDOMLoaded(); }
