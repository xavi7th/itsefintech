<?php

namespace App\Modules\Admin\Transformers;

use App\Modules\CardUser\Models\LoanRequest;
use App\Modules\Admin\Models\Admin;
use App\Modules\CardUser\Transformers\LoanTransactionTransformer;

class AdminLoanRequestTransformer
{
  public function collectionTransformer($collection, $transformerMethod)
  {
    return [
      'loan_requests' => $collection->map(function ($v) use ($transformerMethod) {
        return $this->$transformerMethod($v);
      })
    ];
  }
  public function transformForAdminViewLoanRequests(LoanRequest $loan_request)
  {
    $breakdown_statistics = $loan_request->breakdownStatistics();
    return collect([
      'id' => (int)$loan_request->id,
      'amount' => (float)$loan_request->amount,
      'total_duration' => (int)$loan_request->total_duration,
      'due_date' => $loan_request->created_at->addMonths($loan_request->total_duration)->toDateString(),
      'monthly_interest' => (string)$loan_request->monthly_interest . '%',
      'loan_balance' => (float)$loan_request->loan_balance(),
      'is_fully_repaid' => (bool)$loan_request->is_fully_repaid,
      'is_approved' => (bool)$loan_request->approved_at,
      'approved_by' => $loan_request->approved_by ? (new AdminUserTransformer)->transformForAdminViewAdminsBasicDetails(Admin::find($loan_request->approved_by))['full_name'] : null,
      'is_paid' => (bool)$loan_request->paid_at,
      'paid_by' => $loan_request->marked_paid_by ? (new AdminUserTransformer)->transformForAdminViewAdminsBasicDetails(Admin::find($loan_request->marked_paid_by))['full_name'] : null,
      'request_date' => $loan_request->created_at->toDateString(),
      // 'updated_at' => $loan_request->updated_at,
      'requester' => (new AdminUserTransformer)->transformForAdminViewCardUsers($loan_request->card_user)
    ])->merge($breakdown_statistics);
  }

  public function transformForAdminViewLoanRecovery(LoanRequest $loan_request)
  {
    $breakdown_statistics = $loan_request->breakdownStatistics();
    $loan_transactions = (new LoanTransactionTransformer)->collectionTransformer($loan_request->loan_transactions()
      ->where('transaction_type', '<>', 'loan')->get(), 'basicTransform')['loan_transactions'];

    return collect([
      'id' => (int)$loan_request->id,
      'amount' => (float)$loan_request->amount,
      'total_duration' => (int)$loan_request->total_duration,
      'final_due_date' => $loan_request->final_due_date->toDateString(),
      'next_due_date' => $loan_request->next_due_date()->toDateString(),
      'monthly_interest' => (string)$loan_request->monthly_interest . '%',
      'loan_balance' => (float)$loan_request->loan_balance(),
      'amount_paid' => (float)$loan_request->loan_amount_repaid() + $loan_request->total_servicing_fee(),
      'is_approved' => (bool)$loan_request->approved_at,
      'is_expired' => (bool)$loan_request->is_expired,
      'is_due' => (bool)$loan_request->is_due(),
      'approved_by' => $loan_request->approved_by ? (new AdminUserTransformer)->transformForAdminViewAdminsBasicDetails(Admin::find($loan_request->approved_by))['full_name'] : null,
      'is_paid' => (bool)$loan_request->paid_at,
      'paid_by' => $loan_request->marked_paid_by ? (new AdminUserTransformer)->transformForAdminViewAdminsBasicDetails(Admin::find($loan_request->marked_paid_by))['full_name'] : null,
      'request_date' => $loan_request->created_at->toDateString(),
      'needs_reminder' => $loan_request->needs_reminder(),
      'requester' => (new AdminUserTransformer)->transformForLoanRecoveryViewCardUsers($loan_request->card_user),
      'loan_transactions' => $loan_transactions
    ])->merge($breakdown_statistics);
  }
}
