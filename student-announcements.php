<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['name']) || ($_SESSION['role'] !== 'student' && $_SESSION['role'] !== 'teacher' && $_SESSION['role'] !== 'admin')) {
  header("Location: login.html");
  exit();
}

$name = $_SESSION['name'];
$initial = strtoupper($name[0]);
$role = $_SESSION['role'];

require 'db.php';

$announcementQuery = "SELECT title, message, created_at FROM announcements ORDER BY created_at DESC LIMIT 3";
$announcements = $conn->query($announcementQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>View Announcements | University of Messina</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #6a11cb, #2575fc);
      color: #fff;
      font-family: 'Poppins', sans-serif;
      padding: 40px;
    }
    h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #ffd700;
      font-weight: bold;
    }
    .announcement-box {
      background: rgba(255, 255, 255, 0.1);
      border-left: 6px solid #ffd700;
      padding: 20px;
      border-radius: 15px;
      margin-bottom: 25px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.3);
      transition: all 0.3s ease;
      animation: fadeIn 0.8s ease forwards;
    }
    .announcement-box:hover {
      transform: scale(1.02);
      background: rgba(255,255,255,0.15);
    }
    .announcement-box h4 {
      color: #fff;
    }
    .announcement-box p {
      color: #ddd;
      font-size: 0.95rem;
    }
    .announcement-box small {
      color: #bbb;
    }
    @keyframes fadeIn {
      0% {opacity: 0; transform: translateY(20px);}
      100% {opacity: 1; transform: translateY(0);}
    }
  </style>
</head>
<body>
  <h2>📢 All Announcements</h2>

  <?php if ($announcements->num_rows > 0): ?>
    <?php while($row = $announcements->fetch_assoc()): ?>
      <div class="announcement-box">
        <h4><?php echo htmlspecialchars($row['title']); ?></h4>
        <p><?php echo htmlspecialchars($row['message']); ?></p>
        <small>Posted on: <?php echo date("F d, Y h:i A", strtotime($row['created_at'])); ?></small>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p style="text-align:center">No announcements found 📭</p>
  <?php endif; ?>
</body>
</html>