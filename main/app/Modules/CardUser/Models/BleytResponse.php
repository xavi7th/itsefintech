<?php

namespace App\Modules\CardUser\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;

/**
 * App\Modules\CardUser\Models\BleytResponse
 *
 * @property int $id
 * @property int $card_user_id
 * @property string $bleyt_endpoint
 * @property array|null $supplied_data
 * @property int|null $bleyt_response_status
 * @property string|null $bleyt_response_body
 * @property int|null $is_successful
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BleytResponse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BleytResponse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BleytResponse query()
 * @method static \Illuminate\Database\Eloquent\Builder|BleytResponse whereBleytEndpoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BleytResponse whereBleytResponseBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BleytResponse whereBleytResponseStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BleytResponse whereCardUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BleytResponse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BleytResponse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BleytResponse whereIsSuccessful($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BleytResponse whereSuppliedData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BleytResponse whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
