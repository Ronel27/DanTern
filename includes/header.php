<?php
// 1. Fetch Site Settings from Database
require_once('class/database.php'); // Ensure this path is correct relative to where this file is included
$database = new Database();
$db = $database->getConnection();

// Fetch the title and logo path
$settings_query = $db->query("SELECT site_name, logo_path FROM site_settings WHERE id = 1");
$settings = $settings_query->fetch(PDO::FETCH_ASSOC);

// Fallback values if database is empty
$display_name = !empty($settings['site_name']) ? htmlspecialchars($settings['site_name']) : "Faculty Union";
$display_logo = !empty($settings['logo_path']) ? $settings['logo_path'] : "";
?>

<header id="header" class="header d-flex align-items-center fixed-top" style="background-color: #ffffff; border-bottom: 1px solid #eee;">
  
  <style>
    /* Theme Colors: Maroon and Gold */
    #header .sitename {
      color: #8c1d1d !important; /* Maroon Title */
      font-family: 'Playfair Display', serif;
      font-weight: 700;
      margin-left: 10px;
    }

    #header .nav-link {
      color: #8c1d1d !important; /* Maroon Links */
      font-weight: 600;
      transition: 0.3s;
    }

    #header .nav-link:hover, 
    #header .nav-link.active {
      color: #d4af37 !important; /* Gold on Hover */
    }

    /* Navbar Toggler for Mobile */
    #header .navbar-toggler i {
      color: #8c1d1d !important;
    }

    /* Login Button Styling */
    #header .btn-login {
      border: 1px solid #8c1d1d;
      margin-left: 15px;
      background-color: #8c1d1d;
      color: #ffffff !important;
      padding: 8px 25px;
      border-radius: 50px;
      font-size: 0.9rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
    }

    #header .btn-login:hover {
      background-color: #d4af37;
      border-color: #d4af37;
      color: #000000 !important;
    }

    /* Logout Button Styling (Visible when session is active) */
    #header .btn-logout {
      background-color: #6c757d;
      color: #fff !important;
      border-radius: 50px;
      padding: 8px 20px;
      margin-left: 10px;
    }

    #header .logo img {
      max-height: 50px;
      width: auto;
    }
  </style>

  <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

    <a href="index.php" class="logo d-flex align-items-center me-auto">
      <img src="./<?php echo $display_logo; ?>" alt="Logo">
      <h1 class="sitename"><?php echo $display_name; ?></h1>
    </a>

    <nav class="navbar navbar-expand-lg">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <i class="bi bi-list"></i>
      </button>
      
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
          <li class="nav-item"><a class="nav-link" href="#events">Events</a></li>
          <li class="nav-item"><a class="nav-link" href="#awards">Awards</a></li>
          <li class="nav-item"><a class="nav-link" href="#videos">Videos</a></li>
          <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
          <li class="nav-item"><a class="nav-link" href="gallery.php">Gallery</a></li>
          
          <li class="nav-item">
            <?php if(isset($_SESSION['user_id'])): ?>
                <div class="d-flex align-items-center">
                    <a class="nav-link" href="admins/dashboard.php">Admin Panel</a>
                    <a class="btn btn-logout nav-link" href="auth/logout.php">Logout</a>
                </div>
            <?php else: ?>
                <!-- <a class="btn btn-login nav-link" href="auth/login.php">Login</a> -->
            <?php endif; ?>
          </li>
        </ul>
      </div>
    </nav>

  </div>
</header>