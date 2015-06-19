<?php

namespace Humanity\Entity;

/**
 * @author Dusan Vejin <dutekvejin@gmail.com>
 */
interface EntityInterface {

	/**
	 * @param array $data
	 */
	public function hydrate(array $data);

	/**
	 * @return array
	 */
	public function extract();

}