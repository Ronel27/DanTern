<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: auth/login.php"); exit(); }
require_once('../class/database.php');
$database = new Database();
$db = $database->getConnection();

$msg = "";

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $db->prepare("DELETE FROM officers WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manage_officers.php?msg=Deleted");
    exit();
}

// Handle Add
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_officer'])) {
    $name = $_POST['full_name'];
    $pos = $_POST['position'];
    $dept = $_POST['department'];
    $cat = $_POST['category'];
    $rank = $_POST['rank'];

    $stmt = $db->prepare("INSERT INTO officers (full_name, position, department_acronym, category, rank) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $pos, $dept, $cat, $rank]);
    header("Location: manage_officers.php?msg=Added");
    exit();
}

// --- FIX: Handle Edit Logic ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_officer'])) {
    $id = $_POST['id'];
    $name = $_POST['full_name'];
    $pos = $_POST['position'];
    $dept = $_POST['department'];
    $cat = $_POST['category'];
    $rank = $_POST['rank'];

    $stmt = $db->prepare("UPDATE officers SET full_name = ?, position = ?, department_acronym = ?, category = ?, rank = ? WHERE id = ?");
    $stmt->execute([$name, $pos, $dept, $cat, $rank, $id]);
    header("Location: manage_officers.php?msg=Updated");
    exit();
}

$officers = $db->query("SELECT * FROM officers ORDER BY rank ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Officers - Admin</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table thead { background: #8c1d1d; color: white; }
        .btn-maroon { background: #8c1d1d; color: white; border: none; }
        .btn-maroon:hover { background: #d4af37; color: black; }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between mb-4">
        <h3><a href="dashboard.php" class="text-dark text-decoration-none mr-3">&larr;</a> Manage Union Officers</h3>
        <button class="btn btn-maroon" data-toggle="modal" data-target="#addModal">Add New Officer</button>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?php echo $_GET['msg']; ?> successful!</div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Dept</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($officers as $o): ?>
                <tr>
                    <td><?php echo $o['rank']; ?></td>
                    <td><?php echo htmlspecialchars($o['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($o['position']); ?></td>
                    <td><?php echo $o['department_acronym']; ?></td>
                    <td><span class="badge badge-secondary"><?php echo $o['category']; ?></span></td>
                    <td>
                        <button class="btn btn-sm btn-info edit-btn" 
                                data-id="<?php echo $o['id']; ?>"
                                data-name="<?php echo htmlspecialchars($o['full_name']); ?>"
                                data-pos="<?php echo htmlspecialchars($o['position']); ?>"
                                data-dept="<?php echo htmlspecialchars($o['department_acronym']); ?>"
                                data-cat="<?php echo $o['category']; ?>"
                                data-rank="<?php echo $o['rank']; ?>"
                                data-toggle="modal" data-target="#editModal">Edit</button>

                        <a href="?delete=<?php echo $o['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this officer?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" method="POST">
            <div class="modal-header"><h5>Add New Officer</h5></div>
            <div class="modal-body">
                <input type="text" name="full_name" class="form-control mb-2" placeholder="Full Name" required>
                <input type="text" name="position" class="form-control mb-2" placeholder="Position" required>
                <input type="text" name="department" class="form-control mb-2" placeholder="Dept Acronym" required>
                <select name="category" class="form-control mb-2">
                    <option value="Executive">Executive</option>
                    <option value="Finance">Finance</option>
                </select>
                <input type="number" name="rank" class="form-control mb-2" placeholder="Rank (Order)" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" name="add_officer" class="btn btn-maroon">Save Officer</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" method="POST">
            <div class="modal-header"><h5>Edit Officer</h5></div>
            <div class="modal-body">
                <input type="hidden" name="id" id="edit_id">
                <label class="small font-weight-bold">Full Name</label>
                <input type="text" name="full_name" id="edit_name" class="form-control mb-2" required>
                
                <label class="small font-weight-bold">Position</label>
                <input type="text" name="position" id="edit_pos" class="form-control mb-2" required>
                
                <label class="small font-weight-bold">Department Acronym</label>
                <input type="text" name="department" id="edit_dept" class="form-control mb-2" required>
                
                <label class="small font-weight-bold">Category</label>
                <select name="category" id="edit_cat" class="form-control mb-2">
                    <option value="Executive">Executive</option>
                    <option value="Finance">Finance</option>
                </select>
                
                <label class="small font-weight-bold">Rank</label>
                <input type="number" name="rank" id="edit_rank" class="form-control mb-2" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" name="edit_officer" class="btn btn-maroon">Update Changes</button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Script to fill the Edit Modal with existing data
$('.edit-btn').on('click', function() {
    $('#edit_id').val($(this).data('id'));
    $('#edit_name').val($(this).data('name'));
    $('#edit_pos').val($(this).data('pos'));
    $('#edit_dept').val($(this).data('dept'));
    $('#edit_cat').val($(this).data('cat'));
    $('#edit_rank').val($(this).data('rank'));
});
</script>

</body>
</html>