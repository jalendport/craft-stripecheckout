<?php
/**
 * Stripe Checkout plugin for Craft CMS 3.x
 *
 * Bringing the power of Stripe Checkout to your Craft templates.
 *
 * @link      https://github.com/jalendport/craft-stripecheckout
 * @copyright Copyright (c) 2018 Jalen Davenport
 */

namespace jalendport\stripecheckout\twigextensions;

use jalendport\stripecheckout\StripeCheckout;

use Craft;

class StripeCheckoutTwigExtension extends \Twig_Extension
{
  public function getName()
  {
      return 'Stripe Checkout';
  }
  public function getFunctions()
  {
      return [
          new \Twig_SimpleFunction('checkout', [$this, 'checkout']),
      ];
  }
  public function checkout($options = [])
  {
      return StripeCheckout::getInstance()->checkoutService->getCheckoutHtml($options);;
  }
}
