<?php

namespace app\controllers;

use app\components\OdooApi;
use app\models\CompanyForm;
use app\models\ContactForm;
use GuzzleHttp\Exception\GuzzleException;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

class SiteController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function actions()
	{
		return [
			'error' => ['class' => 'yii\web\ErrorAction'],
			'captcha' => [
				'class' => 'yii\captcha\CaptchaAction',
				'backColor' => 0xFFFFFF,
				'maxLength' => 5,
				'minLength' => 4,
				'transparent' => true,
			],
		];
	}

	/**
	 * Displays homepage.
	 *
	 * @return string
	 * @throws GuzzleException
	 */
	public function actionCompany()
	{
		$model = new CompanyForm();

		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			try {
				$newDatabaseName = strtolower(str_replace(' ', '_', $model->name));
				
				//Ajlouni this is the employees data  (noob)
				$employees = json_decode($model->employees, true);

				$model->logo = UploadedFile::getInstance($model, 'logo');
				if (!$model->logo) {
					throw new \Exception("Logo file is required.");
				}

				$base64Logo = base64_encode(file_get_contents($model->logo->tempName));

				$templateMap = [
					'restaurant'  => 'test',
					'supermarket' => 'test',
					'pharmacy'    => 'test',
					'shop'        => 'test',
					'hr'          => 'test',
				];

				if (!isset($templateMap[$model->package_type])) {
					throw new \Exception("Invalid package type.");
				}

				// Create Odoo API instance
				$odoo = new OdooApi();

				// Clone database
				$newDbUrl = $odoo->cloneDatabase($templateMap[$model->package_type], $newDatabaseName);

				if (!$newDbUrl) {
					throw new \Exception("Failed to clone database.");
				}

				// Get country ID
				$countryId = $odoo->getCountryId($newDatabaseName, $model->country);
				if (!$countryId) {
					throw new \Exception("Failed to retrieve country ID.");
				}

				// Prepare company data
				$companyData = [
					'name'              => $model->name,
					'street'            => $model->street,
					'city'              => $model->city,
					'country_id'        => $countryId,
					'email'             => $model->email,
					'phone'             => $model->phone,
					'company_registry'  => $model->registration_id,
					'logo'              => $base64Logo,
				];

				// Update company in Odoo
				$updateSuccess = $odoo->updateCompany($newDatabaseName, 1, $companyData);
				if (!$updateSuccess) {
					throw new \Exception("Failed to update company details.");
				}

				// Create user in Odoo
				$userCreationSuccess = $odoo->createUser($newDatabaseName, $model->email, 'admin123');
				if (!$userCreationSuccess) {
					throw new \Exception("Failed to create admin user.");
				}

				Yii::$app->session->setFlash('success', "Company system created successfully!");

			} catch (\Exception $e) {
				Yii::$app->session->setFlash('error', $e->getMessage());
			}

			return $this->refresh();
		}

		return $this->render('company_form', ['model' => $model]);
	}


	/**
	 * Displays contact page.
	 *
	 * @return Response|string
	 */
	public function actionContact()
	{
		$model = new ContactForm();
		if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
			Yii::$app->session->setFlash('contactFormSubmitted', 'Thank you for contacting us. We will respond as soon as possible.');
			return $this->refresh();
		}
		return $this->render('contact', ['model' => $model]);
	}

	/**
	 * Displays about page.
	 *
	 * @return string
	 */
	public function actionAbout()
	{
		return $this->render('about');
	}

	public function actionPrivacy()
	{
		return $this->render('privacy');
	}

	public function actionTerms()
	{
		return $this->render('terms');
	}
}
