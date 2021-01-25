<?php

namespace App\Modules\Admin\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\CardUser\Models\BleytResponse;

class CreateBleytWallets extends Command
{
  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'bleyt:create-wallets';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Create bleyt wallets for those that don´t have.';
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
    // dd(CardUser::withBleytAccount()->count());
      // ray()->clearScreen();

    $endpoint = config('services.bleyt.check_card_balance_endpoint');
    // $endpoint = config('services.bleyt.create_wallet_endpoint');

    /**
      * @var CardUser $cardUser
      */
    // foreach (CardUser::withoutBleytAccount()->cursor() as $cardUser) {
    foreach (CardUser::withBleytAccount()->cursor() as $cardUser) {

      if (!$cardUser->first_debit_card) {
        continue;
      }

      // $dataSupplied = [
      //   'firstName' => $cardUser->first_name,
      //   'lastName' => $cardUser->last_name,
      //   'email' => $cardUser->email,
      //   'phoneNumber' => $cardUser->phone,
      //   'dateOfBirth' => $cardUser->date_of_birth ?? now()->subYears(20)->toDateString(),
      //   'bvn' => $cardUser->plain_bvn ?? '',
      // ];
      $dataSupplied = [
        'customerId' => $cardUser->bleyt_customer_id
      ];

      // $response = Http::withToken(config('services.bleyt.secret_key'))->post($endpoint, $dataSupplied);
      $response = Http::withToken(config('services.bleyt.secret_key'))->get($endpoint, $dataSupplied);

      // if ($response->ok()) {
      //   $receivedDetails = $response->object();
      //   $cardUser->bleyt_customer_id = $receivedDetails->customer->id;
      //   $cardUser->first_debit_card->bleyt_wallet_id = $receivedDetails->wallet->id;
      //   $cardUser->first_debit_card->save();
      //   $cardUser->save();
      // }
      if ($response->ok()) {
        $receivedDetails = $response->object();
        dump($receivedDetails->wallet->bookedBalance, $receivedDetails->wallet->availableBalance);
      }

      BleytResponse::logToDB($endpoint, $dataSupplied, $response, $cardUser);

      dump($response->object(), $response->status());
    }
  }
}
