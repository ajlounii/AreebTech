<?php
namespace app\models;

use Yii;
use yii\base\Model;

class CompanyForm extends Model
{
	public $name;
	public $email;
	public $phone;
	public $package_type;
	public $street;
	public $country;
	public $city;
	public $address;
	public $features = [];
	public $employees = [];
	public $registration_id;
	public $social_security_id;
	public $logo;
	// Validation rules
	public function rules()
	{
		return [
			[['name', 'email', 'phone', 'package_type', 'street', 'country', 'city', 'registration_id', 'social_security_id'], 'required'],
			['email', 'email'],
			[['name', 'street', 'country', 'city'], 'string', 'max' => 255],
			[['registration_id', 'social_security_id'], 'string', 'max' => 50],
			[['phone'], 'match', 'pattern' => '/^\+?[0-9]{10,15}$/', 'message' => 'Please enter a valid phone number.'],
			[['logo'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 2],
			['employees', 'safe'],
		];
	}

	public function packageOptions()
	{
		return [
			'restaurant' => [
				'title' => 'Restaurant',
				'description' => 'Manage your restaurant efficiently with real-time stock tracking, automated replenishment, and seamless order processing.'
			],
			'supermarket' => [
				'title' => 'Supermarket',
				'description' => 'Optimize your supermarket operations with advanced inventory tracking, automated stock refills, and real-time sales insights.'
			],
			'hr' => [
				'title' => 'Human Resource',
				'description' => 'Simplify HR processes with an all-in-one solution for payroll, attendance tracking, employee management, and performance insights.'
			],
			'pharmacy' => [
				'title' => 'Pharmacy',
				'description' => 'Ensure smooth pharmacy management with automated inventory updates, prescription tracking, and compliance-ready solutions.'
			],
			'shop' => [
				'title' => 'Shop',
				'description' => 'Streamline your retail store with smart inventory management, fast checkout, and personalized customer engagement tools.'
			],
		];
	}
}
