/**
 * メニューと FAQ。依存なし。
 */
(function () {
  const toggle = document.querySelector('.js-nav-toggle');
  const nav = document.querySelector('.js-nav');
  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      const open = toggle.getAttribute('aria-expanded') === 'true';
      toggle.setAttribute('aria-expanded', open ? 'false' : 'true');
      nav.classList.toggle('is-open', !open);
      document.body.classList.toggle('nav-open', !open);
    });
  }
})();
