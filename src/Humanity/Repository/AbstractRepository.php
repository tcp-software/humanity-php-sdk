<?php

namespace Humanity\Repository;

use Humanity\Humanity;
use Humanity\Response;

/**
 * @author Dusan Vejin <dutekvejin@gmail.com>
 */
class AbstractRepository implements RepositoryInterface {

	/**
	 * @var Humanity
	 */
	protected $humanity;

	/**
	 * @var int|null
	 */
	protected $code;

	/**
	 * @var string|null
	 */
	protected $message;

	/**
	 * @param Humanity $humanity
	 */
	public function __construct(Humanity $humanity) {
		$this->setHumanity($humanity);
	}

	/**
	 * @return Humanity
	 */
	public function getHumanity() {
		return $this->humanity;
	}

	/**
	 * @param Humanity $humanity
	 */
	public function setHumanity(Humanity $humanity) {
		$this->humanity = $humanity;
	}

	/**
	 * @return int|null
	 */
	public function getCode() {
		return $this->code;
	}

	/**
	 * @param int|null $code
	 */
	public function setCode($code) {
		$this->code = $code;
	}

	/**
	 * @return string|null
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * @param string|null $message
	 */
	public function setMessage($message) {
		$this->message = $message;
	}

	/**
	 * @param Response $response
	 */
	public function setError(Response $response) {
		$error = $response->getError();

		$this->setCode($error['code']);
		$this->setMessage($error['message']);
	}

}