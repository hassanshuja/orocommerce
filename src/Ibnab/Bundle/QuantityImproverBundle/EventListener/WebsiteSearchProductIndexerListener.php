<?php

namespace Ibnab\Bundle\QuantityImproverBundle\EventListener;

use Oro\Bundle\WebsiteSearchBundle\Event\IndexEntityEvent;
use Oro\Bundle\WebsiteSearchBundle\Manager\WebsiteContextManager;
use Oro\Bundle\DataGridBundle\Event\PreBuild;
use Oro\Bundle\ProductBundle\Entity\Product;
use Ibnab\Bundle\QuantityImproverBundle\Provider\ConfigurationProvider;

/**
 * Add product related data to search index
 * Main data added from product attributes and some data added manually inside listener
 */
class WebsiteSearchProductIndexerListener
{
    /** @var WebsiteContextManager */
    private $websiteContextManager;
    /**
     * @var ConfigurationProvider
     */
    protected $configurationProvider;
    /**
     * @param WebsiteContextManager                 $websiteContextManager
     */
    public function __construct(
        WebsiteContextManager $websiteContextManager,
        ConfigurationProvider $configurationProvider
        ) {
        $this->websiteContextManager = $websiteContextManager;
        $this->configurationProvider = $configurationProvider;
    }
    
    /**
     * @param PreBuild $event
     */
    public function onPreBuild(PreBuild $event): void
    {
        $config = $event->getConfig();
        $config->offsetSetByPath('[columns][minimum_quantity_to_order]', ['label' => 'ibnab.frontend.product.minimum_quantity_to_order']);
        $config->offsetAddToArrayByPath('[source][query][select]', ['decimal.minimum_quantity_to_order as minimum_quantity_to_order']);
        $config->offsetSetByPath('[columns][maximum_quantity_to_order]', ['label' => 'ibnab.frontend.maximum_quantity_to_order']);
        $config->offsetAddToArrayByPath('[source][query][select]', ['decimal.maximum_quantity_to_order as maximum_quantity_to_order']);
        $config->offsetSetByPath('[columns][quantity_multiplication]', ['label' => 'ibnab.frontend.quantity_multiplication']);
        $config->offsetAddToArrayByPath('[source][query][select]', ['decimal.quantity_multiplication as quantity_multiplication']);
    }
    /**
     * @param IndexEntityEvent $event
     * add min and max and increment values to froentend website search result
     * to use in select box.
     */
    public function onWebsiteSearchIndex(IndexEntityEvent $event)
    {
        $websiteId = $this->websiteContextManager->getWebsiteId($event->getContext());
        if (!$websiteId) {
            $event->stopPropagation();

            return;
        }

        /** @var Product[] $products */
        $products = $event->getEntities();


        foreach ($products as $product) {
           $qtyMulti = !is_null($this->configurationProvider->getIncementQuantity($product)) && is_numeric($this->configurationProvider->getIncementQuantity($product)) ? $this->configurationProvider->getIncementQuantity($product) : 0;
           $min = !is_null($this->configurationProvider->getMinimumLimit($product)) && is_numeric($this->configurationProvider->getMinimumLimit($product)) ? $this->configurationProvider->getMinimumLimit($product) : 0;
           $max = !is_null($this->configurationProvider->getMaximumLimit($product)) && is_numeric($this->configurationProvider->getMaximumLimit($product)) ? $this->configurationProvider->getMaximumLimit($product) : 0;
            $event->addField(
                $product->getId(),
                'minimum_quantity_to_order',
                $min
            );
            if(!is_null($qtyMulti)){
            $event->addField(
                $product->getId(),
                'quantity_multiplication',
                $qtyMulti
            );  
            }else{
            $event->addField(
                $product->getId(),
                'quantity_multiplication',
                0
            );                
            }
            $event->addField(
                $product->getId(),
                'maximum_quantity_to_order',
                $max
            );
            
        }
    }
}
