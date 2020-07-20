<?php

namespace App\Http\Controllers;

use App\Helpers\AuthHelper;
use App\Http\Transformers\UserTransform;
use App\Contracts\UserRepositoryInterface;
use App\Http\Requests\UserRequests\GetUserRequest;
use App\Http\Requests\UserRequests\PutUserRequest;	
use App\Http\Requests\UserRequests\LoginUserRequest;
use App\Http\Requests\UserRequests\PostCreateUserRequest;

class UserController extends Controller
{
	protected $userRepository;
	protected $userTransform;

	public function __construct(
		UserRepositoryInterface $userRepository,
		UserTransform $userTransform
	) {
		$this->userRepository = $userRepository;
		$this->userTransform = $userTransform;
	}

	public function getUsersController(GetUserRequest $request)
	{
		$request->validated();

		$users = $this->userRepository->getUsers($request, $request->input('limit', 20));

		return $this->userTransform->transformUsers($users);
	}
	public function getUserByIdController($id){
		$userId = $id == '_me' ? AuthHelper::getUserId() : $id;
		$user =  $this->userRepository->getUser($userId);
		return response()->json(['user' => $user]);
	}

	public function createUserController(PostCreateUserRequest $request)
	{
		$data = $request->validated();
		$user = $this->userRepository->createUser($data);

		return response()->json(['user' => $user]);
	}

	public function updateUserController(PutUserRequest $request, $id)
	{
		$data = $request->validated();
		$user = $this->userRepository->updateUser($data, $id);
		
		return response()->json(['user' => $user]);
	}

	public function loginUserController(LoginUserRequest $request)
	{
		$token = $this->userRepository->loginUser($request->validated());
		// dd('ok');
		return response()->json($token);
	}
}