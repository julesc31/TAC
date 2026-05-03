// ─── Header scroll effect ──────────────────────────────────────────────────
const header = document.getElementById('site-header');
if (header) {
  const onScroll = () => {
    if (window.scrollY > 50) {
      header.classList.add('scrolled');
      header.classList.remove('header-transparent');
    } else if (header.classList.contains('header-transparent') || document.body.dataset.page === 'home') {
      header.classList.remove('scrolled');
      if (document.body.dataset.page === 'home') header.classList.add('header-transparent');
    }
  };
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
}

// ─── Burger menu ────────────────────────────────────────────────────────
const burger = document.getElementById('burger');
const nav = document.getElementById('main-nav');
const burgerIconMenu = document.getElementById('burger-icon-menu');
const burgerIconClose = document.getElementById('burger-icon-close');
if (burger && nav) {
  burger.addEventListener('click', () => {
    const open = nav.classList.toggle('open');
    burger.setAttribute('aria-expanded', open);
    if (burgerIconMenu) burgerIconMenu.style.display = open ? 'none' : '';
    if (burgerIconClose) burgerIconClose.style.display = open ? '' : 'none';
  });
  nav.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
    nav.classList.remove('open');
    burger.setAttribute('aria-expanded', false);
    if (burgerIconMenu) burgerIconMenu.style.display = '';
    if (burgerIconClose) burgerIconClose.style.display = 'none';
  }));
}

// ─── Photo gallery filter + lightbox ────────────────────────────────────────────
function initGallery() {
  const filterBtns = document.querySelectorAll('.filter-btn');
  const photoItems = document.querySelectorAll('.photo-item');
  const lightbox = document.getElementById('lightbox');
  const lbImg = document.getElementById('lb-img');
  const lbCat = document.getElementById('lb-cat');
  const lbCaption = document.getElementById('lb-caption');
  const lbCounter = document.getElementById('lb-counter');
  const lbClose = document.getElementById('lb-close');
  const lbPrev = document.getElementById('lb-prev');
  const lbNext = document.getElementById('lb-next');

  if (!filterBtns.length) return;

  let visiblePhotos = Array.from(photoItems);
  let currentIndex = 0;

  // Filter
  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      filterBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      const cat = btn.dataset.cat;
      photoItems.forEach(item => {
        const show = cat === 'Tous' || item.dataset.category === cat;
        item.style.display = show ? '' : 'none';
      });
      visiblePhotos = Array.from(photoItems).filter(i => i.style.display !== 'none');
    });
  });

  // Lightbox open
  photoItems.forEach((item, _) => {
    item.addEventListener('click', () => {
      const idx = visiblePhotos.indexOf(item);
      if (idx === -1) return;
      currentIndex = idx;
      showLightbox(currentIndex);
    });
  });

  function showLightbox(idx) {
    const item = visiblePhotos[idx];
    if (!item) return;
    lbImg.src = item.dataset.url;
    lbCat.textContent = item.dataset.category;
    lbCaption.textContent = item.dataset.caption;
    lbCounter.textContent = (idx + 1) + ' / ' + visiblePhotos.length;
    lightbox.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  if (lbClose) lbClose.addEventListener('click', closeLightbox);
  if (lightbox) lightbox.addEventListener('click', e => { if (e.target === lightbox) closeLightbox(); });
  if (lbPrev) lbPrev.addEventListener('click', () => { currentIndex = (currentIndex - 1 + visiblePhotos.length) % visiblePhotos.length; showLightbox(currentIndex); });
  if (lbNext) lbNext.addEventListener('click', () => { currentIndex = (currentIndex + 1) % visiblePhotos.length; showLightbox(currentIndex); });

  document.addEventListener('keydown', e => {
    if (!lightbox || !lightbox.classList.contains('open')) return;
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowLeft') { currentIndex = (currentIndex - 1 + visiblePhotos.length) % visiblePhotos.length; showLightbox(currentIndex); }
    if (e.key === 'ArrowRight') { currentIndex = (currentIndex + 1) % visiblePhotos.length; showLightbox(currentIndex); }
  });

  function closeLightbox() {
    lightbox.classList.remove('open');
    document.body.style.overflow = '';
  }
}

// ─── Calendar ────────────────────────────────────────────────────────────
function initCalendar(events) {
  const modal = document.getElementById('event-modal');
  const modalOverlay = document.getElementById('modal-overlay');

  document.querySelectorAll('.cal-event').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.eventId;
      const ev = events.find(e => e.id === id);
      if (!ev || !modal) return;
      document.getElementById('modal-type-badge').className = 'modal-type ev-' + ev.type;
      document.getElementById('modal-type-badge').textContent = ev.typeLabel;
      document.getElementById('modal-event-title').textContent = ev.title;
      document.getElementById('modal-event-desc').textContent = ev.desc || '';
      document.getElementById('modal-event-desc').style.display = ev.desc ? '' : 'none';
      document.getElementById('modal-event-date').textContent = ev.dateFR;
      document.getElementById('modal-event-loc').textContent = ev.location;
      modalOverlay.classList.add('open');
    });
  });

  document.querySelectorAll('.upcoming-event').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.eventId;
      const ev = events.find(e => e.id === id);
      if (!ev || !modal) return;
      document.getElementById('modal-type-badge').className = 'modal-type ev-' + ev.type;
      document.getElementById('modal-type-badge').textContent = ev.typeLabel;
      document.getElementById('modal-event-title').textContent = ev.title;
      document.getElementById('modal-event-desc').textContent = ev.desc || '';
      document.getElementById('modal-event-desc').style.display = ev.desc ? '' : 'none';
      document.getElementById('modal-event-date').textContent = ev.dateFR;
      document.getElementById('modal-event-loc').textContent = ev.location;
      modalOverlay.classList.add('open');
    });
  });

  const closeModal = () => modalOverlay && modalOverlay.classList.remove('open');
  document.getElementById('modal-close')?.addEventListener('click', closeModal);
  modalOverlay?.addEventListener('click', e => { if (e.target === modalOverlay) closeModal(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

  // Month navigation
  const prevBtn = document.getElementById('cal-prev');
  const nextBtn = document.getElementById('cal-next');
  const todayBtn = document.getElementById('cal-today');

  if (prevBtn) prevBtn.addEventListener('click', () => changeMonth(-1));
  if (nextBtn) nextBtn.addEventListener('click', () => changeMonth(1));
  if (todayBtn) todayBtn.addEventListener('click', () => {
    const now = new Date();
    window.calYear = now.getFullYear();
    window.calMonth = now.getMonth();
    renderCalGrid(events);
  });

  function changeMonth(delta) {
    window.calMonth += delta;
    if (window.calMonth < 0) { window.calMonth = 11; window.calYear--; }
    if (window.calMonth > 11) { window.calMonth = 0; window.calYear++; }
    renderCalGrid(events);
  }
}

function renderCalGrid(events) {
  // This is handled server-side with PHP, JS just does the modal/click
}

document.addEventListener('DOMContentLoaded', () => {
  initGallery();
});
