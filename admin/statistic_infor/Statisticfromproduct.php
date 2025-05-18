<?php

require_once __DIR__ . '/../../dao/StatisticDao.php';
$labels = [];
$revenues = [];
if (isset($_GET['from_date']) && isset($_GET['to_date'])) {
    $from = $_GET['from_date'];
    $to = $_GET['to_date'];
    $statisticDao = new StatisticDao();
    $data = $statisticDao->revenueByProduct($from, $to);
    foreach ($data as $row) {
        $labels[] = $row['name'];
        $revenues[] = $row['total_revenue'];
    }
}
?>
<style>
.statistic-container {
    max-width: 900px;
    margin: 40px auto;
    background: #232837;
    border-radius: 18px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.25);
    padding: 32px 40px 40px 40px;
}
.statistic-container h2 {
    text-align: center;
    color: #7ecbff;
    margin-bottom: 32px;
}
.statistic-form {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 18px;
    margin-bottom: 36px;
}
.statistic-form label {
    font-weight: 500;
    color: #a0aec0;
}
.statistic-form input[type="date"] {
    padding: 8px 12px;
    border: 1px solid #353b4b;
    border-radius: 6px;
    font-size: 15px;
    background: #232837;
    color: #e6e6e6;
    transition: border 0.2s;
}
.statistic-form input[type="date"]:focus {
    border: 1.5px solid #7ecbff;
    outline: none;
}
.statistic-form button[type="submit"] {
    padding: 9px 22px;
    background: linear-gradient(90deg, #4f8cff 60%, #38c6ff 100%);
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(79,140,255,0.08);
    transition: background 0.2s, transform 0.15s;
}
.statistic-form button[type="submit"]:hover {
    background: linear-gradient(90deg, #38c6ff 60%, #4f8cff 100%);
    transform: translateY(-2px) scale(1.03);
}
.chart-container {
    margin: 0 auto;
    max-width: 800px;
    background: #232837;
    border-radius: 12px;
    padding: 24px 18px 18px 18px;
    box-shadow: 0 2px 8px rgba(44, 62, 80, 0.13);
}
.statistic-container p {
    text-align: center;
    color: #ff7675;
    font-weight: 500;
    margin-top: 32px;
}
@media (max-width: 600px) {
    .statistic-container {
        padding: 16px 6px;
    }
    .statistic-form {
        flex-direction: column;
        gap: 10px;
    }
    .chart-container {
        padding: 10px 2px;
    }
}</style>
<div class="statistic-container">
    <h2>Thống kê doanh thu theo sản phẩm</h2>
    <form class="statistic-form" method="GET" action="">
        <label>Từ ngày:</label>
        <input type="date" name="from_date" required value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : ''; ?>">
        <label>Đến ngày:</label>
        <input type="date" name="to_date" required value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : ''; ?>">
        <button type="submit">Thống kê</button>
    </form>

    <?php if (!empty($labels)): ?>
        <div class="chart-container">
            <canvas id="revenueChart" width="800" height="400"></canvas>
        </div>
        <script>
            const ctx = document.getElementById('revenueChart').getContext('2d');
            const revenueChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($labels); ?>,
                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: <?php echo json_encode($revenues); ?>,
                        backgroundColor: 'rgba(79, 140, 255, 0.7)',
                        borderRadius: 8,
                        maxBarThickness: 48
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let value = context.parsed.y || 0;
                                    return ' ' + value.toLocaleString() + ' VNĐ';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: { color: 'white', font: { weight: 500 } }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: 'white',
                                callback: function(value) {
                                    return value.toLocaleString() + ' VNĐ';
                                }
                            }
                        }
                    }
                }
            });
        </script>
    <?php elseif (isset($_GET['from_date'])): ?>
        <p>Không có dữ liệu trong khoảng thời gian này.</p>
    <?php endif; ?>
</div>