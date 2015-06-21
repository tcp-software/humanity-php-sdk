<?php

namespace Humanity\Repository;

use Humanity\Entity\Timeclock as TimeclockEntity;
use Humanity\Entity\TimeclockBreak;

/**
 * @author Dusan Vejin <dutekvejin@gmail.com>
 */
class Timeclock extends AbstractRepository {

	/**
	 * Get timeclock by id.
	 * @scopes timeclock.view
	 *
	 * @param string $id Timeclock id
	 *
	 * @return TimeclockEntity|null
	 */
	public function get($id) {
		$response = $this->getHumanity()->get('timeclocks/:id', (string) $id);

		if ($response->hasError()) {
			$this->setError($response);
			return null;
		}

		return new TimeclockEntity($response->getData());
	}

	/**
	 * Get Employee's Timeclock status. Returns empty Timeclock if Employee is not clocked in.
	 * @scopes timeclock.view
	 *
	 * @param string $id Employee id
	 *
	 * @return TimeclockEntity|null
	 */
	public function getEmployeeStatus($id) {
		$response = $this->getHumanity()->get('timeclocks/status/:id', (string) $id);

		if ($response->hasError()) {
			$this->setError($response);
			return null;
		}

		return new TimeclockEntity($response->getData() ?: []);
	}

	/**
	 * Get Timeclocks in time range for Company.
	 * @scopes timeclock.view
	 *
	 * @param \DateTime $from
	 * @param \DateTime $to
	 *
	 * @return TimeclockEntity[]|array
	 */
	public function getFromCompany(\DateTime $from, \DateTime $to) {
		$response = $this->getHumanity()->get('timeclocks', null, [
			'from' => $from->format(\DateTime::ISO8601),
			'to' => $to->format(\DateTime::ISO8601),
		]);

		if ($response->hasError()) {
			$this->setError($response);
			return [];
		}

		$collection = [];

		foreach ($response as $data) {
			$collection[] = new TimeclockEntity($data);
		}

		return $collection;
	}

	/**
	 * Timeclock clock in.
	 * @scopes timeclock.edit
	 *
	 * @param string $id Employee id
	 *
	 * @return TimeclockEntity|bool
	 */
	public function clockIn($id) {
		$response = $this->getHumanity()->post('timeclocks/clockin', null, [
			'employee_id' => $id,
		]);

		if ($response->hasError()) {
			$this->setError($response);
			return false;
		}

		return new TimeclockEntity($response->getData());
	}

	/**
	 * Timeclock clock out.
	 * @scopes timeclock.edit
	 *
	 * @param TimeclockEntity $timeclock
	 *
	 * @return bool
	 */
	public function clockOut(TimeclockEntity $timeclock) {
		$response = $this->getHumanity()->put('timeclocks/:id/clockout', $timeclock->timeclock_id);

		if ($response->hasError()) {
			$this->setError($response);
			return false;
		}

		$timeclock->hydrate($response->getData());
		return true;
	}

	/**
	 * Start a break on Timeclock.
	 * @scopes timeclock.edit
	 *
	 * @param TimeclockEntity $timeclock
	 *
	 * @return TimeclockEntity|bool
	 */
	public function breakIn(TimeclockEntity $timeclock) {
		$response = $this->getHumanity()->post('timeclocks/:id/breakin', $timeclock->timeclock_id);

		if ($response->hasError()) {
			$this->setError($response);
			return false;
		}

		return new TimeclockBreak($response->getData());
	}

	/**
	 * End a Break on Timeclock.
	 * @scopes timeclock.edit
	 *
	 * @param TimeclockBreak $timeclockBreak
	 * @param TimeclockEntity $timeclock
	 *
	 * @return bool
	 */
	public function breakOut(TimeclockBreak $timeclockBreak, TimeclockEntity $timeclock) {
		$response = $this->getHumanity()->put('timeclocks/:id/breakout', $timeclock->timeclock_id, [
			'break_id' => $timeclockBreak->break_id
		]);

		if ($response->hasError()) {
			$this->setError($response);
			return false;
		}

		return true;
	}

	/**
	 * Edit Timeclock.
	 * @scopes timeclock.edit
	 *
	 * @param TimeclockEntity $timeclock
	 *
	 * @return bool
	 */
	public function update(TimeclockEntity $timeclock) {
		$response = $this->getHumanity()->put('timeclocks/:id', $timeclock->timeclock_id, $timeclock->extract());

		if ($response->hasError()) {
			$this->setError($response);
			return false;
		}

		$timeclock->hydrate($response->getData());
		return true;
	}

	/**
	 * Delete Timeclock.
	 * @scopes timeclock.manage
	 *
	 * @param TimeclockEntity $timeclock
	 *
	 * @return bool
	 */
	public function delete(TimeclockEntity $timeclock) {
		$response = $this->getHumanity()->delete('timeclocks/:id', $timeclock->timeclock_id);

		if ($response->hasError()) {
			$this->setError($response);
			return false;
		}

		$timeclock->hydrate($response->getData());
		return true;
	}
}