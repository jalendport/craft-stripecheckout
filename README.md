# Stripe Checkout plugin for Craft CMS 3.x

Bringing the power of Stripe Checkout to your Craft templates.

## Requirements

This plugin requires Craft CMS 3.0.0-RC1 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require lukeyouell/craft3-stripecheckout

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Stripe.

## Stripe Checkout Overview

[Stripe Checkout](https://stripe.com/docs/checkout) is the best payment flow on web and mobile. It provides your users with a streamlined, mobile-ready payment experience that is constantly improving.

## Configuring Stripe Checkout

#### Account Mode

Easily switch between test & live modes.

#### Default Currency

The default currency to be used, this can be overridden in your templates.

#### Test Credentials

Your test publishable & secret keys sourced from your Stripe dashboard.

#### Live Credentials

Your live publishable & secret keys sourced from your Stripe dashboard.

## Using Stripe

#### Setup

Setting up Stripe Checkout is very similar to using Stripe.js, except for the following:

- Set your form's `action` value to `stripe-checkout/charge`

##### Basic Example

```twig
<form action="" method="post">

  <input type="hidden" name="action" value="stripe-checkout/charge">
  <input type="hidden" name="redirect" value="{{ 'success?status={status}&id={id}'|hash }}">

  {{ craft.stripeCheckout.checkoutOptions({
       amount: 999
  }) }}

</form>
```

`checkoutOptions` will create a hidden encrypted input with all of the relevant details, along with the Stripe Checkout button and javascript.

##### Checkout Options

All of the standard [Stripe Checkout parameters](https://stripe.com/docs/checkout#integration-simple-parameters) are supported, simply add them to your `checkoutOptions` request.

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

##### Metadata

It's possible to pass additional data as charge metadata, simply supply the input field names in an array using the `metadata` checkout option.

```twig
<input type="text" name="shippingComments">

{{ craft.stripeCheckout.checkoutOptions({
     metadata: ['shippingComments']
}) }}
```

##### Full Example

```twig
<form action="" method="post">

  <input type="hidden" name="action" value="stripe-checkout/charge">
  <input type="hidden" name="redirect" value="{{ 'success?status={status}&id={id}'|hash }}">

  <label>Size</label>
  <select name="sizes[]" multiple>
    <option value="small">Small</option>
    <option value="medium">Medium</option>
    <option value="large">Large</option>
  </select>

  <label>Shipping comments</label>
  <textarea name="shippingComments"></textarea>

  {{ craft.stripeCheckout.checkoutOptions({
       amount: 10000,
       locale: 'auto',
       name: 'Demo',
       description: 'This is a demo',
       image: 'https://www.yourwebsite.com/images/checkout.png',
       currency: 'gbp',
       email: 'joe.bloggs@yourwebsite.com',
       label: 'Pay with Card',
       panelLabel: 'Pay',
       zipCode: true,
       billingAddress: true,
       shippingAddress: true,
       allowRememberMe: true,
       metadata: ['sizes', 'shippingComments']
  }) }}

</form>
```

##### Redirecting

Once submitted, the user will be redirected to the location specified in the `redirect` input, which must contain a hashed value.

The full [Stripe charge object](https://stripe.com/docs/api#charge_object) will be available to pass along in the url.

#### Displaying Stripe Charge

##### Post submission

The Stripe charge object is passed as flash data and can be accessed by using the following:

`{% set charge = craft.app.session.hasFlash('charge') %}`

## Stripe Roadmap

Some things to do, and ideas for potential features:

- Refunds from the CP
- Improved documentation

Brought to you by [Luke Youell](https://github.com/lukeyouell)
