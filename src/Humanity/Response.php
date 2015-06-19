<?php

namespace Humanity;

/**
 *
 */
class Response implements \ArrayAccess, \Iterator, \Countable {

	/**
	 * @var int
	 */
	protected $status;
	
	/**
	 * @var array|null
	 */
	protected $error;

	/**
	 * @var array|null
	 */
	protected $data;

	/**
	 * @var array|null
	 */
	protected $meta;

	/**
	 * @return int
	 */
	function getStatus() {
		return $this->status;
	}

	/**
	 * @param int $status
	 */
	function setStatus($status) {
		$this->status = $status;
	}

	/**
	 * @return array|null
	 */
	function getError() {
		return $this->error;
	}

	/**
	 * @param array|null $error
	 */
	function setError($error) {
		$this->error = $error;
	}

	/**
	 * @return bool
	 */
	function hasError() {
		return null !== $this->error;
	}

	/**
	 * @return array|null
	 */
	function getData() {
		return $this->data;
	}

	/**
	 * @param array|null $data
	 */
	function setData($data) {
		$this->data = $data;
	}

	public function offsetExists($offset) {
		return isset($this->data[$offset]);
	}

	public function offsetGet($offset) {
		return $this->data[$offset];
	}

	public function offsetSet($offset, $value) {
		$this->data[$offset] = $value;
	}

	public function offsetUnset($offset) {
		unset($this->data[$offset]);
	}

	public function current() {
		return current($this->data);
	}

	public function key() {
		return key($this->data);
	}

	public function next() {
		return next($this->data);
	}

	public function rewind() {
		return reset($this->data);
	}

	public function valid() {
		return null !== key($this->data);
	}

	public function count() {
		return count($this->data);
	}

	public function __get($name) {
		return $this[$name];
	}

	public function __set($name, $value) {
		$this->data[$name] = $value;
	}

}
