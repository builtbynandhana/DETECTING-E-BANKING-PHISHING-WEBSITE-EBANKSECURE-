<?php
// Include your database connection
$servername = "localhost";  // Replace with your server name
$username = "root";         // Replace with your database username
$password = "pass123";      // Replace with your database password
$dbname = "phishing_detection";  // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get the count of phishing and legitimate URLs
$sql_phishing = "SELECT COUNT(id) AS phishing_count FROM phishing WHERE if_phish = 1";
$sql_legitimate = "SELECT COUNT(id) AS legitimate_count FROM phishing WHERE if_phish = 0";

$result_phishing = $conn->query($sql_phishing);
$result_legitimate = $conn->query($sql_legitimate);

$phishing_count = 0;
$legitimate_count = 0;

if ($result_phishing->num_rows > 0) {
    $row = $result_phishing->fetch_assoc();
    $phishing_count = $row['phishing_count'];
}

if ($result_legitimate->num_rows > 0) {
    $row = $result_legitimate->fetch_assoc();
    $legitimate_count = $row['legitimate_count'];
}

// Query to get the count of URL checks grouped by date
$sql_checks_over_time = "
    SELECT DATE(checked_at) AS check_date, COUNT(*) AS checks_count 
    FROM url_check_history 
    WHERE checked_at IS NOT NULL 
    GROUP BY DATE(checked_at)
    ORDER BY check_date DESC
    LIMIT 7"; // Get the last 7 days
$result_checks_over_time = $conn->query($sql_checks_over_time);

$dates = [];
$checks_counts = [];

if ($result_checks_over_time->num_rows > 0) {
    while ($row = $result_checks_over_time->fetch_assoc()) {
        $dates[] = $row['check_date'];
        $checks_counts[] = $row['checks_count'];
    }
} else {
    // Fill with zeros if no data available
    $dates = array_map(function($day) {
        return date('Y-m-d', strtotime("-$day days"));
    }, range(0, 6));
    $checks_counts = array_fill(0, 7, 0);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
            text-align: center;
        }
        h2 {
            color: #333;
        }
        .chart-container {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin: 20px auto;
        }
        .chart {
            width: 45%; /* Each chart takes up 45% of the width */
            background-color: #ffffff;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        canvas {
            width: 100% !important; /* Full width of the container */
            height: 400px !important; /* Set height for better visibility */
        }
        .back-btn {
            padding: 10px 20px;
            margin-bottom: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<button class="back-btn" onclick="window.location.href='userhome.html'">← Back</button>

<h2>Statistics Dashboard</h2>

<div class="chart-container">
    <!-- Pie Chart Container -->
    <div class="chart">
        <h3>Phishing vs Legitimate URLs</h3>
        <canvas id="phishingChart"></canvas>
    </div>
    
    <!-- Line Chart for URL Checks Over Time -->
    <div class="chart">
        <h3>URL Checks Over Time</h3>
        <canvas id="checksOverTimeChart"></canvas>
    </div>
</div>

<script>
    // Pie Chart Data
    var phishingCount = <?php echo $phishing_count; ?>;
    var legitimateCount = <?php echo $legitimate_count; ?>;

    // Pie chart using Chart.js
    var ctxPie = document.getElementById('phishingChart').getContext('2d');
    var phishingChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: ['Phishing', 'Legitimate'],
            datasets: [{
                label: 'URLs',
                data: [phishingCount, legitimateCount],
                backgroundColor: ['#ff6384', '#36a2eb'], // Colors for pie slices
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });

    // Line Chart Data for URL Checks Over Time
    var dates = <?php echo json_encode($dates); ?>;
    var checksCounts = <?php echo json_encode($checks_counts); ?>;

    // Line chart for URL checks over time
    var ctxLine = document.getElementById('checksOverTimeChart').getContext('2d');
    var checksOverTimeChart = new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: dates, // x-axis labels (dates)
            datasets: [{
                label: 'URL Checks',
                data: checksCounts, // y-axis data (count of URL checks)
                fill: false,
                borderColor: '#36a2eb',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    },
                    ticks: {
                        autoSkip: true,
                        maxTicksLimit: 7 // Limit the number of ticks on x-axis
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Number of URL Checks'
                    },
                    beginAtZero: true,
                    suggestedMax: Math.max(...checksCounts) + 1 // Set max y value dynamically
                }
            }
        }
    });
</script>

</body>
</html>
