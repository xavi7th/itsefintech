<?php

namespace App\Modules\Admin\Transformers;

use App\User;
use App\Modules\Admin\Models\Admin;
use App\Modules\SalesRep\Models\SalesRep;
use App\Modules\CardAdmin\Models\CardAdmin;
use App\Modules\Accountant\Models\Accountant;
use App\Modules\NormalAdmin\Models\NormalAdmin;
use App\Modules\DispatchAdmin\Models\DispatchAdmin;
use App\Modules\AccountOfficer\Models\AccountOfficer;
use App\Modules\CustomerSupport\Models\CustomerSupport;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\CardUser\Models\DebitCard;

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
			'is_suspended' => (boolean)$user->deleted_at
		];
	}

	public function transformForAdminViewNormalAdmins(NormalAdmin $user)
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
			'is_suspended' => (boolean)$user->deleted_at
		];
	}

	public function transformForAdminViewAccountants(Accountant $user)
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
			'is_suspended' => (boolean)$user->deleted_at
		];
	}

	public function transformForAdminViewAccountOfficers(AccountOfficer $user)
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
			'is_suspended' => (boolean)$user->deleted_at
		];
	}

	public function transformForAdminViewCardAdmins(CardAdmin $user)
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
			'is_suspended' => (boolean)$user->deleted_at
		];
	}

	public function transformForAdminViewCustomerSupports(CustomerSupport $user)
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
			'is_suspended' => (boolean)$user->deleted_at
		];
	}

	public function transformForAdminViewDispatchAdmins(DispatchAdmin $user)
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
			'is_suspended' => (boolean)$user->deleted_at
		];
	}

	public function transformForAdminViewSalesReps(SalesRep $user)
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
			'is_suspended' => (boolean)$user->deleted_at
		];
	}
	public function transformForAdminViewCardUsers(CardUser $user)
	{
		return [
			'id' => (int)$user->id,
			'full_name' => (string)$user->first_name . ' ' . $user->first_name,
			'email' => (string)$user->email,
			'phone' => (string)$user->phone,
			'bvn' => (string)$user->bvn,
			'user_passport' => (string)$user->user_passport,
			'gender' => (string)$user->gender,
			'acc_type' => (string)$user->acc_type,
			'acc_num' => (string)$user->acc_num,
			'address' => (string)$user->address,
			'dob' => (string)$user->dob,
			'is_verified' => (boolean)$user->is_otp_verified(),
			'is_suspended' => (boolean)$user->deleted_at,
			'can_withdraw' => (boolean)$user->can_withdraw,
			'cards' => $user->debit_cards
		];
	}
}
