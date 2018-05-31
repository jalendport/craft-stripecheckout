# craft.stripecheckout.charges

### How to get charges

You can access your charges from your templates via `craft.stripecheckout.charges`

```twig
{% set charges = craft.stripecheckout.charges.all() %}

{% for charge in charges %}
  {{ charge.id }} - {{ charge.formattedAmount }}
{% endfor %}
```

### Parameters

`craft.stripecheckout.charges` supports the following parameters:

#### `amount`

Get charges by amount.

#### `amountRefunded`

Get charges by amount refunded.

#### `chargeStatus`

Get charges with the status set as `succeeded`, `pending` or `failed`.

#### `currency`

Get charges by three-letter [ISO currency code](https://www.iso.org/iso-4217-currency-codes.html).

#### `email`

Get charges related to an email address.

#### `failureCode`

Get charges by error code (see [the errors section](https://stripe.com/docs/api#errors) for a list of codes).

#### `live`

Get charges in either live or test mode. Boolean value required.

#### `paid`

Get charges that have been paid. Boolean value required.

#### `refunded`

Get charges that have been refunded. Boolean value required.

#### `stripeId`

Get a charge related to a Stripe ID.
