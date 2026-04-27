<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { 
    header("Location: ../auth/login.php"); exit(); 
}
require_once('../class/database.php');
$database = new Database();
$db = $database->getConnection();

$success = "";
$error = "";

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $site_name = trim($_POST['site_name']);
    $logo_path = $_POST['current_logo']; // Keep old logo by default

    // Handle File Upload if a new logo is selected
    if (!empty($_FILES['logo']['name'])) {
        $target_dir = "../img/";
        $file_name = time() . "_" . basename($_FILES["logo"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)) {
            $logo_path = "img/" . $file_name; // Store relative path for the frontend
        } else {
            $error = "Failed to upload logo.";
        }
    }

    if (empty($error)) {
        $stmt = $db->prepare("UPDATE site_settings SET site_name = ?, logo_path = ? WHERE id = 1");
        $stmt->execute([$site_name, $logo_path]);
        $success = "Site settings updated successfully!";
    }
}

// Fetch current settings
$settings = $db->query("SELECT * FROM site_settings WHERE id = 1")->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Site Identity</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f7f6; }
        .card { border-top: 5px solid #8c1d1d; }
        .btn-maroon { background: #8c1d1d; color: white; }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="col-md-6 mx-auto">
        <a href="dashboard.php" class="btn btn-sm btn-outline-secondary mb-3">&larr; Dashboard</a>
        <div class="card p-4 shadow-sm">
            <h4 class="mb-4">Site Title & Logo</h4>
            
            <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>
            <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>

            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="current_logo" value="<?php echo $settings['logo_path']; ?>">
                
                <div class="form-group">
                    <label>Site Name</label>
                    <input type="text" name="site_name" class="form-control" value="<?php echo htmlspecialchars($settings['site_name']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Current Logo</label><br>
                    <img src="../<?php echo $settings['logo_path']; ?>" height="60" class="mb-2 border p-1">
                    <input type="file" name="logo" class="form-control-file">
                    <small class="text-muted">Leave empty to keep current logo.</small>
                </div>

                <button type="submit" class="btn btn-maroon btn-block mt-4">Save Changes</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>