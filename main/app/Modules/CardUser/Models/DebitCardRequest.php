<?php

namespace App\Modules\CardUser\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Admin\Models\ActivityLog;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\CardUser\Models\DebitCard;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\CardUser\Models\DebitCardType;
use App\Modules\CardUser\Models\DebitCardRequestStatus;
use App\Modules\Admin\Transformers\AdminDebitCardRequestTransformer;

/**
 * App\Modules\CardUser\Models\DebitCardRequest
 *
 * @property int $id
 * @property int|null $debit_card_id
 * @property int|null $debit_card_type_id
 * @property int $card_user_id
 * @property int|null $sales_rep_id
 * @property int $debit_card_request_status_id
 * @property string $phone
 * @property string $address
 * @property string $zip
 * @property string $city
 * @property string $payment_method
 * @property int $is_paid
 * @property int $is_payment_confirmed
 * @property int|null $confirmed_by
 * @property int|null $last_updated_by
 * @property string|null $last_updater_user_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Modules\CardUser\Models\CardUser $card_user
 * @property-read \App\Modules\CardUser\Models\DebitCard|null $debit_card
 * @property-read \App\Modules\CardUser\Models\DebitCardRequestStatus $debit_card_request_status
 * @property-read \App\Modules\CardUser\Models\DebitCardType|null $debit_card_type
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardRequest newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\CardUser\Models\DebitCardRequest onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardRequest pendingAccountantConfirmation()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardRequest remoteRequests()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardRequest whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardRequest whereCardUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardRequest whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardRequest whereConfirmedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardRequest whereDebitCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardRequest whereDebitCardRequestStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardRequest whereDebitCardTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardRequest whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardRequest whereIsPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardRequest whereIsPaymentConfirmed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardRequest whereLastUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardRequest whereLastUpdaterUserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardRequest wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardRequest wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardRequest whereSalesRepId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardRequest whereZip($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\CardUser\Models\DebitCardRequest withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\CardUser\Models\DebitCardRequest withoutTrashed()
 * @mixin \Eloquent
 * @property string $state
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardRequest whereState($value)
 */
