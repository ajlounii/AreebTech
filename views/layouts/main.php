<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\assets\CompanyFormAsset;
use app\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\helpers\Url;

CompanyFormAsset::register($this);
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
</head>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

<body class="d-flex flex-column h-100">
<nav class="custom-navbar navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="<?= Url::home() ?>">
            <i class="fas fa-desktop mr-2"></i>AREEBTech
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#customNavbar"
                aria-controls="customNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="customNavbar">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link <?= Yii::$app->controller->action->id === 'index' ? 'active' : '' ?>" href="<?= Url::home() ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= Yii::$app->controller->action->id === 'about' ? 'active' : '' ?>" href="<?= Url::to(['site/about']) ?>">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= Yii::$app->controller->action->id === 'contact' ? 'active' : '' ?>" href="<?= Url::to(['site/contact']) ?>">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<?php $this->beginBody() ?>

<!-- Removed NavBar Section -->

<main role="main" class="flex-shrink-0">
    <div class="container-fluid px-0">
		<?= Breadcrumbs::widget([
			'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
		]) ?>
		<?= Alert::widget() ?>
		<?= $content ?>
    </div>
</main>

<footer class="custom-footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-brand">
                <i class="fas fa-desktop mr-2"></i>  AREEBTech
                <p class="tagline">Digital Solution</p>
            </div>
            <div class="footer-social">
                <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
        <div class="footer-bottom">
            <p class="copyright">&copy; <?= date('Y') ?> AREEBTech . All rights reserved.</p>
            <div class="footer-links">
                <a href="<?= Url::to(['site/privacy']) ?>">Privacy Policy</a>
                <a href="<?= Url::to(['site/terms']) ?>">Terms of Service</a>
                <a href="<?= Url::to(['site/contact']) ?>">Contact Us</a>
            </div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
