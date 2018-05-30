# Creating Charges

Creating a charge requires Stripe Checkout to be inserted into your templates.

### Basic example

```twig
<form action="" method="post">
  {{ csrfInput() }}
  {{ redirectInput('checkout/confirmation') }}
  <input type="hidden" name="action" value="stripe-checkout/charge">

  {{ checkout({
      amount: 500
  }) }}
</form>
```

`checkout()` creates a hidden encrypted input that contains all of your Stripe parameters along with the Stripe Checkout button and javascript.

### Checkout Options

All of the standard Stripe Checkout parameters are supported, simply add them to your `checkout()` request.

| Parameter         | Expected value        |
| ----------------- | --------------------- |
| `amount`          | Amount (in pence) |
| `locale`          | [User's preferred language](https://support.stripe.com/questions/what-languages-does-stripe-checkout-support) |
| `name`            | Name of your company or website |
| `description`     | Description of what is being purchased |
| `image`           | URL pointing to a square image. |
| `currency`        | 3-letter currency ISO code |
| `email`           | Email address of your user |
| `label`           | Text to be shown on the blue button |
| `panelLabel`      | Label of the payment button in the Checkout form |
| `zipCode`         | Whether Checkout should validate the billing postal code (true or false) |
| `billingAddress`  | Whether Checkout should collect the user's billing address (true or false) |
| `shippingAddress` | Whether Checkout should collect the user's shipping address (true or false) |
| `allowRememberMe` | Whether to include the option to "Remember Me" for future purchases (true or false) |
| `metadata`        | An array of additional input field names to pass as charge metadata |

### Metadata

It's possible to pass additional data as charge metadata, simply provide the input field names in an array using the `metadata` checkout parameter.

```twig
<input type="text" name="orderComments">

{{ checkout({
    amount: 500,
    metadata: ['orderComments']
}) }}
```

### Redirecting

The user will be redirected to the location defined in your redirect input, with the full [charge model](charge-model.md) available as flash data.

```twig
{% set charge = craft.app.session.getFlash('charge') %}
```

Any errors will be made available too:

```twig
{% set errors = craft.app.session.getFlash('errors') %}
```
