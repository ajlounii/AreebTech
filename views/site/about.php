<?php
/** @var yii\web\View $this */
use yii\helpers\Html;

$this->title = 'About Us';
?>
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <h1 class="hero-title"><?= Html::encode($this->title) ?></h1>
        <p class="hero-subtitle">Empowering Your Business With Next‑Gen Solutions</p>
    </div>
</section>

<!-- About Content Section -->
<section class="about-content">
    <div class="container">
        <div class="row align-items-center">
            <!-- Image Column -->
            <div class="col-md-6 about-image">
                <img src="/images/about_image.jpg" alt="About Us" class="img-fluid rounded shadow">
            </div>
            <!-- Text Column -->
            <div class="col-md-6">
                <div class="content-box">
                    <h2 class="content-title">Our Vision & Mission</h2>
                    <p class="content-description">
                        At <strong>[Your Company Name]</strong>, we deliver a seamless, integrated business management solution powered by <strong>Odoo 18 Community</strong>. Our platform is engineered to streamline operations, enhance productivity, and empower organizations across various industries.
                    </p>
                    <ul class="content-list">
                        <li>
                            <i class="fa fa-check-circle"></i>
                            <strong>HR System:</strong> Streamline employee management, payroll, and recruitment.
                        </li>
                        <li>
                            <i class="fa fa-check-circle"></i>
                            <strong>Store Management:</strong> Optimize inventory control and sales operations.
                        </li>
                        <li>
                            <i class="fa fa-check-circle"></i>
                            <strong>Supermarket System:</strong> Enhance POS efficiency and customer service.
                        </li>
                        <li>
                            <i class="fa fa-check-circle"></i>
                            <strong>Restaurant Management:</strong> Seamlessly handle orders and table reservations.
                        </li>
                        <li>
                            <i class="fa fa-check-circle"></i>
                            <strong>Pharmacy System:</strong> Efficiently manage prescriptions, stock, and compliance.
                        </li>
                    </ul>
                    <p class="content-conclusion">
                        Our innovative approach guarantees high performance, robust security, and an intuitive user experience—making us the ideal partner for your digital transformation journey.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
