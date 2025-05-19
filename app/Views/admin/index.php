<h1 class="mt-4"><?= $title ?? '' ?></h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item">Dashboard</li>
</ol>

<div class="row mb-4">
    <div class="col-md-4">
        <a href="<?= route_to('admin.users.index') ?>" class="text-decoration-none card text-white bg-primary mb-3 d-flex flex-row align-items-center justify-content-around">
            <div class="card-body">
                <h5 class="card-title">Total Users</h5>
                <p class="card-text display-4"><?= $totalUsers ?? 0 ?></p>
            </div>
            <i class="fas fa-users fa-3x text-white-50 me-3"></i>
        </a>
    </div>

    <div class="col-md-4">
        <a href="<?= route_to('admin.pizzas.index') ?>" class="text-decoration-none card text-white bg-success mb-3 d-flex flex-row align-items-center justify-content-around">
            <div class="card-body">
                <h5 class="card-title">Total Pizzas</h5>
                <p class="card-text display-4"><?= $totalPizzas ?? 0 ?></p>
            </div>
            <i class="fas fa-pizza-slice fa-3x text-white-50 me-3"></i>
        </a>
    </div>

    <div class="col-md-4">
        <a href="<?= route_to('admin.orders.index') ?>" class="text-decoration-none card text-white bg-danger mb-3 d-flex flex-row align-items-center justify-content-around">
            <div class="card-body">
                <h5 class="card-title">Total Orders</h5>
                <p class="card-text display-4"><?= $totalOrders ?? 0 ?></p>
            </div>
            <i class="fas fa-shopping-cart fa-3x text-white-50 me-3"></i>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-line me-1"></i>
                Weekly Sales
            </div>
            <div class="card-body">
                <canvas id="salesChart" width="100%" height="40"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-pie me-1"></i>
                Top Selling Pizzas
            </div>
            <div class="card-body">
                <canvas id="topPizzasChart" width="100%" height="40"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    // Weekly Sales Chart (Line Chart)
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode(array_column($salesData, 'day')) ?>,
            datasets: [{
                label: 'Sales (₱)',
                data: <?= json_encode(array_column($salesData, 'total')) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return '₱' + context.raw.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₱' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Top Pizzas Chart (Doughnut Chart)
    const topPizzasCtx = document.getElementById('topPizzasChart').getContext('2d');
    const topPizzasChart = new Chart(topPizzasCtx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_column($topPizzas, 'name')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($topPizzas, 'order_count')) ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.raw + ' orders';
                        }
                    }
                }
            }
        }
    });
</script>
