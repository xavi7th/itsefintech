<?php

namespace App\Modules\CardUser\Models;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Watson\Rememberable\Rememberable;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Admin\Models\ActivityLog;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\CardUser\Models\LoanRequest;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\CardUser\Transformers\LoanRequestTransformer;
use App\Modules\CardUser\Transformers\LoanTransactionTransformer;
use App\Modules\Admin\Transformers\AdminLoanTransactionTransformer;
use App\Modules\CardUser\Http\Requests\MakeLoanRepaymentValidation;

/**
 * App\Modules\CardUser\Models\LoanTransaction
 *
 * @property int $id
 * @property int $card_user_id
 * @property int $loan_request_id
 * @property float $amount
 * @property string $transaction_type
 * @property \Illuminate\Support\Carbon $next_installment_due_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Modules\CardUser\Models\CardUser $card_user
 * @property-read \App\Modules\CardUser\Models\LoanRequest $loan_request
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanTransaction newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\CardUser\Models\LoanTransaction onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanTransaction whereCardUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanTransaction whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanTransaction whereLoanRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanTransaction whereNextInstallmentDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanTransaction whereTransactionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanTransaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\CardUser\Models\LoanTransaction withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\CardUser\Models\LoanTransaction withoutTrashed()
 * @mixin \Eloquent
 */
class LoanTransaction extends Model
{
  use SoftDeletes;
  use Rememberable;

  protected $fillable = ['card_user_id', 'amount', 'transaction_type', 'next_installment_due_date',];

  /**
   * The attributes that should be mutated to dates.
   *
   * @var array
   */
  protected $dates = [
    'next_installment_due_date',
  ];

  public function loan_request()
  {
    return $this->belongsTo(LoanRequest::class);
  }

  public function is_school_fees(): bool
  {
    return $this->loan_request->is_school_fees;
  }

  public function card_user()
  {
    return $this->belongsTo(CardUser::class);
  }


  static function adminRoutes()
  {
    Route::group(['namespace' => '\App\Modules\CardUser\Models'], function () {
      Route::get('loan-transactions', 'LoanTransaction@showAllLoanTransactions')->middleware('auth:admin,normal_admin');
    });
  }

  static function cardUserRoutes()
  {
    Route::group(['namespace' => '\App\Modules\CardUser\Models', 'middleware' => ['verified_card_users']], function () {
      Route::get('loan-transactions', 'LoanTransaction@getLoanTransaction')->middleware('auth:card_user');
      Route::get('loan-transactions/summary', 'LoanTransaction@getLoanTransactionSummary')->middleware('auth:card_user');
      Route::post('loan-transaction/{loan_request}/make-payment', 'LoanTransaction@makeLoanRepayment')->middleware('auth:card_user');
    });
  }

  /**
   * Card User Route Methods
   */

  public function getLoanTransaction(Request $request)
  {
    try {
      return collect((new LoanTransactionTransformer)->transformForSummary($request))->merge((new LoanTransactionTransformer)->collectionTransformer($request->user()->loan_transactions, 'transformForUserViewLoanTransactions'));
    } catch (\Throwable $th) {
      return response()->json(['mesage' => 'User has no existing loan transaction'], 404);
    }
  }

  public function getLoanTransactionSummary(Request $request)
  {
    return (new LoanRequestTransformer)->collectionTransformer($request->user()->running_loan_requests, 'transformWithLoanTransactions');
  }

  public function makeLoanRepayment(MakeLoanRepaymentValidation $request, LoanRequest $loan_request)
  {
    /**
     * Determine if the user is repaying the loan or just paying the minimum repayment
     */
    $trans_type = $request->amount == $loan_request->breakdownStatistics()->minimum_repayment_amount ? 'servicing' : 'repayment';

    DB::beginTransaction();
    /**
     * Create a transaction for this loan request
     */
    $loan_transaction = $loan_request->loan_transactions()->create([
      'card_user_id' => auth()->id(),
      'amount' => $request->amount,
      'transaction_type' => $trans_type,
      /**
       * ? Get the $loan_request->loan_transactions->last()'s next_installment_due_date and add a month to it?
       */
      'next_installment_due_date' => now()->addMonth()
    ]);

    ActivityLog::logUserActivity(auth()->user()->email . 'made a loan repayment. Amount: ' . $request->amount . ' Type: '  . $trans_type);

    DB::commit();

    return (new LoanTransactionTransformer)->transformForUserViewLoanTransactions($loan_transaction);
  }

  /**
   * ! Admin Route Methods
   */

  public function showAllLoanTransactions()
  {

    $loan_requests = LoanTransaction::all();

    return (new AdminLoanTransactionTransformer)->collectionTransformer($loan_requests, 'transformForAdminViewLoanTransactions');
  }

  public static function boot()
  {

    parent::boot();

    static::created(function ($transaction) {
      if ($transaction->loan_request->is_fully_paid()) {
        $transaction->loan_request->is_fully_repaid = true;
        $transaction->loan_request->save();
      }
    });
  }
}
