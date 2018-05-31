# General Config

The config items below can be placed into a `stripe-checkout.php` file in your `craft/config` directory, this is a handy way to have different settings across multiple environments.

### `pluginNameOverride`

The plugin name as you’d like it to be displayed in the CP.

### `accountMode`

Whether you want to use the `test` or `live` Stripe credentials.

### `testPublishableKey`

Your Stripe test publishable key. See [API Keys](https://stripe.com/docs/keys).

### `testSecretKey`

Your Stripe test secret key. See [API Keys](https://stripe.com/docs/keys).

### `livePublishableKey`

Your Stripe live publishable key. See [API Keys](https://stripe.com/docs/keys).

### `liveSecretKey`

Your Stripe live secret key. See [API Keys](https://stripe.com/docs/keys).

### `defaultCurrency`

Three-letter ISO currency code. Must be a [supported currency](https://stripe.com/docs/currencies).
