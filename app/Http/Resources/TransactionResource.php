<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                 => $this->id,
            'seller_id'          => $this->seller_id,
            'sell_amount'        => $this->sell_amount,
            'sell_currency'      => $this->sell_currency,
            'receiving_amount'   => $this->receiving_amount,
            'receiving_currency' => $this->receiving_currency,
            'status'             => $this->status,
            'buyer_id'           => $this->buyer_id
        ];
    }
}