class DebitCardRequest extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'address', 'phone', 'debit_card_request_status_id', 'payment_method', 'zip', 'city', 'debit_card_type_id', 'state'
  ];

  public function card_user()
  {
    return $this->belongsTo(CardUser::class);
  }

  public function debit_card_request_status()
  {
    return $this->belongsTo(DebitCardRequestStatus::class);
  }

  public function debit_card()
  {
    return $this->belongsTo(DebitCard::class);
  }

  public function debit_card_type()
  {
    return $this->belongsTo(DebitCardType::class);
  }

  /**
   * Scope a query to only include remote card requests.
   *
   * @param  \Illuminate\Database\Eloquent\Builder  $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeRemoteRequests($query)
  {
    return $query->where('sales_rep_id', null);
  }

  /**
   * Scope a query to only include card requests that have not been confirmed by the accountant.
   *
   * @param  \Illuminate\Database\Eloquent\Builder  $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopePendingAccountantConfirmation($query)
  {
    return $query->where('is_payment_confirmed', false)->where('is_paid', true);
  }

  static function cardAdminRoutes()
  {
    Route::group(['namespace' => '\App\Modules\CardUser\Models', 'prefix' => 'api'], function () {
      Route::put('debit-card-request/{debit_card_request}/paid', 'DebitCardRequest@markRequestAsPaid')->middleware('auth:card_admin');

      Route::put('debit-card-request/{debit_card_request}/allocate', 'DebitCardRequest@allocateCardToRequest')->middleware('auth:card_admin');

      Route::put('debit-card-request/{debit_card_request}/status/update', 'DebitCardRequest@updateRequestStatus')->middleware('auth:card_admin');
    });
  }

  static function accountantRoutes()
  {
    Route::group(['namespace' => '\App\Modules\CardUser\Models', 'prefix' => 'api'], function () {
      Route::put('debit-card-request/{debit_card_request}/paid/confirm', 'DebitCardRequest@confirmRequestPayment')->middleware('auth:accountant');
    });
  }

  static function adminRoutes()
  {
    Route::group(['namespace' => '\App\Modules\CardUser\Models'], function () {

      Route::get('debit-card-requests', 'DebitCardRequest@getDebitCardRequests')->middleware('auth:admin,card_admin,accountant,normal_admin,account_officer');

      Route::delete('debit-card-request/{debit_card_request}/delete', 'DebitCardRequest@deleteCardRequest')->middleware('auth:admin');
    });
  }

  /**
   * ! Admin routes
   */
  public function getDebitCardRequests()
  {
    if (auth('admin')->check()) {
      return (new AdminDebitCardRequestTransformer)->collectionTransformer(DebitCardRequest::withTrashed()->get(), 'transformForAdminViewDebitCardRequests');
    } else if (auth('card_admin')->check()) {
      $pending_card_requests = DebitCardRequest::remoteRequests()->get();
      return (new AdminDebitCardRequestTransformer)->collectionTransformer($pending_card_requests, 'transformForAdminViewDebitCardRequests');
    } else if (auth('account_officer')->check()) {
      $pending_card_requests = DebitCardRequest::remoteRequests()->orWhere->pendingAccountantConfirmation()->get();
      return (new AdminDebitCardRequestTransformer)->collectionTransformer($pending_card_requests, 'transformForAdminViewDebitCardRequests');
    } else if (auth('accountant')->check()) {
      $pending_card_requests = DebitCardRequest::pendingAccountantConfirmation()->get();
      return (new AdminDebitCardRequestTransformer)->collectionTransformer($pending_card_requests, 'transformForAdminViewDebitCardRequests');
    } else if (auth('normal_admin')->check()) {
      $pending_card_requests = DebitCardRequest::all();
      return (new AdminDebitCardRequestTransformer)->collectionTransformer($pending_card_requests, 'transformForAdminViewDebitCardRequests');
    }
  }

  public function allocateCardToRequest(DebitCardRequest $debit_card_request)
  {

    /** Check that request is a new request */
    if (intval($debit_card_request->debit_card_request_status_id) !== 1) {
      return generate_422_error(['card' => ['This card request is already being processed']]);
    }
    /** check that there is no card associated previously to request */
    if (intval($debit_card_request->debit_card_id)) {
      return generate_422_error(['card' => ['This card request already has a debit card allocated to it']]);
    }

    /** Check card exists */
    if (!DebitCard::exists(request('card_number'))) {
      return generate_422_error(['card' => ['This Debit Card does not exist in the records']]);
    }

    $debit_card = DebitCard::retrieve(request('card_number'));

    /** Check card has being assigned to this sales rep */
    if ((auth('sales_rep')->check() && auth()->id() !== intval($debit_card->sales_rep_id)) || !is_null($debit_card->sales_rep_id)) {
      return generate_422_error(['card' => ['This Debit Card has not being assigned to you']]);
    }

    /** Check card has not being assigned to another user */
    if ($debit_card->card_user_id) {
      return generate_422_error(['card' => ['This Debit Card belongs to another user']]);
    }

    /** Check that the debit card matches the request type */
    if (intval($debit_card_request->debit_card_type_id) !== intval($debit_card->debit_card_type->id)) {
      return generate_422_error(['card' => ['This Debit Card type does not match the requested card type']]);
    }

    DB::beginTransaction();

    /**  Attach debit card id to this request */
    $debit_card_request->debit_card_id = $debit_card->id;

    /** Set this sales rep as the one attending to the request */
    // $debit_card_request->sales_rep_id = auth()->id();

    /** Set last_updated_by of this request */
    $debit_card_request->last_updated_by = auth()->id();
    $debit_card_request->save();

    /** Allocate the card to the user that made the request */
    $debit_card->card_user_id = $debit_card_request->card_user_id;
    $debit_card->save();

    /** Create activity */
    ActivityLog::notifyAdmins(auth()->user()->email . ' allocated debit card ' . $debit_card->card_number . ' to ' . $debit_card_request->card_user->email . '\'s card request');
    ActivityLog::notifyCardAdmins(auth()->user()->email . ' allocated debit card ' . $debit_card->card_number . ' to ' . $debit_card_request->card_user->email . '\'s card request');

    DB::commit();
    return response()->json([], 204);
  }

  public function markRequestAsPaid(DebitCardRequest $debit_card_request)
  {
    $debit_card_request->is_paid = true;
    $debit_card_request->save();

    ActivityLog::notifyAdmins(auth()->user()->email . ' marked ' . $debit_card_request->card_user->email . '\'s card request as paid.');
    ActivityLog::notifyAccountOfficers(auth()->user()->email . ' marked ' . $debit_card_request->card_user->email . '\'s card request as paid.');
    ActivityLog::notifyAccountants(auth()->user()->email . ' marked ' . $debit_card_request->card_user->email . '\'s card request as paid.');

    return response()->json([], 204);
  }

  public function confirmRequestPayment(DebitCardRequest $debit_card_request)
  {
    $debit_card_request->is_payment_confirmed = true;
    $debit_card_request->confirmed_by = auth()->id();
    $debit_card_request->last_updated_by = auth()->id();
    // $debit_card_request->last_updater_user_type = get_class(auth()->user());
    $debit_card_request->save();

    ActivityLog::notifyAccountants(auth()->user()->email . ' marked ' . $debit_card_request->card_user->email . '\'s card request payment as confirmed.');
    ActivityLog::notifyAdmins(auth()->user()->email . ' marked ' . $debit_card_request->card_user->email . '\'s card request payment as confirmed.');
    ActivityLog::notifyAccountOfficers(auth()->user()->email . ' marked ' . $debit_card_request->card_user->email . '\'s card request payment as confirmed.');

    return response()->json([], 204);
  }

  public function updateRequestStatus(DebitCardRequest $debit_card_request)
  {
    // return request('details.debit_card_request_status_id');
    if (is_null($debit_card_request->debit_card_id)) {
      return generate_422_error(['invalid' => ['No debit card has been assigned to this debit card request']]);
    }
    $debit_card_request->debit_card_request_status_id = request('details.debit_card_request_status_id');
    $debit_card_request->save();

    ActivityLog::notifyCardAdmins(auth()->user()->email . ' updated the delivery status ' . $debit_card_request->card_user->email . '\'s card request.');
    ActivityLog::notifyAdmins(auth()->user()->email . ' updated the delivery status ' . $debit_card_request->card_user->email . '\'s card request.');
    ActivityLog::notifyAccountOfficers(auth()->user()->email . ' updated the delivery status ' . $debit_card_request->card_user->email . '\'s card request.');

    return response()->json(['new_status' => DebitCardRequestStatus::find(request('details.debit_card_request_status_id'))->name], 203);
  }

  public function deleteCardRequest(DebitCardRequest $debit_card_request)
  {
    return;
    $debit_card_request->delete();
    return response()->json(['rsp' => true], 204);
  }
}
