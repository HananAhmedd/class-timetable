<?php
session_start();
require 'db.php';

// ✅ Proper session check for admin
if (!isset($_SESSION['name']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// ✅ Fetch data for analytics
$studentCount = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'student'")->fetch_assoc()['count'];
$teacherCount = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'teacher'")->fetch_assoc()['count'];
$courseCount = $conn->query("SELECT COUNT(*) as count FROM courses")->fetch_assoc()['count'];
$adminCount = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'admin'")->fetch_assoc()['count'];
$totalUsers = $studentCount + $teacherCount + $adminCount;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Analytics | University of Messina</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      background-color: #f5f5f5;
      font-family: 'Poppins', sans-serif;
      color: #333;
      min-height: 100vh;
    }
    .container {
      background-color: #ffffff;
      border-radius: 20px;
      padding: 40px;
      margin: 40px auto;
      max-width: 1000px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    }
    .navbar {
      background-color: #00274C;
    }
    .navbar-brand, .nav-link, .navbar-text {
      color: #fff !important;
    }
    .chart-container {
      margin-bottom: 40px;
    }
    .card {
      background-color: #ffffff;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
      text-align: center;
      margin-bottom: 30px;
    }
    .card h4 {
      margin-bottom: 20px;
    }
    .card p {
      font-size: 1.5rem;
      color: #333;
      font-weight: 700;
    }
    footer {
      background-color: #00274C;
      color: #ccc;
      padding: 20px 0;
      text-align: center;
      margin-top: 40px;
    }
    footer a {
      color: #ccc;
      margin: 0 10px;
      text-decoration: none;
    }
    footer a:hover {
      color: #fff;
    }
  </style>
</head>
<body>

<!-- ✅ NAVBAR -->
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      <i class="fas fa-university me-2"></i> University of Messina - Admin Panel
    </a>
    <div class="ms-auto d-flex align-items-center">
      <span class="navbar-text me-3">
        Logged in as: <strong><?php echo htmlspecialchars($_SESSION['name']); ?> (Admin)</strong>
      </span>
      <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
  </div>
</nav>

<!-- ✅ MAIN CONTENT -->
<div class="container">
  <h2 class="text-center mb-5">📊 Analytics Dashboard</h2>

  <!-- ✅ Summary Cards -->
  <div class="row">
    <div class="col-md-3">
      <div class="card">
        <h4>Students</h4>
        <p><?php echo $studentCount; ?></p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <h4>Teachers</h4>
        <p><?php echo $teacherCount; ?></p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <h4>Courses</h4>
        <p><?php echo $courseCount; ?></p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <h4>Total Users</h4>
        <p><?php echo $totalUsers; ?></p>
      </div>
    </div>
  </div>

  <!-- ✅ Pie Chart -->
  <div class="chart-container">
    <canvas id="userChart"></canvas>
  </div>
</div>

<!-- ✅ FOOTER -->
<footer>
  <p>&copy; 2025 University of Messina - Admin Panel</p>
  <div>
    <a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i> Instagram</a>
    <a href="https://youtube.com" target="_blank"><i class="fab fa-youtube"></i> YouTube</a>
    <a href="https://www.unime.it" target="_blank"><i class="fas fa-globe"></i> Website</a>
    <a href="#"><i class="fas fa-question-circle"></i> Help</a>
  </div>
</footer>

<script>
  const ctx = document.getElementById('userChart').getContext('2d');
  const userChart = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: ['Students', 'Teachers', 'Admins'],
      datasets: [{
        data: [<?php echo $studentCount; ?>, <?php echo $teacherCount; ?>, <?php echo $adminCount; ?>],
        backgroundColor: ['#6a11cb', '#2575fc', '#00274C'],
        hoverBackgroundColor: ['#543ed8', '#1d65da', '#001b3c']
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            font: {
              size: 14,
              family: 'Poppins'
            }
          }
        }
      }
    }
  });
</script>

</body>
</html>