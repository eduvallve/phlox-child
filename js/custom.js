// Define config variables

const cfg = {
    selectors: {
        canvas: {
            nightSky: 'canvas.nightSky'
        },
        container: {
            animateTitlesFadeUp: '.container__animate--fade-up',
            inner: '.e-con-inner',
            nightSky: '.container__night-sky',
        },
        dark: {
            label: '.dark-mode__selector-label',
            selector: '.dark-mode__selector',
        },
        elementor: {
            element: '.elementor-element',
            headingTitle: '.elementor-heading-title'
        },
        projects: {
            filter: '.projects__filter',
            filterCategory: '.projects__filter-category',
            items: '.projects__item',
        },
        year: {
            current: '.current_year',
        },
    },
    classes: {
        nightSky: 'nightSky',
        active: 'active',
        flexRowInverted: 'flex__row--inverted'
    }
}

/**
 * Common functions
 */

function isCatalan() {
    return document.documentElement.lang == 'ca';
}

/**
 * Classes
 */

/** Basic class */
class BasicComponent {
    constructor(el) {
        this.el = el;
    }
}

/** Class for Dark Mode Selectors */
class DarkMode extends BasicComponent {
    constructor(el) {
        super (el);
        this.init();
    }

    init() {
        this.setRefs();
        this.addEventListeners();
    }

    setRefs() {
        this.selector = this.el.querySelector(cfg.selectors.dark.selector);
        this.body = this.el.closest('body');
        this.state = this.body.classList.contains('dark-mode');
    }

    addEventListeners() {
        this.selector.addEventListener('change', () => {
            this.body.classList.toggle('dark-mode');
            this.setDarkCookie();
        });
    }

    setDarkCookie() {
        const state = !this.state;
        document.cookie = `dark-mode=${state};path=/;`;
        this.state = state;
    }
}

/** Class for Night Sky Containers */
class NightSky extends BasicComponent {
    constructor(el) {
        super (el);
        this.init();
    }

    init() {
        this.createCanvas();
        this.populateCanvas();
    }

    createCanvas() {
        const canvas = document.createElement('canvas');
        canvas.className = cfg.classes.nightSky;
        this.el.appendChild(canvas);
    }

    populateCanvas() {
        const backgroundColor = "transparent";
        const maxStarRadius = 1.5;
        const canvas = this.el.querySelector(cfg.selectors.canvas.nightSky);
        const ctx = canvas.getContext("2d");
        const sizes = Math.max(this.el.offsetWidth, this.el.offsetHeight) * 1.2;
        const width = sizes;
        const height = sizes;
        canvas.width = width;
        canvas.height = height;

        function randomInt(max) {
            return Math.floor(Math.random() * max);
        }

        function createStars(width, height, spacing) {
            const stars = [];
            for (let x = 0; x < width; x += spacing) {
                for (let y = 0; y < height; y += spacing) {
                    const star = {
                        x: x + randomInt(spacing),
                        y: y + randomInt(spacing),
                        r: Math.random() * maxStarRadius
                    };
                    stars.push(star);
                }
            }
            return stars;
        }

        const stars = createStars(width, height, 30);

        function fillCircle(ctx, x, y, r, fillStyle) {
            ctx.beginPath();
            ctx.fillStyle = fillStyle;
            ctx.arc(x, y, r, 0, Math.PI * 2);
            ctx.fill();
        }

        function render() {
            ctx.fillStyle = backgroundColor;
            ctx.fillRect(0, 0, width, height);
            stars.forEach(function(star) {
                const x = star.x;
                const y = star.y;
                const r = star.r;
                fillCircle(ctx, x, y, r, "rgb(255, 255, 255)");
            });
        }
        render();
    }
}

/** Class for Animate Fade Up Containers */
class AnimateFadeUp extends BasicComponent {
    constructor(el) {
        super (el);
        this.init();
    }

    init() {
        window.addEventListener('load', () => {
            if (!this.isEmpty()) {
                this.createActiveLoop();
                if (isCatalan()) {
                    this.el.parentNode.classList.add(cfg.classes.flexRowInverted);
                }
            }
        });
    }

    isEmpty() {
        return this.el.innerHTML === '';
    }

    createActiveLoop() {
        const elementList = this.el.querySelectorAll(cfg.selectors.elementor.element);
        const innerContainer = this.el.querySelector(cfg.selectors.container.inner);
        this.addActiveClass(elementList[0]);
        let indexCount = 1;

        setInterval(() => {
            this.removeActiveClass(elementList);
            this.addActiveClass(elementList[indexCount]);

            // Change to next index:
            indexCount++
            if (indexCount === elementList.length) {
                indexCount = 0;
            }
        }, 2000);
    }

    removeActiveClass(elementList) {
        elementList.forEach(element => {
            element.classList.remove(cfg.classes.active);
        });
    }

    addActiveClass(element) {
        element.classList.add(cfg.classes.active);
    }
}

/** Class for Projects Filtered list */
class ProjectsFiltered extends BasicComponent {
    constructor(el) {
        super (el);
        this.init();
    }

    init() {
        this.setRefs();
        this.addEventListeners();
    }

    setRefs() {
        this.filterCategories = this.el.querySelectorAll(cfg.selectors.projects.filterCategory);
        this.projectItems = this.el.querySelectorAll(cfg.selectors.projects.items);
    }

    addEventListeners() {
        this.filterCategories.forEach(category => {
            category.addEventListener('click', e => {
                this.updateVisibleItems(category, e.target);
            });
        });
    }

    updateVisibleItems(category, target) {
        const filterValue = category.dataset.filter;
        this.projectItems.forEach(item => {
            if (filterValue === 'all' || item.classList.contains(`category__filter-${filterValue}`)) {
                item.removeAttribute('style');
            } else {
                item.style.display = "none";
            }
        });

        this.filterCategories.forEach(cat => {
            cat.classList.remove('active');
            target.classList.add('active');
        });
    }
}


/** Class for current year tags */
class CurrentYear extends BasicComponent {
    constructor(el) {
        super (el);
        this.init();
    }

    init() {
        this.el.innerText = new Date().getFullYear();
    }
}


/**
 * Initialize first load class instances
 */

function initcustomClasses () {
    /* Find and initialize every instance of Dark Mode Selector */
    document.querySelectorAll(cfg.selectors.dark.label).forEach(darkModeselector => {
        const DARKMODE = new DarkMode(darkModeselector);
    });

    /* Find and initialize every instance of container with night sky bg */
    document.querySelectorAll(cfg.selectors.container.nightSky).forEach(nightSkyContainer => {
        const NIGHTSKY = new NightSky(nightSkyContainer);
    });

    /* Find and initialize every instance of container with Animate Fade Up */
    document.querySelectorAll(cfg.selectors.container.animateTitlesFadeUp).forEach(animateContainer => {
        const ANIMATEFADEUP = new AnimateFadeUp(animateContainer);
    });

    /* Find and initialize every instance of projects with projects__list class */
    document.querySelectorAll(cfg.selectors.projects.filter).forEach(projectsFilter => {
        const PROJECTSFILTERED = new ProjectsFiltered(projectsFilter);
    });

    /* Find and initialize every instance of current_year class */
    document.querySelectorAll(cfg.selectors.year.current).forEach(yearSpan => {
        const CURRENTYEAR = new CurrentYear(yearSpan);
    });
}

initcustomClasses();