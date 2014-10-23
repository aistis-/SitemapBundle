<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Form\Factory;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\RouterInterface;
use Tadcka\Bundle\SitemapBundle\Form\Type\SeoType;
use Tadcka\Bundle\SitemapBundle\Model\NodeInterface;
use Tadcka\Bundle\SitemapBundle\Routing\RouterHelper;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 14.6.29 14.12
 */
class SeoFormFactory
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var RouterHelper
     */
    private $routerHelper;

    /**
     * @var string
     */
    private $nodeClass;

    /**
     * @var string
     */
    private $nodeTransClass;

    /**
     * Constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param RouterInterface $router
     * @param RouterHelper $routerHelper
     * @param string $nodeClass
     * @param string $nodeTransClass
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        RouterHelper $routerHelper,
        $nodeClass,
        $nodeTransClass
    ) {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->routerHelper = $routerHelper;
        $this->nodeClass = $nodeClass;
        $this->nodeTransClass = $nodeTransClass;
    }

    /**
     * Create seo form.
     *
     * @param NodeInterface $node
     *
     * @return FormInterface
     */
    public function create(NodeInterface $node)
    {
        return $this->formFactory->create(
            new SeoType($this->routerHelper->hasController($node->getType())),
            $node,
            array(
                'translation_class' => $this->nodeTransClass,
                'action' => $this->router->getContext()->getPathInfo(),
                'data_class' => $this->nodeClass,
            )
        );
    }
}
