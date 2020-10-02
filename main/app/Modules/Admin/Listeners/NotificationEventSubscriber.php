<?php

namespace App\Modules\Admin\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Admin\Models\ActivityLog;
use App\Modules\Admin\Events\LoanDisbursed;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Modules\CardUser\Events\OverdueLoan;
use App\Modules\CardUser\Events\LoanRequested;
use App\Modules\Admin\Events\UserCreditLimitSet;
use App\Modules\Admin\Events\LoanRequestApproved;
use App\Modules\Admin\Notifications\LoanApproved;
use App\Modules\Admin\Notifications\LoanModified;
use App\Modules\Admin\Events\UserMerchantLimitSet;
use App\Modules\Admin\Notifications\LoanProcessed;
use App\Modules\CardUser\Notifications\BVNUpdated;
use App\Modules\Accountant\Events\MerchantLoanPaid;
use App\Modules\CardUser\Events\UserProfileUpdated;
use App\Modules\CardUser\Notifications\LoanOverdue;
use App\Modules\CardUser\Notifications\VoucherPaid;
use App\Modules\Admin\Events\UserVoucherManualDebit;
use App\Modules\CardUser\Notifications\ProfileEdited;
use App\Modules\Admin\Events\ManualLoanTransactionSet;
use App\Modules\CardUser\Notifications\VoucherDebited;
use App\Modules\CardUser\Notifications\VoucherApproved;
use App\Modules\CardUser\Notifications\DebitCardActivated;
use App\Modules\CardUser\Notifications\DebitCardRequested;
use App\Modules\Accountant\Events\MerchantTransactionMarkedAsPaid;
use App\Modules\Accountant\Events\UserApprovesMerchantTransaction;
use App\Modules\Accountant\Events\DebitCardActivated as DebitCardActivatedEvent;
use App\Modules\Accountant\Events\DebitCardRequested as DebitCardRequestedEvent;
use App\Modules\CardUser\Notifications\LoanRequested as LoanRequestedNotification;
use App\Modules\CardUser\Notifications\UserCreditLimitSet as UserCreditLimitSetNotification;

class NotificationEventSubscriber
{

  /**
   * Register the listeners for the subscriber.
   *
   * @param  \Illuminate\Events\Dispatcher  $events
   */
  public function subscribe($events)
  {
    $events->listen(
      UserProfileUpdated::class,
      'App\Modules\Admin\Listeners\NotificationEventSubscriber@handleUserProfileUpdated'
    );

    $events->listen(
      UserBVNUpdated::class,
      'App\Modules\Admin\Listeners\NotificationEventSubscriber@handleUserBVNUpdated'
    );

    $events->listen(
      DebitCardRequestedEvent::class,
      'App\Modules\Admin\Listeners\NotificationEventSubscriber@handleDebitCardRequested'
    );

    $events->listen(
      DebitCardActivatedEvent::class,
      'App\Modules\Admin\Listeners\NotificationEventSubscriber@handleDebitCardActivated'
    );

    $events->listen(
      MerchantTransactionMarkedAsPaid::class,
      'App\Modules\Admin\Listeners\NotificationEventSubscriber@handleMerchantTransactionMarkedPaid'
    );

    $events->listen(
      UserApprovesMerchantTransaction::class,
      'App\Modules\Admin\Listeners\NotificationEventSubscriber@handleUserApprovesMerchantTransaction'
    );

    $events->listen(
      MerchantLoanPaid::class,
      'App\Modules\Admin\Listeners\NotificationEventSubscriber@handleMerchantLoanPaid'
    );

    $events->listen(
      MerchantRequestDebit::class,
      'App\Modules\Admin\Listeners\NotificationEventSubscriber@handleMerchantRequestDebit'
    );

    $events->listen(
      UserVoucherManualDebit::class,
      'App\Modules\Admin\Listeners\NotificationEventSubscriber@handleUserVoucherManualDebit'
    );

    $events->listen(
      UserCreditLimitSet::class,
      'App\Modules\Admin\Listeners\NotificationEventSubscriber@handleUserCreditLimitSet'
    );

    $events->listen(
      UserMerchantLimitSet::class,
      'App\Modules\Admin\Listeners\NotificationEventSubscriber@handleUserMerchantLimitSet'
    );

    $events->listen(
      LoanRequestApproved::class,
      'App\Modules\Admin\Listeners\NotificationEventSubscriber@handleLoanRequestApproved'
    );

    $events->listen(
      LoanRequested::class,
      'App\Modules\Admin\Listeners\NotificationEventSubscriber@handleLoanRequested'
    );

    $events->listen(
      LoanDisbursed::class,
      'App\Modules\Admin\Listeners\NotificationEventSubscriber@handleLoanDisbursed'
    );

    $events->listen(
      ManualLoanTransactionSet::class,
      'App\Modules\Admin\Listeners\NotificationEventSubscriber@handleManualLoanTransactionSet'
    );

    $events->listen(
      ManualLoanTransactionSet::class,
      'App\Modules\Admin\Listeners\NotificationEventSubscriber@handleManualLoanTransactionSet'
    );

    $events->listen(
      OverdueLoan::class,
      'App\Modules\Admin\Listeners\NotificationEventSubscriber@handleManualOverdueLoan'
    );
  }



