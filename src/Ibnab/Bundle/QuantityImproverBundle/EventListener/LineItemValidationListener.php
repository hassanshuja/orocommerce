<?php

namespace Ibnab\Bundle\QuantityImproverBundle\EventListener;

use Oro\Bundle\CheckoutBundle\Entity\CheckoutLineItem;
use Ibnab\Bundle\QuantityImproverBundle\Validator\QuantityToOrderValidatorService;
use Oro\Bundle\ProductBundle\Entity\Product;
use Oro\Bundle\ShoppingListBundle\Entity\LineItem;
use Oro\Bundle\ShoppingListBundle\Event\LineItemValidateEvent;
use Ibnab\Bundle\QuantityImproverBundle\Provider\ConfigurationProvider;

/**
 * Adds validation errors to LineItemValidateEvent.
 */
class LineItemValidationListener {

    /**
     * @var QuantityToOrderValidatorService
     */
    protected $validatorService;

    /** @var ConfigurationProvider */
    private $configurationProvider;

    /**
     * @param QuantityToOrderValidatorService $quantityValidator
     */
    public function __construct(QuantityToOrderValidatorService $quantityValidator, ConfigurationProvider $configurationProvider) {
        $this->validatorService = $quantityValidator;
        $this->configurationProvider = $configurationProvider;
    }

    /**
     * @param LineItemValidateEvent $event
     */
    public function onLineItemValidate(LineItemValidateEvent $event) {
            $lineItems = $event->getLineItems();
            if (!$lineItems instanceof \Traversable) {
                return;
            }

            foreach ($lineItems as $lineItem) {
                // skip checking if the current line item is not supported
                if (!$this->isSupported($lineItem)) {
                    continue;
                }
                $product = $lineItem->getProduct();
                if (!$product instanceof Product) {
                    continue;
                }
                if ($this->configurationProvider->isEnableValidationByProduct($product)){
                  if ($incrementError = $this->validatorService->getIncrementErrorIfInvalid($product, $lineItem->getQuantity())) {
                    $event->addErrorByUnit($product->getSku(), $lineItem->getProductUnitCode(), $incrementError);
                  }
                }
            }
        
    }

    /**
     * @param mixed $lineItem
     * @return bool
     */
    protected function isSupported($lineItem) {
        if ($lineItem instanceof LineItem) {
            return true;
        }
        if (!$lineItem instanceof CheckoutLineItem) {
            return false;
        }

        return !$lineItem->isPriceFixed();
    }

}
