<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\{Cart, Coupon};
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    protected $cartClass;

    public function __construct(Cart $cartClass)
    {
        $this->cartClass = $cartClass;
    }

    public function getCart(Request $request): CartResource
    {
        return CartResource::make($this->cartClass->getSample());
    }

    public function putCoupon(Coupon $coupon, Request $request): CartResource
    {
        $cart = $this->cartClass->getSample();

        if (!$coupon->isValidForCart($cart)) {
            throw ValidationException::withMessages(['coupon' => 'Coupon is not applicable on your cart.']);
        }

        return CartResource::make($cart->addCoupon($coupon));
    }
}
