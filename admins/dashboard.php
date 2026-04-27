<?php
session_start();
// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}
require_once('../class/database.php');
$database = new Database();
$db = $database->getConnection();

// Quick Stats
$count_officers = $db->query("SELECT COUNT(*) FROM officers")->fetchColumn();
$count_objectives = $db->query("SELECT DISTINCT COUNT(content) FROM objectives")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - WMSU FU</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <style>
        :root { --maroon: #8c1d1d; --gold: #d4af37; }
        body { font-family: 'Montserrat', sans-serif; background: #f4f7f6; }
        .sidebar { height: 100vh; background: var(--maroon); color: white; position: fixed; width: 250px; }
        .sidebar a { color: rgba(255,255,255,0.8); padding: 15px; display: block; text-decoration: none; border-bottom: 1px solid rgba(0,0,0,0.1); }
        .sidebar a:hover { background: rgba(0,0,0,0.2); color: var(--gold); }
        .main-content { margin-left: 250px; padding: 40px; }
        .card-stat { border-left: 5px solid var(--gold); }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="p-4 text-center">
        <h4 class="font-weight-bold">WMSU-FU</h4>
        <small>Admin Panel</small>
    </div>
    <a href="dashboard.php"><i class="fas fa-home mr-2"></i> Dashboard</a>
    <a href="manage_site.php"><i class="fas fa-image mr-2"></i> Manage Logo & Title</a>
    <a href="manage_officers.php"><i class="fas fa-users mr-2"></i> Manage Officers</a>
    <a href="manage_vision.php"><i class="fas fa-list mr-2"></i> Vision & Objectives</a>
    <a href="manage_contact.php"><i class="fas fa-address-book me-2"></i> Manage Contact</a>
    <a href="manage_events.php"><i class="fas fa-calendar-alt me-2"></i> Manage Events</a>
    <a href="manage_awards.php"><i class="fas fa-award me-2"></i> Manage Awards</a>
    <a href="manage_videos.php"><i class="fas fa-play-circle me-2"></i> Manage Videos</a>
    <a href="../auth/logout.php" class="text-warning"><i class="fas fa-sign-out-alt mr-2"></i> Logout</a>
</div>

<div class="main-content">
    <h2 class="mb-4">Dashboard Overview</h2>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card card-stat shadow-sm p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted">Total Officers</h6>
                        <h3><?php echo $count_officers; ?></h3>
                    </div>
                    <i class="fas fa-user-tie fa-2x text-muted"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-stat shadow-sm p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted">Objectives</h6>
                        <h3><?php echo $count_objectives; ?></h3>
                    </div>
                    <i class="fas fa-bullseye fa-2x text-muted"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5">
        <h4>Recent Activity</h4>
        <div class="alert alert-info">Welcome back, <?php echo $_SESSION['username']; ?>. Use the sidebar to manage website content.</div>
    </div>
</div>

</body>
</html>