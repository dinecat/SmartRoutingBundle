<?php

/**
 * This file is part of the DinecatSmartRoutingBundle package.
 * @copyright   2013 Dinecat, http://dinecat.com/
 * @license     http://dinecat.com/en/licenses/MIT.html MIT License
 * @link        https://github.com/dinecat/SmartRoutingBundle
 */

namespace Dinecat\SmartRoutingBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Bundle configuration class.
 * @package     DinecatSmartRoutingBundle
 * @subpackage  DependencyInjection
 * @author      Mykola Zyk <relo.san.pub@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('dinecat_smart_routing');

        return $treeBuilder;
    }
}
