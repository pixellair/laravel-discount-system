# Laravel Discount System

A reusable and extensible Laravel package to handle discount coupons with support for:

- Percentage or fixed amount discounts
- Time-limited discounts
- One-time or limited use
- Per-user usage restriction
- Discounts for specific users
- Order amount-based conditions
- Prefix-based unique coupon codes

> 💡 Easily reusable across Laravel projects via `packages/DiscountSystem`.

---

## 📦 Installation

### Step 1: Clone or copy the package

Place the `DiscountSystem` package inside your Laravel app's `packages/` directory:


### Step 2: Update Composer autoloading

In your Laravel project’s `composer.json`:

```json
"autoload": {
    "psr-4": {
        "App\\": "app/",
        "DiscountSystem\\": "packages/DiscountSystem/src/"
    }
}
