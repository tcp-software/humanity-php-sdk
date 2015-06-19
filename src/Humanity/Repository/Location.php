<?php

namespace Humanity\Repository;

use Humanity\Entity\Location as LocationEntity;

/**
 * @author Dusan Vejin <dutekvejin@gmail.com>
 */
class Location extends AbstractRepository {

	/**
	 * Get location by id.
	 * @scopes location.view
	 *
	 * @param string $id Location id
	 *
	 * @return LocationEntity|null
	 */
	public function get($id) {
		$response = $this->getHumanity()->get('locations/:id', (string) $id);

		if ($response->hasError()) {
			$this->setError($response);
			return null;
		}

		return new LocationEntity($response->getData());
	}

	/**
	 * Get all locations from company.
	 * @scopes location.view
	 *
	 * @param string $id Company id
	 *
	 * @return LocationEntity[]|array
	 */
	public function getByCompany($id) {
		$response = $this->getHumanity()->get('companies/:id/locations', (string) $id);

		if ($response->hasError()) {
			$this->setError($response);
			return [];
		}

		$collection = [];

		foreach ($response as $data) {
			$collection[] = new LocationEntity($data);
		}

		return $collection;
	}

	/**
	 * Add new Location to Company.
	 * @scopes location.manage
	 *
	 * @param LocationEntity $location
	 *
	 * @return bool
	 */
	public function create(LocationEntity $location) {
		$response = $this->getHumanity()->post('locations', null, $location->extract());

		if ($response->hasError()) {
			$this->setError($response);
			return false;
		}

		$location->hydrate($response->getData());
		return true;
	}

	/**
	 * Edit Location.
	 * @scopes location.edit
	 *
	 * @param LocationEntity $location
	 *
	 * @return bool
	 */
	public function update(LocationEntity $location) {
		$response = $this->getHumanity()->put('locations/:id', $location->location_id, $location->extract());

		if ($response->hasError()) {
			$this->setError($response);
			return false;
		}

		$location->hydrate($response->getData());
		return true;
	}

	/**
	 * Delete Location.
	 * @scopes location.manage
	 *
	 * @param LocationEntity $location
	 *
	 * @return bool
	 */
	public function delete(LocationEntity $location) {
		$response = $this->getHumanity()->delete('locations/:id', $location->location_id);

		if ($response->hasError()) {
			$this->setError($response);
			return false;
		}

		$location->hydrate($response->getData());
		return true;
	}

}