<?php
require_once('class/database.php');
$database = new Database();
$db = $database->getConnection();

try {
    $awards_query = $db->query("SELECT * FROM awards ORDER BY award_year DESC, created_at DESC");
    $awards = $awards_query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) { 
    $awards = []; 
}
?>

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
    :root { --maroon: #8c1d1d; --gold: #d4af37; --dark: #1a1a1a; }

    /* Section Tightening */
    #awards-section { padding: 40px 0; background: #fdfdfd; }
    .section-title { margin-bottom: 25px; }

    /* Carousel Item Styling */
    .award-item { padding: 12px; outline: none; }
    .award-card {
        background: #fff; border-radius: 15px; overflow: hidden;
        transition: 0.3s ease; cursor: pointer; border: 1px solid #eee;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }
    .award-card:hover { transform: translateY(-8px); border-color: var(--gold); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    .award-card img { width: 100%; height: 280px; object-fit: cover; border-bottom: 3px solid var(--gold); }
    .award-card-body { padding: 15px; text-align: center; }
    .award-card-body h5 { color: var(--maroon); font-weight: 700; margin: 0; font-size: 1.1rem; }
    .award-card-body small { color: #666; font-style: italic; }

    /* Custom Navigation Arrows (Like Events) */
    .award-arrow {
        position: absolute; top: 50%; transform: translateY(-50%);
        z-index: 10; cursor: pointer; background: var(--maroon);
        color: white; width: 42px; height: 42px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; transition: 0.3s;
    }
    .award-arrow:hover { background: var(--gold); color: var(--dark); }
    .award-prev { left: -50px; }
    .award-next { right: -50px; }

    /* Modal Styling (Detail View) */
    .award-modal-overlay {
        display: none; position: fixed; z-index: 9999; left: 0; top: 0; 
        width: 100%; height: 100%; background: rgba(0,0,0,0.9);
        align-items: center; justify-content: center; padding: 20px;
    }
    .award-modal-box {
        background: white; width: 100%; max-width: 950px; border-radius: 20px;
        overflow: hidden; position: relative; max-height: 90vh; display: flex; flex-direction: column;
    }
    .modal-header-union { background: var(--maroon); color: white; padding: 20px; text-align: center; border-bottom: 4px solid var(--gold); }
    .modal-header-union h2 {color:white;, margin: 0; font-weight: 800; text-transform: uppercase; font-size: 1.5rem; }
    
    .modal-main-body { display: flex; flex-wrap: wrap; overflow-y: auto; }
    .modal-img-pane { flex: 1; min-width: 350px; background: #000; display: flex; align-items: center; justify-content: center; padding: 20px; }
    .modal-img-pane img { max-width: 100%; max-height: 450px; border: 4px solid white; border-radius: 5px; }
    
    .modal-text-pane { flex: 1; min-width: 350px; padding: 40px; background: #fff; }
    .info-group { margin-bottom: 20px; }
    .info-label { color: var(--maroon); font-weight: 700; text-transform: uppercase; font-size: 0.75rem; display: block; margin-bottom: 3px; }
    .info-value { font-size: 1.2rem; color: var(--dark); font-weight: 600; display: block; }
    .info-desc { font-size: 1rem; line-height: 1.7; color: #444; border-top: 1px solid #eee; padding-top: 15px; text-align: justify; }

    .close-btn-modal { position: absolute; right: 20px; top: 12px; font-size: 35px; color: white; cursor: pointer; z-index: 100; transition: 0.3s; }
    .close-btn-modal:hover { color: var(--gold); transform: scale(1.1); }
</style>

<section id="awards">
    <div class="container">
        <div class="section-title text-center">
            <h2 style="color: var(--maroon); font-weight: 800; font-size: 2.2rem;">FACULTY AWARDS & RECOGNITION</h2>
            <div style="width: 60px; height: 4px; background: var(--gold); margin: 5px auto 0;"></div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-11 position-relative">
                <div class="award-arrow award-prev"><i class="fas fa-chevron-left"></i></div>
                <div class="award-arrow award-next"><i class="fas fa-chevron-right"></i></div>
                
                <div class="awards-slick-carousel">
                    <?php if(!empty($awards)): foreach ($awards as $row): ?>
                        <div class="award-item">
                            <div class="award-card" onclick="openAwardModal(
                                '<?php echo addslashes($row['award_title']); ?>', 
                                '<?php echo addslashes($row['recipient_name']); ?>', 
                                '<?php echo addslashes($row['description']); ?>', 
                                '<?php echo htmlspecialchars($row['award_image']); ?>', 
                                '<?php echo $row['award_year']; ?>'
                            )">
                                <img src="<?php echo htmlspecialchars($row['award_image']); ?>" alt="Award">
                                <div class="award-card-body">
                                    <h5><?php echo htmlspecialchars($row['award_title']); ?></h5>
                                    <small><?php echo htmlspecialchars($row['recipient_name']); ?></small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; else: ?>
                        <div class="text-center p-5 w-100"><p>No awards found.</p></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="awardOverlay" class="award-modal-overlay">
    <div class="award-modal-box">
        <span class="close-btn-modal" onclick="closeAwardModal()">&times;</span>
        <div class="modal-header-union"><h2 id="mTitle"></h2></div>
        
        <div class="modal-main-body">
            <div class="modal-img-pane">
                <img id="mImg" src="">
            </div>

            <div class="modal-text-pane">
                <div class="info-group">
                    <span class="info-label">Honoree</span>
                    <span id="mRecipient" class="info-value"></span>
                </div>
                <div class="info-group">
                    <span class="info-label">Year Conferred</span>
                    <span id="mYear" class="info-value"></span>
                </div>
                <div class="info-group">
                    <span class="info-label">Achievement Details</span>
                    <div id="mDesc" class="info-desc"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

<script>
$(document).ready(function(){
    $('.awards-slick-carousel').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3500,
        pauseOnHover: true,
        prevArrow: $('.award-prev'),
        nextArrow: $('.award-next'),
        responsive: [
            { breakpoint: 992, settings: { slidesToShow: 2 } },
            { breakpoint: 768, settings: { slidesToShow: 1 } }
        ]
    });
});

function openAwardModal(title, recipient, desc, img, year) {
    document.getElementById('mTitle').innerText = title;
    document.getElementById('mRecipient').innerText = recipient;
    document.getElementById('mYear').innerText = year;
    document.getElementById('mDesc').innerText = desc;
    document.getElementById('mImg').src = img;
    
    document.getElementById('awardOverlay').style.display = 'flex';
    document.body.style.overflow = 'hidden'; // Disable scroll
}

function closeAwardModal() {
    document.getElementById('awardOverlay').style.display = 'none';
    document.body.style.overflow = 'auto'; // Enable scroll
}

// Exit modal on background click
window.onclick = function(e) {
    if (e.target == document.getElementById('awardOverlay')) closeAwardModal();
}
</script>