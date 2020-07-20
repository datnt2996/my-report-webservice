<?php

namespace App\Contracts;

interface UserRepositoryInterface
{
	/**
	 * Get 5 posts hot in a month the last.
	 * @return mixed
	 */

	/**
	 * get all users.
	 *
	 * @param [Request] $request
	 * @param integer $limit
	 * @return void
	 */
	public function getUsers($request, $limit = 20);


	/**
	 * get all users.
	 *
	 * @param [Request] $request
	 * @param integer $limit
	 * @return void
	 */
	public function getUser($id);

	/**
	 * create new user.
	 *
	 * @param [type] $data
	 * @return void
	 */
	public function createUser($data);

	/**
	 * update user.
	 *
	 * @param [type] $data
	 * @return void
	 */
	public function updateUser($data, $id);

	/**
	 * login user.
	 *
	 * @param [type] $data
	 * @return void
	 */
	public function loginUser($data);
}