<?php

namespace Humanity\Entity;

/**
 * @author Dusan Vejin <dutekvejin@gmail.com>
 */
class Employee extends AbstractEntity {

	const SCOPE_VIEW = 'employee.view';
	const SCOPE_MANAGE = 'employee.manage';
	const SCOPE_EDIT = 'employee.edit';

	const SCOPES = [
		self::SCOPE_VIEW,
		self::SCOPE_MANAGE,
		self::SCOPE_EDIT,
	];

	public $employee_id;
	public $account_id;
	public $company_id;
	public $role_id;
	public $email;
	public $first_name;
	public $last_name;
	public $display_name;
	public $avatar;
	public $timezone;
	public $birth_date;
	public $positions;
	public $contacts;
	public $created_at;
	public $deleted_at;

}