<?php
namespace app\assets;

use yii\web\AssetBundle;

class CompanyFormAsset extends AssetBundle
{
	public $basePath = '@webroot';
	public $baseUrl = '@web';

	public $css = [
		'css/main.css',
	];

	public $js = [
		'js/company-form.js',
	];

	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap4\BootstrapAsset',
		'yii\bootstrap4\BootstrapPluginAsset',
	];
}