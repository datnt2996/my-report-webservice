<?php

namespace App\Repositories;

use App\Model\User;
use App\Helpers\ApiHelper;
use App\Helpers\AuthHelper;
use App\Exceptions\LoginException;
use App\Exceptions\NotFoundException;
use App\Contracts\UserRepositoryInterface;

class UserEloquentRepository extends EloquentRepository implements UserRepositoryInterface
{
	public function getModel()
	{
		return User::class;
	}

	public function getUsers($request, $limit = 20)
	{
		return $this->_model->simplePaginate($limit);
	}

	public function createUser($data)
	{
		$data['receive_announcements'] = $data['receive_announcements'] ?? true;
		$data['permission'] = $data['permission'] ?? null;
		$data['is_organization'] = !empty($data['is_organization']);
		$data['locale'] = $data['locale'] ?? null;
		$data['user_type'] = $data['user_type'] ?? 'user';
		$data['roles'] = $data['user_type'] ?? 'user';

		return $this->_model->create($data);
	}

	public function checkUser($id)
	{
		$user = $this->_model->find($id);
		if (empty($user)) {
			NotFoundException::render();
		}
		return $user;
	}
	public function getUser($id){
		return $this->checkUser($id);
	}


	public function updateUser($data, $id)
	{
		$user = $this->checkUser($id);
		if (!empty($data)) {
			$data = collect($data)->only(
				'full_name',
				'screen_name',
				'number_phone',
				'image',
				'receive_announcements',
				'password' 
			);
			$user->update($data->toArray());
		}

		return $user;
	}

	public function loginUser($data)
	{
		$user = LoginException::check($this->_model, $data['email'], $data['password']);
		return ['token' => AuthHelper::generateUserToken($user->id, $user->is_organization, $user->is_admin), 'user' => $user];
	}
}