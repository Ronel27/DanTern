<?php
// Corrected path to step out of 'admins' folder to find 'class'
require_once('../class/database.php');
$database = new Database();
$db = $database->getConnection();

// --- HANDLE DELETE ---
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $db->prepare("DELETE FROM awards WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manage_awards.php");
    exit();
}

// --- HANDLE CREATE ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_award'])) {
    $title = $_POST['award_title'];
    $recipient = $_POST['recipient_name'];
    $desc = $_POST['description'];
    $year = $_POST['award_year'];
    
    $upload_dir = "../uploads/awards/"; 
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
    
    $file_name = time() . "_" . basename($_FILES["award_image"]["name"]);
    $upload_file = $upload_dir . $file_name;

    if (move_uploaded_file($_FILES["award_image"]["tmp_name"], $upload_file)) {
        $db_save_path = "uploads/awards/" . $file_name; 
        $stmt = $db->prepare("INSERT INTO awards (award_title, recipient_name, description, award_image, award_year) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $recipient, $desc, $db_save_path, $year]);
        header("Location: manage_awards.php?success=1");
        exit();
    }
}

// --- HANDLE UPDATE (EDIT) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_award'])) {
    $id = $_POST['award_id'];
    $title = $_POST['award_title'];
    $recipient = $_POST['recipient_name'];
    $desc = $_POST['description'];
    $year = $_POST['award_year'];

    if (!empty($_FILES["award_image"]["name"])) {
        $upload_dir = "../uploads/awards/";
        $file_name = time() . "_" . basename($_FILES["award_image"]["name"]);
        $upload_file = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES["award_image"]["tmp_name"], $upload_file)) {
            $db_save_path = "uploads/awards/" . $file_name;
            $stmt = $db->prepare("UPDATE awards SET award_title=?, recipient_name=?, description=?, award_year=?, award_image=? WHERE id=?");
            $stmt->execute([$title, $recipient, $desc, $year, $db_save_path, $id]);
        }
    } else {
        $stmt = $db->prepare("UPDATE awards SET award_title=?, recipient_name=?, description=?, award_year=? WHERE id=?");
        $stmt->execute([$title, $recipient, $desc, $year, $id]);
    }
    header("Location: manage_awards.php?updated=1");
    exit();
}

$awards = $db->query("SELECT * FROM awards ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Awards - Faculty Union</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --maroon: #8c1d1d; }
        .btn-maroon { background: var(--maroon); color: white; }
        .btn-maroon:hover { background: #6b1616; color: white; }
        .img-preview { width: 60px; height: 60px; object-fit: cover; border-radius: 5px; border: 1px solid #ddd; }
        .back-link { text-decoration: none; color: #666; font-weight: 500; transition: 0.3s; }
        .back-link:hover { color: var(--maroon); }
    </style>
</head>
<body class="bg-light">

<div class="container mt-4 pb-5">
    <div class="mb-4">
        <a href="dashboard.php" class="back-link">
            <i class="bi bi-arrow-left-circle-fill"></i> Back to Dashboard
        </a>
    </div>

    <div class="card shadow mb-5">
        <div class="card-header btn-maroon">
            <h4 class="mb-0">Add New Faculty Award</h4>
        </div>
        <div class="card-body">
            <form action="" method="POST" enctype="multipart/form-data" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Award Title</label>
                    <input type="text" name="award_title" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Recipient Name</label>
                    <input type="text" name="recipient_name" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Award Year</label>
                    <input type="number" name="award_year" class="form-control" value="<?php echo date('Y'); ?>" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Award Image</label>
                    <input type="file" name="award_image" class="form-control" accept="image/*" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Description / Citation</label>
                    <textarea name="description" class="form-control" rows="3" required></textarea>
                </div>
                <div class="col-12">
                    <button type="submit" name="add_award" class="btn btn-maroon w-100">Publish Award</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0 text-dark">Existing Awards</h4>
            <span class="badge bg-secondary"><?php echo count($awards); ?> Total</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Image</th>
                        <th>Award Details</th>
                        <th>Year</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($awards)): foreach ($awards as $row): ?>
                    <tr>
                        <td><img src="../<?php echo $row['award_image']; ?>" class="img-preview"></td>
                        <td>
                            <strong class="text-dark"><?php echo htmlspecialchars($row['award_title']); ?></strong><br>
                            <small class="text-muted">Recipient: <?php echo htmlspecialchars($row['recipient_name']); ?></small>
                        </td>
                        <td><span class="badge bg-light text-dark border"><?php echo $row['award_year']; ?></span></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-primary" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                            <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this record?')">
                                <i class="bi bi-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="4" class="text-center py-4 text-muted">No awards found in database.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="editAwardModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-header btn-maroon text-white">
                    <h5 class="modal-title">Edit Award Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="award_id" id="edit_id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Award Title</label>
                            <input type="text" name="award_title" id="edit_title" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Recipient Name</label>
                            <input type="text" name="recipient_name" id="edit_recipient" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Award Year</label>
                            <input type="number" name="award_year" id="edit_year" class="form-control" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Update Image (Leave blank to keep current)</label>
                            <input type="file" name="award_image" class="form-control" accept="image/*">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description / Citation</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="4" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="update_award" class="btn btn-maroon">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
function openEditModal(award) {
    document.getElementById('edit_id').value = award.id;
    document.getElementById('edit_title').value = award.award_title;
    document.getElementById('edit_recipient').value = award.recipient_name;
    document.getElementById('edit_year').value = award.award_year;
    document.getElementById('edit_description').value = award.description;
    
    var editModal = new bootstrap.Modal(document.getElementById('editAwardModal'));
    editModal.show();
}
</script>

</body>
</html>