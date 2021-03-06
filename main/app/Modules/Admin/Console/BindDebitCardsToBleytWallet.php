<?php

namespace App\Modules\Admin\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Modules\CardUser\Models\DebitCard;
use App\Modules\CardUser\Models\BleytResponse;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\CardUser\Notifications\BleytActivationFailed;

class BindDebitCardsToBleytWallet extends Command
{
  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'bleyt:activate-debit-cards';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Activate debit cards on bleyt and tie them to bleyt wallets.';
  protected $notification = [];

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    $endpoint1 = config('services.bleyt.issue_card_endpoint');
    $endpoint2 = config('services.bleyt.activate_card_endpoint');

      /**
       * @var DebitCard $debitCard
       */
    foreach (DebitCard::bleytUnactivated()->titaniumBlack()->with('card_user')->get() as $debitCard) {

      /**
       * @var CardUser $cardUser
       */

      $cardUser = $debitCard->card_user;

      /**
       * First supply card customer address
       */

      if (!$cardUser->hasAddress() || !$cardUser->plain_bvn) {
        $cardUser->notify(new BleytActivationFailed());
        continue;
      }

      $dataSupplied1 = [
        'customerId' => $cardUser->bleyt_customer_id,
        'address1' => $cardUser->address . ' ' . $cardUser->city,
        'address2' => $cardUser->address . ' ' . $cardUser->city,
        'bvn' => $cardUser->plain_bvn
      ];

      $dataSupplied2 = [
        'customerId' => $cardUser->bleyt_customer_id,
        'bvn' => $cardUser->plain_bvn,
        'last6' => $debitCard->last6_digits,
      ];

      $response = Http::withToken(config('services.bleyt.secret_key'))->post($endpoint1, $dataSupplied1);
      BleytResponse::logToDB($endpoint1, $dataSupplied1, $response, $cardUser);

      dump([$cardUser->first_name => $response->object()]);

      if ($response->ok()) {
        $response = Http::withToken(config('services.bleyt.secret_key'))->post($endpoint2, $dataSupplied2);
        BleytResponse::logToDB($endpoint2, $dataSupplied2, $response, $cardUser);

        $debitCard->is_bleyt_activated = true;
        $debitCard->save();

        dump([$cardUser->first_name => $response->object()]);
      }
    }
  }
}
