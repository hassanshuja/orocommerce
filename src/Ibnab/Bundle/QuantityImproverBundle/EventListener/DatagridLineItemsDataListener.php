<?php

namespace Ibnab\Bundle\QuantityImproverBundle\EventListener;

use Oro\Bundle\ProductBundle\Entity\Product;
use Oro\Bundle\ProductBundle\Event\DatagridLineItemsDataEvent;
use Oro\Bundle\ProductBundle\Model\ProductLineItemInterface;
use Ibnab\Bundle\QuantityImproverBundle\Provider\ConfigurationProvider;

/**
 * Adds line items basic data.
 */
class DatagridLineItemsDataListener {

    /**
     * @var ConfigurationProvider
     */
    protected $configurationProvider;

    /**
     * @param ConfigurableProductProvider $configurableProductProvider
     * @param LocalizationHelper $localizationHelper
     * @param AttachmentManager $attachmentManager
     */
    public function __construct(
    ConfigurationProvider $configurationProvider
    ) {
        $this->configurationProvider = $configurationProvider;
    }

    /**
     * Add value required to create select box with available increment by x quantity to
     * frontend shopping list view and edit page , will get used in the twig with event:
     * oro_product.datagrid_line_items_data.frontend-customer-user-shopping-list-edit-grid
     * @param DatagridLineItemsDataEvent $event
     */
    public function onLineItemData(DatagridLineItemsDataEvent $event) : void
    {
        if ($this->configurationProvider->isEnable())
        /** @var ProductLineItemInterface $lineItem */
            $dataForAllLineItems = $event->getDataForAllLineItems();
        foreach ($event->getLineItems() as $lineItem) {
            $lineItemId = $lineItem->getEntityIdentifier();
            if (isset($dataForAllLineItems[$lineItemId])) {
                $lineItemData = $dataForAllLineItems[$lineItemId];
                $product = $lineItem->getProduct();
                if ($product) {
                    
                    $minimumQuantityToOrder = $this->configurationProvider->getMinimumLimit($product);               
                    $maximumQuantityToOrder = $this->configurationProvider->getMaximumLimit($product);
                    $qtyMulti = $this->configurationProvider->getIncementQuantity($product);
                    $lineItemData['minimumQuantityToOrder'] = !is_null($minimumQuantityToOrder) ||  $minimumQuantityToOrder == 0 ? $minimumQuantityToOrder : 1;
                    $lineItemData['maximumQuantityToOrder'] = !is_null($maximumQuantityToOrder) ||  $maximumQuantityToOrder == 0 ? $maximumQuantityToOrder : 1;
                    $lineItemData['quantityImproverIncrement'] = !is_null($qtyMulti) ||  $qtyMulti == 0 ? $qtyMulti : 1;
  
                }
                $event->addDataForLineItem($lineItemId, $lineItemData);
            }
        }
    }

}
