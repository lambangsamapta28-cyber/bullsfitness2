// Register Service Worker
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('sw.js')
      .then(reg => console.log('Service Worker terdaftar:', reg.scope))
      .catch(err => console.log('Service Worker gagal:', err));
  });
}

// Inisialisasi Fitur Khusus Mobile & PWA
document.addEventListener('DOMContentLoaded', () => {
  const isMobileScreen = () => window.innerWidth <= 768;

  // 1. Mobile Hamburger Menu (hanya jika elemen nav ul tersedia)
  const nav = document.querySelector('nav:not(.mobile-bottom-nav)');
  const navUl = nav ? nav.querySelector('ul') : null;
  
  if (nav && navUl && !document.querySelector('.mobile-menu-btn')) {
    const toggleBtn = document.createElement('button');
    toggleBtn.className = 'mobile-menu-btn';
    toggleBtn.setAttribute('aria-label', 'Menu Navigasi');
    toggleBtn.innerHTML = '&#9776;';
    
    toggleBtn.addEventListener('click', () => {
      navUl.classList.toggle('nav-open');
      toggleBtn.innerHTML = navUl.classList.contains('nav-open') ? '&times;' : '&#9776;';
    });
    
    nav.insertBefore(toggleBtn, navUl);
  }

  // 2. Banner Install PWA
  let deferredPrompt;
  const installBanner = document.createElement('div');
  installBanner.id = 'pwa-install-banner';
  installBanner.className = 'pwa-banner';
  installBanner.innerHTML = `
    <div class="pwa-banner-content">
      <img src="img/icon-192.png" alt="Logo" class="pwa-banner-icon">
      <div class="pwa-banner-text">
        <strong>Install Bull's Fitness</strong>
        <span>Pasang aplikasi di layar HP Anda</span>
      </div>
      <button id="pwa-install-btn" class="pwa-btn-install">Install</button>
      <button id="pwa-close-btn" class="pwa-btn-close">&times;</button>
    </div>
  `;
  document.body.appendChild(installBanner);

  window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
    
    // Hanya tampilkan banner jika dibuka di layar HP dan belum di-close oleh user
    if (isMobileScreen() && !sessionStorage.getItem('pwa_dismissed')) {
      installBanner.style.display = 'block';
    }
  });

  const installBtn = document.getElementById('pwa-install-btn');
  if (installBtn) {
    installBtn.addEventListener('click', async () => {
      if (!deferredPrompt) return;
      deferredPrompt.prompt();
      const { outcome } = await deferredPrompt.userChoice;
      console.log('Install outcome:', outcome);
      deferredPrompt = null;
      installBanner.style.display = 'none';
    });
  }

  const closeBtn = document.getElementById('pwa-close-btn');
  if (closeBtn) {
    closeBtn.addEventListener('click', () => {
      installBanner.style.display = 'none';
      sessionStorage.setItem('pwa_dismissed', 'true');
    });
  }

  // 3. Bottom Navigation Bar untuk Handphone
  if (!document.querySelector('.mobile-bottom-nav')) {
    const currentPath = window.location.pathname.split('/').pop() || 'index.php';
    const isCurrent = (page) => currentPath === page ? 'active' : '';

    const bottomNav = document.createElement('nav');
    bottomNav.className = 'mobile-bottom-nav';
    bottomNav.innerHTML = `
      <a href="index.php" class="${isCurrent('index.php')}">
        <span class="nav-icon">🏠</span>
        <span class="nav-label">Beranda</span>
      </a>
      <a href="member.php" class="${isCurrent('member.php')}">
        <span class="nav-icon">💳</span>
        <span class="nav-label">Member</span>
      </a>
      <a href="tip.php" class="${isCurrent('tip.php')}">
        <span class="nav-icon">💡</span>
        <span class="nav-label">Tips</span>
      </a>
      <a href="tentang.php" class="${isCurrent('tentang.php')}">
        <span class="nav-icon">ℹ️</span>
        <span class="nav-label">Tentang</span>
      </a>
      <a href="profile.php" class="${isCurrent('profile.php') || isCurrent('login.php')}">
        <span class="nav-icon">👤</span>
        <span class="nav-label">Akun</span>
      </a>
    `;
    document.body.appendChild(bottomNav);
  }
});
