<?php

namespace App\Modules\CardUser\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;

class BleytResponse extends Model
{
  protected $fillable = ['bleyt_endpoint', 'supplied_data', 'bleyt_response_status', 'bleyt_response_body', 'is_successful', 'card_user_id'];
  protected $casts = [
    'supplied_data' => 'json',
  ];

  /**
   * Save the response from the bleyt call for further review
   *
   * @param string $endpoint The endpoint we called
   * @param array $dataSupplied The data supplied to the endpoint
   * @param \Illuminate\Http\Client\Response $response The response object from which we will extract the relevant information
   * @param CardUser $cardUser The user that we called this request for
   *
   * @return self
   */
  static function logToDB(string $endpoint, array $dataSupplied, Response $response, CardUser $cardUser = null): self
  {
    return self::create([
      'card_user_id' => $cardUser->id,
      'bleyt_endpoint' => $endpoint,
      'supplied_data' => $dataSupplied,
      'bleyt_response_status' => $response->status(),
      'bleyt_response_body' => $response->body(),
      'is_successful' => $response->successful(),
    ]);
  }
}