  public function handleUserProfileUpdated($event)
  {
    $event->cardUser->notify(new ProfileEdited);
  }

  public function handleUserBVNUpdated($event)
  {
    $event->cardUser->notify(new BVNUpdated);
  }

  public function handleDebitCardRequested($event)
  {

    ActivityLog::logUserActivity('You successfully paid for a ' . $event->debit_card_type . ' debit card');
    ActivityLog::notifyCardAdmins(request()->user()->email . ' successfully paid for a ' . $event->debit_card_type . ' debit card');
    ActivityLog::notifyAdmins(request()->user()->email . ' successfully paid for a ' . $event->debit_card_type . ' debit card');
    ActivityLog::notifyAccountOfficers(request()->user()->email . ' successfully paid for a ' . $event->debit_card_type . ' debit card');
    ActivityLog::notifyNormalAdmins(request()->user()->email . ' successfully paid for a ' . $event->debit_card_type . ' debit card');

    try {
      request()->user()->notify(new DebitCardRequested($event->debit_card_type));
    } catch (\Throwable $th) {
      ActivityLog::notifyAdmins('New Debit Card request alert not sent to ' . request()->user()->email . ' because ' . $th->getMessage());
      ActivityLog::notifyNormalAdmins('New Debit Card request alert not sent to ' . request()->user()->email . ' because ' . $th->getMessage());
    }
  }


  public function handleDebitCardActivated($event)
  {
    ActivityLog::logUserActivity(request()->user()->email . ' has just successfully activated his new credit card');
    ActivityLog::notifyCardAdmins(request()->user()->email . ' has just successfully activated his new credit card');
    ActivityLog::notifyAccountOfficers(request()->user()->email . ' has just successfully activated his new credit card');

    request()->user()->notify(new DebitCardActivated);
  }

  public function handleMerchantTransactionMarkedPaid($event)
  {
    $message = request()->user()->email . ' marked ' . $event->transaction->merchant->name .
      '´s transaction on voucher ' . $event->transaction->voucher->code . ' for ' . $event->transaction->created_at . ' as paid.';

    ActivityLog::notifyAdmins($message);
    ActivityLog::notifyAccountants($message);
  }

  public function handleUserApprovesMerchantTransaction($event)
  {
    $message = request()->user()->email . ' approves voucher debit from ' . optional($event->transaction->merchant)->name . '. Voucher Number: ' . optional($event->transaction->voucher)->code;

    ActivityLog::logUserActivity($message);
    ActivityLog::notifyAccountOfficers($message);
    ActivityLog::notifyAdmins($message);

    auth('card_user')->user()->notify(new VoucherApproved(optional($event->transaction->merchant)->name));
  }

  public function handleMerchantLoanPaid($event)
  {
    $message = request()->user()->email . ' repays merchant credit. Voucher Number: ' . $event->voucher_code . '. Amount: ' . to_naira(request('amount'));

    ActivityLog::logUserActivity($message);
    ActivityLog::notifyAccountants($message);
    ActivityLog::notifyAccountOfficers($message);
    ActivityLog::notifyAdmins($message);
    ActivityLog::notifyNormalAdmins($message);

    request()->user()->notify(new VoucherPaid(request('amount')));
  }

