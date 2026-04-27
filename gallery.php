<?php
require_once('./class/database.php');
$database = new Database();
$db = $database->getConnection();

// 1. Fetch Awards
$awards = $db->query("SELECT award_title as title, recipient_name, award_image as path, description, award_year, 'award' as type, created_at FROM awards")->fetchAll(PDO::FETCH_ASSOC);

// 2. Fetch Videos
$videos = $db->query("SELECT video_title as title, video_source as path, 'video' as type, created_at, video_type FROM admin_videos")->fetchAll(PDO::FETCH_ASSOC);

// 3. Fetch Events 
$events = $db->query("SELECT title as title, subtitle, banner_path as path, location, event_time, admission, description, 'event' as type, created_at FROM events")->fetchAll(PDO::FETCH_ASSOC);

$gallery_items = array_merge($awards, $videos, $events);
usort($gallery_items, function($a, $b) {
    return strtotime($b['created_at']) - strtotime($a['created_at']);
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery - Office of Culture & Arts</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --maroon: #8c1d1d; }
        body { background-color: #f8f9fa; }
        
        .sidebar { height: 100vh; background: white; border-right: 1px solid #dee2e6; position: fixed; padding-top: 20px; z-index: 1000; }
        .nav-link { color: #495057; font-weight: 500; padding: 12px 20px; transition: 0.3s; cursor: pointer; }
        .nav-link:hover, .nav-link.active { background: var(--maroon); color: white !important; }
        .nav-link i { margin-right: 10px; width: 20px; text-align: center; }

        .gallery-header { background: white; padding: 15px 40px; border-bottom: 1px solid #dee2e6; position: sticky; top: 0; z-index: 999; }
        .search-container { max-width: 400px; }
        .search-container .form-control { border-radius: 20px; padding-left: 40px; }
        .search-container i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #adb5bd; }

        .gallery-content { margin-left: 250px; }
        .gallery-body { padding: 40px; }
        
        .gallery-item { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.05); transition: 0.3s; height: 100%; border: 1px solid #eee; cursor: pointer; }
        .gallery-item:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        
        .media-wrapper { position: relative; width: 100%; padding-top: 56.25%; background: #000; }
        .media-wrapper img, .media-wrapper video, .media-wrapper iframe { position: absolute; top: 0; left: 0; bottom: 0; right: 0; width: 100%; height: 100%; object-fit: cover; }

        .item-details { padding: 15px; }
        .item-title { font-size: 0.95rem; font-weight: 600; margin-bottom: 8px; color: #212529; }
        
        .bg-award { background-color: #ffc107; color: #000; }
        .bg-video { background-color: #dc3545; color: #fff; }
        .bg-event { background-color: #0d6efd; color: #fff; }

        .detail-info { font-size: 0.9rem; margin-bottom: 10px; padding: 8px; background: #f8f9fa; border-radius: 5px; }
        .detail-info i { color: var(--maroon); width: 20px; margin-right: 10px; }
        
        /* Full Image View Style (Used for All Images tab) */
        .simple-view img { max-height: 85vh; width: auto; max-width: 100%; margin: 0 auto; display: block; }

        @media (max-width: 768px) { .sidebar { position: relative; height: auto; width: 100%; border-right: none; } .gallery-content { margin-left: 0; } }
    </style>
</head>
<body>

<div class="container-fluid p-0">
    <div class="row g-0">
        <div class="col-md-3 col-lg-2 sidebar">
            <div class="text-center mb-4">
                <h5 class="fw-bold" style="color: var(--maroon);">Faculty Union</h5>
                <small class="text-muted">Media Center</small>
            </div>
            
            <nav class="nav flex-column">
                <a class="nav-link active" onclick="setCategory('all', this)">
                    <i class="fas fa-th-large"></i> All Media
                </a>
                <a class="nav-link" onclick="setCategory('video', this)">
                    <i class="fas fa-video"></i> Videos
                </a>
                <a class="nav-link" onclick="setCategory('image_only', this)">
                    <i class="fas fa-images"></i> All Images
                </a>
                <a class="nav-link" onclick="setCategory('award', this)">
                    <i class="fas fa-award"></i> Awards
                </a>
                <a class="nav-link" onclick="setCategory('event', this)">
                    <i class="fas fa-calendar-alt"></i> Events
                </a>
                <hr class="mx-3">
                <a class="nav-link text-muted" href="index.php">
                    <i class="fas fa-arrow-left"></i> Back to Home
                </a>
            </nav>
        </div>

        <div class="col-md-9 col-lg-10 gallery-content">
            <div class="gallery-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold">Gallery</h4>
                <div class="search-container position-relative">
                    <i class="fas fa-search"></i>
                    <input type="text" id="gallerySearch" class="form-control" placeholder="Search..." onkeyup="applyFilters()">
                </div>
            </div>

            <div class="gallery-body">
                <div class="row g-4" id="gallery-grid">
                    <?php foreach ($gallery_items as $item): ?>
                    <div class="col-sm-6 col-lg-4 gallery-card" 
                         data-type="<?php echo $item['type']; ?>" 
                         data-title="<?php echo strtolower(htmlspecialchars($item['title'])); ?>">
                        
                        <div class="gallery-item" onclick="viewDetails(<?php echo htmlspecialchars(json_encode($item)); ?>)">
                            <div class="media-wrapper">
                                <?php if ($item['type'] == 'video'): ?>
                                    <i class="fas fa-play-circle position-absolute top-50 start-50 translate-middle text-white fa-3x" style="z-index: 1;"></i>
                                    <img src="https://img.youtube.com/vi/<?php 
                                        preg_match('/embed\/([^\/\?]+)/', $item['path'], $id); 
                                        echo $id[1] ?? 'default'; 
                                    ?>/0.jpg">
                                <?php else: ?>
                                    <img src="<?php echo htmlspecialchars($item['path']); ?>">
                                <?php endif; ?>
                            </div>
                            <div class="item-details">
                                <div class="item-title text-truncate"><?php echo htmlspecialchars($item['title']); ?></div>
                                <span class="badge bg-<?php echo $item['type']; ?> rounded-pill"><?php echo strtoupper($item['type']); ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="modalBodyContent">
                </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
let currentCategory = 'all';

function setCategory(category, element) {
    document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
    element.classList.add('active');
    currentCategory = category;
    applyFilters();
}

function applyFilters() {
    const searchTerm = document.getElementById('gallerySearch').value.toLowerCase();
    const cards = document.querySelectorAll('.gallery-card');
    cards.forEach(card => {
        const title = card.getAttribute('data-title');
        const type = card.getAttribute('data-type');
        const matchesSearch = title.includes(searchTerm);
        let matchesCategory = (currentCategory === 'all' || type === currentCategory);
        if (currentCategory === 'image_only') matchesCategory = (type === 'award' || type === 'event');
        card.style.display = (matchesSearch && matchesCategory) ? 'block' : 'none';
    });
}

function viewDetails(data) {
    const modalBody = document.getElementById('modalBodyContent');
    modalBody.innerHTML = ""; // Clear content

    // Check if we are in "All Images" or "All Media" mode to show the SIMPLE view
    if (currentCategory === 'image_only' || currentCategory === 'all') {
        if (data.type === 'video') {
            renderDetailedView(data); // Videos always need the player
        } else {
            renderSimpleView(data); // Simple view: Just the image
        }
    } else {
        // Detailed view for specific Awards or Events tabs
        renderDetailedView(data);
    }
    
    new bootstrap.Modal(document.getElementById('detailsModal')).show();
}

function renderSimpleView(data) {
    const modalBody = document.getElementById('modalBodyContent');
    modalBody.innerHTML = `
        <div class="simple-view text-center">
            <img src="${data.path}" class="rounded shadow-sm img-fluid">
            <h4 class="mt-3 fw-bold">${data.title}</h4>
        </div>
    `;
}

function renderDetailedView(data) {
    const modalBody = document.getElementById('modalBodyContent');
    let mediaHtml = data.type === 'video' 
        ? `<div class="ratio ratio-16x9 rounded overflow-hidden shadow-sm">${data.video_type === 'youtube' ? `<iframe src="${data.path}" allowfullscreen></iframe>` : `<video controls autoplay><source src="${data.path}" type="video/mp4"></video>`}</div>`
        : `<img src="${data.path}" class="img-fluid rounded shadow-sm">`;

    let infoHtml = "";
    if (data.type === 'award') {
        infoHtml = `<div class="detail-info"><i class="fas fa-user"></i> <strong>Recipient:</strong> ${data.recipient_name}</div>
                    <div class="detail-info"><i class="fas fa-calendar"></i> <strong>Year:</strong> ${data.award_year}</div>`;
    } else if (data.type === 'event') {
        infoHtml = `<div class="detail-info"><i class="fas fa-map-marker-alt"></i> <strong>Location:</strong> ${data.location}</div>
                    <div class="detail-info"><i class="fas fa-clock"></i> <strong>Time:</strong> ${data.event_time}</div>`;
    }

    modalBody.innerHTML = `
        <div class="row g-4">
            <div class="col-md-5">${mediaHtml}</div>
            <div class="col-md-7">
                <h3 class="fw-bold mb-1" style="color: var(--maroon);">${data.title}</h3>
                <p class="text-muted mb-3">${data.subtitle || data.type.toUpperCase()}</p>
                ${infoHtml}
                <div class="mt-3 border-top pt-3"><p>${data.description || "No description provided."}</p></div>
            </div>
        </div>
    `;
}
</script>
</body>
</html>