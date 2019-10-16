<?php

namespace App\Http\Resources;

use App\Cart;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'coupon' => $this->when(!empty($this->coupon), function () {
                return [
                    'amount' => $this->coupon->amount,
                    'name' => $this->coupon->name,
                    'original_total' => $this->items->pluck('price')->sum(),
                    'type' => $this->coupon->type,
                ];
            }),
            'items' => $this->items->each(function ($i) { $i->price = number_format($i->price, 2); }),
            'total' => number_format($this->total, 2),
        ];
    }
}
