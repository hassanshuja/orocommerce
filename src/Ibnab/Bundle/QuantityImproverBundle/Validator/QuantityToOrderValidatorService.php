<?php

namespace Ibnab\Bundle\QuantityImproverBundle\Validator;

use Doctrine\Common\Collections\Collection;
use Oro\Bundle\EntityBundle\Fallback\EntityFallbackResolver;
use Oro\Bundle\EntityBundle\Manager\PreloadingManager;
use Oro\Bundle\InventoryBundle\Model\Inventory as ParentInventory;
use Ibnab\Bundle\QuantityImproverBundle\Model\Inventory;
use Oro\Bundle\ProductBundle\Entity\Product;
use Oro\Bundle\ProductBundle\Model\ProductLineItemInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

/**
 * Checks if shopping list line items follow minimum and maximum quantity restrictions.
 */
class QuantityToOrderValidatorService
{
    use LoggerAwareTrait;
    /**
     * @var EntityFallbackResolver
     */
    protected $fallbackResolver;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var PreloadingManager
     */
    private $preloadingManager;

    /**
     * @param EntityFallbackResolver $fallbackResolver
     * @param TranslatorInterface $translator
     * @param PreloadingManager $preloadingManager
     */
    public function __construct(
        EntityFallbackResolver $fallbackResolver,
        TranslatorInterface $translator,
        PreloadingManager $preloadingManager
    ) {
        $this->fallbackResolver = $fallbackResolver;
        $this->translator = $translator;
        $this->preloadingManager = $preloadingManager;
        $this->logger = new NullLogger();
    }

    /**
     * @param Collection|ProductLineItemInterface[] $lineItems
     * @return bool
     */
    public function isLineItemListValid($lineItems)
    {
        $this->preloadingManager->preloadInEntities(
            $lineItems instanceof Collection ? $lineItems->toArray() : $lineItems,
            [
                'product' => [
                    'minimumQuantityToOrder' => [],
                    'maximumQuantityToOrder' => [],
                    'category' => [
                        'minimumQuantityToOrder' => [],
                        'maximumQuantityToOrder' => [],
                    ],
                ],
            ]
        );

        foreach ($lineItems as $item) {
            $product = $item->getProduct();
            $quantity = $item->getQuantity();
            if (!$product instanceof Product) {
                continue;
            }
            
            if ($this->isOutIncrementQuantity($this->getIncementQuantity($product),$this->getMinimumLimit($product),$this->getMaximumLimit($product), $quantity)
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param mixed $minimumLimit
     * @param mixed $maximumLimit
     * @param mixed $incrementQuantity
     * @param int $quantity
     * @return bool
     */
    public function isOutIncrementQuantity($incrementQuantity,$minimumLimit,$maximumLimit, $quantity)
    {
        if (!is_numeric($minimumLimit) || !is_numeric($maximumLimit) || !is_numeric($incrementQuantity)) {
            return false;
        }
        //$this->logger->info('v1do '.$maximumLimit.' ');
        if($minimumLimit <= $maximumLimit){           
        for($i = $minimumLimit ; $i <= $maximumLimit ; $i = $i + $incrementQuantity){
            
            if($i == $quantity  || $maximumLimit == $quantity){
                return false;
            }
        }
        }
        return true;
    }

    /**
     * @param Product $product
     * @return mixed
     */
    public function getMinimumLimit(Product $product)
    {
        return $this->fallbackResolver->getFallbackValue(
            $product,
            ParentInventory::FIELD_MINIMUM_QUANTITY_TO_ORDER
        );
    }

    /**
     * @param Product $product
     * @return mixed
     */
    public function getMaximumLimit(Product $product)
    {
        return $this->fallbackResolver->getFallbackValue(
            $product,
            ParentInventory::FIELD_MAXIMUM_QUANTITY_TO_ORDER
        );
    }
    
    /**
     * @param Product $product
     * @return mixed
     */
    public function getIncementQuantity(Product $product)
    {
        return $this->fallbackResolver->getFallbackValue(
            $product,
            Inventory::FIELD_INCREMENT_QUANTITY_TO_ORDER
        );
    }

    /**
     * @param Product $product
     * @param int|float $quantity
     * @return bool|string
     */
    public function getIncrementErrorIfInvalid(Product $product, $quantity)
    {
        $incrementQuantity = $this->getIncementQuantity($product);
        $minimumLimit = $this->getMinimumLimit($product);
        $maximumLimit = $this->getMaximumLimit($product);

        if ($this->isOutIncrementQuantity($incrementQuantity,$minimumLimit,$maximumLimit, $quantity)) {
            return $this->translator->trans(
                'ibnab.quantity_improver.product.error.increment_quantity_wrong',
                ['%increment%' => $incrementQuantity]
            );
        }

        return false;
    }


    
    
    /**
     * @param Product $product
     * @param int $incrementQuantity
     * @param string $messageSuffix
     * @return string
     */
    protected function getErrorMessage(Product $product, $incrementQuantity, $messageSuffix)
    {
        return $this->translator->trans(
            'ibnab.quantity_improver.product.error.' . $messageSuffix,
            [
                '%increment%' => $incrementQuantity,
                '%sku%' => $product->getSku(),
                '%product_name%' => $product->getName(),
            ]
        );
    }
}
