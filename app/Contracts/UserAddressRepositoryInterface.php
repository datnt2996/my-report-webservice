<?php

namespace App\Contracts;

interface UserAddressRepositoryInterface
{
	/**
	 * Get all.
	 * @return mixed
	 */
	public function getUserAddresses($request);
	public function createUserAddress($request, $userId);
	public function updateUserAddress($request, $addressId);
	public function deleteUserAddress($addressId);
}