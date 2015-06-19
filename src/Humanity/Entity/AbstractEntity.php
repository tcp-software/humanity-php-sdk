<?php

namespace Humanity\Entity;

/**
 * @author Dusan Vejin <dutekvejin@gmail.com>
 */
abstract class AbstractEntity implements EntityInterface {

	/**
	 * @param array $data
	 */
	public function __construct(array $data = []) {
		$this->hydrate($data);
	}

	/**
	 * @param array $data
	 */
	public function hydrate(array $data) {
		foreach ($data as $key => $value) {
			if (property_exists($this, $key)) {
				$this->{$key} = $value;
			}
		}
	}

	/**
	 * @return array
	 */
	public function extract() {
		$data = [];

		foreach (get_object_vars($this) as $key => $value) {
			$data[$key] = $value;
		}

		return $data;
	}

}