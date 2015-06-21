<?php

namespace Humanity\Entity;

/**
 * @author Dusan Vejin <dutekvejin@gmail.com>
 */
class Account extends AbstractEntity {

	const SCOPE_VIEW = 'account.view';

	const SCOPES = [
		self::SCOPE_VIEW,
	];

	public $account_id;
	public $email;
	public $first_name;
	public $last_name;
	public $username;
	public $timezone;
	public $contacts;
	public $is_invited;
	public $status;

}