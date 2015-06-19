Humanity SDK for PHP
====================


Installation
------------

Install the latest version with

```bash
$ composer require shiftplanning/humanity-php-sdk
```

Usage
=====

Initialize
----------

Make sure to loade composer autoload file.

```php
<?php
use \Humanity\Entity\Company;
use \Humanity\Entity\Employee;
use \Humanity\Humanity;

// Load Humanity SDK for PHP via composer

$config = [
	'provider' => [
		'clientId' => '...',
		'clientSecret' => '...',
		'redirectUri' => '...',
		'scopes' => [
			Company::SCOPE_VIEW,
			Employee::SCOPE_VIEW
		],
	],
];

// Create new instance of humanity class
$humanity = new Humanity($config);
```

Obtain access token
-------------------

Obtained access token will be saved for you in _SESSION variable. 

```php
// Obtain access token
$humanity->obtainAccessToken();
// Get access token instance
$accessToken = $humanity->getAccessToken();
printf('Access token: %s', $accessToken->accessToken);
```

Retrive logged employee data
----------------------------

Invoking Humanity::me() will return Employee entity instance

```php
$me = $humanity->me();
printf('Hello, %s', $me->display_name);
```

Working with entities
---------------------

Retrieve data

```php
// Get Company repository instance
$companyRepository = $humanity->getCompanyRepository();
// Retrieve company data for current logged employee
$company = $companyRepository->get($me->company_id);
printf('Company: %s<br/>', $company->name);

// Get Employee repository instance
$employeeRepository = $humanity->getEmployeeRepository();
// Retrieve employees data for company.
$employees = $employeeRepository->getByCompany($company->company_id);

echo 'Employees: ';
echo '<ul>';
// Iterating employees collection
foreach ($employees as $employee) {
	printf('<li>%s</li>', $employee->display_name);
}
echo '</ul>';
```
