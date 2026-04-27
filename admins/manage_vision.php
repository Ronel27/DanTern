<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { 
    header("Location: ../auth/login.php"); 
    exit(); 
}

require_once('../class/database.php');
$database = new Database();
$db = $database->getConnection();

$success = "";

// 1. Handle Vision Update (Single Textarea)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_vision'])) {
    $new_vision = $_POST['vision'];
    $stmt = $db->prepare("UPDATE union_info SET vision = ? WHERE id = 1");
    $stmt->execute([$new_vision]);
    $success = "Vision updated successfully!";
}

// 2. Handle Objective Actions (Add/Edit/Delete)
if (isset($_GET['delete_obj'])) {
    $stmt = $db->prepare("DELETE FROM objectives WHERE id = ?");
    $stmt->execute([$_GET['delete_obj']]);
    header("Location: manage_mission.php?msg=Deleted");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_objective'])) {
    $content = trim($_POST['content']);
    if (!empty($content)) {
        $stmt = $db->prepare("INSERT INTO objectives (content) VALUES (?)");
        $stmt->execute([$content]);
        header("Location: manage_mission.php?msg=Added");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_objective'])) {
    $stmt = $db->prepare("UPDATE objectives SET content = ? WHERE id = ?");
    $stmt->execute([trim($_POST['content']), $_POST['id']]);
    header("Location: manage_mission.php?msg=Updated");
    exit();
}

// Fetch Current Data
$vision = $db->query("SELECT vision FROM union_info WHERE id = 1")->fetchColumn();
$objectives = $db->query("SELECT * FROM objectives ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Vision & Objectives</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <style>
        :root { --maroon: #8c1d1d; --gold: #d4af37; }
        body { background-color: #f4f7f6; font-family: sans-serif; padding-bottom: 50px; }
        .btn-maroon { background: var(--maroon); color: white; border: none; font-weight: 600; }
        .btn-maroon:hover { background: var(--gold); color: black; }
        .section-card { border: none; border-top: 5px solid var(--maroon); border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 30px; }
        .table thead { background: var(--maroon); color: white; }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="mb-4">
        <a href="dashboard.php" class="text-muted text-decoration-none"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        <h2 class="mt-2" style="color: var(--maroon); font-weight: 700;">Manage Vision & Objectives</h2>
    </div>

    <?php if($success || isset($_GET['msg'])): ?>
        <div class="alert alert-success">Action successful!</div>
    <?php endif; ?>

    <div class="card section-card p-4">
        <h5 class="mb-3"><i class="fas fa-eye mr-2"></i> Union Vision</h5>
        <form method="POST">
            <div class="form-group">
                <textarea name="vision" class="form-control" rows="4" required><?php echo htmlspecialchars($vision); ?></textarea>
            </div>
            <button type="submit" name="update_vision" class="btn btn-maroon px-4">Update Vision</button>
        </form>
    </div>

    <div class="card section-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5><i class="fas fa-list-ul mr-2"></i> Union Objectives</h5>
            <button class="btn btn-sm btn-maroon" data-toggle="modal" data-target="#addModal"><i class="fas fa-plus"></i> Add Objective</button>
        </div>
        
        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="10%">#</th>
                    <th>Objective</th>
                    <th width="20%">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($objectives as $index => $obj): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo nl2br(htmlspecialchars($obj['content'])); ?></td>
                    <td>
                        <button class="btn btn-sm btn-info edit-btn" 
                                data-id="<?php echo $obj['id']; ?>"
                                data-content="<?php echo htmlspecialchars($obj['content']); ?>"
                                data-toggle="modal" data-target="#editModal"><i class="fas fa-edit"></i></button>
                        <a href="?delete_obj=<?php echo $obj['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i></a>
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
            <div class="modal-header"><h5>Add New Objective</h5></div>
            <div class="modal-body">
                <textarea name="content" class="form-control" rows="4" placeholder="Enter objective content..." required></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" name="add_objective" class="btn btn-maroon">Save</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" method="POST">
            <div class="modal-header"><h5>Edit Objective</h5></div>
            <div class="modal-body">
                <input type="hidden" name="id" id="edit_id">
                <textarea name="content" id="edit_content" class="form-control" rows="4" required></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" name="edit_objective" class="btn btn-maroon">Update</button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $('.edit-btn').on('click', function() {
        $('#edit_id').val($(this).data('id'));
        $('#edit_content').val($(this).data('content'));
    });
</script>
</body>
</html>