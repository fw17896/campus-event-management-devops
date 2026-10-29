<?php
require 'config.php';
$msg='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $action=$_POST['action']??'';
    if($action==='add'){
        $s=$conn->prepare("INSERT INTO events(title,description,event_date,venue,capacity) VALUES(?,?,?,?,?)");
        $cap=(int)$_POST['capacity'];
        $s->bind_param("ssssi",$_POST['title'],$_POST['description'],$_POST['event_date'],$_POST['venue'],$cap);
        $msg=$s->execute()?'Event added successfully.':'Error: '.$conn->error;
    } elseif($action==='delete'){
        $id=(int)$_POST['id']; $conn->query("DELETE FROM events WHERE id=$id"); $msg='Event deleted.';
    }
}
$rows=$conn->query("SELECT * FROM events ORDER BY event_date");
include 'includes/header.php';
?>
<div class="page-head"><h1>Events</h1><a class="btn" href="#add">Add Event</a></div>
<?php if($msg): ?><div class="alert success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<div class="form-card" id="add"><h2>Add Event</h2><form method="post">
<input type="hidden" name="action" value="add">
<label>Title</label><input name="title" required>
<label>Description</label><textarea name="description"></textarea>
<label>Event Date</label><input type="date" name="event_date" required>
<label>Venue</label><input name="venue" required>
<label>Capacity</label><input type="number" name="capacity" min="1" value="50" required>
<br><br><button class="btn">Save Event</button>
</form></div>
<br>
<div class="table-wrap"><table><tr><th>ID</th><th>Title</th><th>Date</th><th>Venue</th><th>Capacity</th><th>Action</th></tr>
<?php while($r=$rows->fetch_assoc()): ?><tr>
<td><?= $r['id'] ?></td><td><?= htmlspecialchars($r['title']) ?></td><td><?= $r['event_date'] ?></td><td><?= htmlspecialchars($r['venue']) ?></td><td><?= $r['capacity'] ?></td>
<td><form method="post" onsubmit="return confirm('Delete this event?')"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $r['id'] ?>"><button class="btn danger">Delete</button></form></td>
</tr><?php endwhile; ?></table></div>
<?php include 'includes/footer.php'; ?>
