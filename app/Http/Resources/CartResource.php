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
                    'original_total' => number_format($this->items->pluck('price')->sum(), 2, null, false),
                    'type' => $this->coupon->type,
                ];
            }),
            'items' => $this->items->each(function ($i) { $i->price = number_format($i->price, 2, null, false); }),
            'total' => number_format($this->total, 2, null, false),
        ];
    }
}
