<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\{Cart, Coupon};
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function getCart(Request $request): CartResource
    {
        return CartResource::make(Cart::get());
    }

    public function putCoupon(Coupon $coupon, Request $request): CartResource
    {
        $cart = Cart::get();

        if (!$coupon->isValidForCart($cart)) {
            throw ValidationException::withMessages(['coupon' => 'Coupon is not applicable on your cart.']);
        }

        $cart->addCoupon($coupon);

        return CartResource::make($cart);
    }
}
