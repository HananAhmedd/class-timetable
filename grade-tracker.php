<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Grade Tracker | University of Messina</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #6a11cb, #2575fc);
      color: #fff;
      min-height: 100vh;
      overflow-x: hidden;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding-top: 50px;
    }
    .container {
      max-width: 900px;
      background: rgba(255, 255, 255, 0.1);
      padding: 30px;
      border-radius: 15px;
      color: #fff;
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
      animation: fadeIn 0.8s ease-out;
      margin-bottom: 40px;
      width: 90%;
    }
    h2 {
      margin-bottom: 20px;
      font-weight: 700;
      letter-spacing: 1px;
    }
    .grade-input {
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
      margin-bottom: 20px;
    }
    .grade-input input,
    .grade-input select {
      flex: 1;
      padding: 12px;
      border-radius: 8px;
      border: none;
      margin-bottom: 10px;
      transition: all 0.3s ease;
    }
    .grade-input input:focus,
    .grade-input select:focus {
      outline: none;
      transform: scale(1.02);
      background: rgba(255, 255, 255, 0.2);
    }
    .grade-input button {
      background-color: #fff;
      color: #6a11cb;
      padding: 12px 20px;
      border-radius: 8px;
      border: none;
      font-weight: bold;
      cursor: pointer;
      width: 100%;
      margin-top: 10px;
      transition: all 0.3s ease;
    }
    .grade-input button:hover {
      background-color: #341a8c;
      color: #fff;
      transform: scale(1.05);
    }
    .grade-list {
      background: rgba(255, 255, 255, 0.15);
      padding: 20px;
      border-radius: 15px;
      margin-bottom: 20px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }
    .grade-list h3 {
      margin-bottom: 15px;
      font-weight: 600;
    }
    .grade-item {
      display: flex;
      justify-content: space-between;
      background: rgba(255, 255, 255, 0.1);
      padding: 12px 20px;
      margin-bottom: 10px;
      border-radius: 10px;
      transition: all 0.3s ease;
    }
    .grade-item:hover {
      background: #5e49d8;
      color: #fff;
      transform: scale(1.03);
    }
    .back-btn {
      background-color: #fff;
      color: #2575fc;
      padding: 10px 20px;
      border-radius: 8px;
      border: none;
      font-weight: bold;
      cursor: pointer;
      width: 100%;
      margin-top: 10px;
      transition: all 0.3s ease;
    }
    .back-btn:hover {
      background-color: #ddd;
      color: #2575fc;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
<div class="container">
  <h2>📊 Grade Tracker</h2>

  <!-- ✅ Grade Input Form -->
  <div class="grade-input">
    <input type="text" id="courseName" placeholder="Course Name" required>
    <input type="text" id="instructorName" placeholder="Instructor Name" required>
    <select id="grade" required>
      <option value="">Select Grade</option>
      <option value="A+">A+</option>
      <option value="A">A</option>
      <option value="B+">B+</option>
      <option value="B">B</option>
      <option value="C+">C+</option>
      <option value="C">C</option>
      <option value="D">D</option>
      <option value="F">F</option>
    </select>
    <button onclick="addGrade()">Add Grade</button>
  </div>

  <!-- ✅ Grade List -->
  <div class="grade-list" id="gradeList">
    <h3>Your Grades</h3>
    <div id="gradesContainer">
      <p>No grades added yet.</p>
    </div>
  </div>

  <button onclick="window.location.href='student-dashboard.php'" class="back-btn">Back to Dashboard</button>
</div>

<script>
function addGrade() {
  const courseName = document.getElementById("courseName").value.trim();
  const instructorName = document.getElementById("instructorName").value.trim();
  const grade = document.getElementById("grade").value;

  if (courseName === "" || instructorName === "" || grade === "") {
    alert("Please fill in all the fields.");
    return;
  }

  const gradeItem = document.createElement("div");
  gradeItem.classList.add("grade-item");
  gradeItem.innerHTML = `
    <strong>${courseName}</strong>
    <span>${grade} – ${instructorName}</span>
  `;

  const gradesContainer = document.getElementById("gradesContainer");
  gradesContainer.appendChild(gradeItem);

  // Clear input fields
  document.getElementById("courseName").value = "";
  document.getElementById("instructorName").value = "";
  document.getElementById("grade").value = "";

  // Remove placeholder if present
  const placeholder = gradesContainer.querySelector("p");
  if (placeholder) {
    placeholder.remove();
  }
}
</script>
</body>
</html>