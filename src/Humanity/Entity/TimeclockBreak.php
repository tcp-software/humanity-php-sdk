<?php

namespace Humanity\Entity;

/**
 * @author Dusan Vejin <dutekvejin@gmail.com>
 */
class TimeclockBreak extends AbstractEntity {

	public $break_id;
	public $start;
	public $end;
	public $break_in_type;
	public $break_out_type;
	public $break_in_image_url;
	public $break_out_image_url;
	public $duration;
	public $created_by;
	public $created_at;
	public $updated_by;
	public $updated_at;
	public $deleted_by;
	public $deleted_at;

}