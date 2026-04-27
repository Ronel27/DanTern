<?php
// Fetch Contact Data with a fallback to avoid disappearing sections
try {
    $contact_query = $db->query("SELECT * FROM contact_info WHERE id = 1");
    $contact = $contact_query->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $contact = false;
}

// Define defaults if database returns nothing
$address = $contact['address'] ?? 'Address not available';
$phone = $contact['phone'] ?? 'N/A';
$hours = $contact['hours'] ?? '';
$email = $contact['email'] ?? 'info@wmsu.edu.ph';
$fb_url = $contact['facebook_url'] ?? '#';
$fb_name = $contact['facebook_name'] ?? 'Faculty Union Facebook';
?>

<section id="contact" class="contact section light-background">
    <div class="container section-title text-center">
        <h2>Contact Us</h2>
        <p>Get in touch with the Faculty Union for inquiries and support.</p>
    </div>

    <div class="container">
        <div class="row gy-4 justify-content-center">
            <div class="col-lg-10">
                <div class="row gy-4">
                    
                    <div class="col-md-6 d-flex">
                        <div class="info-item w-100">
                            <i class="bi bi-geo-alt flex-shrink-0"></i>
                            <div>
                                <h3>Address</h3>
                                <p><?php echo nl2br(htmlspecialchars($address)); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 d-flex">
                        <div class="info-item w-100">
                            <i class="bi bi-telephone flex-shrink-0"></i>
                            <div>
                                <h3>Call Us</h3>
                                <p><?php echo htmlspecialchars($phone); ?></p>
                                <p><?php echo htmlspecialchars($hours); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 d-flex">
                        <div class="info-item w-100">
                            <i class="bi bi-envelope flex-shrink-0"></i>
                            <div>
                                <h3>Email Us</h3>
                                <p><a href="mailto:<?php echo $email; ?>"><?php echo htmlspecialchars($email); ?></a></p>
                                <p>For general inquiries</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 d-flex">
                        <div class="info-item w-100">
                            <i class="bi bi-facebook flex-shrink-0"></i>
                            <div>
                                <h3>Follow Us</h3>
                                <p><a href="<?php echo $fb_url; ?>" target="_blank"><?php echo htmlspecialchars($fb_name); ?></a></p>
                                <p>For updates and events</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>