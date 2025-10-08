# Stripe Integration Package for Laravel

A no-code Stripe payment integration package for Laravel that provides a complete payment solution with minimal setup.

## Features

- 🚀 **No-code setup** - Install and configure with simple Artisan commands
- 💳 **Complete payment flow** - Payment forms, processing, success/cancel pages
- 📱 **Responsive UI** - Beautiful Bootstrap-based payment forms
- 🔒 **Secure** - Built-in CSRF protection and secure payment handling
- 📊 **Payment tracking** - Database storage for all payment records
- 🎨 **Customizable** - Easily customize views and styling
- ⚡ **Laravel 10, 11, 12 support** - Compatible with latest Laravel versions

## Requirements

- PHP ^8.1
- Laravel ^10.0|^11.0|^12.0
- Stripe PHP SDK (automatically suggested)

## Installation

### 1. Install via Composer

```bash
composer require alamin/easy-stripe
```

### 2. Run Installation Command

```bash
php artisan easy-stripe:install
```

### 3. Run Migrate Command

```bash
php artisan migrate
```

This command will:
- Publish configuration files
- Publish and run database migrations
- Publish views to `resources/views/stripe-integration/`
- Copy controller and model files to your app
- Add routes to `routes/web.php`

## Configuration

### 1. Environment Variables

Add your Stripe keys to your `.env` file:

```env
STRIPE_PUBLISHABLE_KEY=pk_test_your_publishable_key
STRIPE_SECRET_KEY=sk_test_your_secret_key
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret
```

### 2. Configuration File

The package publishes a configuration file at `config/easy-stripe.php`:

```php
return [
    'stripe' => [
        'publishable_key' => env('STRIPE_PUBLISHABLE_KEY'),
        'secret_key' => env('STRIPE_SECRET_KEY'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],
    'currency' => env('STRIPE_CURRENCY', 'usd'),
    'routes' => [
        'prefix' => 'stripe',
        'middleware' => ['web'],
    ],
];
```

## Usage

### Available Routes

After installation, the following routes will be available:

```
GET  /stripe                    - Payment form
POST /stripe/payment           - Process payment
GET  /stripe/success           - Payment success page
GET  /stripe/cancel            - Payment cancelled page
GET  /stripe/payments          - List all payments
```

### Basic Payment Flow

1. **Payment Form**: Visit `/stripe` to see the payment form
2. **Process Payment**: Form submits to `/stripe/payment`
3. **Success/Cancel**: User redirected to success or cancel page
4. **View Payments**: Admin can view all payments at `/stripe/payments`

### Customization

#### Views

All views are published to `resources/views/stripe-integration/`:

```
resources/views/stripe-integration/
├── layouts/
│   └── app.blade.php           # Main layout
├── stripe-payment/
│   ├── index.blade.php         # Payment form
│   ├── success.blade.php       # Success page
│   ├── cancel.blade.php        # Cancel page
│   └── list.blade.php          # Payments list
```

#### Controller

The `StripePaymentController` is copied to `app/Http/Controllers/` and can be customized:

```php
// app/Http/Controllers/StripePaymentController.php
class StripePaymentController extends Controller
{
    public function index()
    {
        return view('stripe-integration.stripe-payment.index');
    }
    
    public function processPayment(Request $request)
    {
        // Payment processing logic
    }
    
    // ... other methods
}
```

#### Model

The `StripePayment` model is copied to `app/Models/` for payment tracking:

```php
// app/Models/StripePayment.php
class StripePayment extends Model
{
    protected $fillable = [
        'stripe_payment_intent_id',
        'amount',
        'currency',
        'status',
        'customer_email',
        'customer_name',
        'metadata',
    ];
}
```

### Database Schema

The package creates a `stripe_payments` table with the following structure:

```php
Schema::create('stripe_payments', function (Blueprint $table) {
    $table->id();
    $table->string('stripe_payment_intent_id')->unique();
    $table->integer('amount'); // Amount in cents
    $table->string('currency', 3)->default('usd');
    $table->string('status');
    $table->string('customer_email')->nullable();
    $table->string('customer_name')->nullable();
    $table->json('metadata')->nullable();
    $table->timestamps();
});
```

## Advanced Usage

### Custom Payment Amounts

You can pass custom amounts via URL parameters:

```
/stripe?amount=2000&currency=usd&description=Custom Product
```

### Webhook Handling

To handle Stripe webhooks, add the webhook endpoint to your Stripe dashboard:

```
https://yourdomain.com/stripe/webhook
```

### Custom Styling

The package includes Bootstrap 5 styling by default. You can customize the CSS in the layout file:

```blade
{{-- resources/views/stripe-integration/layouts/app.blade.php --}}
<style>
    .payment-card {
        background: white;
        border-radius: 15px;
        /* Your custom styles */
    }
</style>
```

## Security

- All forms include CSRF protection
- Stripe keys should be kept in environment variables
- Payment processing uses Stripe's secure API
- No sensitive card data is stored locally

## Testing

You can test payments using Stripe's test card numbers:

```
Card Number: 4242424242424242
Expiry: Any future date
CVC: Any 3 digits
```

## Troubleshooting

### Common Issues

1. **Views not found**: Ensure views are published to `resources/views/stripe-integration/`
2. **Routes not working**: Check that routes are added to `routes/web.php`
3. **Stripe errors**: Verify your API keys in `.env` file
4. **Database errors**: Run `php artisan migrate` to ensure tables exist


## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## Support

For support, please open an issue on the GitHub repository or contact [alaminbubt85@gmail.com](mailto:alaminbubt85@gmail.com)

## Changelog

### Version 1.0.0
- Initial release
- Basic payment processing
- Bootstrap UI
- Database tracking
- Laravel 10, 11, 12 support
