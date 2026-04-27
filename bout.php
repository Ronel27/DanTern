<?php
/**
 * 1. Path to your class file
 */
require_once('class/database.php'); 

/**
 * 2. Create an instance and get the PDO connection
 */
$database = new Database();
$db = $database->getConnection(); 

if (!$db) {
    die("Database connection failed.");
}

try {
    /**
     * 3. Fetch Vision
     * From Table: 'union_info' | Column: 'vision'
     */
    $vision_stmt = $db->prepare("SELECT vision FROM union_info LIMIT 1");
    $vision_stmt->execute();
    $vision_data = $vision_stmt->fetch(PDO::FETCH_ASSOC);

    /**
     * 4. Fetch Objectives
     * From Table: 'objectives' | Column: 'content'
     */
    $objectives_stmt = $db->prepare("SELECT DISTINCT content FROM objectives ORDER BY sort_order ASC");
    $objectives_stmt->execute();
    $objectives_result = $objectives_stmt->fetchAll(PDO::FETCH_ASSOC);

    /**
     * 5. Fetch Executive Officers
     */
    $exec_stmt = $db->prepare("SELECT * FROM officers WHERE category = 'Executive' ORDER BY rank ASC");
    $exec_stmt->execute();
    $exec_rows = $exec_stmt->fetchAll(PDO::FETCH_ASSOC);

    /**
     * 6. Fetch Finance Officers
     */
    $fin_stmt = $db->prepare("SELECT * FROM officers WHERE category = 'Finance' ORDER BY rank ASC");
    $fin_stmt->execute();
    $fin_rows = $fin_stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>About - WMSU Faculty Union</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body { 
        font-family: 'Montserrat', sans-serif; 
        background-color: #f8f9fa; 
        line-height: 1.8; 
    }
    
    .blog-header { border-bottom: 4px solid #8c1d1d; }

    .header-img { 
        position: relative; 
        overflow: hidden; 
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2); 
        border-radius: 8px; 
    }

    /* FIX: Adjusted padding and gradient to lift the shadow higher */
    .header-overlay { 
        position: absolute; 
        bottom: 0; 
        left: 0; 
        right: 0; 
        padding: 120px 30px 60px 30px; /* Increased top padding to push text/shadow up */
        background: linear-gradient(to top, rgba(140, 29, 29, 0.98) 0%, rgba(140, 29, 29, 0.6) 60%, transparent 100%); 
        color: white; 
        text-align: center; 
    }

    .artistic-title { 
        font-family: 'Playfair Display', serif; 
        font-weight: 900; 
        text-transform: uppercase; 
        letter-spacing: 2px; 
    }

    .section-title { 
        color: #8c1d1d; 
        border-left: 6px solid #d4af37; 
        padding-left: 15px; 
        margin-bottom: 25px; 
        font-family: 'Playfair Display', serif; 
        font-weight: 700; 
    }

    .content-card { 
        background: white; 
        padding: 40px; 
        margin-bottom: 40px; 
        border-top: 5px solid #8c1d1d; 
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05); 
    }

    .vision-container { 
        background-color: #fdfaf3; 
        border: 1px solid #e9d7a5; 
        padding: 30px; 
        text-align: center; 
        border-radius: 8px; 
    }

    .person-card { 
        background-color: #fcfcfc; 
        border-left: 3px solid #8c1d1d; 
        padding: 15px; 
        margin-bottom: 15px; 
        transition: 0.3s; 
    }

    .person-card:hover { 
        transform: translateX(8px); 
        background: #fff; 
        box-shadow: 0 4px 10px rgba(0,0,0,0.1); 
    }

    .person-name { font-weight: bold; color: #8c1d1d; display: block; }

    .team-header { 
        padding: 12px; 
        background-color: #8c1d1d; 
        color: #fff; 
        margin-bottom: 20px; 
        text-align: center; 
        font-weight: bold; 
        text-transform: uppercase; 
    }

    .finance-header { 
        padding: 12px; 
        background-color: #d4af37; 
        color: #000; 
        margin-bottom: 20px; 
        text-align: center; 
        font-weight: bold; 
        text-transform: uppercase; 
    }

    .back-to-top { 
        background-color: #8c1d1d; 
        color: white; 
        padding: 10px 25px; 
        text-decoration: none; 
        transition: 0.3s; 
        border-radius: 4px; 
    }

    .back-to-top:hover { 
        background-color: #d4af37; 
        color: #000; 
        text-decoration: none; 
    }
</style>
</head>

<body>
  <div class="container mt-3">
    <header class="blog-header py-3 mb-4">
      <div class="row flex-nowrap justify-content-between align-items-center">
        <div class="col-4">
          <a class="btn btn-outline-dark" href="index.php">
             &larr; Back to Home
          </a>
        </div>
        <!-- <div class="col-4 text-center">
          <strong class="h4">WMSU-FU</strong>
        </div> -->
        <div class="col-4 text-right"></div>
      </div>
    </header>

    <div class="p-3 p-md-5 text-white rounded header-img position-relative" style="background-image: url('https://scontent.fcgy2-2.fna.fbcdn.net/v/t39.30808-6/487762729_1102274405279123_4372650538705936606_n.jpg?_nc_cat=103&ccb=1-7&_nc_sid=f727a1&_nc_eui2=AeHh0BocOQ2wmfOiKp73w9MmXGhV-5QAwFFcaFX7lADAUcQ9KnbTrrOc9GulnTsZogBbUMUvTylrl480tQkV03D2&_nc_ohc=JHXWTz4j4hcQ7kNvwGJB1pM&_nc_oc=AdmrD3a-mDUV7V9-3aOkoYsHdToJOmI6ZfWjQ3RVxdScAzKMTfBdi58D5unkVB7X3wo&_nc_zt=23&_nc_ht=scontent.fcgy2-2.fna&_nc_gid=rtTsBWbLmxbfmekFAvLdUw&oh=00_AfFmNQ38fxXYAjPlUa5BBLzXa3Ajq8940b07Nt09ZZ4TbQ&oe=68140E2A'); 
        background-size: cover; 
        background-position: center; 
        background-repeat: no-repeat; 
        height: 450px;">
      <div class="header-overlay">
        <h1 class="artistic-title display-4">WMSU Faculty Union</h1>
        <p class="lead subtitle">United for Progress, Dedicated to Service</p>
      </div>
    </div>

    <main role="main" class="mt-5">
      <div class="row">
        <div class="col-md-11 mx-auto">
          
          <div class="content-card">
            <h2 class="section-title">Vision</h2>
            <div class="vision-container">
              <p class="vision-text lead">
                "<?php echo htmlspecialchars($vision_data['vision'] ?? 'Vision content is currently unavailable.'); ?>"
              </p>
            </div>
          </div>

          <div class="content-card">
            <h2 class="section-title">Objectives</h2>
            <div class="p-2">
              <ol class="lead" style="font-size: 1.05rem;">
                <?php foreach($objectives_result as $obj): ?>
                  <li><?php echo htmlspecialchars($obj['content']); ?></li>
                <?php endforeach; ?>
              </ol>
            </div>
          </div>

          <div class="content-card">
            <h2 class="section-title">Union Leadership</h2>
            <div class="team-header">Executive Officers</div>
            <div class="row mb-4">
              <?php 
              $split = ceil(count($exec_rows) / 2);
              $chunks = array_chunk($exec_rows, $split > 0 ? $split : 1);
              foreach ($chunks as $column): ?>
                <div class="col-md-6">
                  <?php foreach ($column as $officer): ?>
                    <div class="person-card">
                      <span class="person-name"><?php echo htmlspecialchars($officer['position']); ?></span> 
                      <?php echo htmlspecialchars($officer['full_name']); ?> 
                      (<?php echo htmlspecialchars($officer['department_acronym']); ?>)
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php endforeach; ?>
            </div>

            <div class="finance-header">Finance Officers</div>
            <div class="row">
              <?php foreach($fin_rows as $fin): ?>
                <div class="col-md-6">
                  <div class="person-card">
                    <span class="person-name"><?php echo htmlspecialchars($fin['position']); ?></span> 
                    <?php echo htmlspecialchars($fin['full_name']); ?> 
                    (<?php echo htmlspecialchars($fin['department_acronym']); ?>)
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

        </div>
      </div>
    </main>

    <footer class="blog-footer py-5 text-center">
      <p>&copy; <?php echo date("Y"); ?> Western Mindanao State University Faculty Union.</p>
      <p><a href="#" class="back-to-top">Back to top</a></p>
    </footer>
  </div>
</body>
</html>