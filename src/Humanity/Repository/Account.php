<?php

namespace Humanity\Repository;

use Humanity\Entity\Account as AccountEntity;

/**
 * @author Dusan Vejin <dutekvejin@gmail.com>
 */
class Account extends AbstractRepository {

	/**
	 * Get account by id.
	 * @scopes account.view
	 *
	 * @param string $id Account id
	 *
	 * @return AccountEntity|null
	 */
	public function get($id) {
		$response = $this->getHumanity()->get('accounts/:id', (string) $id);

		if ($response->hasError()) {
			$this->setError($response);
			return null;
		}

		return new AccountEntity($response->getData());
	}

}