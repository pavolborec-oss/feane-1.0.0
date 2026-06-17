<?php

namespace App;

class ProductSeeder
{
    public static function seedFromJson(ProductRepository $repo, string $jsonPath): void
    {
        if (!file_exists($jsonPath)) {
            return;
        }

        $json = file_get_contents($jsonPath);
        $data = json_decode($json, true);
        if (!is_array($data)) {
            return;
        }

        foreach ($data as $item) {
            $repo->add([
                'name' => $item['name'] ?? '',
                'description' => $item['description'] ?? '',
                'price' => $item['price'] ?? 0,
                'category' => $item['category'] ?? '',
                'image' => $item['image'] ?? '',
            ]);
        }
    }
}
