<?php

namespace App\Modules\CardUser\Models;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Watson\Rememberable\Rememberable;
use Illuminate\Database\Eloquent\Model;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\Admin\Events\LoanDisbursed;
use App\Modules\CardUser\Events\OverdueLoan;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\CardUser\Models\LoanTransaction;
use App\Modules\Admin\Events\LoanRequestApproved;
use App\Modules\CardUser\Notifications\LoanRequested;
use App\Modules\Admin\Events\ManualLoanTransactionSet;
use App\Modules\CardUser\Transformers\LoanRequestTransformer;
use App\Modules\Admin\Transformers\AdminLoanRequestTransformer;
use App\Modules\CardUser\Http\Requests\CreateLoanRequestValidation;

/**
 * App\Modules\CardUser\Models\LoanRequest
 *
 * @property int $id
 * @property int $card_user_id
 * @property float $amount
 * @property float $monthly_interest
 * @property int $total_duration
 * @property bool $is_school_fees
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property int|null $approved_by
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property int|null $marked_paid_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $reminded_at
 * @property-read \App\Modules\CardUser\Models\CardUser $card_user
 * @property-read mixed $due_date
 * @property-read \App\Modules\CardUser\Models\LoanTransaction|null $last_loan_transaction
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Modules\CardUser\Models\LoanTransaction[] $loan_transactions
 * @property-read int|null $loan_transactions_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanRequest newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\CardUser\Models\LoanRequest onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanRequest whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanRequest whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanRequest whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanRequest whereCardUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanRequest whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanRequest whereIsSchoolFees($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanRequest whereMarkedPaidBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanRequest whereMonthlyInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanRequest wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanRequest whereRemindedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanRequest whereTotalDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\CardUser\Models\LoanRequest withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\CardUser\Models\LoanRequest withoutTrashed()
 * @mixin \Eloquent
 * @property bool $is_fully_repaid
 * @property-read mixed $final_due_date
 * @property-read bool $is_expired
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanRequest approved()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanRequest disbursed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanRequest fullyPaid($state)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanRequest notApproved()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\LoanRequest whereIsFullyRepaid($value)
 */
class LoanRequest extends Model
{
  use SoftDeletes, Rememberable;

  protected $fillable = ['amount', 'total_duration', 'monthly_interest', 'is_school_fees'];

  protected $appends = ['final_due_date', 'is_expired'];

  protected $casts = [
    'is_school_fees' => 'boolean', 'is_expired' => 'boolean',
    'final_due_date' => 'datetime', 'is_fully_repaid' => 'boolean',
    'approved_at' => 'datetime', 'paid_at' => 'datetime',
    'reminded_at' => 'datetime'
  ];

  public function card_user()
  {
    return $this->belongsTo(CardUser::class);
  }

  public function loan_transactions()
  {
    return $this->hasMany(LoanTransaction::class);
  }

  public function last_loan_transaction()
  {
    return $this->hasOne(LoanTransaction::class)->latest();
  }

  public function extraAmount(): float
  {
    return $this->loan_transactions()->where('transaction_type', 'others')->sum('amount');
  }

  public function total_servicing_fee(): float
  {
    return $this->loan_transactions()->where('transaction_type', 'servicing')->sum('amount');
  }

  public function loan_amount_repaid(): float
  {
    return $this->loan_transactions()->where('transaction_type', 'repayment')->sum('amount');
  }

  public function loan_balance()
  {
    return $this->breakdownStatistics()->total_repayment_amount + $this->extraAmount() - $this->loan_transactions()->where('transaction_type', 'repayment')->sum('amount');
  }

  public function getFinalDueDateAttribute()
  {
    return Carbon::parse($this->created_at)->addMonths($this->total_duration);
  }

  public function next_due_date()
  {
    return $this->last_loan_transaction->next_installment_due_date;
  }

  public function getIsExpiredAttribute(): bool
  {
    return $this->final_due_date->lte(now());
  }

  public function is_fully_paid(): bool
  {
    return $this->loan_balance() <= 0;
  }

  public function is_due(): bool
  {
    return $this->next_due_date()->lte(now());
  }

  public function is_due_soon(): bool
  {
    return $this->next_due_date()->lte(now()->subDays(3));
  }

  public function needs_reminder(): bool
  {
    return $this->is_due() && (is_null($this->reminded_at) || $this->reminded_at->lte($this->next_due_date()));
  }

