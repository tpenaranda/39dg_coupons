<?php

namespace Tests\Feature;

use App\{Cart, Item, Coupon};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartTest extends TestCase
{
    public function testAddCoupon()
    {
        $cart = factory(Cart::class)->make([
            'items' => factory(Item::class, 2)->make(['price' => 25]),
        ]);

        $coupon = factory(Coupon::class)->make(['amount' => 25, 'rules' => [], 'type' => 'percent']);

        $this->assertEmpty($cart->coupon);

        $cart->addCoupon($coupon);

        $this->assertNotEmpty($cart->coupon);
        $this->assertEquals(37.5, $cart->total);
    }

    public function testGetDiscountForFixedCoupon()
    {
        $cart = factory(Cart::class)->make([
            'items' => factory(Item::class, 2)->make(['price' => 25]),
        ])->addCoupon(factory(Coupon::class)->make(['amount' => 10, 'rules' => [], 'type' => 'fixed']));

        $this->assertEquals(40, $cart->total);
    }
    public function testGetDiscountForPercentCoupon()
    {
        $cart = factory(Cart::class)->make([
            'items' => factory(Item::class, 2)->make(['price' => 25]),
        ])->addCoupon(factory(Coupon::class)->make(['amount' => 25, 'rules' => [], 'type' => 'percent']));

        $this->assertEquals(37.5, $cart->total);
    }

    public function testGetDiscountForMixedCoupon()
    {
        $coupon = factory(Coupon::class)->make(['amount' => 10, 'rules' => [], 'type' => 'mixed']);

        $cart = factory(Cart::class)->make([
            'items' => factory(Item::class, 2)->make(['price' => 25]),
        ])->addCoupon($coupon);

        $this->assertEquals(40, $cart->total);

        $cart = factory(Cart::class)->make([
            'items' => factory(Item::class, 2)->make(['price' => 100]),
        ])->addCoupon($coupon);

        $this->assertEquals(180, $cart->total);
    }

    public function testGetDiscountForRejectedCoupon()
    {
        $cart = factory(Cart::class)->make([
            'items' => factory(Item::class, 2)->make(['price' => 1000]),
        ])->addCoupon(factory(Coupon::class)->make(['amount' => 20, 'rules' => [], 'type' => 'rejected']));

        $this->assertEquals(1580, $cart->total);
    }

    public function testValidateMinItems()
    {
        $cart = factory(Cart::class)->make([
            'items' => factory(Item::class, 2)->make(['price' => 1000]),
        ]);

        $this->assertTrue($cart->validateMinItems(1));
        $this->assertTrue($cart->validateMinItems(2));
        $this->assertFalse($cart->validateMinItems(3));
    }

    public function testValidateMinTotal()
    {
        $cart = factory(Cart::class)->make([
            'items' => factory(Item::class, 3)->make(['price' => 100]),
        ]);

        $this->assertTrue($cart->validateMinTotal(299));
        $this->assertFalse($cart->validateMinTotal(300));
        $this->assertFalse($cart->validateMinTotal(301));
    }

}