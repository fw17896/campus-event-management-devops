<?php
require 'config.php';
$students = $conn->query("SELECT COUNT(*) c FROM students")->fetch_assoc()['c'];
$events = $conn->query("SELECT COUNT(*) c FROM events")->fetch_assoc()['c'];
$registrations = $conn->query("SELECT COUNT(*) c FROM registrations")->fetch_assoc()['c'];
$upcoming = $conn->query("SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date LIMIT 5");
include 'includes/header.php';
?>
<section class="hero">
<h1>Campus Event Management System</h1>
<p>A simple web application demonstrating CRUD operations and database integration for the DevOps cloud deployment assignment.</p>
<div class="grid">
<div class="card"><h3>Students</h3><div class="stat"><?= $students ?></div><a class="btn" href="students.php">Manage Students</a></div>
<div class="card"><h3>Events</h3><div class="stat"><?= $events ?></div><a class="btn" href="events.php">Manage Events</a></div>
<div class="card"><h3>Registrations</h3><div class="stat"><?= $registrations ?></div><a class="btn" href="registrations.php">Manage Registrations</a></div>
</div>
</section>
<h2>Upcoming Events</h2>
<div class="table-wrap"><table><tr><th>Event</th><th>Date</th><th>Venue</th><th>Capacity</th></tr>
<?php while($e=$upcoming->fetch_assoc()): ?>
<tr><td><?= htmlspecialchars($e['title']) ?></td><td><?= htmlspecialchars($e['event_date']) ?></td><td><?= htmlspecialchars($e['venue']) ?></td><td><?= htmlspecialchars($e['capacity']) ?></td></tr>
<?php endwhile; ?>
</table></div>
<?php include 'includes/footer.php'; ?>
