<?php

namespace Humanity\Repository;

use Humanity\Humanity;

/**
 * @author Dusan Vejin <dutekvejin@gmail.com>
 */
interface RepositoryInterface {

	/**
	 * @return Humanity
	 */
	public function getHumanity();

	/**
	 * @param Humanity $humanity
	 */
	public function setHumanity(Humanity $humanity);

}