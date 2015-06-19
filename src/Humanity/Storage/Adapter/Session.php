<?php

namespace Humanity\Storage\Adapter;

/**
 * @author Dusan Vejin <dutekvejin@gmail.com>
 */
class Session implements AdapterInterface {

	/**
	 * Start session
	 */
	public function __construct() {
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
	}

	/**
	 * Get session value.
	 *
	 * @param string $index
	 * @param mixed  $default
	 *
	 * @return mixed
	 */
	public function get($index, $default = null) {
		if (!$this->has($index)) {
			return $default;
		}

		return $_SESSION[$index];
	}

	/**
	 * Set session value.
	 *
	 * @param string $index
	 * @param mixed  $value
	 */
	public function set($index, $value) {
		$_SESSION[$index] = $value;
	}

	/**
	 * Check if index exists
	 *
	 * @param $index
	 *
	 * @return bool
	 */
	public function has($index) {
		return isset($_SESSION[$index]);
	}

}