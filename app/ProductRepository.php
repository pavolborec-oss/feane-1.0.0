<?php

namespace App;

use PDO;

class ProductRepository
{
    private PDO $pdo;

    public function __construct(Database $database)
    {
        $this->pdo = $database->getConnection();
    }

    public function getAll(): array
    {
        $statement = $this->pdo->query('SELECT * FROM products ORDER BY id ASC');
        return $statement->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $statement = $this->pdo->prepare('SELECT * FROM products WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        $product = $statement->fetch();
        return $product === false ? null : $product;
    }

    public function getByCategory(string $category): array
    {
        $statement = $this->pdo->prepare('SELECT * FROM products WHERE category = :category ORDER BY id ASC');
        $statement->execute(['category' => $category]);
        return $statement->fetchAll();
    }

    public function add(array $data): int
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO products (name, description, price, category, image)
             VALUES (:name, :description, :price, :category, :image)'
        );
        $statement->execute([
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'category' => $data['category'],
            'image' => $data['image'] ?? '',
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $statement = $this->pdo->prepare(
            'UPDATE products SET name = :name, description = :description, price = :price, category = :category, image = :image WHERE id = :id'
        );
        return $statement->execute([
            'id' => $id,
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'category' => $data['category'],
            'image' => $data['image'] ?? '',
        ]);
    }

    public function delete(int $id): bool
    {
        $statement = $this->pdo->prepare('DELETE FROM products WHERE id = :id');
        return $statement->execute(['id' => $id]);
    }
}
