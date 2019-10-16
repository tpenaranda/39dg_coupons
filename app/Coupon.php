<?php

namespace App;

use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Support\Collection;

class Coupon extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'amount',
        'name',
        'rules',
        'type',
    ];

    const TYPE_FIXED = 'fixed';
    const TYPE_PERCENT = 'percent';
    const TYPE_MIXED = 'mixed';
    const TYPE_REJECTED = 'rejected';

    public function getRouteKeyName(): string
    {
        return 'name';
    }

    public function getRulesAttribute(): Collection
    {
        return collect(json_decode($this->attributes['rules'], true));
    }

    public function setRulesAttribute($value): string
    {
        return $this->attributes['rules'] = json_encode($value);
    }

    public function setNameAttribute($value): string
    {
        return $this->attributes['name'] = strtoupper($value);
    }

    public function isValidForCart(Cart $cart): bool
    {
        return $this->rules->every(function ($v, $k) use ($cart) {
            $validatorMethod = 'validate' . ucfirst(camel_case($k));

            return $cart->{$validatorMethod}($v);
        });
    }
}