  /**
   * Return the statistics of the loan request
   *
   * @property float $amount
   * @property bool $is_school_fees
   * @property float $interest_rate
   * @property int $total_duration
   * @property float $minimum_repayment_amount
   * @property float $minimum_repayment_amount_in_kobo
   * @property float $total_interest_amount
   * @property float $total_repayment_amount
   * @property float $scheduled_repayment_amount
   * @property float $scheduled_repayment_amount_in_kobo
   *
   * @return object
   */
  public function breakdownStatistics(): object
  {
    return (object)[
      'amount' => (float)$this->amount,
      'is_school_fees' => (boolean)$this->is_school_fees,
      'interest_rate' => (float)$this->monthly_interest,
      'total_duration' => (string)$this->total_duration . ' months',
      'minimum_repayment_amount' => (float)round($this->amount * ($this->monthly_interest / 100), 2),
      'minimum_repayment_amount_in_kobo' => (float)(round($this->amount * ($this->monthly_interest / 100), 2) * 100),
      'total_interest_amount' => (float)round($total_interest_amount = ($this->monthly_interest / 100) * $this->amount * (now()->diffInMonths(($this->paid_at)) + 1), 2),
      'total_repayment_amount' => (float)round($total_repayment_amount = $total_interest_amount + $this->amount, 2),
      'scheduled_repayment_amount' => (float)number_format($total_repayment_amount / $this->total_duration, 2, '.', ''),
      'scheduled_repayment_amount_in_kobo' => (float)(number_format($total_repayment_amount / $this->total_duration, 2, '.', '') * 100)
    ];
  }

  static function normalAdminRoutes()
  {
    Route::group(['namespace' => '\App\Modules\CardUser\Models', 'prefix' => 'api'], function () {
      Route::put('loan-request/{loan_request}/approve', 'LoanRequest@approveLoanRequest')->middleware('auth:normal_admin');
    });
  }

  static function adminRoutes()
  {
    Route::group(['namespace' => '\App\Modules\CardUser\Models'], function () {
      Route::get('loan-requests/', 'LoanRequest@showAllLoanRequests')->middleware('auth:admin,normal_admin,accountant');
      Route::get('loan-recovery', 'LoanRequest@showAllLoanRecoveries')->middleware('auth:admin,normal_admin,accountant');
      Route::put('loan-request/{loan_request}/paid', 'LoanRequest@markLoanRequestAsPaid')->middleware('auth:accountant');
      Route::post('loan-request/{loan_request}/remind', 'LoanRequest@sendOverDueLoanReminder')->middleware('auth:accountant,admin');
      Route::put('loan-request/{loan_request}/transaction/add', 'LoanRequest@manuallyAddLoanRequestTransaction')->middleware('auth:accountant,admin');
    });
  }

  static function cardUserRoutes()
  {
    Route::group(['namespace' => '\App\Modules\CardUser\Models', 'middleware' => ['verified_card_users']], function () {
      Route::get('loan-request', 'LoanRequest@getLoanRequest')->middleware('auth:card_user');
      Route::get('{loan_request}/loan-transactions', 'LoanRequest@getLoanRequestTransactions')->middleware('auth:card_user');
      Route::get('loan-request/create', 'LoanRequest@getLoanRequestBreakdown')->middleware('auth:card_user');
      Route::post('loan-request/create', 'LoanRequest@makeLoanRequest')->middleware('auth:card_user');
    });
  }

  /**
   * ! Card User Route Methods
   */

  public function getLoanRequest(Request $request)
  {
    try {
      return (new LoanRequestTransformer)->collectionTransformer($request->user()->loan_request, 'transform');
    } catch (\Throwable $th) {
      return response()->json(['mesage' => 'User has no existing loan request'], 404);
    }
  }

  public function getLoanRequestTransactions(LoanRequest $loan_request)
  {
    return (new LoanRequestTransformer)->transformLoanTransactions($loan_request);
  }

  public function getLoanRequestBreakdown(CreateLoanRequestValidation $request)
  {
    $loan_request = resolve(LoanRequest::class);
    $loan_request->amount = (float)$request->input('amount');
    $loan_request->monthly_interest = auth()->user()->credit_percentage;
    $loan_request->total_duration = (int)$request->input('total_duration');
    return (array)$loan_request->breakdownStatistics();
  }

  public function makeLoanRequest(CreateLoanRequestValidation $request)
  {

    $loan_request = $request->user()->loan_request()->create([
      'amount' => $request->input('amount'),
      'total_duration' => $request->input('total_duration'),
      'monthly_interest' => auth()->user()->credit_percentage,
      'is_school_fees' => $is_school_fees_loan = filter_var($request->is_school_fees, FILTER_VALIDATE_BOOLEAN)
    ]);

    event(new LoanRequested($request->user(), $request->input('amount'), $is_school_fees_loan));

    return response()->json((array)$loan_request->breakdownStatistics(), 201);
  }



  /**
   * !Admin Route Methods
   */

  public function showAllLoanRequests()
  {
    if (auth('admin')->check()) {
      $loan_requests = LoanRequest::withTrashed()->get();
    } else if (auth('normal_admin')->check()) {
      $loan_requests = LoanRequest::notApproved()->get();
    } else if (auth('accountant')->check()) {
      $loan_requests = LoanRequest::approved()->get();
    }
    return (new AdminLoanRequestTransformer)->collectionTransformer($loan_requests, 'transformForAdminViewLoanRequests');
  }

