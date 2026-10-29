<?php
require 'config.php';
$msg='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    if(($_POST['action']??'')==='register'){
        $student=(int)$_POST['student_id']; $event=(int)$_POST['event_id'];
        $check=$conn->query("SELECT COUNT(*) c FROM registrations WHERE event_id=$event")->fetch_assoc()['c'];
        $capacity=$conn->query("SELECT capacity FROM events WHERE id=$event")->fetch_assoc()['capacity'];
        if($check >= $capacity) $msg='Registration failed: event is full.';
        else {
            $s=$conn->prepare("INSERT INTO registrations(student_id,event_id) VALUES(?,?)");
            $s->bind_param("ii",$student,$event);
            $msg=$s->execute()?'Registration successful.':'Registration failed. Student may already be registered.';
        }
    } elseif(($_POST['action']??'')==='delete'){
        $id=(int)$_POST['id']; $conn->query("DELETE FROM registrations WHERE id=$id"); $msg='Registration cancelled.';
    }
}
$students=$conn->query("SELECT id,name FROM students ORDER BY name");
$events=$conn->query("SELECT id,title,event_date FROM events ORDER BY event_date");
$rows=$conn->query("SELECT r.id,s.name student,e.title event_name,e.event_date,r.registered_at FROM registrations r JOIN students s ON s.id=r.student_id JOIN events e ON e.id=r.event_id ORDER BY r.id DESC");
include 'includes/header.php';
?>
<div class="page-head"><h1>Registrations</h1></div>
<?php if($msg): ?><div class="alert <?= str_contains($msg,'successful') ? 'success':'' ?>"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<div class="form-card"><h2>Register Student for Event</h2><form method="post">
<input type="hidden" name="action" value="register">
<label>Student</label><select name="student_id" required><option value="">Select student</option>
<?php while($s=$students->fetch_assoc()): ?><option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option><?php endwhile; ?></select>
<label>Event</label><select name="event_id" required><option value="">Select event</option>
<?php while($e=$events->fetch_assoc()): ?><option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['title']) ?> (<?= $e['event_date'] ?>)</option><?php endwhile; ?></select>
<br><br><button class="btn">Register</button>
</form></div><br>
<div class="table-wrap"><table><tr><th>ID</th><th>Student</th><th>Event</th><th>Date</th><th>Registered At</th><th>Action</th></tr>
<?php while($r=$rows->fetch_assoc()): ?><tr><td><?= $r['id'] ?></td><td><?= htmlspecialchars($r['student']) ?></td><td><?= htmlspecialchars($r['event_name']) ?></td><td><?= $r['event_date'] ?></td><td><?= $r['registered_at'] ?></td>
<td><form method="post"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $r['id'] ?>"><button class="btn danger">Cancel</button></form></td></tr><?php endwhile; ?></table></div>
<?php include 'includes/footer.php'; ?>
