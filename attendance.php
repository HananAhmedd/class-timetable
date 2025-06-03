<?php
session_start();
if (!isset($_SESSION['name']) || $_SESSION['role'] !== 'student') {
  header("Location: login.php");
  exit();
}

$name = $_SESSION['name'];
$initial = strtoupper($name[0]);

require 'db.php';
$today = date('l');

$sql = "
SELECT 
  c.name AS course_name, 
  u.name AS instructor, 
  c.department, 
  c.semester, 
  t.day_of_week, 
  t.start_time, 
  t.end_time, 
  t.room_number,
  t.id as timetable_id
FROM timetables t
JOIN courses c ON t.course_id = c.id
JOIN users u ON t.teacher_id = u.id
ORDER BY t.day_of_week, t.start_time;
";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Attendance Management | University of Messina</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #6a11cb, #2575fc);
      color: #fff;
      font-family: 'Poppins', sans-serif;
      min-height: 100vh;
      padding: 20px;
    }
    .attendance-container {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      padding: 30px;
      margin-bottom: 40px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
    }
    .attendance-card {
      background: rgba(255, 255, 255, 0.15);
      padding: 20px;
      border-radius: 15px;
      margin-bottom: 20px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
      transition: all 0.3s ease;
      cursor: pointer;
    }
    .attendance-card:hover {
      background: #5e49d8;
      transform: scale(1.05);
      color: #fff;
      box-shadow: 0 12px 25px rgba(0,0,0,0.3);
    }
    .attendance-card h4 {
      margin-bottom: 5px;
    }
    .attendance-card p {
      color: #ddd;
      font-size: 0.9rem;
      font-weight: 500;
    }
    .attendance-card button {
      background-color: #00c853;
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 10px;
    }
    .attendance-card button:hover {
      background-color: #00a152;
    }
    .attendance-card button:disabled {
      background-color: #777;
      cursor: not-allowed;
    }
    footer {
      margin-top: 20px;
      padding: 20px 0;
      background-color: #00274C;
      color: #ccc;
      text-align: center;
      box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.5);
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

<div class="attendance-container">
  <h2>📅 Check In for Your Classes</h2>
  <?php while ($row = $result->fetch_assoc()): ?>
    <div class="attendance-card" id="attendance-<?php echo base64_encode($row['course_name']); ?>">
      <h4><?php echo htmlspecialchars($row['course_name']); ?></h4>
      <p><strong>Instructor:</strong> <?php echo htmlspecialchars($row['instructor']); ?></p>
      <p><strong>Day:</strong> <?php echo htmlspecialchars($row['day_of_week']); ?></p>
      <p><strong>Time:</strong> <?php echo date("h:i A", strtotime($row['start_time'])); ?> - <?php echo date("h:i A", strtotime($row['end_time'])); ?></p>
      <p><strong>Room:</strong> <?php echo htmlspecialchars($row['room_number']); ?></p>

      <?php
        $todayName = strtolower(date('l'));
        $classDay = strtolower($row['day_of_week']);
      ?>

      <?php if ($todayName === $classDay): ?>
        <button onclick="markAttendance('<?php echo $row['course_name']; ?>', <?php echo $row['timetable_id']; ?>)">Mark Attendance</button>
      <?php else: ?>
        <button disabled>🚫 Not Today</button>
      <?php endif; ?>
    </div>
  <?php endwhile; ?>
</div>

<footer>
  <p>&copy; 2025 University of Messina - Student Attendance Panel</p>
  <div>
    <a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i> Instagram</a>
    <a href="https://youtube.com" target="_blank"><i class="fab fa-youtube"></i> YouTube</a>
    <a href="https://www.unime.it" target="_blank"><i class="fas fa-globe"></i> Website</a>
    <a href="#"><i class="fas fa-question-circle"></i> Help</a>
  </div>
</footer>

<script>
function markAttendance(courseName, timetableId) {
  fetch("mark_attendance.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: `course=${encodeURIComponent(courseName)}&timetable_id=${timetableId}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      const encodedId = btoa(courseName);
      const card = document.getElementById(`attendance-${encodedId}`);
      const button = card.querySelector("button");
      button.textContent = "✅ Marked Present";
      button.disabled = true;
      button.style.backgroundColor = "#00897b";
    } else {
      alert("❌ " + data.message);
    }
  })
  .catch((error) => {
    console.error("Network ERROR:", error);
    alert("⚠️ Network error. Please try again.");
  });
}
</script>

</body>
</html>