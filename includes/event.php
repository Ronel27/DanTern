<?php
require_once('class/database.php');
$database = new Database();
// Ensure your Database class uses PDO for the getConnection() method
$db = $database->getConnection();

try {
    // We select all columns from the 'events' table
    $events_query = $db->query("SELECT * FROM events ORDER BY created_at DESC");
    $events = $events_query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) { 
    $events = []; 
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
    /* Modal and Overlay Styles */
    .event-clickable { cursor: pointer; display: block; text-decoration: none; color: inherit; }
    .custom-modal-overlay {
        display: none; position: fixed; z-index: 9999; left: 0; top: 0; 
        width: 100%; height: 100%; background-color: rgba(0,0,0,0.8);
        align-items: center; justify-content: center; padding: 20px;
    }
    .modal-card {
        background: white; width: 100%; max-width: 800px; border-radius: 15px;
        overflow: hidden; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }
    .modal-close-btn { position: absolute; right: 20px; top: 15px; font-size: 30px; cursor: pointer; color: #fff; z-index: 100; }
    .modal-header-bg { background: #8c1d1d; color: white; padding: 20px; }
    .detail-row { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 15px; }
    .detail-box { flex: 1; min-width: 140px; background: #f8f9fa; padding: 10px; border-radius: 8px; border-left: 4px solid #8c1d1d; }
    
    /* Tagline Style */
    #modalTagline, .card-tagline {
        display: block;
        color: #8c1d1d;
        font-weight: 700;
        font-style: italic;
        margin-bottom: 10px;
    }
    .card-tagline { font-size: 0.9rem; margin-top: 5px; }

    /* Highlights Style */
    .card-highlights {
        font-size: 0.85rem;
        color: #666;
        border-top: 1px solid #eee;
        padding-top: 10px;
        margin-top: 10px;
    }
</style>

<section id="events" class="news-section section light-background">
    <div class="container" data-aos="fade-up">
        <div class="section-title text-center">
            <h2>Upcoming Events</h2>
            <p>Stay updated with the latest happenings.</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-11 position-relative">
                <div class="education-soul-carousel">
                    <?php if (!empty($events)): ?>
                        <?php foreach ($events as $row): ?>
                            <?php 
                                // Map 'subtitle' from database to the 'tag' variable for the script
                                $tag = isset($row['subtitle']) ? addslashes($row['subtitle']) : ''; 
                            ?>
                            <div class="event-clickable" onclick="showEvent(
                                '<?php echo addslashes($row['title']); ?>', 
                                '<?php echo addslashes($row['description']); ?>', 
                                '<?php echo $row['banner_path']; ?>', 
                                '<?php echo $row['event_dates']; ?>', 
                                '<?php echo $row['location']; ?>', 
                                '<?php echo $row['event_time']; ?>', 
                                '<?php echo $row['admission']; ?>', 
                                '<?php echo addslashes($row['highlights']); ?>', 
                                '<?php echo $tag; ?>'
                            )">
                                <div class="news-post">
                                    <div class="news-post-inner">
                                        <img width="400" height="200" src="<?php echo htmlspecialchars($row['banner_path']); ?>" class="aligncenter" alt="Event">
                                        <div class="news-content">
                                            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                                            
                                            <div class="entry-meta">
                                                <span class="posted-on"><i class="far fa-calendar-alt"></i> <?php echo htmlspecialchars($row['event_dates']); ?></span>
                                            </div>

                                            <?php if(!empty($row['subtitle'])): ?>
                                                <span class="card-tagline"><?php echo htmlspecialchars($row['subtitle']); ?></span>
                                            <?php endif; ?>

                                            <div class="card-highlights">
                                                <strong>Highlights:</strong>
                                                <p><?php echo htmlspecialchars(substr($row['highlights'], 0, 100)); ?>...</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center">No upcoming events at this time.</p>
                    <?php endif; ?>
                </div>
                <span class="left-arrow carousel-arrow slick-arrow"><i class="fas fa-angle-left"></i></span>
                <span class="right-arrow carousel-arrow slick-arrow"><i class="fas fa-angle-right"></i></span>
            </div>
        </div>
    </div>
</section>

<div id="eventDetailOverlay" class="custom-modal-overlay">
    <div class="modal-card">
        <span class="modal-close-btn" onclick="hideEvent()">&times;</span>
        <div class="modal-header-bg">
            <h3 id="modalTitle" style="margin:0;"></h3>
        </div>
        <div style="padding: 25px;">
            <div class="row">
                <div class="col-md-5">
                    <img id="modalImg" src="" style="width:100%; border-radius:10px; margin-bottom:15px;">
                </div>
                <div class="col-md-7">
                    <h6><strong>Event Overview:</strong></h6>
                    <p id="modalDesc" style="line-height:1.6; color:#333;"></p>
                    <div class="detail-row">
                        <div class="detail-box"><strong>Location:</strong><br><span id="modalLoc"></span></div>
                        <div class="detail-box"><strong>Time:</strong><br><span id="modalTime"></span></div>
                        <div class="detail-box"><strong>Entry:</strong><br><span id="modalAdm"></span></div>
                        <div class="detail-box"><strong>Date:</strong><br><span id="modaldate"></span></div>
                    </div>
                </div>
            </div>
            <hr>
            <div id="modalTagline" style="font-size: 1.2rem;"></div>
            <h6><strong>Event Highlights:</strong></h6>
            <p id="modalHigh" class="text-muted"></p>
        </div>
    </div>
</div>

<script>
function showEvent(title, desc, img, date, loc, time, adm, high, tagline) {
    document.getElementById('modalTitle').innerText = title;
    document.getElementById('modalDesc').innerText = desc;
    document.getElementById('modalImg').src = img;
    document.getElementById('modalLoc').innerText = loc;
    document.getElementById('modalTime').innerText = time;
    document.getElementById('modalAdm').innerText = adm;
    document.getElementById('modaldate').innerText = date;
    document.getElementById('modalHigh').innerText = high;
    
    const tagEl = document.getElementById('modalTagline');
    if(tagline && tagline.trim() !== "") {
        tagEl.innerText = tagline;
        tagEl.style.display = "block";
    } else {
        tagEl.style.display = "none";
    }

    document.getElementById('eventDetailOverlay').style.display = 'flex';
}

function hideEvent() {
    document.getElementById('eventDetailOverlay').style.display = 'none';
}

// Close modal if user clicks outside the modal card
window.onclick = function(event) {
    if (event.target == document.getElementById('eventDetailOverlay')) {
        hideEvent();
    }
}
</script>