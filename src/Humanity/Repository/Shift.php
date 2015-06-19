<?php

namespace Humanity\Repository;

use Humanity\Entity\Shift as ShiftEntity;

/**
 * @author Dusan Vejin <dutekvejin@gmail.com>
 */
class Shift extends AbstractRepository {

	/**
	 * Get shift by id.
	 * @scopes shift.view
	 *
	 * @param string $id Shift id
	 *
	 * @return ShiftEntity|null
	 */
	public function get($id) {
		$response = $this->getHumanity()->get('shifts/:id', (string) $id);

		if ($response->hasError()) {
			$this->setError($response);
			return null;
		}

		return new ShiftEntity($response->getData());
	}

	/**
	 * Get Shifts related to Entity identified by unique_id parameter.
	 * @scopes shift.view
	 *
	 * @param string $id Company id
	 *
	 * @return ShiftEntity[]|array
	 */
	public function getByEntity($id, \DateTime $from, \DateTime $to) {
		$response = $this->getHumanity()->get('shifts', null, [
			'unique_id' => $id,
			'from' => $from->format(\DateTime::ISO8601),
			'to' => $to->format(\DateTime::ISO8601),
		]);

		if ($response->hasError()) {
			$this->setError($response);
			return [];
		}

		$collection = [];

		foreach ($response as $data) {
			$collection[] = new ShiftEntity($data);
		}

		return $collection;
	}

	/**
	 * Add new Shift to Company.
	 * @scopes shift.manage
	 *
	 * @param ShiftEntity $shift
	 *
	 * @return bool
	 */
	public function create(ShiftEntity $shift) {
		$response = $this->getHumanity()->post('shifts', null, $shift->extract());

		if ($response->hasError()) {
			$this->setError($response);
			return false;
		}

		$shift->hydrate($response->getData());
		return true;
	}

	/**
	 * Edit Shift.
	 * @scopes shift.edit
	 *
	 * @param ShiftEntity $shift
	 *
	 * @return bool
	 */
	public function update(ShiftEntity $shift) {
		$response = $this->getHumanity()->put('shifts/:id', $shift->shift_id, $shift->extract());

		if ($response->hasError()) {
			$this->setError($response);
			return false;
		}

		$shift->hydrate($response->getData());
		return true;
	}

	/**
	 * Publish all Shifts in given time range.
	 * @scope shift.manage
	 *
	 * @param \DateTime $start
	 * @param \DateTime $end
	 *
	 * @return bool
	 */
	public function publish(\DateTime $start, \DateTime $end) {
		$response = $this->getHumanity()->put('shifts/publish', null, [
			'start' => $start->format(\DateTime::ISO8601),
			'end' => $end->format(\DateTime::ISO8601),
		]);

		if ($response->hasError()) {
			$this->setError($response);
			return false;
		}

		return true;
	}

	/**
	 * Delete Shift.
	 * @scopes shift.manage
	 *
	 * @param ShiftEntity $shift
	 *
	 * @return bool
	 */
	public function delete(ShiftEntity $shift) {
		$response = $this->getHumanity()->delete('shifts/:id', $shift->shift_id);

		if ($response->hasError()) {
			$this->setError($response);
			return false;
		}

		$shift->hydrate($response->getData());
		return true;
	}

}