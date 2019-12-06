<?php

namespace App\Modules\Admin\Transformers;

use App\User;
use App\Modules\Admin\Models\Admin;

class AdminUserTransformer
{
	public function collectionTransformer($collection, $transformerMethod)
	{
		// return $collection;
		// return [
		// 	'total' => $collection->count(),
		// 	'current_page' => $collection->currentPage(),
		// 	'path' => $collection->resolveCurrentPath(),
		// 	$collection->hasMorePages(),
		// 	'to' => $collection->lastItem(),
		// 	'from' => $collection->firstItem(),
		// 	'last_page' => $collection->lastPage(),
		// 	'next_page_url' => $collection->nextPageUrl(),
		// 	'per_page' => $collection->perPage(),
		// 	'prev_page_url' => $collection->previousPageUrl(),
		// 	'total' => $collection->total(),
		// 	'first_page_url' => $collection->url($collection->firstItem()),
		// 	'last_page_url' => $collection->url($collection->lastPage()),
		// 	$collection->items(),
		// ];
		return [
			// 'total' => $collection->count(),
			// 'current_page' => $collection->currentPage(),
			// 'path' => $collection->resolveCurrentPath(),
			// 'to' => $collection->lastItem(),
			// 'from' => $collection->firstItem(),
			// 'last_page' => $collection->lastPage(),
			// 'next_page_url' => $collection->nextPageUrl(),
			// 'per_page' => $collection->perPage(),
			// 'prev_page_url' => $collection->previousPageUrl(),
			// 'total' => $collection->total(),
			// 'first_page_url' => $collection->url($collection->firstItem()),
			// 'last_page_url' => $collection->url($collection->lastPage()),
			'users' => $collection->map(function ($v) use ($transformerMethod) {
				return $this->$transformerMethod($v);
			})
		];
	}

	public function transform(AppUser $user)
	{
		return [
			'name' => $user->name,
		];
	}

	public function transformForAdminViewUsers(User $user)
	{
		return [
			'id' => (int)$user->id,
			'full_name' => (string)$user->full_name,
			'email' => (string)$user->email,
			'phone' => (string)$user->phone,
			'bvn' => (string)$user->bvn,
			'user_passport' => (string)$user->user_passport,
			'gender' => (string)$user->gender,
			'acc_type' => (string)$user->acc_type,
			'acc_num' => (string)$user->acc_num,
			'address' => (string)$user->address,
			'dob' => (string)$user->dob,
			'is_verified' => (boolean)$user->is_verified(),
			'is_processed' => (boolean)$user->is_processed,
		];
	}

	public function transformForAdminViewAdmins(Admin $user)
	{
		return [
			'id' => (int)$user->id,
			'role' => $user->role_id == 2 ? 'super admin' : 'admin',
			'full_name' => (string)$user->full_name,
			'email' => (string)$user->email,
			'phone' => (string)$user->phone,
			'bvn' => (string)$user->bvn,
			'user_passport' => (string)$user->user_passport,
			'gender' => (string)$user->gender,
			'acc_type' => (string)$user->acc_type,
			'acc_num' => (string)$user->acc_num,
			'address' => (string)$user->address,
			'dob' => (string)$user->dob,
			'is_verified' => (boolean)$user->is_verified(),
		];
	}
}
