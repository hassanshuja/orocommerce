<?php

namespace Ibnab\Bundle\PlusMinusBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Oro\Bundle\ConfigBundle\DependencyInjection\SettingsBuilder;

class Configuration implements ConfigurationInterface
{
    const ROOT_NODE = IbnabPlusMinusExtension::ALIAS; 
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
                'enable'   => ['value' => 1]
            )
        );

        return $treeBuilder;
    }
    
}
