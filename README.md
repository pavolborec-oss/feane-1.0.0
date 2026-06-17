# 📖 Feane - Jednoduchá PHP Stránka s OOP

## ✨ O Projekte

Jednoduchá webová stránka reštaurácie vytvorená v **PHP s OOP princípmi**.

## 🏛️ OOP Štruktúra

### Product.php - Trieda produktu
```php
class Product {
    public $id;
    public $name;
    public $price;
    
    public function __construct($id, $name, $price, ...) { }
    public function getDisplay() { }
    public function getFullInfo() { }
}
```

### Menu.php - Trieda menu
```php
class Menu {
    private $products = [];
    
    public function addProduct(Product $product) { }
    public function getAll() { }
    public function getById($id) { }
    public function getByCategory($category) { }
}
```

## 📄 PHP Stránky

- **index.php** - Domovská stránka s produktami
- **menu.php** - Filtrovateľné menu podľa kategórií
- **about.html** - Statická stránka (bez zmien)
- **book.html** - Statická stránka (bez zmien)

## 🚀 Ako Spustiť

1. Otvorte: `http://localhost/feane-1.0.0/index.php`
2. Alebo: `http://localhost/feane-1.0.0/menu.php`

## ✅ Vlastnosti

✅ OOP - Product a Menu triedy  
✅ PHP - Dynamické stránky  
✅ Bez databázy - Vzorové dáta v PHP  
✅ Bez frameworku - Čisté PHP  
✅ Bez CMS - Vlastná implementácia  

---

**Verzia**: 1.0.0  
**Status**: ✅ Hotovo
