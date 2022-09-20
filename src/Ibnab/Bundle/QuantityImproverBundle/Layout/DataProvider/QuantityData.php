<?php
namespace Ibnab\Bundle\QuantityImproverBundle\Layout\DataProvider;

use Ibnab\Bundle\QuantityImproverBundle\Provider\ConfigurationProvider;

class QuantityData
{
    /**
     * @var ConfigurationProvider
     */
    protected $configurationProvider;
  
    /**
     * @param ConfigManager $configManager
     */
    public function __construct(ConfigurationProvider $configurationProvider)
    {
        $this->configurationProvider = $configurationProvider;
    }

    public function getMaximumQuantityToOrder(){
        return $this->configurationProvider->getMaximumQuantityToOrder();
    }
    public function getMinimumQuantityToOrder(){
        return $this->configurationProvider->getMinimumQuantityToOrder();
    }
    /**
     * @param Product $product
     * @return mixed
     */
    public function getMinimumLimit(Product $product)
    {
        return $this->configurationProvider->getMinimumLimit($product);
    }

    /**
     * @param Product $product
     * @return mixed
     */
    public function getMaximumLimit(Product $product)
    {
        return $this->configurationProvider->getMaximumLimit($product);
    }
}
