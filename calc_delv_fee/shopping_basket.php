<?php

class Product
{
    private string $code;
    private string $name;
    private float $price;

    public function __construct(string $code, string $name, float $price)
    {
        $this->code = $code;
        $this->name = $name;
        $this->price = $price;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}

class DeliveryCalculator
{
    private const THRESHOLD_FREE = 90.00;
    private const THRESHOLD_MEDIUM = 50.00;
    private const CHARGE_HIGH = 4.95;
    private const CHARGE_MEDIUM = 2.95;
    private const CHARGE_FREE = 0.00;

    public function calculateDeliveryCharge(float $subtotal): float
    {
        if ($subtotal >= self::THRESHOLD_FREE) {
            return self::CHARGE_FREE;
        }
        if ($subtotal >= self::THRESHOLD_MEDIUM) {
            return self::CHARGE_MEDIUM;
        }
        return self::CHARGE_HIGH;
    }
}

class OfferCalculator
{
    public function applyRedWidgetOffer(array $products): float
    {
        $redWidgets = array_filter($products, fn($product) => $product->getCode() === 'R01');
        $redWidgetCount = count($redWidgets);
        
        if ($redWidgetCount < 2) {
            return 0;
        }

        $discount = floor($redWidgetCount / 2) * (32.95 / 2);
        return $discount;
    }
}

class Basket
{
    private array $products = [];
    private array $catalogue;
    private DeliveryCalculator $deliveryCalculator;
    private OfferCalculator $offerCalculator;

    public function __construct(
        array $catalogue,
        DeliveryCalculator $deliveryCalculator,
        OfferCalculator $offerCalculator
    ) {
        $this->catalogue = $catalogue;
        $this->deliveryCalculator = $deliveryCalculator;
        $this->offerCalculator = $offerCalculator;
    }

    public function add(string $productCode): void
    {
        if (!isset($this->catalogue[$productCode])) {
            throw new InvalidArgumentException("Product code not found: $productCode");
        }
        $this->products[] = $this->catalogue[$productCode];
    }

    public function total(): float
    {
        $subtotal = array_sum(array_map(fn($product) => $product->getPrice(), $this->products));
        $discount = $this->offerCalculator->applyRedWidgetOffer($this->products);
        $deliveryCharge = $this->deliveryCalculator->calculateDeliveryCharge($subtotal - $discount);
        
        return round($subtotal - $discount + $deliveryCharge, 2);
    }
}

// Initialize the catalogue
$catalogue = [
    'R01' => new Product('R01', 'Red Widget', 32.95),
    'G01' => new Product('G01', 'Green Widget', 24.95),
    'B01' => new Product('B01', 'Blue Widget', 7.95),
];

// Create dependencies
$deliveryCalculator = new DeliveryCalculator();
$offerCalculator = new OfferCalculator();

// Test cases
$testCases = [
    ['B01', 'G01'],
    ['R01', 'R01'],
    ['R01', 'G01'],
    ['B01', 'B01', 'R01', 'R01', 'R01']
];

// Run tests
foreach ($testCases as $products) {
    $basket = new Basket($catalogue, $deliveryCalculator, $offerCalculator);
    
    echo "Test case: " . implode(', ', $products) . "\n";
    
    foreach ($products as $productCode) {
        $basket->add($productCode);
    }
    
    echo "Total: $" . number_format($basket->total(), 2) . "\n\n";
}