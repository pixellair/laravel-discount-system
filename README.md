# 🎟️ Laravel Discount System

A reusable, Composer-installable **coupon-based discount system** for Laravel projects.

Easily integrates into any Laravel app to provide:

- 🎯 Percentage or fixed amount discounts
- ⏳ Time-limited discounts
- 🔂 One-time use or limited usage
- 👤 Per-user restrictions (only once per user)
- 👥 Coupons for specific users only
- 💵 Minimum order amount validation
- 🧠 Smart code generation with type prefixes

---

## 🚀 Installation

### 1. Require via Composer

```
composer require yourvendor/discount-system
```
### 2. Publish and Run Migrations
To create the necessary tables for storing discounts and their usage:

```
php artisan vendor:publish --tag=discount-system-migrations
php artisan migrate
```

### 🔧 Setup (If Needed)
If you're using Laravel 8+ with package auto-discovery, no setup is needed.

If not, register the service provider manually in config/app.php:

```
'providers' => [
    DiscountSystem\DiscountSystemServiceProvider::class,
],
```
###⚙️ Usage
Apply a Coupon to an Order
```
use DiscountSystem\Services\DiscountService;

$service = new DiscountService();

$result = $service->apply($couponCode, $userId, $orderAmount);

$discountAmount = $result['amount'];
$discount = $result['discount'];
```
Record a Usage After Applying
````
$service->recordUsage($discount, $userId);
````
### 🧪 Validations Performed in apply()
✅ Coupon exists

⏳ Coupon is active (start & end time)

🔂 Usage limit not exceeded

👤 Coupon not already used by this user (if once_per_user)

👥 User is in allowed list (if specific_user_ids set)

💵 Order meets min_order_amount requirement

If any condition fails, a ValidationException is thrown with a meaningful message.

🧾 Code Generation
Generate a prefixed coupon code for any type:
````
$code = $discountService->generateCode('T'); // e.g., T7K92LA
````
The prefix lets you categorize discounts (e.g., T for time-limited, A for amount-based).

💡 Example Controller Usage
````
use DiscountSystem\Services\DiscountService;

public function applyCoupon(Request $request)
{
    $service = new DiscountService();

    try {
        $result = $service->apply($request->code, auth()->id(), $order->total);

        $order->discount_amount = $result['amount'];
        $order->save();

        $service->recordUsage($result['discount'], auth()->id());

        return response()->json(['success' => true, 'discount' => $result['amount']]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json(['error' => $e->getMessage()], 422);
    }
}
````
