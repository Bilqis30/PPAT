/**
 * WebArsipp - Pimpinan Executive Dashboard Charts
 * Renders responsive Category Distribution (Doughnut) and Monthly Inflow Trend (Bar).
 */

document.addEventListener('DOMContentLoaded', () => {
    const dataContainer = document.getElementById('chart-data');
    if (!dataContainer) return;

    try {
        const categoryLabels = JSON.parse(dataContainer.getAttribute('data-category-labels') || '[]');
        const categoryValues = JSON.parse(dataContainer.getAttribute('data-category-values') || '[]');
        const monthlyLabels = JSON.parse(dataContainer.getAttribute('data-monthly-labels') || '[]');
        const monthlyValues = JSON.parse(dataContainer.getAttribute('data-monthly-values') || '[]');

        // 1. Chart Kategori (Doughnut Chart)
        const catCanvas = document.getElementById('categoryChart');
        if (catCanvas && categoryLabels.length > 0) {
            new Chart(catCanvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: categoryLabels,
                    datasets: [{
                        data: categoryValues,
                        backgroundColor: [
                            '#0284c7', // Sky-600 (Surat Masuk)
                            '#4f46e5', // Indigo-600 (Surat Keluar)
                            '#d97706', // Amber-600 (Berkas)
                            '#0d9488', // Teal-600 (Laporan BPN)
                            '#7c3aed', // Violet-600 (Laporan Dispenda)
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                padding: 12,
                                font: {
                                    family: "'Plus Jakarta Sans', sans-serif",
                                    size: 11
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ' ' + context.label + ': ' + context.raw + ' Dokumen';
                                }
                            }
                        }
                    },
                    cutout: '68%'
                }
            });
        }

        // 2. Chart Tren Bulanan (Bar Chart)
        const trendCanvas = document.getElementById('trendChart');
        if (trendCanvas && monthlyLabels.length > 0) {
            new Chart(trendCanvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                        label: 'Volume Dokumen',
                        data: monthlyValues,
                        backgroundColor: '#1e293b',
                        hoverBackgroundColor: '#0f172a',
                        borderRadius: 6,
                        maxBarThickness: 32,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ' Terarsip: ' + context.raw + ' Dokumen';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                font: {
                                    family: "'Plus Jakarta Sans', sans-serif",
                                    size: 11
                                }
                            },
                            grid: {
                                color: '#f1f5f9'
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    family: "'Plus Jakarta Sans', sans-serif",
                                    size: 11
                                }
                            },
                            grid: { display: false }
                        }
                    }
                }
            });
        }
    } catch (e) {
        console.error('Gagal menginisialisasi grafik:', e);
    }
});
