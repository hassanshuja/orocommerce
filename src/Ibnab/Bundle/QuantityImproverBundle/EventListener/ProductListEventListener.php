<?php

namespace Ibnab\Bundle\QuantityImproverBundle\EventListener;

use Oro\Bundle\ProductBundle\Event\BuildQueryProductListEvent;
use Oro\Bundle\ProductBundle\Event\BuildResultProductListEvent;

/**
 * Adds common fields to storefront product lists.
 */
class ProductListEventListener
{

    public function onBuildQuery(BuildQueryProductListEvent $event): void
    {
        $event->getQuery()
            ->addSelect('decimal.minimum_quantity_to_order')
            ->addSelect('decimal.maximum_quantity_to_order')
            ->addSelect('decimal.quantity_multiplication');
        

    }

    public function onBuildResult(BuildResultProductListEvent $event): void
    {
        foreach ($event->getProductData() as $productId => $data) {
            $productView = $event->getProductView($productId);
            $productView->set('minimum_quantity_to_order', $data['minimum_quantity_to_order']);  
            $productView->set('maximum_quantity_to_order', $data['maximum_quantity_to_order']);   
            $productView->set('quantity_multiplication', $data['quantity_multiplication']); 
        }
    }
}

