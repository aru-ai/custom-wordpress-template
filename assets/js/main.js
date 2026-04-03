document.addEventListener('DOMContentLoaded', function () {
  const body = document.body;
  const toggle = document.querySelector('.mbt-menu-toggle');
  const nav = document.querySelector('.mbt-nav');
  const syncModalState = function () {
    const hasOpenOverlay = document.querySelector('.mbt-modal.is-open, .mbt-lightbox.is-open');
    body.classList.toggle('mbt-modal-open', !!hasOpenOverlay);
  };

  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      const isOpen = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', String(isOpen));
      body.classList.toggle('mbt-menu-open', isOpen);
    });
  }

  document.querySelectorAll('.mbt-menu .menu-item-has-children > a').forEach(function (link) {
    link.addEventListener('click', function (event) {
      if (window.innerWidth > 980) return;
      const item = link.parentElement;
      if (!item) return;
      event.preventDefault();
      item.classList.toggle('is-open');
    });
  });

  document.querySelectorAll('.mbt-menu a').forEach(function (link) {
    link.addEventListener('click', function () {
      if (!toggle || !nav || window.innerWidth > 980) return;
      if (link.parentElement && link.parentElement.classList.contains('menu-item-has-children')) return;
      nav.classList.remove('is-open');
      toggle.setAttribute('aria-expanded', 'false');
      body.classList.remove('mbt-menu-open');
    });
  });

  document.addEventListener('click', function (event) {
    if (!toggle || !nav || window.innerWidth > 980) return;
    if (!nav.classList.contains('is-open')) return;
    if (event.target.closest('.mbt-menu-toggle') || event.target.closest('.mbt-nav')) return;
    nav.classList.remove('is-open');
    toggle.setAttribute('aria-expanded', 'false');
    body.classList.remove('mbt-menu-open');
  });

  window.addEventListener('resize', function () {
    if (!toggle || !nav || window.innerWidth <= 980) return;
    nav.classList.remove('is-open');
    toggle.setAttribute('aria-expanded', 'false');
    body.classList.remove('mbt-menu-open');
  });

  document.querySelectorAll('[data-mbt-carousel]').forEach(function (carousel) {
    const track = carousel.querySelector('[data-mbt-carousel-track]');
    const prevButtons = Array.from(carousel.querySelectorAll('[data-mbt-carousel-prev]'));
    const nextButtons = Array.from(carousel.querySelectorAll('[data-mbt-carousel-next]'));
    const dots = carousel.querySelector('[data-mbt-carousel-dots]');
    const dotCountSetting = parseInt(carousel.getAttribute('data-mbt-carousel-dots-count') || '', 10);
    const items = Array.from(track ? track.querySelectorAll('[data-mbt-carousel-item], .mbt-capability-card') : []);

    if (!track || !prevButtons.length || !nextButtons.length) return;

    const getStep = function () {
      const firstCard = track.querySelector('.mbt-capability-card');
      const gapValue = window.getComputedStyle(track).gap || window.getComputedStyle(track).columnGap || '0';
      const gap = parseFloat(gapValue) || 0;

      if (!firstCard) {
        return track.clientWidth * 0.8;
      }

      return firstCard.getBoundingClientRect().width + gap;
    };

    const getMaxScroll = function () {
      return Math.max(0, track.scrollWidth - track.clientWidth);
    };

    const getSnapPositions = function () {
      const maxScroll = getMaxScroll();

      if (!items.length) {
        return [0];
      }

      const firstOffset = items[0].offsetLeft;
      const positions = items.map(function (item) {
        const rawPosition = item.offsetLeft - firstOffset;
        return Math.max(0, Math.min(maxScroll, rawPosition));
      });

      return positions.filter(function (position, index) {
        return index === 0 || Math.abs(position - positions[index - 1]) > 2;
      });
    };

    const getDotTargets = function () {
      const maxScroll = getMaxScroll();
      const positions = getSnapPositions();

      if (items.length <= 1 || maxScroll <= 2 || positions.length <= 1) {
        return [0];
      }

      if (!Number.isNaN(dotCountSetting) && dotCountSetting > 0) {
        if (dotCountSetting >= positions.length) {
          return positions;
        }

        const mappedTargets = [];

        for (let index = 0; index < dotCountSetting; index += 1) {
          const positionIndex = Math.round(((positions.length - 1) * index) / (dotCountSetting - 1));
          mappedTargets.push(positions[positionIndex]);
        }

        return mappedTargets.filter(function (target, index) {
          return index === 0 || Math.abs(target - mappedTargets[index - 1]) > 2;
        });
      }

      return positions;
    };

    const getClosestIndex = function (targets) {
      let closestIndex = 0;
      let closestDistance = Infinity;

      targets.forEach(function (target, index) {
        const distance = Math.abs(track.scrollLeft - target);

        if (distance < closestDistance) {
          closestDistance = distance;
          closestIndex = index;
        }
      });

      return closestIndex;
    };

    const getClosestDotIndex = function () {
      return getClosestIndex(getDotTargets());
    };

    const getClosestSnapIndex = function () {
      return getClosestIndex(getSnapPositions());
    };

    const scrollToPosition = function (position) {
      const maxScroll = getMaxScroll();
      const target = Math.max(0, Math.min(maxScroll, position));
      track.scrollTo({ left: target, behavior: 'smooth' });
    };

    const renderDots = function () {
      if (!dots) return;

      dots.innerHTML = '';

      getDotTargets().forEach(function (target, index) {
        const dot = document.createElement('button');
        dot.type = 'button';
        dot.className = 'mbt-capabilities-carousel__dot';
        dot.setAttribute('aria-label', 'Go to carousel position ' + (index + 1));
        dot.addEventListener('click', function () {
          scrollToPosition(target);
        });
        dots.appendChild(dot);
      });
    };

    const updateDots = function () {
      if (!dots) return;

      const activeIndex = getClosestDotIndex();
      dots.querySelectorAll('.mbt-capabilities-carousel__dot').forEach(function (dot, index) {
        const isActive = index === activeIndex;
        dot.classList.toggle('is-active', isActive);
        dot.setAttribute('aria-current', isActive ? 'true' : 'false');
      });
    };

    const updateButtons = function () {
      const isSingle = items.length <= 1 || getMaxScroll() <= 2;
      prevButtons.forEach(function (button) {
        button.disabled = isSingle;
      });
      nextButtons.forEach(function (button) {
        button.disabled = isSingle;
      });
      updateDots();
    };

    prevButtons.forEach(function (button) {
      button.addEventListener('click', function () {
        if (items.length <= 1) return;
        const positions = getSnapPositions();
        const currentIndex = getClosestSnapIndex();
        const targetIndex = currentIndex <= 0 ? positions.length - 1 : currentIndex - 1;
        scrollToPosition(positions[targetIndex]);
      });
    });

    nextButtons.forEach(function (button) {
      button.addEventListener('click', function () {
        if (items.length <= 1) return;
        const positions = getSnapPositions();
        const currentIndex = getClosestSnapIndex();
        const targetIndex = currentIndex >= positions.length - 1 ? 0 : currentIndex + 1;
        scrollToPosition(positions[targetIndex]);
      });
    });

    renderDots();
    track.addEventListener('scroll', updateButtons, { passive: true });
    window.addEventListener('resize', updateButtons);
    updateButtons();
  });

  const lightbox = document.getElementById('mbt-portfolio-lightbox');
  const lightboxImage = lightbox ? lightbox.querySelector('[data-mbt-lightbox-image-target]') : null;
  const lightboxCaption = lightbox ? lightbox.querySelector('[data-mbt-lightbox-caption]') : null;

  const openLightbox = function (trigger) {
    if (!lightbox || !lightboxImage) return;

    const image = trigger.getAttribute('data-mbt-lightbox-image') || '';
    const title = trigger.getAttribute('data-mbt-lightbox-title') || '';

    if (image === '') return;

    lightboxImage.src = image;
    lightboxImage.alt = title;

    if (lightboxCaption) {
      lightboxCaption.textContent = title;
    }

    lightbox.classList.add('is-open');
    lightbox.setAttribute('aria-hidden', 'false');
    syncModalState();
  };

  const closeLightbox = function () {
    if (!lightbox || !lightboxImage) return;

    lightbox.classList.remove('is-open');
    lightbox.setAttribute('aria-hidden', 'true');
    lightboxImage.src = '';
    lightboxImage.alt = '';

    if (lightboxCaption) {
      lightboxCaption.textContent = '';
    }

    syncModalState();
  };

  document.addEventListener('click', function (event) {
    const lightboxTrigger = event.target.closest('[data-mbt-lightbox-trigger]');
    const lightboxCloser = event.target.closest('[data-mbt-lightbox-close]');

    if (lightboxTrigger) {
      event.preventDefault();
      openLightbox(lightboxTrigger);
      return;
    }

    if (lightboxCloser) {
      closeLightbox();
    }
  });

  document.addEventListener('click', function (event) {
    const trigger = event.target.closest('[data-modal-target]');
    const closer = event.target.closest('[data-modal-close]');

    if (trigger) {
      const modal = document.getElementById(trigger.getAttribute('data-modal-target'));
      if (modal) {
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        syncModalState();
      }
    }

    if (closer) {
      const modal = closer.closest('.mbt-modal');
      if (modal) {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        syncModalState();
      }
    }
  });

  document.addEventListener('keydown', function (event) {
    if (event.key !== 'Escape') return;

    closeLightbox();

    document.querySelectorAll('.mbt-modal.is-open').forEach(function (modal) {
      modal.classList.remove('is-open');
      modal.setAttribute('aria-hidden', 'true');
    });

    syncModalState();
  });
});
