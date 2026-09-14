(function () {
  'use strict';

  /* Sticky header state */
  var header = document.getElementById('irs-siteHeader');
  var onScroll = function () {
    header.classList.toggle('irs-is-stuck', window.scrollY > 20);
  };
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  /* Mobile drawer */
  var burger = document.getElementById('irs-burger');
  var drawer = document.getElementById('irs-drawer');
  var drawerClose = document.getElementById('irs-drawerClose');

  function setDrawer(open) {
    drawer.classList.toggle('irs-is-open', open);
    burger.classList.toggle('irs-is-open', open);
    burger.setAttribute('aria-expanded', String(open));
    document.body.style.overflow = open ? 'hidden' : '';
  }
  burger.addEventListener('click', function () {
    setDrawer(!drawer.classList.contains('irs-is-open'));
  });
  drawerClose.addEventListener('click', function () { setDrawer(false); });
  drawer.addEventListener('click', function (e) {
    if (e.target === drawer) setDrawer(false);
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') setDrawer(false);
  });
  drawer.querySelectorAll('a').forEach(function (a) {
    a.addEventListener('click', function () { setDrawer(false); });
  });

  /* Mobile submenu accordions */
  drawer.querySelectorAll('button.irs-m-link').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var open = btn.getAttribute('aria-expanded') === 'true';
      var panel = btn.nextElementSibling;
      btn.setAttribute('aria-expanded', String(!open));
      panel.style.maxHeight = open ? null : panel.scrollHeight + 'px';
    });
  });

  /* Service tabs */
  var tabs = document.getElementById('irs-tabs');
  if (tabs) {
    tabs.querySelectorAll('.irs-tab-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        tabs.querySelectorAll('.irs-tab-btn').forEach(function (b) {
          b.classList.remove('irs-is-active');
          b.setAttribute('aria-selected', 'false');
        });
        tabs.querySelectorAll('.irs-tab-panel').forEach(function (p) {
          p.classList.remove('irs-is-active');
        });
        btn.classList.add('irs-is-active');
        btn.setAttribute('aria-selected', 'true');
        var panel = document.getElementById(btn.getAttribute('aria-controls'));
        panel.classList.add('irs-is-active');
        // hidden panels never trigger the observer, so reveal their content now
        panel.querySelectorAll('.irs-reveal,[data-reveal]').forEach(function (el) {
          el.classList.add('irs-is-in');
        });
      });
    });
  }

  /* FAQ accordion */
  document.querySelectorAll('.irs-faq-q').forEach(function (q) {
    q.addEventListener('click', function () {
      var item = q.parentElement;
      var panel = q.nextElementSibling;
      var isOpen = item.classList.contains('irs-is-open');

      document.querySelectorAll('.irs-faq-item.irs-is-open').forEach(function (openItem) {
        openItem.classList.remove('irs-is-open');
        openItem.querySelector('.irs-faq-a').style.maxHeight = null;
      });

      if (!isOpen) {
        item.classList.add('irs-is-open');
        panel.style.maxHeight = panel.scrollHeight + 'px';
      }
    });
  });

  /* ---- Motion: honour the user's reduced motion preference ---- */
  var reduceMotion = window.matchMedia &&
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---- Staggered reveal on scroll ---- */
  var revealEls = document.querySelectorAll('.irs-reveal,[data-reveal]');

  // children of a [data-stagger] container cascade in one after another
  document.querySelectorAll('[data-stagger]').forEach(function (group) {
    var row = 0, lastTop = null;
    Array.prototype.forEach.call(group.children, function (child) {
      var top = child.offsetTop;
      if (lastTop !== null && Math.abs(top - lastTop) > 4) row = 0;
      lastTop = top;
      child.style.setProperty('--d', Math.min(row, 6) * 80 + 'ms');
      row++;
    });
  });

  if (reduceMotion || !('IntersectionObserver' in window)) {
    revealEls.forEach(function (el) { el.classList.add('irs-is-in'); });
  } else {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('irs-is-in');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -70px 0px' });
    revealEls.forEach(function (el) { io.observe(el); });
  }

  /* ---- Stat counters ---- */
  var stats = document.querySelectorAll('.irs-stat-num');
  function countUp(el) {
    var raw = el.textContent.trim();
    var match = raw.match(/^(\d+)(.*)$/);
    if (!match) return;
    var target = parseInt(match[1], 10), suffix = match[2] || '';
    if (reduceMotion) { el.textContent = target + suffix; return; }
    var dur = 1500, start = null;
    el.textContent = '0' + suffix;
    function frame(now) {
      if (start === null) start = now;
      var p = Math.min((now - start) / dur, 1);
      var eased = 1 - Math.pow(1 - p, 3);
      el.textContent = Math.round(target * eased) + suffix;
      if (p < 1) requestAnimationFrame(frame);
    }
    requestAnimationFrame(frame);
  }
  if ('IntersectionObserver' in window) {
    var statIO = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) { countUp(e.target); statIO.unobserve(e.target); }
      });
    }, { threshold: 0.6 });
    stats.forEach(function (el) { statIO.observe(el); });
  }

  /* ---- Scroll rail + banner parallax, batched into one rAF ---- */
  var rail = document.getElementById('irs-scrollProgress');
  var heroBg = document.querySelector('.irs-hero-bg');
  var hero = document.querySelector('.irs-hero');
  var ticking = false;

  function onFrame() {
    ticking = false;
    var y = window.scrollY || window.pageYOffset;

    if (rail) {
      var max = document.documentElement.scrollHeight - window.innerHeight;
      rail.style.width = (max > 0 ? Math.min(y / max, 1) * 100 : 0) + '%';
    }
    if (heroBg && !reduceMotion) {
      var h = hero.offsetHeight;
      if (y < h) {
        // capped so the graphic can never pull away from the banner edge
        heroBg.style.translate = '0 ' + Math.min(y * 0.14, h * 0.1) + 'px';
      }
    }
  }
  function requestFrame() {
    if (!ticking) { ticking = true; requestAnimationFrame(onFrame); }
  }
  window.addEventListener('scroll', requestFrame, { passive: true });
  window.addEventListener('resize', requestFrame, { passive: true });
  requestFrame();

  /* Demo form handling. Swap for Contact Form 7 or Gravity Forms in WordPress. */
  function wireForm(formId, successId) {
    var form = document.getElementById(formId);
    if (!form) return;
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }
      document.getElementById(successId).classList.add('irs-is-visible');
      form.reset();
    });
  }
  wireForm('heroForm', 'heroSuccess');
  wireForm('contactForm', 'contactSuccess');

  /* Footer year */
  var yearEl = document.getElementById('irs-year');
  if (yearEl) {
    yearEl.textContent = new Date().getFullYear();
  }
})();