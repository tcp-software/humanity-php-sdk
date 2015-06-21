<?php

namespace Humanity\Entity;

/**
 * @author Dusan Vejin <dutekvejin@gmail.com>
 */
class Timeclock extends AbstractEntity {

	const SCOPE_VIEW = 'timeclock.view';
	const SCOPE_MANAGE = 'timeclock.manage';
	const SCOPE_EDIT = 'timeclock.edit';

	const SCOPES = [
		self::SCOPE_VIEW,
		self::SCOPE_MANAGE,
		self::SCOPE_EDIT,
	];

	public $timeclock_id;
	public $employee_id;
	public $position_id;
	public $shift_id;
	public $start;
	public $end;
	public $duration;
	public $breaks;
	public $note;
	public $status;
	public $created_by;
	public $created_at;
	public $deleted_by;
	public $deleted_at;

}