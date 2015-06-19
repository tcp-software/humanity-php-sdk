<?php

namespace Humanity\Repository;

use Humanity\Entity\Employee as EmployeeEntity;

/**
 * @author Dusan Vejin <dutekvejin@gmail.com>
 */
class Employee extends AbstractRepository {

	/**
	 * Get current employee.
	 *
	 * @return EmployeeEntity|null
	 */
	public function me() {
		$response = $this->getHumanity()->get('employees/me');

		if ($response->hasError()) {
			$this->setError($response);
			return null;
		}

		return new EmployeeEntity($response->getData());
	}

	/**
	 * Get employee by id.
	 * @scopes employee.view
	 *
	 * @param string $id Employee id
	 *
	 * @return EmployeeEntity|null
	 */
	public function get($id) {
		$response = $this->getHumanity()->get('employees/:id', (string) $id);

		if ($response->hasError()) {
			$this->setError($response);
			return null;
		}

		return new EmployeeEntity($response->getData());
	}

	/**
	 * Get all employees from company.
	 * @scopes employee.view
	 *
	 * @param string $id Company id
	 *
	 * @return EmployeeEntity[]|array
	 */
	public function getByCompany($id) {
		$response = $this->getHumanity()->get('companies/:id/employees', (string) $id);

		if ($response->hasError()) {
			$this->setError($response);
			return [];
		}

		$collection = [];

		foreach ($response as $data) {
			$collection[] = new EmployeeEntity($data);
		}

		return $collection;
	}

	/**
	 * Add new Employee to Company.
	 * @scopes employee.manage
	 *
	 * @param EmployeeEntity $employee
	 *
	 * @return bool
	 */
	public function create(EmployeeEntity $employee) {
		$response = $this->getHumanity()->post('employees', null, $employee->extract());

		if ($response->hasError()) {
			$this->setError($response);
			return false;
		}

		$employee->hydrate($response->getData());
		return true;
	}

	/**
	 * Edit Employee.
	 * @scopes employee.edit
	 *
	 * @param EmployeeEntity $employee
	 *
	 * @return bool
	 */
	public function update(EmployeeEntity $employee) {
		$response = $this->getHumanity()->put('employees/:id', $employee->employee_id, $employee->extract());

		if ($response->hasError()) {
			$this->setError($response);
			return false;
		}

		$employee->hydrate($response->getData());
		return true;
	}

	/**
	 * Delete Employee.
	 * @scopes employee.manage
	 *
	 * @param EmployeeEntity $employee
	 *
	 * @return bool
	 */
	public function delete(Employee $employee) {
		$response = $this->getHumanity()->delete('employees/:id', $employee->employee_id);

		if ($response->hasError()) {
			$this->setError($response);
			return false;
		}

		$employee->hydrate($response->getData());
		return true;
	}
}