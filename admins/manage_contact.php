<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header("Location: ../auth/login.php"); exit(); }
require_once('../class/database.php');
$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $db->prepare("UPDATE contact_info SET address=?, phone=?, hours=?, email=?, facebook_url=?, facebook_name=? WHERE id=1");
    $stmt->execute([$_POST['address'], $_POST['phone'], $_POST['hours'], $_POST['email'], $_POST['facebook_url'], $_POST['facebook_name']]);
    $success = "Contact info updated!";
}

$info = $db->query("SELECT * FROM contact_info WHERE id=1")->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Contact Info</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style> :root { --maroon: #8c1d1d; } .btn-maroon { background: var(--maroon); color: white; } </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="col-md-8 mx-auto card p-4 shadow-sm" style="border-top: 5px solid #8c1d1d;">
            <h4>Manage Faculty Union Contact Details</h4>
            <?php if(isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
            <form method="POST">
                <div class="form-group"><label>Address</label><textarea name="address" class="form-control" rows="3"><?php echo $info['address']; ?></textarea></div>
                <div class="row">
                    <div class="col-md-6 form-group"><label>Phone</label><input type="text" name="phone" class="form-control" value="<?php echo $info['phone']; ?>"></div>
                    <div class="col-md-6 form-group"><label>Office Hours</label><input type="text" name="hours" class="form-control" value="<?php echo $info['hours']; ?>"></div>
                </div>
                <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" value="<?php echo $info['email']; ?>"></div>
                <div class="row">
                    <div class="col-md-6 form-group"><label>FB Page Name</label><input type="text" name="facebook_name" class="form-control" value="<?php echo $info['facebook_name']; ?>"></div>
                    <div class="col-md-6 form-group"><label>FB URL</label><input type="text" name="facebook_url" class="form-control" value="<?php echo $info['facebook_url']; ?>"></div>
                </div>
                <button type="submit" class="btn btn-maroon btn-block">Update Contact Information</button>
                <a href="dashboard.php" class="btn btn-link btn-block">Back to Dashboard</a>
            </form>
        </div>
    </div>
</body>
</html>