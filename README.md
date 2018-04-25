# Easily add Eloquent model pivot events to your Laravel app

The `signifly/laravel-pivot-events` package allows you to easily add Eloquent model pivots events to your Laravel app.

Below is a small example of how to use it.

```php
// Remember to add use statement
use Signifly\PivotEvents\HasPivotEvents;

class User
{
    use HasPivotEvents;
}
```

Now you would be able to listen for the newly available pivot events:

```php
use Signifly\PivotEvents\HasPivotEvents;

class User
{
    use HasPivotEvents;

    protected static function boot()
    {
        static::pivotAttaching(function ($model) {
            // To get related changes
            $model->getPivotChanges();
            // returns all pivot changes
            // [
            //    'attach' => [
            //        'shops' => [
            //            2 => ['scopes' => 'orders'],
            //        ],
            //    ],
            // ]

            // To get related changes for specific type
            $model->getPivotChanges('attach');
            // returns pivot changes for attach
            // [
            //    'shops' => [
            //        2 => ['scopes' => 'orders'],
            //    ],
            // ]
        });

        static::pivotAttached(function ($model) {
            //
        });

        static::pivotDetaching(function ($model) {
            //
        });

        static::pivotDetached(function ($model) {
            //
        });

        static::pivotUpdating(function ($model) {
            //
        });

        static::pivotUpdated(function ($model) {
            //
        });
    }
}
```

You can also use observers for the available events: `pivotAttaching`, `pivotAttached`, `pivotDetaching`, `pivotDetached`, `pivotUpdating`, `pivotUpdated`.

## Documentation
Until further documentation is provided, please have a look at the tests.

## Installation

You can install the package via composer:

```bash
$ composer require signifly/laravel-pivot-events
```

The package will automatically register itself.

## Testing
```bash
$ composer test
```

## Security

If you discover any security issues, please email dev@signifly.com instead of using the issue tracker.

## Credits

- [Morten Poul Jensen](https://github.com/pactode)
- [Travis Elkins](https://github.com/telkins)
- [All contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
