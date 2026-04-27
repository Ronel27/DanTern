<?php
// Include your database class
require_once 'class/database.php';

// Initialize the database class
$database = new Database();
$db = $database->getConnection(); // Adjust this method name to match your class

try {
    // Fetch data from the database
    $stmt = $db->prepare("SELECT * FROM about_content WHERE section_name = 'about_union' LIMIT 1");
    $stmt->execute();
    $content = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fallback if database is empty
    if (!$content) {
        $content = [
            'heading' => 'Upholding Faculty Rights and Academic Freedom',
            'p1' => 'The WMSU Faculty Union is a united and independent organization...',
            'p2' => 'Our union serves as a strong collective voice...',
            'p3' => 'We are committed to defending academic freedom...',
            'image_path' => 'facultyu.webp' 
        ];
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<section id="about" class="about section">
  <div class="container">
    <div class="row position-relative gy-4">
    <div class="col-lg-6 about-img" data-aos="zoom-out" data-aos-delay="200">
    <img src="<?= htmlspecialchars($content['image_path']) ?>" 
         class="img-fluid" 
         style="border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 100%;" 
         alt="WMSU Faculty Union Image">
</div>
      
      <div class="col-lg-6 d-flex align-items-center" data-aos="fade-up" data-aos-delay="100">
        <div class="content">
          <div class="section-title" style="text-align: left; padding-bottom: 0;">
            <h2 style="border-bottom: none; display: block; color: #8c1d1d;">About the Faculty Union</h2>
          </div>

          <h3 style="color: #333; font-weight: 700; margin-top: 10px;"><?= htmlspecialchars($content['heading']) ?></h3>
          <p style="text-align: justify;"><?= nl2br(htmlspecialchars($content['p1'])) ?></p>
          <br>
          <div>
            <a class="btn btn-primary" href="./bout.php" style="background-color: #8c1d1d; border-color: #8c1d1d;" role="button">See More</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>