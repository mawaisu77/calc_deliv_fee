# Shopping Basket Implementation

This is a simple shopping basket implementation in PHP that demonstrates object-oriented programming principles and includes features like delivery charge calculation and special offers.

## Project Structure

- `shopping_basket.php` - Contains all the classes and test cases

## Classes

### Product
Represents a product with properties:
- `code` (string): Unique product identifier
- `name` (string): Product name
- `price` (float): Product price

### DeliveryCalculator
Calculates delivery charges based on subtotal:
- Free delivery (£0.00) for orders £90 and above
- £2.95 delivery for orders £50 and above
- £4.95 delivery for orders under £50

### OfferCalculator
Handles special offers:
- Currently implements "Red Widget" offer: buy one red widget, get the second half price

### Basket
Main shopping basket class that:
- Maintains list of products
- Calculates total price including:
  - Product costs
  - Applicable discounts
  - Delivery charges

## Assumptions
1. Product Codes:
   - Product codes are unique strings
   - Product codes are case-sensitive
   - Only predefined products in the catalogue can be added to basket
2. Pricing:
   - All prices are positive numbers
   - Prices are stored and calculated with 2 decimal places
3. Special Offers:
   - Red Widget offer applies to pairs (every 2nd item half price)
   - Multiple offers can be applied to the same order
   - Delivery charge is calculated after discounts are applied
5. Basket:
   - No limit on quantity of items
   - Items can be added but not removed
   - Same product can be added multiple times


