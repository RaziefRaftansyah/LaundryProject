import '../css/app.css';
import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

Alpine.plugin(collapse);
window.Alpine = Alpine;
Alpine.start();

/**
 * Lazy-load Chart.js sekali. Mengembalikan promise yang resolve ke Chart constructor.
 * Digunakan oleh halaman admin/panel yang butuh chart, tanpa memblokir render.
 */
window.loadChartLib = function () {
    if (window.__chartLibPromise) return window.__chartLibPromise;
    window.__chartLibPromise = import('chart.js/auto').then(({ default: Chart }) => {
        window.Chart = Chart;
        return Chart;
    });
    return window.__chartLibPromise;
};

/**
 * Dynamic helper untuk memuat Chart.js hanya pada halaman yang membutuhkan (on-demand)
 */
window.initChart = async function(canvasId, config) {
    const el = document.getElementById(canvasId);
    if (!el) return null;
    const Chart = await window.loadChartLib();
    return new Chart(el, config);
};
