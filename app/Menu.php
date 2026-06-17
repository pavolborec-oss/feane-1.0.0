<?php

namespace App;

class Menu
{
    private $products = [];

    public function addProduct(Product $product)
    {
        $this->products[] = $product;
    }

    public function getAll()
    {
        return $this->products;
    }

    public function getById($id)
    {
        foreach ($this->products as $product) {
            if ($product->id == $id) {
                return $product;
            }
        }
        return null;
    }

    public function getByCategory($category)
    {
        $result = [];
        foreach ($this->products as $product) {
            if ($product->category == $category) {
                $result[] = $product;
            }
        }
        return $result;
    }

    public function getCategories()
    {
        $categories = [];
        foreach ($this->products as $product) {
            if (!in_array($product->category, $categories, true)) {
                $categories[] = $product->category;
            }
        }
        return $categories;
    }

    public function count()
    {
        return count($this->products);
    }
}
