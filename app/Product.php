<?php

namespace App;

class Product
{
    public $id;
    public $name;
    public $description;
    public $price;
    public $category;
    public $image;

    public function __construct($id, $name, $description, $price, $category, $image = "")
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->category = $category;
        $this->image = $image;
    }

    public function getDisplay()
    {
        return "<strong>{$this->name}</strong> - €{$this->price}";
    }

    public function getFullInfo()
    {
        return "ID: {$this->id} | {$this->name} | {$this->description} | €{$this->price}";
    }
}
