<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header("Location: ../auth/login.php"); exit(); }
require_once('../class/database.php');
$database = new Database();
$db = $database->getConnection();

// Handle Delete
if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM events WHERE id = ?")->execute([$_GET['delete']]);
    header("Location: manage_events.php?msg=Deleted");
    exit();
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    $desc = $_POST['description'];
    $dates = $_POST['event_dates'];
    $loc = $_POST['location'];
    $time = $_POST['event_time'];
    $adm = $_POST['admission'];
    $feat = $_POST['features'];
    $high = $_POST['highlights'];
    
    // Simple Image Upload Logic
    $banner = $_POST['current_banner'] ?? 'img/event-default.jpg';
    if (!empty($_FILES['banner']['name'])) {
        $target = "../img/" . time() . "_" . $_FILES['banner']['name'];
        if (move_uploaded_file($_FILES['banner']['tmp_name'], $target)) {
            $banner = "img/" . basename($target);
        }
    }

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update
        $sql = "UPDATE events SET title=?, subtitle=?, description=?, event_dates=?, location=?, event_time=?, admission=?, features=?, highlights=?, banner_path=? WHERE id=?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$title, $subtitle, $desc, $dates, $loc, $time, $adm, $feat, $high, $banner, $_POST['id']]);
    } else {
        // Insert
        $sql = "INSERT INTO events (title, subtitle, description, event_dates, location, event_time, admission, features, highlights, banner_path) VALUES (?,?,?,?,?,?,?,?,?,?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$title, $subtitle, $desc, $dates, $loc, $time, $adm, $feat, $high, $banner]);
    }
    header("Location: manage_events.php?msg=Success");
    exit();
}

$events = $db->query("SELECT * FROM events ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Events - Faculty Union</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <style>
        :root { --maroon: #8c1d1d; --gold: #d4af37; }
        body { background: #f4f7f6; }
        .btn-maroon { background: var(--maroon); color: white; }
        .table thead { background: var(--maroon); color: white; }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="d-flex justify-content-between mb-4">
        <h3><a href="dashboard.php" class="text-dark mr-2"><i class="fas fa-arrow-left"></i></a> Manage Events</h3>
        <button class="btn btn-maroon" data-toggle="modal" data-target="#eventModal" onclick="clearForm()">+ Post New Event</button>
    </div>

    <div class="card shadow-sm">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Event Title</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($events as $e): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($e['title']); ?></strong></td>
                    <td><?php echo htmlspecialchars($e['event_dates']); ?></td>
                    <td><?php echo htmlspecialchars($e['location']); ?></td>
                    <td>
                        <button class="btn btn-sm btn-info edit-btn" 
                            data-id="<?php echo $e['id']; ?>"
                            data-title="<?php echo htmlspecialchars($e['title']); ?>"
                            data-subtitle="<?php echo htmlspecialchars($e['subtitle']); ?>"
                            data-desc="<?php echo htmlspecialchars($e['description']); ?>"
                            data-dates="<?php echo htmlspecialchars($e['event_dates']); ?>"
                            data-loc="<?php echo htmlspecialchars($e['location']); ?>"
                            data-time="<?php echo htmlspecialchars($e['event_time']); ?>"
                            data-adm="<?php echo htmlspecialchars($e['admission']); ?>"
                            data-feat="<?php echo htmlspecialchars($e['features']); ?>"
                            data-high="<?php echo htmlspecialchars($e['highlights']); ?>"
                            data-banner="<?php echo $e['banner_path']; ?>"
                            data-toggle="modal" data-target="#eventModal">Edit</button>
                        <a href="?delete=<?php echo $e['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this event?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" method="POST" enctype="multipart/form-data">
            <div class="modal-header"><h5>Event Details</h5></div>
            <div class="modal-body">
                <input type="hidden" name="id" id="event_id">
                <input type="hidden" name="current_banner" id="current_banner">
                
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Event Title</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Tagline/Subtitle</label>
                        <input type="text" name="subtitle" id="subtitle" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label>Description (About the Event)</label>
                    <textarea name="description" id="description" class="form-control" rows="3" required></textarea>
                </div>

                <div class="row">
                    <div class="col-md-4 form-group"><label>Dates</label><input type="text" name="event_dates" id="event_dates" class="form-control" placeholder="Dec 1-10, 2025"></div>
                    <div class="col-md-4 form-group"><label>Location</label><input type="text" name="location" id="location" class="form-control" placeholder="Faculty Union Hall"></div>
                    <div class="col-md-4 form-group"><label>Time</label><input type="text" name="event_time" id="event_time" class="form-control" placeholder="8:00 AM - 5:00 PM"></div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group"><label>Admission</label><input type="text" name="admission" id="admission" class="form-control" placeholder="Free Entry"></div>
                    <div class="col-md-6 form-group"><label>Featuring/Features</label><input type="text" name="features" id="features" class="form-control" placeholder="Arts, Music, etc."></div>
                </div>

                <div class="form-group">
                    <label>Highlights (One per line)</label>
                    <textarea name="highlights" id="highlights" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label>Banner Image</label>
                    <input type="file" name="banner" class="form-control-file">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-maroon">Save Event</button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
$('.edit-btn').click(function() {
    $('#event_id').val($(this).data('id'));
    $('#title').val($(this).data('title'));
    $('#subtitle').val($(this).data('subtitle'));
    $('#description').val($(this).data('desc'));
    $('#event_dates').val($(this).data('dates'));
    $('#location').val($(this).data('loc'));
    $('#event_time').val($(this).data('time'));
    $('#admission').val($(this).data('adm'));
    $('#features').val($(this).data('feat'));
    $('#highlights').val($(this).data('high'));
    $('#current_banner').val($(this).data('banner'));
});

function clearForm() {
    $('#event_id').val('');
    $('.modal-body input, .modal-body textarea').val('');
}
</script>
</body>
</html>