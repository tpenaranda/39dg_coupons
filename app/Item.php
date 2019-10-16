<?php

namespace App;

use Illuminate\Support\Collection;

class Item
{
    public $id;
    public $description;
    public $price;

    public function __construct(array $opts = [])
    {
        $this->id = $opts['id'] ?? null;
        $this->description = $opts['description'] ?? '';
        $this->price = $opts['price'] ?? 0;
    }

    public function newCollection(array $models = []): Collection
    {
        return collect($models);
    }
}

