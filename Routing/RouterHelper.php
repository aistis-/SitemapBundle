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

use Ferrandini\Urlizer;
use Tadcka\Bundle\SitemapBundle\Exception\RouteException;
use Tadcka\Bundle\SitemapBundle\Model\NodeInterface;
use Tadcka\Bundle\SitemapBundle\Model\NodeTranslationInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 10/1/14 4:37 PM
 */
class RouterHelper
{
    /**
     * @var array
     */
    private $controllers;

    /**
     * @var string
     */
    private $strategy;

    /**
     * Constructor.
     *
     * @param array $controllers
     * @param string $strategy
     */
    public function __construct(array $controllers, $strategy)
    {
        $this->controllers = $controllers;
        $this->strategy = $strategy;
    }

    /**
     * Get route controller.
     *
     * @param string $nodeType
     *
     * @throws RouteException
     */
    public function getRouteController($nodeType)
    {
        if ($this->hasRouteController($nodeType)) {
            return $this->controllers[$nodeType];
        }

        throw new RouteException(sprintf('%s node type don\'t have controller', $nodeType));
    }

    /**
     * Get route pattern.
     *
     * @param string $pattern
     * @param string $locale
     * @param NodeInterface $node
     *
     * @return string
     *
     * @throws RouteException
     */
    public function getRoutePattern($pattern, NodeInterface $node, $locale)
    {
        if (false === $this->hasRouteController($node->getType())) {
            throw new RouteException(sprintf('Node type %s don\'t have controller!', $node->getType()));
        }

        if (!trim($pattern)) {
            throw new RouteException('Pattern cannot be empty!');
        }

        /** @var NodeInterface $parent */
        $parent = $node->getParent();
        $routePattern = $this->normalizeRoutePattern($pattern);
        if ((RouteGenerator::STRATEGY_FULL_PATH === $this->strategy) && (null !== $parent)) {
            $routePattern = $this->getRouteFullPath($parent, $locale) . '/' . ltrim($routePattern, '/');
        }

        return $routePattern;
    }

    /**
     * Check if has route controller.
     *
     * @param string $nodeType
     *
     * @return bool
     */
    public function hasRouteController($nodeType)
    {
        return isset($this->controllers[$nodeType]);
    }

    /**
     * Normalize route pattern.
     *
     * @param string $pattern
     *
     * @return string
     */
    public function normalizeRoutePattern($pattern)
    {
        $data = explode('/', $pattern);

        $result = '';
        foreach ($data as $string) {
            if (trim($string)) {
                $result .= '/' . Urlizer::urlize($string);
            }
        }

        return $result;
    }

    /**
     * Get route full path.
     *
     * @param NodeInterface $node
     * @param string $locale
     *
     * @return string
     */
    private function getRouteFullPath(NodeInterface $node, $locale)
    {
        $path = '';
        /** @var NodeInterface $parent */
        $parent = $node->getParent();

        if ((null !== $parent) && $this->hasRouteController($parent->getType())) {
            $path = $this->getRouteFullPath($parent, $locale);
        }

        /** @var NodeTranslationInterface $translation */
        $translation = $node->getTranslation($locale);
        if ((null !== $translation) && (null !== $translation->getRoute())) {
            $path .= $this->normalizeRoutePattern($translation->getRoute()->getRoutePattern());
        }

        return $path;
    }
}
