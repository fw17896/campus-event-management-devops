<?php
require 'config.php';
$msg='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $action=$_POST['action']??'';
    if($action==='add'){
        $s=$conn->prepare("INSERT INTO students(name,email,department) VALUES(?,?,?)");
        $s->bind_param("sss",$_POST['name'],$_POST['email'],$_POST['department']);
        $msg=$s->execute()?'Student added successfully.':'Error: '.$conn->error;
    } elseif($action==='delete'){
        $id=(int)$_POST['id']; $conn->query("DELETE FROM students WHERE id=$id"); $msg='Student deleted.';
    }
}
$rows=$conn->query("SELECT * FROM students ORDER BY id DESC");
include 'includes/header.php';
?>
<div class="page-head"><h1>Students</h1><a class="btn" href="#add">Add Student</a></div>
<?php if($msg): ?><div class="alert success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<div class="form-card" id="add"><h2>Add Student</h2><form method="post">
<input type="hidden" name="action" value="add">
<label>Name</label><input name="name" required>
<label>Email</label><input type="email" name="email" required>
<label>Department</label><input name="department" required>
<br><br><button class="btn">Save Student</button>
</form></div>
<br>
<div class="table-wrap"><table><tr><th>ID</th><th>Name</th><th>Email</th><th>Department</th><th>Action</th></tr>
<?php while($r=$rows->fetch_assoc()): ?><tr>
<td><?= $r['id'] ?></td><td><?= htmlspecialchars($r['name']) ?></td><td><?= htmlspecialchars($r['email']) ?></td><td><?= htmlspecialchars($r['department']) ?></td>
<td><form method="post" onsubmit="return confirm('Delete this student?')"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $r['id'] ?>"><button class="btn danger">Delete</button></form></td>
</tr><?php endwhile; ?></table></div>
<?php include 'includes/footer.php'; ?>
