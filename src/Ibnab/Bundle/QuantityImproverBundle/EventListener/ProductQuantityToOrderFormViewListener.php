<?php

namespace Ibnab\Bundle\QuantityImproverBundle\EventListener;

use Oro\Bundle\ProductBundle\Entity\Product;
use Oro\Bundle\UIBundle\Event\BeforeListRenderEvent;
use Oro\Bundle\UIBundle\Fallback\AbstractFallbackFieldsFormView;

class ProductQuantityToOrderFormViewListener extends AbstractFallbackFieldsFormView
{
    /**
     * Add order by increment of x field to backend product view page section 
     * by twig template
     * @param BeforeListRenderEvent $event
     */
    public function onProductView(BeforeListRenderEvent $event)
    {
        $product = $this->getEntityFromRequest(Product::class);
        if (!$product) {
            return;
        }

        $this->addBlockToEntityView(
            $event,
            '@IbnabQuantityImprover/Product/viewIncrementQuantityToOrder.html.twig',
            $product,
            'oro.product.sections.inventory'
        );
    }

    /**
     * Add order by increment of x field to backend product eidt page section 
     * by twig template
     * @param BeforeListRenderEvent $event
     */
    public function onProductEdit(BeforeListRenderEvent $event)
    {
        $this->addBlockToEntityEdit(
            $event,
            '@IbnabQuantityImprover/Product/editIncrementQuantityToOrder.html.twig',
            'oro.product.sections.inventory'
        );
    }
}
