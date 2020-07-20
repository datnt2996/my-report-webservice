<?php

namespace App\Repositories;

use App\Model\UserAddress;
use App\Helpers\AuthHelper;
use App\Exceptions\NotFoundException;
use App\Repositories\EloquentRepository;
use App\Repositories\UserEloquentRepository;
use App\Contracts\UserAddressRepositoryInterface;

class UserAddressEloquentRepository extends EloquentRepository
implements UserAddressRepositoryInterface
{
	public function getModel()
	{
		return UserAddress::class;
	}
	public function getUserAddresses($request)
	{
		$userId = AuthHelper::getUserID();
		$limit = $request->input('limit', 20);
		return $this->_model->where('user_id', $userId)
			->orderBy('created_at', 'DESC')->simplePaginate($limit);
	}
	public function createUserAddress($data, $userId)
	{
		$data['user_id'] = $userId;
		return $this->_model->create($data);
	}
	public function updateUserAddress($data, $addressId)
	{
		$address = $this->checkAddress($addressId);
		(new  UserEloquentRepository())->checkUser($address->user_id);
		return $address->update($data);
	}
	public function deleteUserAddress($addressId)
	{
		$address = $this->checkAddress($addressId);
		(new  UserEloquentRepository())->checkUser($address->user_id);
		return $address->delete();
	}
	public function checkAddress($addressId)
	{
		$address = $this->_model->find($$addressId);
		if (empty($address)) {
			throw new NotFoundException(
				'user address not found',
			);
		}
		return $address;
	}
}