  public function handleMerchantRequestDebit($event)
  {
    ActivityLog::notifyAdmins($event->merchant->name . ' requests voucher debit from ' . optional($event->voucher->card_user)->email . '. Voucher Number: ' . $event->voucher->code);

    request()->user()->notify(new VoucherPaid(request('amount')));
  }

  public function handleUserVoucherManualDebit($event)
  {
    $message = request()->user()->email . ' manually debited ' . to_naira($event->amount) . ' from ' .
      $event->card_user->email . '´s voucher with code ' . $event->voucher_code . ' on behalf of merchant: ' . $event->merchant_name;

    ActivityLog::notifyAdmins($message);
    ActivityLog::notifyAccountants($message);
    ActivityLog::notifyAccountOfficers($message);

    try {
      request()->user()->notify(new VoucherDebited(request('amount'), $event->voucher_code, $event->merchant_name));
    } catch (\Throwable $th) { }
  }

  public function handleUserCreditLimitSet($event)
  {
    $message = $event->card_user->email . '´s credit limit updated to ' . to_naira($event->amount) . ' at interest rate of '
      . $event->interest_rate . '% by ' . request()->user()->email;

    ActivityLog::notifyAccountOfficers($message);
    ActivityLog::notifyAdmins($message);

    try {
      $event->card_user->notify(new UserCreditLimitSetNotification($event->card_user, $event->amount, $$event->interest_rate));
    } catch (\Throwable $th) {
    }
  }

  public function handleUserMerchantLimitSet($event)
  {
    $message = $event->card_user->email . '´s merchant limit updated to ' . to_naira($event->amount) . ' at interest rate of '
      . $event->interest_rate . '% by ' . request()->user()->email;

    ActivityLog::notifyAccountOfficers($message);
    ActivityLog::notifyAdmins($message);
  }

  public function handleLoanRequestApproved($event)
  {
    $message = request()->user()->email . ' approved a loan request of ' . to_naira($event->amount) . ' for ' . $event->card_user->email;

    ActivityLog::notifyAccountOfficers($message);
    ActivityLog::notifyAccountants($message);
    ActivityLog::notifyAdmins($message);
    ActivityLog::notifyNormalAdmins($message);

    $event->card_user->notify(new LoanApproved($event->amount, $event->is_school_fees));
  }

  public function handleLoanRequested($event)
  {
    if ($event->is_school_fees_loan) {
      $message = request()->user()->email . ' made a school fees loan request of ' . to_naira($event->amount);
    } else {
      $message = request()->user()->email . ' made a loan request of ' . to_naira($event->amount);
    }

    ActivityLog::logUserActivity($message);
    ActivityLog::notifyAccountOfficers($message);
    ActivityLog::notifyAccountants($message);
    ActivityLog::notifyAdmins($message);
    ActivityLog::notifyNormalAdmins($message);
    auth()->user()->notify(new LoanRequestedNotification($event->amount, $event->is_school_fees_loan));
  }

  public function handleLoanDisbursed($event)
  {
    $message = request()->user()->email . ' marked ' .  $event->card_user->email . '\'s loan request of ' . to_naira($event->amount) . ' as paid.';

    ActivityLog::notifyAccountOfficers($message);
    ActivityLog::notifyAccountants($message);
    ActivityLog::notifyAdmins($message);
    ActivityLog::notifyNormalAdmins($message);

    $event->card_user->notify(new LoanProcessed($event->amount, $event->is_school_fees_loan));
  }

  public function handleManualLoanTransactionSet($event)
  {
    $message = request()->user()->email . ' manually added a loan transaction for ' .  $event->card_user->email . ' of amount ' .
      to_naira($event->amount) . ' as a ' . $event->transaction_type . ' transaction. The user´s loan balance has been adjusted accordingly.';

    ActivityLog::notifyAccountOfficers($message);
    ActivityLog::notifyAccountants($message);
    ActivityLog::notifyAdmins($message);
    ActivityLog::notifyNormalAdmins($message);

    $event->card_user->notify(new LoanModified($event->amount));
  }

  public function handleManualOverdueLoan($event)
  {
    $message = 'Loan over due reminder sent to ' .  $event->loan_request->card_user->email;

    ActivityLog::notifyAccountOfficers($message);
    ActivityLog::notifyAccountants($message);
    ActivityLog::notifyAdmins($message);

    $event->loan_request->card_user->notify(new LoanOverdue($event->loan_request));
  }
}
