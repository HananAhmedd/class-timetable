<?php
session_start();
if (!isset($_SESSION['name']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.html");
    exit();
}
require 'db.php';

$result = $conn->query("SELECT * FROM announcements ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>📢 View Announcements</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #6a11cb, #2575fc);
      font-family: 'Poppins', sans-serif;
      color: #fff;
      padding: 60px 20px;
    }
    .container {
      max-width: 900px;
      margin: auto;
      animation: fadeIn 1s ease;
    }
    h2 {
      text-align: center;
      font-weight: 700;
      margin-bottom: 30px;
      font-size: 2.5rem;
    }
    .announcement {
      background: rgba(255, 255, 255, 0.1);
      border-left: 6px solid #fff;
      margin-bottom: 25px;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.2);
      animation: slideUp 0.6s ease;
      position: relative;
    }
    .announcement h4 {
      color: #ffd700;
      font-weight: bold;
      margin-bottom: 10px;
    }
    .announcement p {
      color: #fff;
      font-size: 1.1rem;
      margin-bottom: 5px;
    }
    .announcement small {
      color: #ccc;
    }
    .new-badge {
      position: absolute;
      top: 15px;
      right: 15px;
      background-color: #ff4081;
      color: #fff;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: bold;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideUp {
      from { transform: translateY(30px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
  </style>
</head>
<body>
  <div class="container">
    <h2><i class="fas fa-bullhorn"></i> Announcements</h2>
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="announcement">
        <h4><?= htmlspecialchars($row['title']) ?></h4>
        <p><?= nl2br(htmlspecialchars($row['message'])) ?></p>
        <small><i class="fas fa-clock"></i> Posted on <?= date('F j, Y \a\t g:i a', strtotime($row['created_at'])) ?></small>
        <div class="new-badge">NEW!</div>
      </div>
    <?php endwhile; ?>
  </div>
</body>
</html>