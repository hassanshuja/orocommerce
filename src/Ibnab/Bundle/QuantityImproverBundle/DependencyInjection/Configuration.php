<?php

namespace Ibnab\Bundle\QuantityImproverBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Oro\Bundle\ConfigBundle\DependencyInjection\SettingsBuilder;

class Configuration implements ConfigurationInterface
{
    const ROOT_NODE = IbnabQuantityImproverExtension::ALIAS; 

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder(self::ROOT_NODE);
        $rootNode = $treeBuilder->getRootNode();
        SettingsBuilder::append(
            $rootNode,
            array(
                'enable'   => ['value' => 1],
                'enable_select'   => ['value' => 1],
                'enable_message_increment'   => ['value' => 1],
                'increment'   => ['value' => 1],
                'increment_quantity_to_order' => ['type' => 'decimal', 'value' => 1],
                
            )
        );

        return $treeBuilder;
    }
    
}
