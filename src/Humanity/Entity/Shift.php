<?php

namespace Humanity\Entity;

/**
 * @author Dusan Vejin <dutekvejin@gmail.com>
 */
class Shift extends AbstractEntity {

	const SCOPE_VIEW = 'shift.view';
	const SCOPE_MANAGE = 'shift.manage';

	const SCOPES = [
		self::SCOPE_VIEW,
		self::SCOPE_MANAGE,
	];

	public $shift_id;
	public $position_id;
	public $name;
	public $description;
	public $color;
	public $start;
	public $end;
	public $breaks;
	public $working;
	public $employees;
	public $published;
	public $published_by;
	public $published_at;
	public $created_by;
	public $created_at;
	public $deleted_by;
	public $deleted_at;
}