<?php

namespace Humanity\Entity;

/**
 * @author Dusan Vejin <dutekvejin@gmail.com>
 */
class Location extends AbstractEntity {

	const SCOPE_VIEW = 'location.view';
	const SCOPE_MANAGE = 'location.manage';
	const SCOPE_DELETE = 'location.delete';

	const SCOPES = [
		self::SCOPE_VIEW,
		self::SCOPE_MANAGE,
		self::SCOPE_DELETE,
	];

	public $location_id;
	public $name;
	public $description;
	public $address;
	public $timezone;
	public $latitude;
	public $longitude;
	public $is_default;
	public $created_at;
	public $deleted_at;

}