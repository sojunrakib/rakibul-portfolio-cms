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

  const profileSlides = document.querySelectorAll('[data-profile-slide]');
  if (profileSlides.length > 1 && !prefersReduced) {
    let profileIndex = 0;
    window.setInterval(() => {
      profileSlides[profileIndex].classList.remove('is-active');
      profileIndex = (profileIndex + 1) % profileSlides.length;
      profileSlides[profileIndex].classList.add('is-active');
    }, 5000);
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

  /* ============================================================
     HOLOGRAPHIC NETWORK OVERLAY — Premium Cinematic Background
     ============================================================ */
  (function initSpiderWeb() {
    var canvas = document.getElementById('spiderWebCanvas');
    if (!canvas) return;
    var ctx = canvas.getContext('2d');
    if (!ctx) return;

    var isDark = function() { return root.classList.contains('dark'); };
    var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    var W = 0, H = 0, dpr = 1;
    var nodes = [], stars = [], particles = [], fogLayers = [], huds = [];
    var mouseX = -9999, mouseY = -9999;
    var smX = -9999, smY = -9999;
    var mActive = false;
    var raf = null, lastT = 0;

    var CONN_DIST = 190;
    var MOUSE_RAD = 240;
    var MOUSE_FORCE = 0.04;
    var NODE_SPEED = 0.16;
    var TAU = Math.PI * 2;

    function lerp(a, b, t) { return a + (b - a) * t; }
    function rand(lo, hi) { return lo + Math.random() * (hi - lo); }

    /* ── Entity Factories ───────────────────────────────────── */

    function mkNode(i, total) {
      var layer = i < total * 0.2 ? 0 : i < total * 0.5 ? 1 : 2;
      var spread = layer === 0 ? 1.3 : layer === 1 ? 1.1 : 1;
      var x = rand(-W * 0.1, W * spread);
      var y = rand(-H * 0.1, H * spread);
      var hues = [160, 168, 175, 185];
      return {
        x: x, y: y, bx: x, by: y,
        vx: rand(-1, 1) * NODE_SPEED,
        vy: rand(-1, 1) * NODE_SPEED,
        ph: rand(0, TAU), ps: rand(0.0003, 0.0009),
        ax: rand(8, 24), ay: rand(6, 18),
        r: layer === 0 ? rand(1, 2) : layer === 1 ? rand(1.5, 2.5) : rand(2, 3.2),
        L: layer,
        h: hues[Math.floor(Math.random() * hues.length)],
        pp: rand(0, TAU)
      };
    }

    function mkStar() {
      return {
        x: Math.random() * W, y: Math.random() * H,
        r: rand(0.4, 1.6), ba: rand(0.08, 0.35),
        ph: rand(0, TAU), sp: rand(0.001, 0.004),
        h: Math.random() > 0.6 ? 168 : 200
      };
    }

    function mkParticle() {
      var depth = Math.random();
      return {
        x: Math.random() * W, y: Math.random() * H,
        vx: rand(-0.08, 0.08), vy: rand(-0.06, 0.06),
        r: lerp(0.6, 3, depth), a: lerp(0.06, 0.3, depth),
        ph: rand(0, TAU), sp: rand(0.001, 0.003),
        h: [165, 175, 185][Math.floor(Math.random() * 3)],
        depth: depth, att: Math.random() > 0.4
      };
    }

    function mkFog() {
      return {
        x: rand(0, W), y: rand(0, H), r: rand(300, 600),
        vx: rand(-0.02, 0.02), vy: rand(-0.015, 0.015),
        a: rand(0.012, 0.028),
        h: Math.random() > 0.4 ? 170 : 210
      };
    }

    function mkHUD() {
      return {
        x: rand(W * 0.08, W * 0.92), y: rand(H * 0.08, H * 0.92),
        br: rand(35, 90), rn: Math.floor(rand(2, 4)),
        rot: rand(0, TAU), rs: rand(-0.00025, 0.00025),
        a: rand(0.02, 0.045),
        dash: Math.random() > 0.4, tick: Math.random() > 0.5
      };
    }

    /* ── Build All Entities ─────────────────────────────────── */

    function nodeTarget() {
      var area = W * H;
      if (area < 500000) return 30;
      if (area < 1200000) return 50;
      return 70;
    }

    function buildAll() {
      var n = nodeTarget();
      nodes = [];
      for (var i = 0; i < n; i++) nodes.push(mkNode(i, n));
      var sc = Math.min(100, Math.floor(W * H / 18000));
      stars = [];
      for (var s = 0; s < sc; s++) stars.push(mkStar());
      var pc = Math.min(30, Math.floor(W * H / 50000));
      particles = [];
      for (var p = 0; p < pc; p++) particles.push(mkParticle());
      fogLayers = [mkFog(), mkFog(), mkFog()];
      huds = [mkHUD(), mkHUD(), mkHUD()];
    }

    /* ── Resize ─────────────────────────────────────────────── */

    function resize() {
      dpr = Math.min(window.devicePixelRatio || 1, 2);
      W = window.innerWidth;
      H = window.innerHeight;
      canvas.width = W * dpr;
      canvas.height = H * dpr;
      canvas.style.width = W + 'px';
      canvas.style.height = H + 'px';
      ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
      buildAll();
    }

    /* ── Events ─────────────────────────────────────────────── */

    document.addEventListener('mousemove', function(e) {
      mouseX = e.clientX;
      mouseY = e.clientY;
      mActive = true;
    }, { passive: true });

    document.addEventListener('mouseleave', function() {
      mActive = false;
      mouseX = mouseY = -9999;
    });

    window.addEventListener('resize', resize, { passive: true });
    resize();

    /* ── Update Functions ───────────────────────────────────── */

    function updateNodes(dt) {
      smX = lerp(smX, mActive ? mouseX : -9999, 0.07);
      smY = lerp(smY, mActive ? mouseY : -9999, 0.07);
      for (var i = 0; i < nodes.length; i++) {
        var n = nodes[i];
        n.ph += n.ps * dt;
        var dx = Math.sin(n.ph) * n.ax + Math.cos(n.ph * 0.7 + 1.3) * n.ax * 0.4;
        var dy = Math.cos(n.ph * 0.85 + 0.5) * n.ay + Math.sin(n.ph * 0.6 + 2.1) * n.ay * 0.35;
        n.bx += n.vx * dt * 0.016;
        n.by += n.vy * dt * 0.016;
        if (n.bx < -120) n.bx = W + 100;
        if (n.bx > W + 120) n.bx = -100;
        if (n.by < -120) n.by = H + 100;
        if (n.by > H + 120) n.by = -100;
        n.x = n.bx + dx;
        n.y = n.by + dy;
        if (mActive) {
          var mdx = smX - n.x;
          var mdy = smY - n.y;
          var dist = Math.sqrt(mdx * mdx + mdy * mdy);
          if (dist < MOUSE_RAD && dist > 1) {
            var str = (1 - dist / MOUSE_RAD) * MOUSE_FORCE * dt * 0.016;
            n.x += mdx * str;
            n.y += mdy * str;
          }
        }
      }
    }

    function updateParticles(dt) {
      for (var i = 0; i < particles.length; i++) {
        var p = particles[i];
        p.ph += p.sp * dt;
        p.x += p.vx * dt * 0.016;
        p.y += p.vy * dt * 0.016;
        if (p.x < -20) p.x = W + 15;
        if (p.x > W + 20) p.x = -15;
        if (p.y < -20) p.y = H + 15;
        if (p.y > H + 20) p.y = -15;
        if (p.att && mActive) {
          var dx = smX - p.x;
          var dy = smY - p.y;
          var dist = Math.sqrt(dx * dx + dy * dy);
          if (dist < MOUSE_RAD * 0.8 && dist > 1) {
            var str = (1 - dist / (MOUSE_RAD * 0.8)) * 0.015 * dt * 0.016;
            p.x += dx * str;
            p.y += dy * str;
          }
        }
      }
    }

    function updateFog(dt) {
      for (var i = 0; i < fogLayers.length; i++) {
        var f = fogLayers[i];
        f.x += f.vx * dt * 0.016;
        f.y += f.vy * dt * 0.016;
        if (f.x < -f.r) f.x = W + f.r * 0.5;
        if (f.x > W + f.r) f.x = -f.r * 0.5;
        if (f.y < -f.r) f.y = H + f.r * 0.5;
        if (f.y > H + f.r) f.y = -f.r * 0.5;
      }
    }

    /* ── Draw: Volumetric Fog ───────────────────────────────── */

    function drawFog(t) {
      var dk = isDark();
      for (var i = 0; i < fogLayers.length; i++) {
        var f = fogLayers[i];
        var breathe = 0.8 + Math.sin(t * 0.0003 + i * 2) * 0.2;
        var a = f.a * (dk ? 1 : 0.5) * breathe;
        var g = ctx.createRadialGradient(f.x, f.y, 0, f.x, f.y, f.r);
        g.addColorStop(0, 'hsla(' + f.h + ',60%,55%,' + a + ')');
        g.addColorStop(0.5, 'hsla(' + f.h + ',50%,45%,' + (a * 0.4) + ')');
        g.addColorStop(1, 'hsla(' + f.h + ',40%,40%,0)');
        ctx.beginPath();
        ctx.arc(f.x, f.y, f.r, 0, TAU);
        ctx.fillStyle = g;
        ctx.fill();
      }
    }

    /* ── Draw: Star Field ───────────────────────────────────── */

    function drawStars(t) {
      var dk = isDark();
      for (var i = 0; i < stars.length; i++) {
        var s = stars[i];
        var twinkle = 0.6 + Math.sin(t * s.sp + s.ph) * 0.4;
        var a = s.ba * twinkle * (dk ? 1 : 0.4);
        if (a < 0.01) continue;
        ctx.beginPath();
        ctx.arc(s.x, s.y, s.r, 0, TAU);
        ctx.fillStyle = 'hsla(' + s.h + ',60%,85%,' + a + ')';
        ctx.fill();
        if (s.r > 1.2 && a > 0.15) {
          var g = ctx.createRadialGradient(s.x, s.y, 0, s.x, s.y, s.r * 4);
          g.addColorStop(0, 'hsla(' + s.h + ',70%,80%,' + (a * 0.3) + ')');
          g.addColorStop(1, 'hsla(' + s.h + ',60%,70%,0)');
          ctx.beginPath();
          ctx.arc(s.x, s.y, s.r * 4, 0, TAU);
          ctx.fillStyle = g;
          ctx.fill();
        }
      }
    }

    /* ── Draw: Neural Network (connections + nodes + pulses) ── */

    function drawNetwork(t) {
      var dk = isDark();
      var maxDS = CONN_DIST * CONN_DIST;
      var breathe = 0.88 + Math.sin(t * 0.0006) * 0.12;
      var i, j, a, b, dx, dy, dS, d, mL, aB, al, hu, lw, g, px, py, it, tp, intensity;

      for (i = 0; i < nodes.length; i++) {
        a = nodes[i];
        for (j = i + 1; j < nodes.length; j++) {
          b = nodes[j];
          dx = a.x - b.x;
          dy = a.y - b.y;
          dS = dx * dx + dy * dy;
          if (dS > maxDS) continue;
          d = Math.sqrt(dS);
          mL = Math.min(a.L, b.L);
          aB = mL === 0 ? (dk ? 0.06 : 0.04) : (dk ? 0.1 : 0.06);
          al = (1 - d / CONN_DIST) * aB * breathe;
          if (al < 0.003) continue;
          hu = (a.h + b.h) / 2;
          lw = mL === 0 ? 0.3 : (a.L === b.L ? 0.65 : 0.4);
          ctx.beginPath();
          ctx.moveTo(a.x, a.y);
          ctx.lineTo(b.x, b.y);
          ctx.strokeStyle = 'hsla(' + hu + ',75%,65%,' + al + ')';
          ctx.lineWidth = lw;
          ctx.stroke();

          if (a.L >= 1 && b.L >= 1 && dk && al > 0.03) {
            for (var p = 0; p < 2; p++) {
              tp = ((t * (0.0004 - p * 0.00005) + i * (31 - p * 18) + j * (17 + p * 24)) % 1);
              px = lerp(a.x, b.x, tp);
              py = lerp(a.y, b.y, tp);
              it = (1 - d / CONN_DIST) * 0.3 * breathe;
              if (it < 0.015) continue;
              g = ctx.createRadialGradient(px, py, 0, px, py, 5);
              g.addColorStop(0, 'hsla(168,92%,78%,' + it + ')');
              g.addColorStop(0.4, 'hsla(175,85%,70%,' + (it * 0.3) + ')');
              g.addColorStop(1, 'hsla(180,80%,65%,0)');
              ctx.beginPath();
              ctx.arc(px, py, 5, 0, TAU);
              ctx.fillStyle = g;
              ctx.fill();
            }
          }
        }
      }

      for (i = 0; i < nodes.length; i++) {
        var n = nodes[i];
        var iF = n.L === 2;
        var iB = n.L === 0;
        var bA = iB ? (dk ? 0.14 : 0.1) : iF ? (dk ? 0.5 : 0.35) : (dk ? 0.3 : 0.2);
        var gR = iB ? n.r * 3 : iF ? n.r * 5.5 : n.r * 4;
        hu = n.h + Math.sin(t * 0.001 + n.pp) * 8;
        var pulse = 0.85 + Math.sin(t * 0.0018 + n.pp) * 0.15;
        var na = bA * pulse * breathe;
        g = ctx.createRadialGradient(n.x, n.y, 0, n.x, n.y, gR);
        g.addColorStop(0, 'hsla(' + hu + ',80%,68%,' + na + ')');
        g.addColorStop(0.3, 'hsla(' + hu + ',75%,62%,' + (na * 0.4) + ')');
        g.addColorStop(1, 'hsla(' + hu + ',70%,58%,0)');
        ctx.beginPath();
        ctx.arc(n.x, n.y, gR, 0, TAU);
        ctx.fillStyle = g;
        ctx.fill();
        var coreR = n.r * (iF ? 1 : iB ? 0.5 : 0.7);
        ctx.beginPath();
        ctx.arc(n.x, n.y, coreR, 0, TAU);
        ctx.fillStyle = 'hsla(' + hu + ',85%,75%,' + (na * 1.3) + ')';
        ctx.fill();
      }
    }

    /* ── Draw: Holographic HUD Rings ────────────────────────── */

    function drawHUDs(t) {
      var dk = isDark();
      for (var i = 0; i < huds.length; i++) {
        var h = huds[i];
        var hx = h.x, hy = h.y;

        if (mActive) {
          var mdx = smX - h.x;
          var mdy = smY - h.y;
          var md = Math.sqrt(mdx * mdx + mdy * mdy);
          if (md < 300) {
            var pull = (1 - md / 300) * 0.03;
            hx += mdx * pull;
            hy += mdy * pull;
          }
        }

        var rot = h.rot + t * h.rs;
        var br = 0.8 + Math.sin(t * 0.0005 + i * 1.5) * 0.2;
        ctx.save();
        ctx.translate(hx, hy);
        ctx.rotate(rot);

        for (var r = 0; r < h.rn; r++) {
          var rd = h.br + r * 16;
          var a = h.a * (1 - r * 0.18) * br * (dk ? 1 : 0.4);
          if (a < 0.005) continue;

          ctx.beginPath();
          ctx.arc(0, 0, rd, 0, TAU);
          ctx.setLineDash(h.dash && r % 2 === 0 ? [3, 7] : []);
          ctx.strokeStyle = 'hsla(175,65%,62%,' + a + ')';
          ctx.lineWidth = 0.5;
          ctx.stroke();

          if (r === h.rn - 1 && h.tick) {
            var tc = 16;
            for (var ti = 0; ti < tc; ti++) {
              var an = (ti / tc) * TAU;
              ctx.beginPath();
              ctx.moveTo(Math.cos(an) * (rd - 3), Math.sin(an) * (rd - 3));
              ctx.lineTo(Math.cos(an) * (rd + 3), Math.sin(an) * (rd + 3));
              ctx.strokeStyle = 'hsla(175,60%,62%,' + (a * 0.6) + ')';
              ctx.lineWidth = 0.35;
              ctx.stroke();
            }
          }
        }

        var chA = h.a * 0.5 * br * (dk ? 1 : 0.4);
        ctx.setLineDash([]);
        ctx.strokeStyle = 'hsla(175,70%,68%,' + chA + ')';
        ctx.lineWidth = 0.4;
        ctx.beginPath();
        ctx.moveTo(-6, 0); ctx.lineTo(6, 0);
        ctx.moveTo(0, -6); ctx.lineTo(0, 6);
        ctx.stroke();

        var arcS = t * 0.001 + i * 2;
        ctx.beginPath();
        ctx.arc(0, 0, h.br * 0.55, arcS, arcS + 1.2);
        ctx.strokeStyle = 'hsla(175,75%,70%,' + (chA * 0.7) + ')';
        ctx.lineWidth = 0.5;
        ctx.stroke();

        ctx.beginPath();
        ctx.arc(0, 0, 1.5, 0, TAU);
        ctx.fillStyle = 'hsla(175,80%,70%,' + (h.a * 0.6 * br * (dk ? 1 : 0.4)) + ')';
        ctx.fill();

        ctx.setLineDash([]);
        ctx.restore();
      }
    }

    /* ── Draw: Floating Holographic Particles ───────────────── */

    function drawParticles(t) {
      var dk = isDark();
      for (var i = 0; i < particles.length; i++) {
        var p = particles[i];
        var twinkle = 0.7 + Math.sin(t * p.sp * 2 + p.ph) * 0.3;
        var a = p.a * twinkle * (dk ? 1 : 0.35);
        if (a < 0.01) continue;
        var gR = p.r * 3.5;
        var g = ctx.createRadialGradient(p.x, p.y, 0, p.x, p.y, gR);
        g.addColorStop(0, 'hsla(' + p.h + ',80%,75%,' + a + ')');
        g.addColorStop(0.35, 'hsla(' + p.h + ',72%,68%,' + (a * 0.3) + ')');
        g.addColorStop(1, 'hsla(' + p.h + ',65%,62%,0)');
        ctx.beginPath();
        ctx.arc(p.x, p.y, gR, 0, TAU);
        ctx.fillStyle = g;
        ctx.fill();
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.r * 0.6, 0, TAU);
        ctx.fillStyle = 'hsla(' + p.h + ',85%,80%,' + (a * 1.5) + ')';
        ctx.fill();
      }
    }

    /* ── Draw: Mouse Cursor Ripple ──────────────────────────── */

    function drawRipple(t) {
      if (!mActive) return;
      var dk = isDark();
      var bA = dk ? 0.06 : 0.03;
      for (var i = 0; i < 3; i++) {
        var phase = ((t * 0.001 + i * 0.33) % 1);
        var r = 10 + phase * 70;
        var fade = (1 - phase) * (1 - phase);
        ctx.beginPath();
        ctx.arc(smX, smY, r, 0, TAU);
        ctx.strokeStyle = 'hsla(168,80%,70%,' + (bA * fade) + ')';
        ctx.lineWidth = 0.8;
        ctx.stroke();
      }
    }

    /* ── Render Loop ────────────────────────────────────────── */

    function render(t) {
      if (document.hidden) { raf = requestAnimationFrame(render); return; }
      var dt = Math.min(t - lastT, 50);
      lastT = t;
      ctx.clearRect(0, 0, W, H);

      var T = reducedMotion ? 0 : t;
      if (!reducedMotion) {
        updateNodes(dt);
        updateParticles(dt);
        updateFog(dt);
      }

      drawFog(T);
      drawStars(T);
      drawNetwork(T);
      drawHUDs(T);
      drawParticles(T);
      if (!reducedMotion) drawRipple(T);

      raf = requestAnimationFrame(render);
    }

    raf = requestAnimationFrame(render);
  })();

  /* ============================================================
     TECHNOLOGY STACK — Tabs, Parallax, Spider-Web Network
     ============================================================ */
  (function initStackUI() {
    var wrap = document.querySelector('.stack-grid-wrap');
    var grid = document.getElementById('stackGrid');
    var tabs = document.querySelectorAll('.stack-tab');
    var cards = grid ? Array.from(grid.querySelectorAll('.tech-card')) : [];
    var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (!wrap || !grid || cards.length === 0) return;

    /* ── Tab Filtering ─────────────────────────────────────── */
    var pendingAnim = null;
    tabs.forEach(function(tab) {
      tab.addEventListener('click', function() {
        if (this.classList.contains('active')) return;
        tabs.forEach(function(t) { t.classList.remove('active'); t.setAttribute('aria-selected','false'); });
        this.classList.add('active'); this.setAttribute('aria-selected','true');
        var filter = this.getAttribute('data-filter');
        if (pendingAnim) { clearTimeout(pendingAnim); pendingAnim = null; }
        cards.forEach(function(c) {
          c.classList.remove('hidden');
          if (filter === 'all' || c.getAttribute('data-cat') === filter) {
            c.classList.remove('hide');
          } else {
            c.classList.add('hide');
          }
        });
        pendingAnim = setTimeout(function() {
          cards.forEach(function(c) {
            if (c.classList.contains('hide')) c.classList.add('hidden');
          });
          pendingAnim = null;
        }, 400);
      });
    });

    /* ── Mouse Parallax ────────────────────────────────────── */
    if (!reduced) {
      cards.forEach(function(card) {
        var maxRot = 6;
        card.addEventListener('mousemove', function(e) {
          var rect = this.getBoundingClientRect();
          var nx = ((e.clientX - rect.left) / rect.width - 0.5) * 2;
          var ny = ((e.clientY - rect.top) / rect.height - 0.5) * 2;
          this.style.transform = 'perspective(600px) rotateY(' + (nx * maxRot) + 'deg) rotateX(' + (-ny * maxRot) + 'deg) translateY(-6px) scale(1.02)';
        });
        card.addEventListener('mouseleave', function() {
          this.style.transform = '';
        });
      });
    }

    /* ── Spider-Web Canvas ─────────────────────────────────── */
    var canvas = document.getElementById('stackNetCanvas');
    if (!canvas) return;
    var ctx = canvas.getContext('2d');
    if (!ctx) return;

    var isDark = function() { return document.documentElement.classList.contains('dark'); };
    var raf = null, lastT = 0;
    var connDist = 280;

    function getCardCenters() {
      var wrapRect = wrap.getBoundingClientRect();
      var dpr = Math.min(window.devicePixelRatio || 1, 2);
      return cards.map(function(c) {
        if (c.classList.contains('hidden')) return null;
        var r = c.getBoundingClientRect();
        return {
          x: (r.left - wrapRect.left + r.width / 2) * dpr,
          y: (r.top - wrapRect.top + r.height / 2) * dpr,
          visible: r.top < window.innerHeight + 100 && r.bottom > -100
        };
      }).filter(Boolean);
    }

    function resize() {
      var dpr = Math.min(window.devicePixelRatio || 1, 2);
      var w = wrap.offsetWidth;
      var h = wrap.offsetHeight;
      canvas.width = w * dpr;
      canvas.height = h * dpr;
      canvas.style.width = w + 'px';
      canvas.style.height = h + 'px';
    }

    function draw(t) {
      if (document.hidden) { raf = requestAnimationFrame(draw); return; }
      var dt = Math.min(t - lastT, 50);
      lastT = t;
      var T = reduced ? 0 : t;
      var dk = isDark();

      ctx.clearRect(0, 0, canvas.width, canvas.height);

      var positions = getCardCenters();
      if (positions.length < 2) { raf = requestAnimationFrame(draw); return; }

      var maxDS = connDist * connDist * (window.devicePixelRatio || 1);
      var breathe = 0.75 + Math.sin(T * 0.0007) * 0.25;

      for (var i = 0; i < positions.length; i++) {
        for (var j = i + 1; j < positions.length; j++) {
          var a = positions[i], b = positions[j];
          if (!a.visible && !b.visible) continue;
          var dx = a.x - b.x, dy = a.y - b.y;
          var dS = dx * dx + dy * dy;
          if (dS > maxDS) continue;
          var d = Math.sqrt(dS);
          var al = (1 - d / (connDist * (window.devicePixelRatio || 1))) * 0.15 * breathe * (dk ? 1 : 0.4);
          if (al < 0.005) continue;

          ctx.beginPath();
          ctx.moveTo(a.x, a.y);
          ctx.lineTo(b.x, b.y);
          ctx.strokeStyle = 'hsla(170,70%,60%,' + al + ')';
          ctx.lineWidth = 1;
          ctx.stroke();

          if (al > 0.02 && !reduced) {
            var mx = (a.x + b.x) / 2, my = (a.y + b.y) / 2;
            var pulse = ((T * 0.0004 + i * 0.08 + j * 0.15) % 1);
            var px = a.x + (b.x - a.x) * pulse;
            var py = a.y + (b.y - a.y) * pulse;
            var pa = al * 1.5 * (1 - Math.abs(pulse - 0.5) * 2);
            if (pa > 0.01) {
              var pg = ctx.createRadialGradient(px, py, 0, px, py, 4);
              pg.addColorStop(0, 'hsla(168,90%,70%,' + pa + ')');
              pg.addColorStop(1, 'hsla(170,80%,60%,0)');
              ctx.beginPath();
              ctx.arc(px, py, 4, 0, Math.PI * 2);
              ctx.fillStyle = pg;
              ctx.fill();
            }
          }
        }
      }

      raf = requestAnimationFrame(draw);
    }

    window.addEventListener('resize', resize, { passive: true });
    resize();
    raf = requestAnimationFrame(draw);
  })();
})();
