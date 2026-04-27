<!DOCTYPE html>
<html lang="en">

<?php require_once('./includes/head.php');?>
<body class="index-page">

<main class="main">
    <!-- Header Section -->
    <?php include('./includes/header.php');?>

    <!-- Static Section -->
    <?php include('./includes/static.php');?>

    <!-- About Section -->
    <?php include('./includes/about.php');?>

    <!-- Events Section -->
    <?php include('./includes/event.php');?>

    <!-- News Section -->
    <?php include('./includes/awards.php');?>

    <!-- Programs Section -->
    <?php include('./includes/videos.php');?>

    <!-- Contact Section -->
    <?php include('./includes/contact.php');?>

</main>

    <!-- Footer Section -->
    <?php include('./includes/footer.php');?>

    <!-- Scroll Top Button -->
<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center active"><i class="bi bi-arrow-up-short"></i></a>

<!-- Preloader -->
<div id="preloader"></div>

<!-- Vendor JS Files -->
<?php include('./includes/scripts.php');?>

<!-- Your Custom JS Files -->
<script src="jscripts/main.js"></script> <!-- Path is relative, OK -->
<script src="jscripts/ind.js"></script>   <!-- Path is relative, OK -->
</body>
</html>
