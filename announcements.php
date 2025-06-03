<?php
session_start();
require 'db.php';

// ✅ Check if the user is an admin
if (!isset($_SESSION['name']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

// ✅ Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $message = trim($_POST["message"]);

    if (empty($title) || empty($message)) {
        echo "<div style='color: #ff5c5c; text-align: center;'>❌ Please fill in all fields.</div>";
    } else {
        $stmt = $conn->prepare("INSERT INTO announcements (title, message) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $message);

        if ($stmt->execute()) {
            echo "<div style='color: #28a745; text-align: center;'>✅ Announcement added successfully!</div>";
        } else {
            echo "<div style='color: #ff5c5c; text-align: center;'>❌ Error adding announcement: " . $stmt->error . "</div>";
        }

        $stmt->close();
    }
}

// ✅ Fetch announcements
$announcements = $conn->query("SELECT * FROM announcements ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Announcements - University of Messina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f9f9f9;
            font-family: 'Poppins', sans-serif;
            padding: 20px;
        }
        .container {
            background: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        h2 {
            margin-bottom: 30px;
            font-weight: 600;
            color: #343a40;
        }
        .announcement {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .announcement h4 {
            margin-bottom: 10px;
            font-weight: bold;
            color: #007bff;
        }
        .announcement p {
            color: #555;
        }
        .announcement time {
            font-size: 0.8rem;
            color: #999;
            display: block;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>📢 Announcements</h2>

    <!-- ✅ Add New Announcement Form -->
    <form method="POST" action="">
        <input type="text" name="title" class="form-control" placeholder="Announcement Title" required>
        <textarea name="message" class="form-control" rows="5" placeholder="Write your announcement here..." required></textarea>
        <button type="submit" class="btn btn-primary w-100 mt-3">Add Announcement</button>
    </form>

    <hr>

    <!-- ✅ Display Announcements -->
    <?php while ($row = $announcements->fetch_assoc()): ?>
        <div class="announcement">
            <h4><?php echo htmlspecialchars($row['title']); ?></h4>
            <time><?php echo date("F j, Y, g:i a", strtotime($row['created_at'])); ?></time>
            <p><?php echo nl2br(htmlspecialchars($row['message'])); ?></p>
        </div>
    <?php endwhile; ?>
</div>
</body>
</html>