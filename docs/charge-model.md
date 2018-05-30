# ChargeModel

Whenever you're dealing with a charge in your template, you're actually working with a ChargeModel object.

## Simple Output

Outputting a ChargeModel object without attaching a property or method will return the charge's formatted amount.

```twig
<h1>{{ charge }}</h1>
```

Would output:

```
5.00 GBP
```

## Properties

### `amount`

A positive integer in the [smallest currency unit](https://stripe.com/docs/currencies#zero-decimal) (e.g., 100 cents to charge $1.00 or 100 to charge ¥100, a zero-decimal currency) representing how much to charge. The minimum amount is $0.50 US or [equivalent in charge currency](https://support.stripe.com/questions/what-is-the-minimum-amount-i-can-charge-with-stripe).

### `amountRefunded`

Amount in pence refunded (can be less than the amount attribute on the charge if a partial refund was issued).

### `chargeStatus`

The status of the payment is either `succeeded`, `pending`, or `failed`.

### `cpEditUrl`

Returns the URL to the charge within the control panel.

### `currency`

Three-letter ISO currency code.

### `data`

The full [charge object](https://stripe.com/docs/api#charge_object) provided by Stripe.

### `dateCreated`

A [DateTime](http://php.net/manual/en/class.datetime.php) object of the date the charge was created.

### `dateUpdated`

A [DateTime](http://php.net/manual/en/class.datetime.php) object of the date the charge was last updated.

### `description`

An arbitrary string attached to the object. Often useful for displaying to users.

### `failureCode`

Error code explaining reason for charge failure if available (see [the errors section](https://stripe.com/docs/api#errors) for a list of codes).

### `failureMessage`

Message to user further explaining reason for charge failure if available.

### `email`

This is the email address that the receipt for this charge was sent to.

### `id`

The charge record ID.

### `live`

Has the value `true` if the object exists in live mode or the value `false` if the object exists in test mode.

### `paid`

`true` if the charge succeeded, or was successfully authorised for later capture.

### `refunded`

Whether the charge has been fully refunded. If the charge is only partially refunded, this attribute will still be `false`.

### `stripeId`

The charge ID supplied by Stripe.
