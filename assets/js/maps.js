/**
 * Google Maps を交差したら読み込む。キーが無いときは住所テキストのみ。
 */
(function () {
  const el = document.querySelector('.js-map');
  if (!el) {
    return;
  }

  let markers = [];
  try {
    markers = JSON.parse(el.getAttribute('data-markers') || '[]');
  } catch (e) {
    markers = [];
  }

  const key = el.getAttribute('data-key') || (window.minatoData && window.minatoData.mapsKey) || '';

  function draw() {
    if (!key || !window.google || !markers.length) {
      return;
    }
    const center = { lat: markers[0].lat, lng: markers[0].lng };
    const map = new window.google.maps.Map(el, {
      center: center,
      zoom: markers[0].zoom || 14,
      disableDefaultUI: true,
      zoomControl: true,
      styles: [
        { elementType: 'geometry', stylers: [{ color: '#f6f1e8' }] },
        { elementType: 'labels.text.fill', stylers: [{ color: '#1a1814' }] },
        { featureType: 'water', stylers: [{ color: '#d9e3ea' }] },
        { featureType: 'poi', stylers: [{ visibility: 'off' }] },
        { featureType: 'road', stylers: [{ color: '#efe8dc' }] }
      ]
    });
    markers.forEach(function (m) {
      const marker = new window.google.maps.Marker({
        position: { lat: m.lat, lng: m.lng },
        map: map,
        title: m.title
      });
      if (m.address) {
        const info = new window.google.maps.InfoWindow({
          content: '<strong>' + m.title + '</strong><br>' + m.address
        });
        marker.addListener('click', function () {
          info.open({ map: map, anchor: marker });
        });
      }
    });
  }

  function load() {
    if (!key) {
      el.classList.add('is-empty');
      el.textContent = 'Maps API キーが未設定です。カスタマイザーで入力してください。';
      return;
    }
    if (window.google && window.google.maps) {
      draw();
      return;
    }
    window.minatoInitMap = draw;
    const s = document.createElement('script');
    s.src = 'https://maps.googleapis.com/maps/api/js?key=' + encodeURIComponent(key) + '&callback=minatoInitMap';
    s.async = true;
    s.defer = true;
    document.head.appendChild(s);
  }

  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          load();
          io.disconnect();
        }
      });
    });
    io.observe(el);
  } else {
    load();
  }
})();
