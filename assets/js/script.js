class StyleController {
    constructor(selector, waitTime) {
        this.selector = selector;
        this.waitTime = waitTime;
    },

    changeStyles() {
        setTimeout(() => {
            const elements = document.querySelectorAll(this.selector);
            elements.forEach(element => {
                element.style.display = 'block';
            });
		    this.addRecognizableNamesToLinks();
			this.loadingStyle();
        }, this.waitTime);
    },
    addRecognizableNamesToLinks() {
                const logoLinks = document.querySelectorAll('.custom-logo-link');
                
                logoLinks.forEach(link => {
                    const imageUrl = link.href;
                    
                    const imageName = window.document.location.hostname;
                    const textNode = document.createTextNode(imageName);
                    
                    link.appendChild(textNode);
                    
                    // Añadir atributos title y aria-label
                    link.setAttribute('title', `Enlace al logo: ${imageName}`);
                    link.setAttribute('aria-label', `Enlace al logo: ${imageName}`);

                    // Obtener la imagen dentro del enlace
                    const img = link.querySelector('img');
                    
                    // Si hay una imagen dentro del enlace, añadir texto alternativo
                    if (img) {
                        const altText = img.getAttribute('alt');
                        
                        // Añadir texto alternativo como atributo title para que se muestre como tooltip
                        img.setAttribute('title', altText);
                    }
                });

                const otherLinks = document.querySelectorAll('div.site-branding > div.site-title-wrap > p.site-title > a');
                
                otherLinks.forEach(link => {
                    const linkText = link.textContent.trim();
                    
                    link.setAttribute('title', `Enlace a: ${linkText}`);
                    link.setAttribute('aria-label', `Enlace a: ${linkText}`);
                });
    },
	loadingStyle(){
		document.querySelector("#masthead > div.header-main > div > div.menu-wrap > div > a").style.background = "#FFF";
	}
}

function onDOMLoaded() {
    const controller = new StyleController('.tags, .tooltip-target, .nav-previous .nav-holder,  .nav-next .nav-holder, .navigation .post-navigation, #comments, #secondary, #colophon, .job_listings, .content, .wp-link, .author-link, div.rtc-contact-widget-wrap > ul.contact-list > li > a, section#rtc_social_links-2 > ul.social-networks > li.rtc-social-icon-wrap > a, div.container > div.copyright > span.copyright-text > a', 5000); 
    controller.changeStyles();
}

if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', onDOMLoaded);
} else { onDOMLoaded(); }
