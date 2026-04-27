<?php
require_once('class/database.php');
$database = new Database();
$db = $database->getConnection();

try {
    $video_query = $db->query("SELECT * FROM admin_videos ORDER BY created_at DESC");
    $videos = $video_query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) { 
    $videos = []; 
}
?>

<style>
    :root { --maroon: #8c1d1d; --gold: #d4af37; --dark: #1a1a1a; }

    #videos-section { padding: 40px 0; background: #fdfdfd; }
    
    /* Carousel Item Styling */
    .video-item { padding: 12px; outline: none; }
    .video-card {
        background: #fff; border-radius: 15px; overflow: hidden;
        transition: 0.3s ease; cursor: pointer; border: 1px solid #eee;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }
    .video-card:hover { transform: translateY(-8px); border-color: var(--gold); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    
    /* Video Thumbnail Wrapper */
    .video-thumb-container { position: relative; width: 100%; height: 200px; background: #000; overflow: hidden; }
    .video-thumb-container img { width: 100%; height: 100%; object-fit: cover; border-bottom: 3px solid var(--gold); opacity: 0.8; }
    .play-overlay { 
        position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); 
        color: white; font-size: 3rem; text-shadow: 0 0 15px rgba(0,0,0,0.5); transition: 0.3s;
    }
    .video-card:hover .play-overlay { color: var(--gold); transform: translate(-50%, -50%) scale(1.1); }

    .video-card-body { padding: 15px; text-align: center; }
    .video-card-body h5 { color: var(--maroon); font-weight: 700; margin: 0; font-size: 1.1rem; }

    /* Navigation Arrows */
    .video-arrow {
        position: absolute; top: 50%; transform: translateY(-50%);
        z-index: 10; cursor: pointer; background: var(--maroon);
        color: white; width: 42px; height: 42px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; transition: 0.3s;
    }
    .video-arrow:hover { background: var(--gold); color: var(--dark); }
    .video-prev { left: -50px; }
    .video-next { right: -50px; }

    /* Modal Styling */
    .video-modal-overlay {
        display: none; position: fixed; z-index: 9999; left: 0; top: 0; 
        width: 100%; height: 100%; background: rgba(0,0,0,0.9);
        align-items: center; justify-content: center; padding: 20px;
    }
    .video-modal-box {
        background: white; width: 100%; max-width: 900px; border-radius: 20px;
        overflow: hidden; position: relative; box-shadow: 0 0 30px rgba(0,0,0,0.5);
    }
    .video-modal-header { background: var(--maroon); color: white; padding: 15px 20px; border-bottom: 4px solid var(--gold); }
    .video-modal-header h2 { color: white; margin: 0; font-size: 1.3rem; text-transform: uppercase; }
    
    .video-player-container { background: #000; width: 100%; aspect-ratio: 16 / 9; }
    .video-player-container iframe, .video-player-container video { width: 100%; height: 100%; border: none; }

    .close-video-modal { position: absolute; right: 20px; top: 10px; font-size: 30px; color: white; cursor: pointer; z-index: 100; }
</style>

<section id="videos">
    <div class="container">
        <div class="section-title text-center">
            <h2 style="color: var(--maroon); font-weight: 800; font-size: 2.2rem;">LATEST VIDEO HIGHLIGHTS</h2>
            <div style="width: 60px; height: 4px; background: var(--gold); margin: 5px auto 0;"></div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-11 position-relative">
                <div class="video-arrow video-prev"><i class="fas fa-chevron-left"></i></div>
                <div class="video-arrow video-next"><i class="fas fa-chevron-right"></i></div>
                
                <div class="videos-slick-carousel">
                    <?php if(!empty($videos)): foreach ($videos as $row): 
                        // Generate thumbnail if it's YouTube
                        $thumb = $row['thumbnail'];
                        if($row['video_type'] == 'youtube' && empty($thumb)) {
                            preg_match('/embed\/([^?]+)/', $row['video_source'], $matches);
                            $id = $matches[1] ?? '';
                            $thumb = "https://img.youtube.com/vi/$id/hqdefault.jpg";
                        }
                    ?>
                        <div class="video-item">
                            <div class="video-card" onclick="openVideoModal(
                                '<?php echo addslashes($row['video_title']); ?>', 
                                '<?php echo $row['video_source']; ?>', 
                                '<?php echo $row['video_type']; ?>'
                            )">
                                <div class="video-thumb-container">
                                    <img src="<?php echo $thumb; ?>" alt="Thumbnail">
                                    <div class="play-overlay"><i class="fas fa-play-circle"></i></div>
                                </div>
                                <div class="video-card-body">
                                    <h5><?php echo htmlspecialchars($row['video_title']); ?></h5>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; else: ?>
                        <div class="text-center p-5 w-100"><p>No videos available.</p></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="videoOverlay" class="video-modal-overlay">
    <div class="video-modal-box">
        <span class="close-video-modal" onclick="closeVideoModal()">&times;</span>
        <div class="video-modal-header"><h2 id="vTitle"></h2></div>
        <div id="vPlayer" class="video-player-container">
            </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $('.videos-slick-carousel').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 4000,
        prevArrow: $('.video-prev'),
        nextArrow: $('.video-next'),
        responsive: [
            { breakpoint: 992, settings: { slidesToShow: 2 } },
            { breakpoint: 768, settings: { slidesToShow: 1 } }
        ]
    });
});

function openVideoModal(title, source, type) {
    document.getElementById('vTitle').innerText = title;
    const player = document.getElementById('vPlayer');
    
    if(type === 'youtube') {
        player.innerHTML = `<iframe src="${source}?autoplay=1" allow="autoplay; encrypted-media" allowfullscreen></iframe>`;
    } else {
        player.innerHTML = `<video controls autoplay><source src="${source}" type="video/mp4">Your browser does not support the video tag.</video>`;
    }
    
    document.getElementById('videoOverlay').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeVideoModal() {
    document.getElementById('vPlayer').innerHTML = ""; // Stop video
    document.getElementById('videoOverlay').style.display = 'none';
    document.body.style.overflow = 'auto';
}

window.onclick = function(e) {
    if (e.target == document.getElementById('videoOverlay')) closeVideoModal();
}
</script>