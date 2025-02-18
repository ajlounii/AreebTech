<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var app\models\ContactForm $model */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\captcha\Captcha;

$this->title = 'Contact Us';
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="main-container">
    <div class="card">
        <div class="card-header text-center">
<!--            <h2>--><?php //= Html::encode($this->title) ?><!--</h2>-->
            <p>We'd love to hear from you! Fill out the form below to get in touch.</p>
        </div>
        <div class="card-body">
			<?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>
                <div class="alert alert-success text-center">
					<?= Yii::$app->session->getFlash('contactFormSubmitted') ?>
                </div>
			<?php endif; ?>

			<?php $form = ActiveForm::begin([
				'id' => 'contact-form',
				'options' => ['class' => 'custom-form'],
			]); ?>

			<?= $form->field($model, 'name')->textInput(['placeholder' => 'Your Name']) ?>
			<?= $form->field($model, 'email')->textInput(['placeholder' => 'Your Email']) ?>
			<?= $form->field($model, 'subject')->textInput(['placeholder' => 'Subject']) ?>

            <!-- CAPTCHA Widget -->
			<?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
				'template' => '<div class="captcha-box">{image}{input}</div>',
				'captchaAction' => 'site/captcha',
				'imageOptions' => [
					'class' => 'captcha-image',
					'data-src' => Yii::$app->urlManager->createUrl(['site/captcha', 'v' => time()]),
				],
			]) ?>

			<?= $form->field($model, 'body')->textarea(['rows' => 6, 'placeholder' => 'Write your message here...']) ?>

            <div class="text-center">
				<?= Html::submitButton('Send Message', ['class' => 'btn btn-primary']) ?>
            </div>

			<?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
