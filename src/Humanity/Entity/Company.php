<?php

namespace Humanity\Entity;

/**
 * @author Dusan Vejin <dutekvejin@gmail.com>
 */
class Company extends AbstractEntity {

	const SCOPE_VIEW = 'company.view';
	const SCOPE_MANAGE = 'company.manage';

	const SCOPES = [
		self::SCOPE_VIEW,
		self::SCOPE_MANAGE,
	];

	public $company_id;
	public $master;
	public $name;
	public $description;
	public $domain;
	public $member_count;
	public $address;
	public $timezone;
	public $latitude;
	public $longitude;
	public $created_at;
	public $deleted_at;

}
