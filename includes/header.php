<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Campus Event Management</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<nav class="nav">
  <div class="container nav-inner">
    <a class="brand" href="index.php">Campus Events</a>
    <div class="links">
      <a href="index.php">Dashboard</a>
      <a href="events.php">Events</a>
      <a href="students.php">Students</a>
      <a href="registrations.php">Registrations</a>
    </div>
  </div>
</nav>
<main class="container">