  public function showAllLoanRecoveries()
  {
    if (auth('admin')->check()) {
      $loan_requests = LoanRequest::withTrashed()->disbursed()->fullyPaid(false)->get();
    } else if (auth('normal_admin')->check()) {
      $loan_requests = LoanRequest::disbursed()->fullyPaid(false)->get();
    } else if (auth('accountant')->check()) {
      $loan_requests = LoanRequest::disbursed()->fullyPaid(false)->get();
    }
    return (new AdminLoanRequestTransformer)->collectionTransformer($loan_requests, 'transformForAdminViewLoanRecovery');
  }

  public function approveLoanRequest(LoanRequest $loan_request)
  {
    $card_user = $loan_request->card_user;

    event(new LoanRequestApproved($card_user, $loan_request->amount, $loan_request->is_school_fees));

    $loan_request->approved_at = now();
    $loan_request->approved_by = auth()->id();
    $loan_request->save();

    return response()->json(['rsp' => []], 204);
  }

  public function markLoanRequestAsPaid(LoanRequest $loan_request)
  {
    DB::beginTransaction();

    $card_user = $loan_request->card_user;

    $loan_request->paid_at = now();
    $loan_request->marked_paid_by = auth()->id();
    $loan_request->save();

    $loan_request->loan_transactions()->updateOrCreate(
      [
        'loan_request_id' => $loan_request->id
      ],
      [
        'card_user_id' => $loan_request->card_user_id,
        'amount' => ((object)$loan_request->breakdownStatistics())->total_repayment_amount,
        'transaction_type' => $loan_request->is_school_fees ? 'school fees loan' : 'loan',
        'next_installment_due_date' => now()->addMonth(),
      ]
    );

    DB::commit();

    event(new LoanDisbursed($card_user, $loan_request->amount, $loan_request->is_school_fees));

    return response()->json(['rsp' => []], 204);
  }

  public function sendOverDueLoanReminder(LoanRequest $loan_request)
  {
    event(new OverdueLoan($loan_request));

    $loan_request->reminded_at = now();
    $loan_request->save();

    return response()->json(['rsp' => []], 200);
  }

  public function manuallyAddLoanRequestTransaction(Request $request, LoanRequest $loan_request)
  {
    $request->validate([
      'amount' => 'required|numeric',
      'transaction_type' => 'required|string|max:15'
    ]);
    DB::beginTransaction();

    $card_user = $loan_request->card_user;
    $next_installment_due_date = $request->transaction_type == 'others' ?
      $loan_request->last_loan_transaction->next_installment_due_date : now()->addMonth();

    if ($request->transaction_type == 'repayment' && $loan_request->loan_balance() < $request->amount) {
      abort(422, 'The amount is more than the loan balance of ' . $loan_request->loan_balance());
    } elseif ($request->transaction_type == 'repayment' && $loan_request->breakdownStatistics()->scheduled_repayment_amount > $request->amount) {
      abort(422, 'The amount is less than the loan scheduled repayment amount of ' . $loan_request->breakdownStatistics()->scheduled_repayment_amount);
    } elseif ($request->transaction_type == 'servicing' && $loan_request->breakdownStatistics()->minimum_repayment_amount != $request->amount) {
      abort(422, 'The amount is more than the loan servicing amount of ' . $loan_request->breakdownStatistics()->minimum_repayment_amount);
    }


    $loan_request->loan_transactions()->create([
      'card_user_id' => $loan_request->card_user_id,
      'amount' => $request->amount,
      'transaction_type' => $request->transaction_type,
      'next_installment_due_date' => $next_installment_due_date
    ]);

    DB::commit();

    event(new ManualLoanTransactionSet($card_user, $request->amount, $request->transaction_type));

    return response()->json(['rsp' => []], 204);
  }

  /**
   * Scope a query to only include loan requests that have not been approved by the admin.
   *
   * @param  \Illuminate\Database\Eloquent\Builder  $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeNotApproved($query)
  {
    return $query->where('approved_at', null);
  }

  /**
   * Scope a query to only include loan requests that have been approved by the admin.
   *
   * @param  \Illuminate\Database\Eloquent\Builder  $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeApproved($query)
  {
    return $query->where('approved_at', '<>', null);
  }

  /**
   * Scope a query to only include loans that have been disbursed to the requester.
   *
   * @param  \Illuminate\Database\Eloquent\Builder  $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeDisbursed($query)
  {
    return $query->where('paid_at', '<>', null);
  }

  /**
   * Scope a query to only include loans based on their fully paid status.
   *
   * @param  \Illuminate\Database\Eloquent\Builder  $query
   * @param bool $state
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeFullyPaid($query, bool $state)
  {
    return $query->where('is_fully_repaid', $state);
  }
}
