<?php
require 'db.php';

$query = "SELECT title, message, created_at FROM announcements ORDER BY created_at DESC";
$result = $conn->query($query);

$announcements = [];

while ($row = $result->fetch_assoc()) {
    $announcements[] = $row;
}

echo json_encode($announcements);
?>