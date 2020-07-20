<?php

namespace App\Http\Controllers;

use App\Http\Transformers\UserAddressTransform;
use App\Contracts\UserAddressRepositoryInterface;
use App\Http\Requests\UserRequests\GetUserAddressRequest;
use App\Http\Requests\UserRequests\PutUserAddressRequest;
use App\Http\Requests\UserRequests\PostUserAddressRequest;

class UserController extends Controller
{
	protected $userAddressRepository;
	protected $userAddressTransform;

	public function __construct(
		UserAddressRepositoryInterface $userRepository,
		UserAddressTransform $userTransform
	) {
		$this->userAddressRepository = $userRepository;
		$this->userAddressTransform = $userTransform;
	}
	public function getUserAddressesController(GetUserAddressRequest $request)
	{
		$request->validated();
		$addresses = $this->userAddressRepository->getUserAddresses($request);
		return $this->userAddressTransform->transformUserAddresses($addresses);
	}
	public function createUserAddressesController(PostUserAddressRequest $request)
	{
		$data = $request->validated();
		$addresses = $this->userAddressRepository->createUserAddress($data['user_address']);
		return $this->userAddressTransform->transformUserAddresses($addresses);
	}
	public function updateUserAddressesController(PutUserAddressRequest $request, $addressId)
	{
		$data = $request->validated();
		$addresses = $this->userAddressRepository->updateUserAddress($data['user_address'], $addressId);
		return $this->userAddressTransform->transformUserAddresses($addresses);
	}
	public function deleteUserAddressesController($addressId)
	{
		$handle = $this->userAddressRepository->deleteUserAddress($addressId);
		if ($handle) {
			return response()->json(['message' => 'success'], 204);
		}
		return response()->json(['message' => 'failed'], 500);
	}
}