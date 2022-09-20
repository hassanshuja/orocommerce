<?php
namespace Ibnab\Bundle\QuantityImproverBundle\Twig;
use Ibnab\Bundle\QuantityImproverBundle\Provider\ConfigurationProvider;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Psr\Container\ContainerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class QuantityExtension extends AbstractExtension implements ServiceSubscriberInterface{
    
   private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    /**
     * @return array
     */
    public function getFunctions() {
        return [
            new TwigFunction('get_allowed_quantity', [$this, 'getAllowedQuantity']),
            new TwigFunction('format_qty_gamma', [$this, 'formatQty']),
            new TwigFunction('get_incerment_config', [$this, 'getIncermentConfig']),
            new TwigFunction('get_minimum_order', [$this, 'getMinimumToOrder']),
            new TwigFunction('get_maximum_order', [$this, 'getMaximumToOrder']),
            new TwigFunction('is_message_increment', [$this, 'isEnableMessageIncrement'])
        ];
    }

    public function getAllowedQuantity($minimumQuantityToOrder, $maximumQuantityToOrder, $qtyMulti = null) {
        $allowedQuantity = [];
        if (is_null($qtyMulti)) {
            $qtyMulti = $this->configProvider->getIncrement();
        }
        if (is_null($qtyMulti) || $qtyMulti <= 0 ) {
            $qtyMulti = 1;
        }
        if ($minimumQuantityToOrder > 0 && ($maximumQuantityToOrder > $minimumQuantityToOrder || $maximumQuantityToOrder == $minimumQuantityToOrder)) {
            for ($i = $minimumQuantityToOrder; $i < $maximumQuantityToOrder; $i = $i + $qtyMulti) {
                $allowedQuantity[$i . ""] = $i;
            }
            $allowedQuantity[$maximumQuantityToOrder . ""] = $i;
        }
        return $allowedQuantity;
    }

    public function formatQty($qty) {
        if ($this->is_decimal($qty)) {
            //$digit = strlen(substr(strrchr($qty, "."), 1));
            return number_format($qty, 2). "";
        }else{
            return number_format($qty). "";
        }
        return $qty;
    }

    protected function is_decimal($val) {
        return is_numeric($val) && floor($val) != $val;
    }
    public function getIncermentConfig() {
        return $this->getConfigProvider()->getIncrement();
    }
    public function getMinimumToOrder() {
        return $this->getConfigProvider()->getMinimumQuantityToOrder();
    }
    public function getMaximumToOrder() {
        return $this->getConfigProvider()->getMaximumQuantityToOrder();
    }
    public function isEnableMessageIncrement() {
        return $this->getConfigProvider()->isEnableMessageIncrement();
    }
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedServices()
    {
        return [
            'ibnab_quantity_improver.provider.configuration' => ConfigurationProvider::class,
        ];
    }

    private function getConfigProvider(): ConfigurationProvider
    {
        return $this->container->get('ibnab_quantity_improver.provider.configuration');
    }
}
