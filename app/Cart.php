<?php

namespace App;

use Cache;

class Cart
{
    public $coupon;
    public $items;
    public $total;

    public function __construct(array $opts = [])
    {
        $this->items = collect($opts['items'] ?? []);
        $this->calculateTotal();
    }

    public function getSample(bool $new = false): self
    {
        if ($new) {
            Cache::put('sample-cart', factory(self::class)->make());
        }

        return Cache::rememberForever('sample-cart', function () {
            return factory(self::class)->make();
        });
    }

    protected function calculateTotal(): self
    {
        $total = $this->items->pluck('price')->sum();

        if (empty($this->coupon)) {
            $this->total = $total;

            return $this;
        }

        $discountMethod = 'getDiscountFor' . ucfirst($this->coupon->type) . 'Coupon';

        $discount = $this->{$discountMethod}();
        $totalWithDiscount = $total - $discount;

        $this->total = $totalWithDiscount < 0 ? 0 : $totalWithDiscount;

        return $this;
    }

    public function removeCoupon(): self
    {
        $this->coupon = null;

        return $this->calculateTotal();
    }

    public function addCoupon(Coupon $coupon): self
    {
        $this->coupon = $coupon;

        return $this->calculateTotal();
    }

    protected function getDiscountForFixedCoupon(): float
    {
        return $this->coupon->amount;
    }

    protected function getDiscountForPercentCoupon(): float
    {
        return $this->total * ($this->coupon->amount / 100);
    }

    protected function getDiscountForMixedCoupon(): float
    {
        return collect([$this->getDiscountForFixedCoupon(), $this->getDiscountForPercentCoupon()])->max();
    }

    protected function getDiscountForRejectedCoupon(): float
    {
        return $this->getDiscountForFixedCoupon() + $this->getDiscountForPercentCoupon();
    }

    public function validateMinItems(int $value): bool
    {
        return $this->items->count() >= $value;
    }

    public function validateMinTotal(int $value): bool
    {
        return $this->total > $value;
    }
}
