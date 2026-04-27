<style>
    /* Ultra-compressed footer settings */
    .footer {
        padding: 25px 0 15px 0 !important; /* Minimal vertical padding */
        background: #1a1a1a;
        color: #ddd;
        font-size: 0.85rem; /* Compact font size */
    }

    .footer-col h4 {
        font-size: 0.95rem !important;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 10px !important;
        color: #fff;
    }

    .footer-links ul {
        padding: 0;
        list-style: none;
        margin: 0;
    }

    .footer-links ul li {
        padding: 2px 0 !important; /* Tightest possible link spacing */
    }

    .footer-links ul li a {
        color: #bbb;
        text-decoration: none;
        transition: 0.3s;
    }

    .footer-links ul li a:hover {
        color: #d4af37;
    }

    /* Horizontal contact line to save height */
    .contact-compact {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: center;
        margin-top: 10px;
    }

    .contact-compact span {
        display: flex;
        align-items: center;
    }

    .contact-compact i {
        color: #d4af37;
        margin-right: 5px;
    }

    /* Social icons sizing */
    .social-links a {
        font-size: 1.1rem;
        margin: 0 8px;
        color: #bbb;
        transition: 0.3s;
    }

    .social-links a:hover {
        color: #fff;
    }

    .footer .copyright {
        margin-top: 15px !important;
        padding-top: 10px !important;
        border-top: 1px solid rgba(255,255,255,0.1);
        font-size: 0.75rem;
        color: #888;
    }

    @media (max-width: 768px) {
        .contact-compact { flex-direction: column; gap: 5px; align-items: center; }
    }
</style>

<footer id="footer" class="footer">
    <div class="container">
        <div class="row align-items-start">
            
            <div class="col-lg-3 text-center text-lg-start mb-3 mb-lg-0">
                <a href="index.php" class="d-inline-block mb-1">
                    <img src="img/faculty-union-logo-white.png" alt="Logo" style="max-height: 40px;">
                </a>
                <p class="small mb-2" style="line-height: 1.4;">Upholding Faculty Rights and Academic Freedom.</p>
                <div class="social-links d-flex justify-content-center justify-content-lg-start">
                    <a href="https://www.facebook.com/WMSUFacultyUnion" target="_blank"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-twitter-x"></i></a>
                    <a href="#"><i class="bi bi-youtube"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-6 footer-links mb-3 mb-lg-0">
                <h4>Nav</h4>
                <ul>
                    <li><a href="#about">About Us</a></li>
                    <li><a href="bout.php">Officers</a></li>
                </ul>
            </div>

            <div class="col-lg-2 col-6 footer-links mb-3 mb-lg-0">
                <h4>Services</h4>
                <ul>
                    <li><a href="#events">Activities</a></li>
                    <li><a href="#awards">Awards</a></li>
                </ul>
            </div>

            <div class="col-lg-5 text-center text-lg-end">
                <h4>Contact</h4>
                <div class="small text-lg-end" style="color: #bbb;">
                    <p class="mb-1">2nd Floor, Executive Bldg, WMSU Main Campus</p>
                    <div class="contact-compact justify-content-lg-end">
                        <span><i class="bi bi-telephone"></i> +63 62 991 1771</span>
                        <span><i class="bi bi-envelope"></i> facultyunion@wmsu.edu.ph</span>
                    </div>
                </div>
            </div>

        </div>

        <div class="copyright text-center">
            &copy; <span id="footer-year"></span> <strong>WMSU Faculty Union</strong>. All Rights Reserved.
        </div>
    </div>
</footer>

<script>
    document.getElementById('footer-year').textContent = new Date().getFullYear();
</script>