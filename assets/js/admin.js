/**
 * Cafe Espresso - Administrative SaaS Dashboard Scripts
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Admin Product Photo Upload Previewer
    const imageUpload = document.getElementById('admin-product-image');
    const imagePreview = document.getElementById('admin-image-preview');
    
    if (imageUpload && imagePreview) {
        imageUpload.addEventListener('change', () => {
            const file = imageUpload.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    imagePreview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.innerHTML = `<i class="fa-solid fa-image" style="font-size: 2.5rem; color: #8E847C;"></i>`;
            }
        });
    }

    // 2. SaaS Dashboard Chart.js Initializations
    // Monthly Sales Line Chart
    const salesCtx = document.getElementById('salesTrendChart');
    if (salesCtx) {
        const months = JSON.parse(salesCtx.dataset.months || '[]');
        const revenues = JSON.parse(salesCtx.dataset.revenues || '[]');
        
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: months.length ? months : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
                datasets: [{
                    label: 'Revenue (₱)',
                    data: revenues.length ? revenues : [12000, 15000, 18000, 16000, 21000, 25000, 24000, 31000],
                    borderColor: '#C08246', // Elegant Caramel Gold
                    backgroundColor: 'rgba(192, 130, 70, 0.08)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#C08246',
                    pointBorderColor: '#FFFFFF',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        padding: 12,
                        backgroundColor: '#0F1E1A',
                        titleColor: '#FDFBF7',
                        bodyColor: '#FDFBF7',
                        bodyFont: {
                            family: 'Outfit'
                        },
                        titleFont: {
                            family: 'Playfair Display',
                            weight: 'bold'
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#8E847C',
                            font: {
                                family: 'Outfit'
                            }
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(44, 26, 17, 0.05)'
                        },
                        ticks: {
                            color: '#8E847C',
                            font: {
                                family: 'Outfit'
                            },
                            callback: function(value) {
                                return '₱' + value;
                            }
                        }
                    }
                }
            }
        });
    }

    // Category Doughnut Chart
    const categoryCtx = document.getElementById('categoryShareChart');
    if (categoryCtx) {
        const labels = JSON.parse(categoryCtx.dataset.labels || '[]');
        const counts = JSON.parse(categoryCtx.dataset.counts || '[]');
        
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: labels.length ? labels : ['Espresso Craft', 'Velvety Milk', 'Specialty', 'Frappes & Ice', 'Pastries'],
                datasets: [{
                    data: counts.length ? counts : [35, 25, 15, 15, 10],
                    backgroundColor: [
                        '#2C1A11', // Espresso
                        '#4A3325', // Steamed Milk
                        '#C08246', // Gold Accent
                        '#0F1E1A', // Forest Green
                        '#8E847C'  // Gray Muted
                    ],
                    borderWidth: 2,
                    borderColor: '#FFFFFF'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            color: '#2C2520',
                            font: {
                                family: 'Outfit',
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        padding: 12,
                        backgroundColor: '#0F1E1A',
                        bodyColor: '#FDFBF7'
                    }
                },
                cutout: '65%'
            }
        });
    }
});
