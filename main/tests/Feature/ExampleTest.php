<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\CardUser\Models\DebitCard;
use App\Modules\CardUser\Models\BleytResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
  use RefreshDatabase;

  /**
   * A basic test example.
   *
   * @return void
   */
  public function testCardUserCanAccessListOfBleytBanks()
  {

    $this->withoutExceptionHandling();

    $response = $this->actingAs(factory(CardUser::class)->create(['user_passport' => null]), 'card_user')->get('api/v1/debit-card-transactions/bank-list');

    $response->assertStatus(200);
    $response->assertJsonStructure(['list_of_banks']);
  }

  public function testUserCanResolveAccountName()
  {
    $user = factory(CardUser::class)->create();
    $data = [
      'account_number' => '0004642155',
      'sort_code' => '000014'
    ];

    $response = $this->actingAs($user, 'card_user')->call('GET', route('user.resolve_acc_num'), $data);

    $response->assertOk();
    $response->assertSessionHasNoErrors();
    $response->assertJsonStructure(['account_name']);

    $this->assertCount(1, BleytResponse::all());
  }
}
