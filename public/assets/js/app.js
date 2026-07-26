(() => {
  const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const root = document.documentElement;
  const savedTheme = localStorage.getItem('theme');
  if (savedTheme === 'light') root.classList.remove('dark');
  if (savedTheme === 'dark') root.classList.add('dark');

  document.querySelector('[data-theme-toggle]')?.addEventListener('click', () => {
    root.classList.toggle('dark');
    localStorage.setItem('theme', root.classList.contains('dark') ? 'dark' : 'light');
  });

  const navToggle = document.querySelector('[data-nav-toggle]');
  const navMenu = document.querySelector('[data-nav-menu]');
  navToggle?.addEventListener('click', () => {
    navToggle.classList.toggle('open');
    navMenu?.classList.toggle('open');
  });
  navMenu?.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => {
    navMenu.classList.remove('open');
    navToggle?.classList.remove('open');
  }));

  const rotator = document.querySelector('[data-rotator]');
  if (rotator) {
    const words = JSON.parse(rotator.dataset.words || '[]');
    let index = 0;
    const tick = () => {
      index = (index + 1) % words.length;
      rotator.textContent = words[index] || '';
    };
    if (words.length > 1 && !prefersReduced) window.setInterval(tick, 2200);
  }

  if (!prefersReduced) {
    const heroCard = document.querySelector('[data-hero-card]');
    const tiltTarget = document.querySelector('[data-tilt]');
    if (heroCard && tiltTarget) {
      let targetX = 0;
      let targetY = 0;
      let currentX = 0;
      let currentY = 0;
      let rafId = null;
      const lerp = (a, b, t) => a + (b - a) * t;
      const maxTilt = 14;

      const animate = () => {
        currentX = lerp(currentX, targetX, 0.08);
        currentY = lerp(currentY, targetY, 0.08);
        tiltTarget.style.transform = `perspective(600px) rotateY(${currentX}deg) rotateX(${currentY}deg)`;
        if (Math.abs(currentX - targetX) > 0.01 || Math.abs(currentY - targetY) > 0.01) {
          rafId = requestAnimationFrame(animate);
        } else {
          rafId = null;
        }
      };

      heroCard.addEventListener('mousemove', (e) => {
        const rect = heroCard.getBoundingClientRect();
        const nx = ((e.clientX - rect.left) / rect.width) - 0.5;
        const ny = ((e.clientY - rect.top) / rect.height) - 0.5;
        targetX = nx * maxTilt;
        targetY = -ny * maxTilt;
        if (!rafId) rafId = requestAnimationFrame(animate);
      });

      heroCard.addEventListener('mouseleave', () => {
        targetX = 0;
        targetY = 0;
        if (!rafId) rafId = requestAnimationFrame(animate);
      });
    }
  }

  window.addEventListener('load', () => {
    if (prefersReduced || !window.gsap) return;
    if (window.ScrollTrigger) window.gsap.registerPlugin(window.ScrollTrigger);
    gsap.from('.hero-copy > *', { y: 28, opacity: 0, duration: .8, stagger: .08, ease: 'power3.out' });
    gsap.from('.hero-visual', { y: 36, opacity: 0, duration: 1, ease: 'power3.out', delay: .16 });
    gsap.utils.toArray('.reveal').forEach((item) => {
      gsap.from(item, { scrollTrigger: { trigger: item, start: 'top 82%' }, y: 34, opacity: 0, duration: .75, ease: 'power3.out' });
    });
    gsap.utils.toArray('.reveal-child').forEach((item) => {
      gsap.from(item, { scrollTrigger: { trigger: item, start: 'top 88%' }, y: 24, opacity: 0, duration: .55, ease: 'power3.out' });
    });
  });

  const skillsSection = document.querySelector('[data-skills]');
  if (skillsSection) {
    if (prefersReduced) {
      skillsSection.classList.add('is-visible');
    } else {
      const skillsObs = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            skillsObs.unobserve(entry.target);
          }
        });
      }, { threshold: .15 });
      skillsObs.observe(skillsSection);
    }
  }

  const header = document.querySelector('.site-header');
  if (header) {
    let tick = false;
    window.addEventListener('scroll', () => {
      if (!tick) {
        requestAnimationFrame(() => {
          header.classList.toggle('scrolled', window.scrollY > 40);
          tick = false;
        });
        tick = true;
      }
    });
  }

  const navLinks = document.querySelectorAll('.nav-links a[href^="#"]');
  const sections = Array.from(navLinks).map((link) => {
    const el = document.querySelector(link.getAttribute('href'));
    return el;
  }).filter(Boolean);
  if (sections.length > 0 && !prefersReduced) {
    const activeObs = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const id = entry.target.id;
          navLinks.forEach((link) => {
            link.classList.toggle('active', link.getAttribute('href') === `#${id}`);
          });
        }
      });
    }, { threshold: .3, rootMargin: '-56px 0px 0px 0px' });
    sections.forEach((s) => activeObs.observe(s));
  }
})();
