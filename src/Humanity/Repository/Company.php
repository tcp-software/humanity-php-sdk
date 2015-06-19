<?php

namespace Humanity\Repository;

use Humanity\Entity\Company as CompanyEntity;

/**
 * @author Dusan Vejin <dutekvejin@gmail.com>
 */
class Company extends AbstractRepository {

	/**
	 * Get company by id.
	 * @scopes CompanyEntity::SCOPE_VIEW
	 *
	 * @param string $id Company id
	 *
	 * @return CompanyEntity|null
	 */
	public function get($id) {
		$response = $this->getHumanity()->get('companies/:id', (string) $id);

		if ($response->hasError()) {
			$this->setError($response);
			return null;
		}

		return new CompanyEntity($response->getData());
	}

	/**
	 * Edit company.
	 * @scopes CompanyEntity::SCOPE_MANAGE
	 *
	 * @param CompanyEntity $company
	 *
	 * @return bool
	 */
	public function update(CompanyEntity $company) {
		$response = $this->getHumanity()->put('companies/:id', $company->company_id, $company->extract());

		if ($response->hasError()) {
			$this->setError($response);
			return false;
		}

		$company->hydrate($response->getData());
		return true;
	}

}