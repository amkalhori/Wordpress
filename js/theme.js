document.addEventListener('DOMContentLoaded', () => {
    const debugEnabled = Boolean(window?.themeMods?.debug_mode);
    const callamirLog = (...args) => {
        if (debugEnabled && typeof window.console !== 'undefined') {
            window.console.log(...args);
        }
    };


    // --- Modern Mobile Menu Toggle ---
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileMenu = document.querySelector('.nav-mobile');
    const body = document.body;

    if (mobileMenuToggle && mobileMenu) {
        mobileMenuToggle.addEventListener('click', (e) => {
            e.preventDefault();
            const isExpanded = mobileMenuToggle.getAttribute('aria-expanded') === 'true';
            
            // Toggle menu visibility
            mobileMenuToggle.classList.toggle('active');
            mobileMenu.classList.toggle('active');
            mobileMenuToggle.setAttribute('aria-expanded', !isExpanded);
            
            // Prevent body scroll when menu is open
            if (!isExpanded) {
                body.style.overflow = 'hidden';
            } else {
                body.style.overflow = '';
            }
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!mobileMenu.contains(e.target) && !mobileMenuToggle.contains(e.target)) {
                if (mobileMenu.classList.contains('active')) {
                    mobileMenuToggle.classList.remove('active');
                    mobileMenu.classList.remove('active');
                    mobileMenuToggle.setAttribute('aria-expanded', 'false');
                    body.style.overflow = '';
                }
            }
        });

        // Close mobile menu on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
                mobileMenuToggle.classList.remove('active');
                mobileMenu.classList.remove('active');
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
                body.style.overflow = '';
            }
        });

        // Close mobile menu when window is resized to desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768 && mobileMenu.classList.contains('active')) {
                mobileMenuToggle.classList.remove('active');
                mobileMenu.classList.remove('active');
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
                body.style.overflow = '';
            }
        });
    }

    // --- Cookie Functions ---
    const setCookie = (name, value, days) => {
        let expires = '';
        if (days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = `; expires=${date.toUTCString()}`;
        }
        document.cookie = `${name}=${value || ''}${expires}; path=/`;
    };

    const getCookie = (name) => {
        const nameEQ = `${name}=`;
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    };

    // --- Language Switcher ---
    const languageLinks = document.querySelectorAll('.lang-flag-link');

    if (languageLinks.length > 0) {
        languageLinks.forEach((link) => {
            link.addEventListener('click', (event) => {
                const lang = link.getAttribute('data-lang');

                if (!lang) {
                    return;
                }

                event.preventDefault();
                setCookie('language', lang, 7);

                const targetUrl = link.getAttribute('href');
                if (targetUrl) {
                    window.location.href = targetUrl;
                }
            });
        });
    }

    // --- Modern Navigation with Smooth Scrolling ---
    const navLinks = document.querySelectorAll('.nav-menu-desktop a, .nav-menu-mobile a');
    if (navLinks.length > 0) {
        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                const href = link.getAttribute('href');
                
                // Only handle anchor links
                if (href && href.startsWith('#')) {
                    e.preventDefault();
                    const targetId = href.substring(1);
                    const targetSection = document.getElementById(targetId);
                    
                    if (targetSection) {
                        // Close mobile menu if open
                        if (mobileMenu && mobileMenu.classList.contains('active')) {
                            mobileMenuToggle.classList.remove('active');
                            mobileMenu.classList.remove('active');
                            mobileMenuToggle.setAttribute('aria-expanded', 'false');
                            body.style.overflow = '';
                        }
                        
                        // Smooth scroll to target
                        targetSection.scrollIntoView({ 
                            behavior: 'smooth',
                            block: 'start'
                        });
                        
                        // Add visual feedback
                        link.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            link.style.transform = '';
                        }, 150);
                    }
                }
            });
        });
    } else {
        console.warn('No navigation links found');
    }

    // --- Sticky Header Scroll Effects ---
    const header = document.querySelector('.site-header');
    let lastScrollY = window.scrollY;
    let ticking = false;

    function updateHeader() {
        const scrollY = window.scrollY;
        
        if (scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
        
        lastScrollY = scrollY;
        ticking = false;
    }

    function requestTick() {
        if (!ticking) {
            requestAnimationFrame(updateHeader);
            ticking = true;
        }
    }

    window.addEventListener('scroll', requestTick, { passive: true });

    // --- Active Menu Item Highlighting ---
    const sections = document.querySelectorAll('section[id]');
    const menuItems = document.querySelectorAll('.nav-menu-desktop a, .nav-menu-mobile a');
    
    function updateActiveMenuItem() {
        let current = '';
        const scrollPos = window.scrollY + 100;
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.offsetHeight;
            
            if (scrollPos >= sectionTop && scrollPos < sectionTop + sectionHeight) {
                current = section.getAttribute('id');
            }
        });
        
        menuItems.forEach(item => {
            item.classList.remove('active');
            if (item.getAttribute('href') === `#${current}`) {
                item.classList.add('active');
            }
        });
    }
    
    // Update active menu item on scroll with throttling for performance
    let scrollTimeout;
    function throttledUpdateActiveMenuItem() {
        if (scrollTimeout) return;
        scrollTimeout = setTimeout(() => {
            updateActiveMenuItem();
            scrollTimeout = null;
        }, 16); // ~60fps
    }
    
    window.addEventListener('scroll', throttledUpdateActiveMenuItem, { passive: true });
    updateActiveMenuItem(); // Initial call

    // --- FAQ Accordion ---
    const faqQuestions = document.querySelectorAll('.faq-question');
    faqQuestions.forEach(btn => {
        btn.addEventListener('click', () => {
            const expanded = btn.getAttribute('aria-expanded') === 'true';
            btn.setAttribute('aria-expanded', String(!expanded));
            const panel = btn.nextElementSibling;
            if (panel) {
                if (expanded) {
                    panel.hidden = true;
                } else {
                    panel.hidden = false;
                }
            }
        });
    });

    // --- Star Animation ---
    const initStarEffect = (canvasId, starCount) => {
        const canvas = document.getElementById(canvasId);
        callamirLog(`Star canvas ${canvasId} found:`, canvas);
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        let stars = [];

        const resizeCanvas = () => {
            canvas.width = window.innerWidth;
            canvas.height = canvas.height || window.innerHeight;
            stars = [];
            for (let i = 0; i < starCount; i++) {
                stars.push({
                    x: Math.random() * canvas.width,
                    y: Math.random() * canvas.height,
                    radius: Math.random() * 1.5 + 0.5,
                    speed: Math.random() * 0.5 + 0.2
                });
            }
        };

        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        const animateStars = () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = 'white';
            stars.forEach(star => {
                ctx.beginPath();
                ctx.arc(star.x, star.y, star.radius, 0, Math.PI * 2);
                ctx.fill();
                star.y -= star.speed;
                if (star.y < 0) star.y = canvas.height;
            });
            callamirLog('Star animation started for', canvasId);
            requestAnimationFrame(animateStars);
        };

        animateStars();
    };

    // --- Blackhole Animation (Hero) ---
    const initBlackholeEffect = () => {
        const canvas = document.getElementById('blackhole');
        callamirLog('Blackhole canvas found:', canvas);
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        // Black hole center
        const blackHole = {
            x: canvas.width / 2,
            y: canvas.height / 2,
            radius: 50
        };

        if (window.themeMods.hero_blackhole_pattern === 'circular') {
            // Circular Pattern
            let particles = [];
            const resizeCanvas = () => {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
                blackHole.x = canvas.width / 2;
                blackHole.y = canvas.height / 2;
                particles = [];
                for (let i = 0; i < window.themeMods.hero_circle_count; i++) {
                    const angle = Math.random() * Math.PI * 2;
                    const radius = Math.random() * 200 + 50;
                    particles.push({
                        x: blackHole.x + Math.cos(angle) * radius,
                        y: blackHole.y + Math.sin(angle) * radius,
                        radius: Math.random() * 2 + 1,
                        angle: angle,
                        speed: Math.random() * 0.02 + 0.01
                    });
                }
            };

            resizeCanvas();
            window.addEventListener('resize', resizeCanvas);

            const animate = () => {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                ctx.fillStyle = 'rgba(255, 255, 255, 0.8)';
                particles.forEach(particle => {
                    particle.angle += particle.speed;
                    particle.x = blackHole.x + Math.cos(particle.angle) * 100;
                    particle.y = blackHole.y + Math.sin(particle.angle) * 100;
                    ctx.beginPath();
                    ctx.arc(particle.x, particle.y, particle.radius, 0, Math.PI * 2);
                    ctx.fill();
                });
                callamirLog('Blackhole animation started:', window.themeMods.hero_blackhole_pattern);
                requestAnimationFrame(animate);
            };

            animate();
        } else {
            // Hexagon Network Pattern
            const stars = [];
            const numStars = window.themeMods.hero_star_count;
            const numNodes = window.themeMods.hero_circle_count;
            class Star {
                constructor() {
                    this.x = Math.random() * canvas.width;
                    this.y = Math.random() * canvas.height;
                    this.size = Math.random() * 2 + 1;
                    this.twinkle = Math.random() * 0.5 + 0.5;
                }
                draw() {
                    ctx.fillStyle = `rgba(255, 255, 255, ${this.twinkle})`;
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                    ctx.fill();
                    this.twinkle += Math.random() * 0.05 - 0.025;
                    if (this.twinkle < 0.5) this.twinkle = 0.5;
                    if (this.twinkle > 1) this.twinkle = 1;
                }
            }
            class Node {
                constructor() {
                    this.x = Math.random() * canvas.width;
                    this.y = Math.random() * canvas.height;
                    this.size = Math.random() * 4 + 3;
                    this.speedX = (Math.random() - 0.5) * 0.5;
                    this.speedY = (Math.random() - 0.5) * 0.5;
                    this.life = 200;
                    this.phase = 'expand';
                }
                reset() {
                    this.x = Math.random() * canvas.width;
                    this.y = Math.random() * canvas.height;
                    this.size = Math.random() * 4 + 3;
                    this.speedX = (Math.random() - 0.5) * 0.5;
                    this.speedY = (Math.random() - 0.5) * 0.5;
                    this.life = 200;
                    this.phase = 'expand';
                }
                update() {
                    const distToBlackHole = Math.hypot(this.x - blackHole.x, this.y - blackHole.y);
                    if (this.phase === 'expand') {
                        this.x += this.speedX;
                        this.y += this.speedY;
                        this.life--;
                        if (this.life <= 0 || distToBlackHole > canvas.width / 2) {
                            this.phase = 'collapse';
                        }
                    } else {
                        const angle = Math.atan2(blackHole.y - this.y, blackHole.x - this.x);
                        this.x += Math.cos(angle) * 1.5;
                        this.y += Math.sin(angle) * 1.5;
                        if (distToBlackHole < blackHole.radius) {
                            this.reset();
                        }
                    }
                }
                draw() {
                    ctx.fillStyle = `rgba(255, 215, 0, ${this.phase === 'expand' ? this.life / 200 : 1})`;
                    ctx.strokeStyle = `rgba(27, 38, 59, ${this.phase === 'expand' ? this.life / 200 : 1})`;
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    for (let i = 0; i < 6; i++) {
                        const angle = (Math.PI / 3) * i;
                        const x = this.x + this.size * Math.cos(angle);
                        const y = this.y + this.size * Math.sin(angle);
                        if (i === 0) {
                            ctx.moveTo(x, y);
                        } else {
                            ctx.lineTo(x, y);
                        }
                    }
                    ctx.closePath();
                    ctx.fill();
                    ctx.stroke();
                }
            }

            let nodes = [];
            for (let i = 0; i < numStars; i++) {
                stars.push(new Star());
            }
            for (let i = 0; i < numNodes; i++) {
                nodes.push(new Node());
            }

            const animate = () => {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                const gradient = ctx.createRadialGradient(blackHole.x, blackHole.y, 0, blackHole.x, blackHole.y, blackHole.radius);
                gradient.addColorStop(0, '#0A0A0A');
                gradient.addColorStop(1, 'rgba(27, 38, 59, 0)');
                ctx.fillStyle = gradient;
                ctx.beginPath();
                ctx.arc(blackHole.x, blackHole.y, blackHole.radius, 0, Math.PI * 2);
                ctx.fill();
                stars.forEach(star => star.draw());
                nodes.forEach(node => {
                    node.update();
                    node.draw();
                    nodes.forEach(otherNode => {
                        const dist = Math.hypot(node.x - otherNode.x, node.y - otherNode.y);
                        if (dist < 120 && node.phase === 'expand' && otherNode.phase === 'expand') {
                            ctx.strokeStyle = `rgba(27, 38, 59, ${1 - dist / 120})`;
                            ctx.lineWidth = 1.5;
                            ctx.beginPath();
                            ctx.moveTo(node.x, node.y);
                            ctx.lineTo(otherNode.x, otherNode.y);
                            ctx.stroke();
                        }
                    });
                });
                callamirLog('Blackhole animation started:', window.themeMods.hero_blackhole_pattern);
                requestAnimationFrame(animate);
            };
            animate();

            window.addEventListener('resize', () => {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
                blackHole.x = canvas.width / 2;
                blackHole.y = canvas.height / 2;
                nodes = [];
                for (let i = 0; i < numNodes; i++) {
                    nodes.push(new Node());
                }
            });
        }
    };

    // --- Services Animation ---
    const initServicesEffect = () => {
        const canvas = document.getElementById('services-canvas');
        callamirLog('Services canvas found:', canvas);
        if (!canvas) {
            console.error('Services canvas not found!');
            return;
        }
        
        // Test canvas visibility
        callamirLog('Canvas dimensions:', canvas.width, 'x', canvas.height);
        callamirLog('Canvas position:', canvas.offsetTop, canvas.offsetLeft);
        callamirLog('Canvas z-index:', window.getComputedStyle(canvas).zIndex);
        const ctx = canvas.getContext('2d');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        
        // Test drawing to ensure canvas is working
        ctx.fillStyle = 'rgba(255, 215, 0, 0.1)';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        callamirLog('Canvas test drawing completed');

        const pattern = window.themeMods ? window.themeMods.services_pattern : 'circular';
        if (pattern === 'circular') {
            // Circular Pattern
            let circles = [];
            const resizeCanvas = () => {
                canvas.width = window.innerWidth;
                canvas.height = canvas.height || window.innerHeight;
                circles = [];
                for (let i = 0; i < (window.themeMods.services_circle_count || 50); i++) {
                    circles.push({
                        x: Math.random() * canvas.width,
                        y: Math.random() * canvas.height,
                        radius: Math.random() * 5 + 2,
                        dx: (Math.random() - 0.5) * 2,
                        dy: (Math.random() - 0.5) * 2
                    });
                }
            };

            resizeCanvas();
            window.addEventListener('resize', () => {
                resizeCanvas();
                circles.forEach(circle => {
                    circle.x = Math.random() * canvas.width;
                    circle.y = Math.random() * canvas.height;
                });
            });

            const animateCircles = () => {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                ctx.fillStyle = 'rgba(255, 215, 0, 0.5)';
                circles.forEach(circle => {
                    ctx.beginPath();
                    ctx.arc(circle.x, circle.y, circle.radius, 0, Math.PI * 2);
                    ctx.fill();
                    circle.x += circle.dx;
                    circle.y += circle.dy;
                    if (circle.x + circle.radius > canvas.width || circle.x - circle.radius < 0) circle.dx = -circle.dx;
                    if (circle.y + circle.radius > canvas.height || circle.y - circle.radius < 0) circle.dy = -circle.dy;
                });
                requestAnimationFrame(animateCircles);
            };

            animateCircles();
            callamirLog('Services circular animation started');
            
            // Add a simple test animation to verify it's working
            setTimeout(() => {
                callamirLog('Services animation test: Drawing test circles');
                ctx.fillStyle = 'rgba(255, 215, 0, 0.3)';
                ctx.beginPath();
                ctx.arc(canvas.width/2, canvas.height/2, 20, 0, Math.PI * 2);
                ctx.fill();
            }, 1000);
        } else {
            // Hexagon Network Pattern
            const stars = [];
            const numStars = window.themeMods ? window.themeMods.services_star_count || 150 : 150;
            const numNodes = window.themeMods ? window.themeMods.services_circle_count || 50 : 50;
            class Star {
                constructor() {
                    this.x = Math.random() * canvas.width;
                    this.y = Math.random() * canvas.height;
                    this.size = Math.random() * 2 + 1;
                    this.twinkle = Math.random() * 0.5 + 0.5;
                }
                draw() {
                    ctx.fillStyle = `rgba(255, 255, 255, ${this.twinkle})`;
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                    ctx.fill();
                    this.twinkle += Math.random() * 0.05 - 0.025;
                    if (this.twinkle < 0.5) this.twinkle = 0.5;
                    if (this.twinkle > 1) this.twinkle = 1;
                }
            }
            class Node {
                constructor() {
                    this.x = Math.random() * canvas.width;
                    this.y = Math.random() * canvas.height;
                    this.size = Math.random() * 4 + 3;
                    this.speedX = (Math.random() - 0.5) * 0.5;
                    this.speedY = (Math.random() - 0.5) * 0.5;
                    this.life = 200;
                    this.phase = 'expand';
                }
                reset() {
                    this.x = Math.random() * canvas.width;
                    this.y = Math.random() * canvas.height;
                    this.size = Math.random() * 4 + 3;
                    this.speedX = (Math.random() - 0.5) * 0.5;
                    this.speedY = (Math.random() - 0.5) * 0.5;
                    this.life = 200;
                    this.phase = 'expand';
                }
                update() {
                    const distToCenter = Math.hypot(this.x - canvas.width / 2, this.y - canvas.height / 2);
                    if (this.phase === 'expand') {
                        this.x += this.speedX;
                        this.y += this.speedY;
                        this.life--;
                        if (this.life <= 0 || distToCenter > canvas.width / 2) {
                            this.phase = 'collapse';
                        }
                    } else {
                        const angle = Math.atan2(canvas.height / 2 - this.y, canvas.width / 2 - this.x);
                        this.x += Math.cos(angle) * 1.5;
                        this.y += Math.sin(angle) * 1.5;
                        if (distToCenter < 50) {
                            this.reset();
                        }
                    }
                }
                draw() {
                    ctx.fillStyle = `rgba(255, 215, 0, ${this.phase === 'expand' ? this.life / 200 : 1})`;
                    ctx.strokeStyle = `rgba(27, 38, 59, ${this.phase === 'expand' ? this.life / 200 : 1})`;
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    for (let i = 0; i < 6; i++) {
                        const angle = (Math.PI / 3) * i;
                        const x = this.x + this.size * Math.cos(angle);
                        const y = this.y + this.size * Math.sin(angle);
                        if (i === 0) {
                            ctx.moveTo(x, y);
                        } else {
                            ctx.lineTo(x, y);
                        }
                    }
                    ctx.closePath();
                    ctx.fill();
                    ctx.stroke();
                }
            }

            let nodes = [];
            for (let i = 0; i < numStars; i++) {
                stars.push(new Star());
            }
            for (let i = 0; i < numNodes; i++) {
                nodes.push(new Node());
            }

            const animate = () => {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                const gradient = ctx.createRadialGradient(canvas.width / 2, canvas.height / 2, 0, canvas.width / 2, canvas.height / 2, 50);
                gradient.addColorStop(0, '#0A0A0A');
                gradient.addColorStop(1, 'rgba(27, 38, 59, 0)');
                ctx.fillStyle = gradient;
                ctx.beginPath();
                ctx.arc(canvas.width / 2, canvas.height / 2, 50, 0, Math.PI * 2);
                ctx.fill();
                stars.forEach(star => star.draw());
                nodes.forEach(node => {
                    node.update();
                    node.draw();
                    nodes.forEach(otherNode => {
                        const dist = Math.hypot(node.x - otherNode.x, node.y - otherNode.y);
                        if (dist < 120 && node.phase === 'expand' && otherNode.phase === 'expand') {
                            ctx.strokeStyle = `rgba(27, 38, 59, ${1 - dist / 120})`;
                            ctx.lineWidth = 1.5;
                            ctx.beginPath();
                            ctx.moveTo(node.x, node.y);
                            ctx.lineTo(otherNode.x, otherNode.y);
                            ctx.stroke();
                        }
                    });
                });
                callamirLog('Services animation started:', pattern);
                requestAnimationFrame(animate);
            };
            animate();

            window.addEventListener('resize', () => {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
                nodes = [];
                for (let i = 0; i < numNodes; i++) {
                    nodes.push(new Node());
                }
            });
        }
    };

    // --- Initialize Effects ---
    callamirLog('themeMods available:', !!window.themeMods);
    if (window.themeMods) {
        callamirLog('themeMods object:', window.themeMods);
        if (window.themeMods.enable_header_stars) {
            initStarEffect('stars', window.themeMods.star_count_header);
        } else {
            callamirLog('Header stars disabled');
        }
        if (window.themeMods.enable_footer_stars) {
            initStarEffect('footer-stars', window.themeMods.star_count_footer);
        } else {
            callamirLog('Footer stars disabled');
        }
        if (window.themeMods.enable_hero_effect) {
            callamirLog('Initializing hero blackhole effect...');
            initBlackholeEffect();
        } else {
            callamirLog('Hero blackhole effect disabled');
        }
        if (window.themeMods.enable_services_effect) {
            callamirLog('Initializing services cosmic effect...');
            initServicesEffect();
        } else {
            callamirLog('Services cosmic effect disabled');
        }
    } else {
        console.warn('themeMods not available, trying fallback initialization...');
        // Fallback initialization for services effect
        const servicesCanvas = document.getElementById('services-canvas');
        if (servicesCanvas) {
            callamirLog('Fallback: Initializing services cosmic effect...');
            initServicesEffect();
        }
    }
    
    // Additional fallback: Force initialize services effect after a delay
    setTimeout(() => {
        const servicesCanvas = document.getElementById('services-canvas');
        if (servicesCanvas && !servicesCanvas.hasAttribute('data-initialized')) {
            callamirLog('Delayed fallback: Initializing services cosmic effect...');
            servicesCanvas.setAttribute('data-initialized', 'true');
            initServicesEffect();
        }
    }, 2000);

    // --- Modern Service Modal Functionality ---
    const serviceModal = document.getElementById('service-modal');
    const readMoreBtns = document.querySelectorAll('.read-more-btn');
    const modalClose = document.querySelector('.modal-close');
    const modalOverlay = document.querySelector('.modal-overlay');

    // Service data storage
    const serviceData = {};

    // Initialize service data from PHP
    function initializeServiceData() {
        // Use localized service data from WordPress
        if (window.serviceData) {
            Object.assign(serviceData, window.serviceData);
        } else {
            // Fallback to DOM parsing if serviceData is not available
            const serviceCards = document.querySelectorAll('.service-card');
            serviceCards.forEach(card => {
                const serviceId = card.getAttribute('data-service');
                if (serviceId) {
                    serviceData[serviceId] = {
                        title: card.querySelector('.service-title').textContent,
                        description: card.querySelector('.service-description').textContent,
                        price: card.querySelector('.service-price')?.textContent || '',
                        icon: card.querySelector('.service-icon-wrapper i').className,
                        image: '',
                        fullDescription: '',
                        contactForm: ''
                    };
                }
            });
        }
    }

    // Open service modal
    function openServiceModal(serviceId) {
        const data = serviceData[serviceId];
        if (!data) return;

        // Update modal content
        document.getElementById('modal-service-title').textContent = data.title;
        document.getElementById('modal-service-description').textContent = data.fullDescription || data.description;
        
        const priceElement = document.getElementById('modal-service-price');
        if (data.price) {
            priceElement.textContent = data.price;
            priceElement.style.display = 'inline-block';
        } else {
            priceElement.style.display = 'none';
        }

        // Update modal image
        const imageElement = document.getElementById('modal-service-image');
        if (data.image) {
            imageElement.src = data.image;
            imageElement.alt = data.title;
            imageElement.style.display = 'block';
        } else {
            imageElement.style.display = 'none';
        }

        // Update contact form
        const formElement = document.getElementById('modal-service-form');
        if (data.contactForm && data.contactForm.trim() !== '') {
            callamirLog('Setting contact form HTML:', data.contactForm);
            formElement.innerHTML = data.contactForm;
            formElement.style.display = 'block';
            
            // Initialize Contact Form 7 with our robust function
            initializeContactForm7(formElement);
        } else {
            callamirLog('No contact form available for service:', serviceId);
            formElement.style.display = 'none';
        }

        // Show modal
        serviceModal.classList.add('active');
        serviceModal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';

        // Focus management
        const firstFocusable = serviceModal.querySelector('button, input, textarea, select');
        if (firstFocusable) {
            firstFocusable.focus();
        }
    }

    // Close service modal
    function closeServiceModal() {
        serviceModal.classList.remove('active');
        serviceModal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    // Event listeners for read more buttons
    readMoreBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const serviceId = btn.getAttribute('data-service');
            openServiceModal(serviceId);
        });
    });

    // Event listeners for modal close
    if (modalClose) {
        modalClose.addEventListener('click', closeServiceModal);
    }

    if (modalOverlay) {
        modalOverlay.addEventListener('click', closeServiceModal);
    }

    // Keyboard navigation for modal
    document.addEventListener('keydown', (e) => {
        if (serviceModal.classList.contains('active')) {
            if (e.key === 'Escape') {
                closeServiceModal();
            }
            
            // Trap focus within modal
            if (e.key === 'Tab') {
                const focusableElements = serviceModal.querySelectorAll(
                    'button, input, textarea, select, a[href], [tabindex]:not([tabindex="-1"])'
                );
                const firstElement = focusableElements[0];
                const lastElement = focusableElements[focusableElements.length - 1];

                if (e.shiftKey) {
                    if (document.activeElement === firstElement) {
                        e.preventDefault();
                        lastElement.focus();
                    }
                } else {
                    if (document.activeElement === lastElement) {
                        e.preventDefault();
                        firstElement.focus();
                    }
                }
            }
        }
    });

    // Initialize service data
    initializeServiceData();

    // --- Services Carousel Functionality ---
    const initServicesCarousel = () => {
        const carouselContainer = document.querySelector('.services-carousel-container');
        const track = document.querySelector('.services-track');
        const prevBtn = document.querySelector('.carousel-nav-prev');
        const nextBtn = document.querySelector('.carousel-nav-next');
        const indicatorsContainer = document.querySelector('.carousel-indicators');
        
        if (!carouselContainer || !track) {
            callamirLog('Services carousel elements not found');
            return;
        }
        
        const serviceCards = track.querySelectorAll('.service-card');
        const totalServices = serviceCards.length;
        let currentSlide = 0;
        let cardWidth = 300; // Default card width
        let visibleCards = 3; // Default visible cards
        
        callamirLog('Initializing services carousel:', {
            totalServices,
            cardWidth,
            visibleCards
        });
        
        // Calculate visible cards and card width
        const calculateDimensions = () => {
            const containerStyles = window.getComputedStyle(carouselContainer);
            const paddingLeft = parseFloat(containerStyles.paddingLeft) || 0;
            const paddingRight = parseFloat(containerStyles.paddingRight) || 0;
            const containerWidth = carouselContainer.clientWidth - paddingLeft - paddingRight;
            const trackStyles = window.getComputedStyle(track);
            const gap = parseFloat(trackStyles.columnGap || trackStyles.gap) || 24;
            
            if (window.innerWidth <= 480) {
                visibleCards = 1;
                cardWidth = containerWidth; // gap is 0 in mobile CSS
            } else if (window.innerWidth <= 768) {
                visibleCards = 1;
                cardWidth = containerWidth; // gap is 0 in tablet CSS
            } else if (window.innerWidth <= 1200) {
                visibleCards = 2;
                cardWidth = (containerWidth - gap) / 2;
            } else {
                visibleCards = 3;
                cardWidth = (containerWidth - (gap * 2)) / 3;
            }
            
            cardWidth = Math.max(cardWidth, 240);
            
            serviceCards.forEach(card => {
                card.style.flex = `0 0 ${cardWidth}px`;
                card.style.minWidth = `${cardWidth}px`;
                card.style.maxWidth = `${cardWidth}px`;
            });
        };
        
        // Create indicators
        const createIndicators = () => {
            if (!indicatorsContainer) return;
            
            const totalSlides = Math.ceil(totalServices / visibleCards);
            indicatorsContainer.innerHTML = '';
            
            for (let i = 0; i < totalSlides; i++) {
                const indicator = document.createElement('button');
                indicator.className = 'carousel-indicator';
                if (i === 0) indicator.classList.add('active');
                indicator.setAttribute('aria-label', `Go to slide ${i + 1}`);
                indicator.addEventListener('click', () => goToSlide(i));
                indicatorsContainer.appendChild(indicator);
            }
        };
        
        // Update carousel position (no-op for scroll-snap; keep indicators/buttons logic)
        const updateCarousel = () => {
            const totalSlides = Math.ceil(totalServices / visibleCards);
            const trackStyles = window.getComputedStyle(track);
            const gap = parseFloat(trackStyles.columnGap || trackStyles.gap) || 24;
            const slideWidth = cardWidth + gap;
            
            callamirLog('Updating carousel:', {
                currentSlide,
                totalSlides,
                slideWidth,
                scrollLeft: track.parentElement?.scrollLeft || 0
            });
            
            // Update indicators
            const indicators = indicatorsContainer?.querySelectorAll('.carousel-indicator');
            indicators?.forEach((indicator, index) => {
                indicator.classList.toggle('active', index === currentSlide);
            });
            
            // Update navigation button states
            if (prevBtn) {
                prevBtn.style.opacity = currentSlide === 0 ? '0.5' : '1';
                prevBtn.style.pointerEvents = currentSlide === 0 ? 'none' : 'auto';
            }
            if (nextBtn) {
                const totalSlides = Math.ceil(totalServices / visibleCards);
                nextBtn.style.opacity = currentSlide >= totalSlides - 1 ? '0.5' : '1';
                nextBtn.style.pointerEvents = currentSlide >= totalSlides - 1 ? 'none' : 'auto';
            }
        };
        
        // Go to specific slide
        const goToSlide = (slideIndex) => {
            const totalSlides = Math.ceil(totalServices / visibleCards);
            if (slideIndex < 0 || slideIndex >= totalSlides) return;
            currentSlide = slideIndex;
            updateCarousel();
        };
        
        // Next slide
        const nextSlide = () => {
            const totalSlides = Math.ceil(totalServices / visibleCards);
            if (currentSlide < totalSlides - 1) {
                currentSlide++;
                updateCarousel();
            }
        };
        
        // Previous slide
        const prevSlide = () => {
            if (currentSlide > 0) {
                currentSlide--;
                updateCarousel();
            }
        };
        
        // Event listeners
        if (nextBtn) { nextBtn.addEventListener('click', () => {
            const trackStyles = window.getComputedStyle(track);
            const gap = parseFloat(trackStyles.columnGap || trackStyles.gap) || 24;
            const slideWidth = cardWidth + gap;
            track.parentElement.scrollBy({ left: slideWidth * visibleCards, behavior: 'smooth' });
            nextSlide();
        }); }
        if (prevBtn) { prevBtn.addEventListener('click', () => {
            const trackStyles = window.getComputedStyle(track);
            const gap = parseFloat(trackStyles.columnGap || trackStyles.gap) || 24;
            const slideWidth = cardWidth + gap;
            track.parentElement.scrollBy({ left: -slideWidth * visibleCards, behavior: 'smooth' });
            prevSlide();
        }); }
        
        // Touch/swipe support
        let startX = 0;
        let isDragging = false;
        
        track.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            isDragging = true;
        });
        
        track.addEventListener('touchend', (e) => {
            if (!isDragging) return;
            isDragging = false;
            
            const endX = e.changedTouches[0].clientX;
            const diffX = startX - endX;
            
            if (Math.abs(diffX) > 50) {
                if (diffX > 0) { nextBtn?.click(); } else { prevBtn?.click(); }
            }
        });
        
        // Initialize
        calculateDimensions();
        createIndicators();
        updateCarousel();
        
        // Handle window resize
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                calculateDimensions();
                createIndicators();
                updateCarousel();
            }, 250);
        });
        
        callamirLog('Services carousel initialized successfully');
    };
    
    // Initialize carousel with a small delay to ensure DOM is ready
    setTimeout(() => {
        initServicesCarousel();
    }, 100);

    // --- Contact Form 7 Integration ---
    function initializeContactForm7(formElement) {
        if (!formElement) return;
        
        callamirLog('Initializing Contact Form 7 for element:', formElement);
        
        // Wait for DOM to be ready
        setTimeout(() => {
            // Check if Contact Form 7 is available
            callamirLog('wpcf7 available:', typeof wpcf7 !== 'undefined');
            callamirLog('jQuery available:', typeof jQuery !== 'undefined');
            
            // Try multiple initialization methods
            if (typeof wpcf7 !== 'undefined') {
                callamirLog('wpcf7 object:', wpcf7);
                if (wpcf7.init) {
                    callamirLog('Using wpcf7.init');
                    wpcf7.init(formElement);
                } else if (wpcf7.initForm) {
                    callamirLog('Using wpcf7.initForm');
                    wpcf7.initForm(formElement);
                } else if (wpcf7.initFormElement) {
                    callamirLog('Using wpcf7.initFormElement');
                    wpcf7.initFormElement(formElement);
                }
            }
            
            // Try jQuery method
            if (typeof jQuery !== 'undefined' && jQuery.fn.wpcf7) {
                callamirLog('Using jQuery wpcf7');
                jQuery(formElement).wpcf7();
            }
            
            // Try vanilla JS method
            const form = formElement.querySelector('form');
            if (form) {
                callamirLog('Found form element:', form);
                // Trigger form events
                const event = new Event('wpcf7mailsent', { bubbles: true });
                form.dispatchEvent(event);
            }
            
            // Re-trigger Contact Form 7 scripts
            if (typeof window.wpcf7 !== 'undefined') {
                callamirLog('Re-triggering wpcf7.init');
                window.wpcf7.init();
            }
            
            // Force re-initialization of all forms
            const allForms = formElement.querySelectorAll('form');
            allForms.forEach(form => {
                callamirLog('Processing form:', form);
                // Add event listeners
                form.addEventListener('submit', function(e) {
                    callamirLog('Form submitted');
                });
            });
            
            // Additional fallback: Try to trigger Contact Form 7 scripts manually
            if (typeof window.wpcf7 !== 'undefined' && window.wpcf7.init) {
                callamirLog('Manual wpcf7.init trigger');
                try {
                    window.wpcf7.init();
                } catch (e) {
                    console.error('Error initializing wpcf7:', e);
                }
            }
        }, 500); // Increased delay to ensure DOM is ready
    }

    // --- Font Awesome Icon Loading Check ---
    function checkFontAwesomeLoaded() {
        const icons = document.querySelectorAll('.service-icon-wrapper i');
        icons.forEach(icon => {
            if (!icon.classList.contains('fa') && !icon.classList.contains('fas') && !icon.classList.contains('far') && !icon.classList.contains('fab')) {
                console.warn('Font Awesome not loaded properly for icon:', icon);
            }
        });
    }

    // Check Font Awesome after a short delay
    setTimeout(checkFontAwesomeLoaded, 1000);

    // --- Service Card Hover Effects ---
    const serviceCards = document.querySelectorAll('.service-card');
    serviceCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-10px) scale(1.02)';
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = '';
        });
    });

    // --- Performance Optimization for Animations ---
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };

    const animationObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.willChange = 'transform, box-shadow';
            } else {
                entry.target.style.willChange = 'auto';
            }
        });
    }, observerOptions);

    // Observe service cards for performance optimization
    serviceCards.forEach(card => {
        animationObserver.observe(card);
    });

    // --- Navigation Button ---
    callamirLog('Nav button functionality skipped (no .nav-button in DOM)');
});