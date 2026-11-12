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

/**
 * Baca palet warna chart dari CSS variable (app.css @theme).
 * Satu sumber kebenaran: ubah warna di app.css, semua chart ikut berubah.
 */
window.getChartColors = function () {
    const style = getComputedStyle(document.documentElement);
    const css = (name) => style.getPropertyValue(name).trim();
    return {
        income: css('--color-chart-income'),
        incomeHover: css('--color-chart-income-hover'),
        expense: css('--color-chart-expense'),
        grid: css('--color-chart-grid'),
        tooltipBg: css('--color-chart-tooltip-bg'),
        channel: {
            outlet: css('--color-chart-channel-outlet'),
            online: css('--color-chart-channel-online'),
            outletBg: css('--color-chart-channel-outlet-bg'),
            onlineBg: css('--color-chart-channel-online-bg'),
        },
        methods: [
            css('--color-chart-method-1'),
            css('--color-chart-method-2'),
            css('--color-chart-method-3'),
            css('--color-chart-method-4'),
            css('--color-chart-method-5'),
            css('--color-chart-method-6'),
            css('--color-chart-method-7'),
        ],
    };
};
