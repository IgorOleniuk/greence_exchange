<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplyTransactionRequest;
use App\Http\Requests\CreateTransactionRequest;
use App\Http\Requests\OpenTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Fee;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionsController extends Controller
{
    /**
     * Retrieve a list of open transaction (except owned by current user)
     *
     * @param OpenTransactionRequest $request
     * @return JsonResource
     */
    public function openTransactions(OpenTransactionRequest $request): JsonResource
    {
        $transactions = Transaction::whereNot([
            ['seller_id', $request->user_id],
            ['status', 'open']
        ])->get();

        return TransactionResource::collection($transactions);
    }

    /**
     * Create new transaction
     *
     * @param  CreateTransactionRequest $request
     * @return JsonResource
     */
    public function createTransaction(CreateTransactionRequest $request): JsonResource
    {
        $transaction = Transaction::create($this->newTransactionData($request));

        return TransactionResource::make($transaction);
    }

    /**
     * Apply for the transaction
     *
     * @param  ApplyTransactionRequest $request
     * @return JsonResource
     */
    public function applyTransaction(ApplyTransactionRequest $request): JsonResource
    {
        $transaction = Transaction::find($request->transaction_id);
        // process exchange between wallets of users
        $this->makeTransferBetweenWallets($transaction, $request->buyer_id);
        // complete transaction
        $transaction->update([
            'buyer_id'  => $request->buyer_id,
            'status'    =>  'closed'
        ]);

        return TransactionResource::make($transaction);
    }

    /**
     * Get System Fees Filtered By Date Range
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function systemFees(Request $request): JsonResponse
    {
        $fees = Fee::when($request->date_from, function($q) use($request)  {
                    $q->where('created_at', '>=', $request->date_from);
                })->when($request->date_to, function($q) use($request)  {
                    $q->where('created_at', '<=', $request->date_to);
                })->groupBy('currency')
                ->selectRaw('currency, sum(amount) as amount')
                ->get();

        return response()->json(['fees' => $fees]);
    }

    private function newTransactionData($request): array
    {
        return [
            'seller_id'          => $request->seller_id,
            'sell_amount'        => $request->sell_amount,
            'sell_currency'      => $request->sell_currency,
            'receiving_amount'   => $request->receiving_amount,
            'receiving_currency' => $request->receiving_currency,
            'status'             => 'open',
        ];
    }

    /**
     * Transfer money between wallets of the seller (creator of transaction) and buyer (applier of transaction)
     */
    private function makeTransferBetweenWallets($transaction, $buyer_id)
    {
        $seller = User::find($transaction->seller_id);
        $buyer = User::find($buyer_id);
        // buyer pays amount with service fee (2%)
        $buyer_pay_with_fee = $transaction->receiving_amount + round($transaction->receiving_amount * 0.02);

        // define wallets of the seller
        $seller_wallet_sell = $seller->wallets()->where('currency', $transaction->sell_currency)->first();
        $seller_wallet_receive = $seller->wallets()->where('currency', $transaction->receiving_currency)->first();
        // define wallets of the buyer
        $buyer_wallet_sell = $buyer->wallets()->where('currency', $transaction->receiving_currency)->first();
        $buyer_wallet_receive = $buyer->wallets()->where('currency', $transaction->sell_currency)->first();

        // update seller wallets
        // decrease transaction amount from seller wallet
        $seller_wallet_sell->amount = $seller_wallet_sell->amount - $transaction->sell_amount;
        $seller_wallet_sell->save();
        // add receive amount to seller wallet
        $seller_wallet_receive->amount = $seller_wallet_receive->amount + $transaction->receiving_amount;
        $seller_wallet_receive->save();

        // update buyer wallets
        // pay for the transaction with service fees from buyer wallet
        $buyer_wallet_sell->amount = $buyer_wallet_sell->amount - $buyer_pay_with_fee;
        $buyer_wallet_sell->save();
        // receive transaction sell to buyer wallet
        $buyer_wallet_receive->amount = $buyer_wallet_receive->amount + $transaction->sell_amount;
        $buyer_wallet_receive->save();
    }
}
