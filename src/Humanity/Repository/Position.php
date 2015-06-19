<?php

namespace Humanity\Repository;

use Humanity\Entity\Position as PositionEntity;

/**
 * @author Dusan Vejin <dutekvejin@gmail.com>
 */
class Position extends AbstractRepository {

	/**
	 * Get position by id.
	 * @scopes position.view
	 *
	 * @param string $id Position id
	 *
	 * @return PositionEntity|null
	 */
	public function get($id) {
		$response = $this->getHumanity()->get('positions/:id', (string) $id);

		if ($response->hasError()) {
			$this->setError($response);
			return null;
		}

		return new PositionEntity($response->getData());
	}

	/**
	 * Get all positions from company.
	 * @scopes position.view
	 *
	 * @param string $id Company id
	 *
	 * @return PositionEntity[]|array
	 */
	public function getByCompany($id) {
		$response = $this->getHumanity()->get('companies/:id/positions', (string) $id);

		if ($response->hasError()) {
			$this->setError($response);
			return [];
		}

		$collection = [];

		foreach ($response as $data) {
			$collection[] = new PositionEntity($data);
		}

		return $collection;
	}

	/**
	 * Get all positions from location.
	 * @scopes position.view
	 *
	 * @param string $id Location id
	 *
	 * @return PositionEntity[]|array
	 */
	public function getByLocation($id) {
		$response = $this->getHumanity()->get('locations/:id/positions', (string) $id);

		if ($response->hasError()) {
			$this->setError($response);
			return [];
		}

		$collection = [];

		foreach ($response as $data) {
			$collection[] = new PositionEntity($data);
		}

		return $collection;
	}

	/**
	 * Add new Position to Company.
	 * @scopes position.manage
	 *
	 * @param PositionEntity $position
	 *
	 * @return bool
	 */
	public function create(PositionEntity $position) {
		$response = $this->getHumanity()->post('positions', null, $position->extract());

		if ($response->hasError()) {
			$this->setError($response);
			return false;
		}

		$position->hydrate($response->getData());
		return true;
	}

	/**
	 * Edit Position.
	 * @scopes position.edit
	 *
	 * @param PositionEntity $position
	 *
	 * @return bool
	 */
	public function update(PositionEntity $position) {
		$response = $this->getHumanity()->put('positions/:id', $position->position_id, $position->extract());

		if ($response->hasError()) {
			$this->setError($response);
			return false;
		}

		$position->hydrate($response->getData());
		return true;
	}

	/**
	 * Delete Position.
	 * @scopes position.manage
	 *
	 * @param PositionEntity $position
	 *
	 * @return bool
	 */
	public function delete(PositionEntity $position) {
		$response = $this->getHumanity()->delete('positions/:id', $position->position_id);

		if ($response->hasError()) {
			$this->setError($response);
			return false;
		}

		$position->hydrate($response->getData());
		return true;
	}

}