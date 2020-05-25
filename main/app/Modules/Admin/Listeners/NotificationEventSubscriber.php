<?php

namespace App\Modules\Admin\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Admin\Models\ActivityLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Modules\Accountant\Events\MerchantLoanPaid;
use App\Modules\CardUser\Notifications\VoucherPaid;
use App\Modules\CardUser\Notifications\VoucherApproved;
use App\Modules\Accountant\Events\MerchantTransactionMarkedAsPaid;
use App\Modules\Accountant\Events\UserApprovesMerchantTransaction;

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
  }



  public function handleMerchantTransactionMarkedPaid($event)
  {
    ActivityLog::notifyAdmins(request()->user()->email . ' marked ' . $event->transaction->merchant->name .
      '´s transaction on voucher ' . $event->transaction->voucher->code . ' for ' . $event->transaction->created_at . ' as paid.');

    ActivityLog::notifyAccountants(request()->user()->email . ' marked ' . $event->transaction->merchant->name .
      '´s transaction on voucher ' . $event->transaction->voucher->code . ' for ' . $event->transaction->created_at . ' as paid.');
  }

  public function handleUserApprovesMerchantTransaction($event)
  {
    ActivityLog::logUserActivity(request()->user()->email . ' approves voucher debit from ' . optional($event->transaction->merchant)->name . '. Voucher Number: ' . optional($event->transaction->voucher)->code);
    ActivityLog::notifyAccountOfficers(request()->user()->email . ' approves voucher debit from ' . optional($event->transaction->merchant)->name . '. Voucher Number: ' . optional($event->transaction->voucher)->code);
    ActivityLog::notifyAdmins(request()->user()->email . ' approves voucher debit from ' . optional($event->transaction->merchant)->name . '. Voucher Number: ' . optional($event->transaction->voucher)->code);

    auth('card_user')->user()->notify(new VoucherApproved(optional($event->transaction->merchant)->name));
  }

  public function handleMerchantLoanPaid($event)
  {
    ActivityLog::logUserActivity(request()->user()->email . ' repays merchant credit. Voucher Number: ' . $event->voucher_code . '. Amount: ' . request('amount'));
    ActivityLog::notifyAccountants(request()->user()->email . ' repays merchant credit. Voucher Number: ' . $event->voucher_code . '. Amount: ' . request('amount'));
    ActivityLog::notifyAccountOfficers(request()->user()->email . ' repays merchant credit. Voucher Number: ' . $event->voucher_code . '. Amount: ' . request('amount'));
    ActivityLog::notifyAdmins(request()->user()->email . ' repays merchant credit. Voucher Number: ' . $event->voucher_code . '. Amount: ' . request('amount'));
    ActivityLog::notifyNormalAdmins(request()->user()->email . ' repays merchant credit. Voucher Number: ' . $event->voucher_code . '. Amount: ' . request('amount'));

    request()->user()->notify(new VoucherPaid(request('amount')));
  }

  public function handleMerchantRequestDebit($event)
  {
    ActivityLog::notifyAdmins($event->merchant->name . ' requests voucher debit from ' . optional($event->voucher->card_user)->email . '. Voucher Number: ' . $event->voucher->code);

    request()->user()->notify(new VoucherPaid(request('amount')));
  }
}
