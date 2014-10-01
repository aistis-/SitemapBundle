<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Routing;

use Tadcka\Bundle\RoutingBundle\Model\Manager\RouteManagerInterface;
use Tadcka\Bundle\RoutingBundle\Model\RouteInterface;
use Tadcka\Bundle\SitemapBundle\Exception\RouteException;
use Tadcka\Bundle\SitemapBundle\Model\NodeInterface;
use Tadcka\Bundle\SitemapBundle\Model\NodeTranslationInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 10/1/14 4:07 PM
 */
class RouteProvider
{
    /**
     * @var RouteManagerInterface
     */
    private $routeManager;

    /**
     * Constructor.
     *
     * @param RouteManagerInterface $routeManager
     */
    public function __construct(RouteManagerInterface $routeManager)
    {
        $this->routeManager = $routeManager;
    }

    /**
     * Get route by pattern.
     *
     * @param string $pattern
     *
     * @return null|RouteInterface
     */
    public function getRouteByPattern($pattern)
    {
        return $this->routeManager->findByRoutePattern($pattern);
    }

    /**
     * Get route name.
     *
     * @param NodeInterface $node
     * @param null|string $locale
     *
     * @return string
     *
     * @throws RouteException
     */
    public function getRouteName(NodeInterface $node, $locale = null)
    {
        if (!$node->getId()) {
            throw new RouteException('Node id cannot be empty!');
        }

        $name = NodeTranslationInterface::OBJECT_TYPE . '_' . $node->getId();
        if (null !== $locale) {
            $name .= '_' . $locale;
        }

        return $name;
    }
}
