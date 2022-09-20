<?php
namespace Ibnab\Bundle\QuantityImproverBundle\Provider;

use Oro\Bundle\ConfigBundle\Config\ConfigManager;
use Oro\Bundle\InventoryBundle\DependencyInjection\Configuration;
use Oro\Bundle\EntityBundle\Fallback\EntityFallbackResolver;
use Ibnab\Bundle\QuantityImproverBundle\Model\Inventory;
use Oro\Bundle\InventoryBundle\Model\Inventory as OroInventory;
use Oro\Bundle\ProductBundle\Entity\Product;

class ConfigurationProvider
{
    const ENABLEQUANTITYIMPROVER_FIELD = 'ibnab_quantity_improver.enable';
    const INCREMENT_FIELD = 'ibnab_quantity_improver.increment';
    const ENABLEQUANTITYIMPROVER_SELECT_FIELD= 'ibnab_quantity_improver.enable_select';
    const ENABLEQUANTITYIMPROVER_MESSAGE_INCREMENT_FIELD= 'ibnab_quantity_improver.enable_message_increment';
    /**
     * @var EntityFallbackResolver
     */
    protected $fallbackResolver;   
    /**
     * @var ConfigManager
     */
    protected $configManager;

    /**
     * @param ConfigManager $configManager
     * @param EntityFallbackResolver $fallbackResolver
     */
    public function __construct(ConfigManager $configManager,
                                EntityFallbackResolver $fallbackResolver)
    {
        $this->configManager = $configManager;
        $this->fallbackResolver = $fallbackResolver;
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
     * @return mixed
     */
    public function getMinimumLimit(Product $product)
    {
        return $this->fallbackResolver->getFallbackValue(
            $product,
            OroInventory::FIELD_MINIMUM_QUANTITY_TO_ORDER
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
            OroInventory::FIELD_MAXIMUM_QUANTITY_TO_ORDER
        );
    }
    public function getMaximumQuantityToOrder(){
        return $this->configManager->get(Configuration::getMaximumQuantityToOrderFullConfigurationName());
    }
    public function getMinimumQuantityToOrder(){
        return $this->configManager->get(Configuration::getMinimumQuantityToOrderFullConfigurationName());
    }
    public function isEnable(){
        return $this->configManager->get(self::ENABLEQUANTITYIMPROVER_FIELD);
    }
    public function isEnableValidationByProduct(Product $product){
         return $this->fallbackResolver->getFallbackValue(
            $product,
            Inventory::FIELD_ENABLE_INCREMENT_QUANTITY_TO_ORDER
        );
    }
    public function isEnableSelect(){
        return $this->configManager->get(self::ENABLEQUANTITYIMPROVER_SELECT_FIELD);
    }
    public function isEnableMessageIncrement(){
        return $this->configManager->get(self::ENABLEQUANTITYIMPROVER_MESSAGE_INCREMENT_FIELD);
    }
    public function getIncrement(){
        return $this->configManager->get(self::INCREMENT_FIELD);
    }
}
