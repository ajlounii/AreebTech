<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var app\models\CompanyForm $model */

$this->title = 'Create a Company System';
?>

<div class="main-container">
    <div class="card">
        <div class="card-body">
            <div class="step-progress">
                <div class="step-item active" data-step="1">
                    <div class="step-circle">1</div>
                    <div class="step-title">Select Profession</div>
                </div>
                <div class="step-item" data-step="2">
                    <div class="step-circle">2</div>
                    <div class="step-title">Company Info</div>
                </div>
            </div>

			<?php $form = ActiveForm::begin([
				'id' => 'company-form',
				'options' => ['class' => 'form-horizontal'],
				'fieldConfig' => [
					'template' => "{label}\n{input}\n{error}",
					'inputOptions' => ['class' => 'form-control'],
					'errorOptions' => ['class' => 'text-danger small']
				]
			]); ?>

            <div class="form-step active" data-step="1">
                <div>
                    <h2>Select Profession</h2><br>
                </div>

<!--				--><?php //= $form->field($model, 'package_type')->hiddenInput()->label(false) ?>
<!--                <div class="package-features" data-package-input="--><?php //= Html::getInputName($model, 'package_type') ?><!--">-->
<!--					--><?php //foreach ($model->packageOptions() as $value => $label): ?>
<!--                        <div class="feature-card" data-package-value="--><?php //= $value ?><!--">-->
<!--                                <h5>--><?php //= $label['title'] ?><!--</h5>-->
<!--                                <p>--><?php //= $label['description'] ?><!--</p>-->
<!--                        </div>-->
<!--					--><?php //endforeach; ?>
<!--                </div>-->

				<?= $form->field($model, 'package_type')->hiddenInput()->label(false) ?>
                <div class="package-features" data-package-input="<?= Html::getInputName($model, 'package_type') ?>">
					<?php
					$options = $model->packageOptions();
					$totalOptions = count($options);
					$counter = 0;

					foreach ($options as $value => $label):
						$extraClass = ($label['title'] === 'Human Resource') ? 'large' : '';
						?>
                        <div class="feature-card <?= $extraClass ?>" data-package-value="<?= $value ?>">
                            <div class="content">
                                <h5><?= $label['title'] ?></h5>
                                <p><?= $label['description'] ?></p>
                            </div>
                        </div>
					<?php endforeach; ?>
                </div>
            </div>

            <div class="form-step" data-step="2">
                <div>
                    <h2>Company info</h2><br>
                </div>

                <div class="row">
                    <div class="col-md-4">
						<?= $form->field($model, 'registration_id')->textInput([
							'placeholder' => 'e.g. REG123456',
						]) ?>
                    </div>

                    <div class="col-md-4">
                        <div class="col-md-9">
							<?= $form->field($model, 'logo')
								->fileInput(['style' => 'display: block;'])
							?>
                        </div>
                    </div>

                    <div class="col-md-4">
						<?= $form->field($model, 'social_security_id')->textInput([
							'placeholder' => 'e.g. SS123456789',
						]) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
						<?= $form->field($model, 'name')->textInput([
							'placeholder' => 'e.g. Tech Innovators Inc.',
						]) ?>
                    </div>

                    <div class="col-md-4">
                        <div class="col-md-9">
							<?= $form->field($model, 'phone')->textInput([
								'placeholder' => 'e.g. +1 234 567 890',
							]) ?>
                        </div>
                    </div>

                    <div class="col-md-4">
						<?= $form->field($model, 'email')->textInput([
							'placeholder' => 'e.g. john@example.com',
						]) ?>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-8 map-padding">
                        <div class="col-md-10">
                            <label>Location Map</label>
                            <div id="map"></div>

                            <div class="map-controls">
                                <div class="input-group mb-3">
                                    <input type="text" id="address-search" class="form-control"
                                           placeholder="Search for an address...">
                                    <button type="button" id="current-location" class="btn btn-primary">
                                        Current Location
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
						<?= $form->field($model, 'country')->textInput(['id' => 'country']) ?>
						<?= $form->field($model, 'city')->textInput(['id' => 'city']) ?>
						<?= $form->field($model, 'street')->textInput(['id' => 'street']) ?>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <h4>Employees</h4>
                        <table class="table table-bordered" id="employees-table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Full Name</th>
                                <th>Mobile</th>
                                <th>Role</th>
                                <th>Login Name</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                            </thead>

                            <tbody>
                            <!-- Employees will be added here -->
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-success" id="add-employee">Add Employee</button>
                    </div>
                </div>

                <!-- Hidden input for employees data -->
				<?= Html::hiddenInput('CompanyForm[employees]', '', ['id' => 'employees-data']) ?>

            </div>

            <div class="form-navigation">
                <button type="button" class="btn btn-secondary prev-step">Previous</button>
                <button type="button" class="btn btn-primary next-step">Next</button>
				<?= Html::submitButton('Create System', [
					'class' => 'btn submit-btn',
					'style' => 'display: none;'
				]) ?>
            </div>

			<?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<!-- Loading Popup -->
<div id="loading-popup" class="loading-overlay" style="display: none;">
    <div class="loading-content">
        <div class="spinner"></div>
        <p>Processing... Please wait.</p>
    </div>
</div>
