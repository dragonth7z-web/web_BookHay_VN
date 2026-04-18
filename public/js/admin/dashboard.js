document.addEventListener('DOMContentLoaded', function() {
    if (!window.dashboardConfig) {
        console.error('Dashboard configuration is missing.');
        return;
    }

    const config = window.dashboardConfig;
    const isYear = config.period === 'year';
    const isMonth = config.period === 'month';

    const labelThis = isYear ? 'Năm nay' : (isMonth ? 'Tháng này' : 'Tuần này');
    const labelLast = isYear ? 'Năm trước' : (isMonth ? 'Tháng trước' : 'Tuần trước');

    // ================================================================
    // 1. MAIN REVENUE CHART
    // ================================================================
    const ctx = document.getElementById('revenueChart');
    if(ctx && window.Chart) {
        const ctx2d = ctx.getContext('2d');
        
        const gradientThis = ctx2d.createLinearGradient(0, 0, 0, 300);
        gradientThis.addColorStop(0, 'rgba(201, 33, 39, 0.4)');
        gradientThis.addColorStop(0.8, 'rgba(201, 33, 39, 0.05)');
        gradientThis.addColorStop(1, 'rgba(201, 33, 39, 0)');

        const gradientLast = ctx2d.createLinearGradient(0, 0, 0, 300);
        gradientLast.addColorStop(0, 'rgba(156, 163, 175, 0.2)');
        gradientLast.addColorStop(1, 'rgba(156, 163, 175, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: config.chartLabels,
                datasets: [
                    {
                        label: labelLast,
                        data: config.chartLastPeriod,
                        borderColor: '#9CA3AF',
                        backgroundColor: gradientLast,
                        borderWidth: 2,
                        borderDash: [5, 5],
                        fill: true,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                        tension: 0.4
                    },
                    {
                        label: labelThis,
                        data: config.chartThisPeriod,
                        borderColor: '#C92127',
                        backgroundColor: gradientThis,
                        borderWidth: 3,
                        fill: true,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#C92127',
                        pointBorderWidth: 3,
                        pointRadius: 3,
                        pointHoverRadius: 7,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index', intersect: false,
                        backgroundColor: 'rgba(31, 41, 55, 0.95)',
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y + 'M ₫';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        min: 0,
                        ticks: { callback: function(value) { return value + 'M'; } }
                    }
                },
                interaction: { mode: 'nearest', axis: 'x', intersect: false }
            }
        });
    }

    // ================================================================
    // 2. SPARKLINE CHARTS
    // ================================================================
    if (window.Chart) {
        const baseOpts = {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { enabled: true } },
            elements: { line: { borderWidth: 2, tension: 0.4, fill: true }, point: { radius: 0 } },
            scales: { x: { display: false }, y: { display: false } }
        };
        const sparks = [
            { el: document.getElementById('sparklineRevenue'), color: '#C92127', data: config.sparkRevenue },
            { el: document.getElementById('sparklineOrders'),  color: '#C92127', data: config.sparkOrders },
            { el: document.getElementById('sparklineConversion'), color: '#7C3AED', data: [config.conversionRate] }
        ];
        sparks.forEach(s => {
            if (!s.el) return;
            const g = s.el.getContext('2d').createLinearGradient(0, 0, 0, s.el.height);
            g.addColorStop(0, s.color + '33');
            g.addColorStop(1, s.color + '00');
            new Chart(s.el, { type: 'line', data: { labels: s.data.map((_, i) => i+1), datasets: [{ data: s.data, borderColor: s.color, backgroundColor: g }] }, options: baseOpts });
        });
    }

    // ================================================================
    // 3. TOP BOOKS CHART
    // ================================================================
    const topBooksCtx = document.getElementById('topBooksChart');
    if (topBooksCtx && window.Chart) {
        new Chart(topBooksCtx, {
            type: 'bar',
            data: {
                labels: config.topBooksLabels,
                datasets: [{
                    label: 'Doanh thu (M₫)',
                    data: config.topBooksData,
                    backgroundColor: config.topBooksData.map((v, i) => {
                        const colors = ['#C92127', '#DC2626', '#EF4444', '#F87171', '#FCA5A5', '#FECACA', '#FEE2E2', '#FFF1F2', '#FFF5F5', '#FFFBFB'];
                        return colors[i] || '#FEE2E2';
                    }),
                    borderRadius: 4,
                    barPercentage: 0.6
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: ctx => ctx.parsed.x + 'M ₫' } }
                },
                scales: {
                    x: { ticks: { callback: v => v + 'M' } }
                }
            }
        });
    }

    // ================================================================
    // 4. CATEGORY REVENUE CHART
    // ================================================================
    const catCtx = document.getElementById('categoryChart');
    if (catCtx && window.Chart) {
        const catColors = ['#C92127', '#7C3AED', '#0EA5E9', '#10B981', '#F59E0B', '#EC4899', '#6366F1', '#8B5CF6'];
        new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: config.categoryLabels,
                datasets: [{
                    data: config.categoryData,
                    backgroundColor: catColors,
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '60%',
                plugins: {
                    legend: { position: 'right', labels: { usePointStyle: true, pointStyleWidth: 8 } },
                    tooltip: { callbacks: { label: ctx => ctx.label + ': ' + ctx.parsed + 'M ₫' } }
                }
            }
        });
    }

    // ================================================================
    // 5. CUSTOMER SEGMENTATION CHART
    // ================================================================
    const custCtx = document.getElementById('customerChart');
    if (custCtx && window.Chart) {
        new Chart(custCtx, {
            type: 'doughnut',
            data: {
                labels: ['VIP (≥2M)', 'Quay lại (≥2 đơn)', 'Khách mới (tháng này)', 'Khác'],
                datasets: [{
                    data: [
                        config.customerStats.vip,
                        config.customerStats.returning,
                        config.customerStats.new,
                        config.customerStats.others
                    ],
                    backgroundColor: ['#F59E0B', '#3B82F6', '#10B981', '#E5E7EB'],
                    borderWidth: 2, borderColor: '#fff'
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '55%',
                plugins: {
                    legend: { position: 'right', labels: { usePointStyle: true, pointStyleWidth: 8 } },
                    tooltip: { callbacks: { label: ctx => ctx.label + ': ' + ctx.parsed + ' KH' } }
                }
            }
        });
    }

    // ================================================================
    // 6. STOCK STATUS CHART
    // ================================================================
    const stockCtx = document.getElementById('stockChart');
    if (stockCtx && window.Chart) {
        new Chart(stockCtx, {
            type: 'doughnut',
            data: {
                labels: ['Còn hàng (>10)', 'Sắp hết (1-10)', 'Hết hàng (0)'],
                datasets: [{
                    data: [config.stockStats.inStock, config.stockStats.low, config.stockStats.out],
                    backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
                    borderWidth: 2, borderColor: '#fff'
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '55%',
                plugins: {
                    legend: { position: 'right', labels: { usePointStyle: true, pointStyleWidth: 8 } },
                    tooltip: { callbacks: { label: ctx => ctx.label + ': ' + ctx.parsed + ' đầu sách' } }
                }
            }
        });
    }

    // ================================================================
    // 7. ANIMATE CANCEL REASON BARS
    // ================================================================
    setTimeout(() => {
        document.querySelectorAll('.cancel-reason-bar').forEach(bar => {
            const width = bar.getAttribute('data-width');
            if(width) bar.style.width = width + '%';
        });
    }, 100);

});
