<?php

namespace Humanity\Entity;

/**
 * @author Dusan Vejin <dutekvejin@gmail.com>
 */
class Position extends AbstractEntity {

	const SCOPE_VIEW = 'position.view';
	const SCOPE_MANAGE = 'position.manage';
	const SCOPE_DELETE = 'position.delete';

	const SCOPES = [
		self::SCOPE_VIEW,
		self::SCOPE_MANAGE,
		self::SCOPE_DELETE,
	];

	public $position_id;
	public $location_id;
	public $name;
	public $description;
	public $color;
	public $created_at;
	public $deleted_at;

}