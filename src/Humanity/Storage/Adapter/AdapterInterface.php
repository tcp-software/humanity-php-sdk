<?php

namespace Humanity\Storage\Adapter;

/**
 * @author Dusan Vejin <dutekvejin@gmail.com>
 */
interface AdapterInterface {

	/**
	 * Get value from storage.
	 *
	 * @param string $index
	 * @param mixed  $default
	 *
	 * @return mixed
	 */
	public function get($index, $default = null);

	/**
	 * Set value to storage.
	 *
	 * @param string $index
	 * @param mixed  $value
	 */
	public function set($index, $value);

	/**
	 * Check if index exists
	 *
	 * @param $index
	 *
	 * @return bool
	 */
	public function has($index);
	
	/**
	 * Remove index
	 *
	 * @param $index
	 *
	 * @return bool
	 */
	public function remove($index);
}
