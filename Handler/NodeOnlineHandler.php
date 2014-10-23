<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Handler;

use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\ValidatorInterface;
use Tadcka\Bundle\SitemapBundle\Frontend\Message\Messages;
use Tadcka\Bundle\SitemapBundle\Model\NodeInterface;
use Tadcka\Bundle\SitemapBundle\Model\NodeTranslationInterface;
use Tadcka\Bundle\SitemapBundle\Validator\Constraints\NodeParentIsOnline;
use Tadcka\Bundle\SitemapBundle\Validator\Constraints\NodeRouteNotEmpty;
use Tadcka\Bundle\SitemapBundle\Validator\Constraints\NodeTranslationNotNull;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 14.10.23 16.36
 */
class NodeOnlineHandler
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * Constructor.
     *
     * @param TranslatorInterface $translator
     * @param ValidatorInterface $validator
     */
    public function __construct(TranslatorInterface $translator, ValidatorInterface $validator)
    {
        $this->translator = $translator;
        $this->validator = $validator;
    }

    /**
     * Process node online.
     *
     * @param string $locale
     * @param Messages $messages
     * @param NodeInterface $node
     *
     * @return bool
     */
    public function process($locale, Messages $messages, NodeInterface $node)
    {
        $constrains = array(
            new NodeTranslationNotNull($locale),
            new NodeRouteNotEmpty($locale),
            new NodeParentIsOnline($locale)
        );
        $violation = $this->validator->validateValue($node, $constrains);

        if (0 < $violation->count()) {
            foreach ($violation as $value) {
                $messages->addError($value->getMessage());
            }

            return false;
        }

        /** @var NodeTranslationInterface $nodeTranslation */
        $nodeTranslation = $node->getTranslation($locale);
        $nodeTranslation->setOnline(!$nodeTranslation->isOnline());

        return true;
    }

    /**
     * On success.
     *
     * @param string $locale
     * @param Messages $messages
     */
    public function onSuccess($locale, Messages $messages)
    {
        $success = $this->translator->trans('success.online_save', array('%locale%' => $locale), 'TadckaSitemapBundle');
        $messages->addSuccess($success);
    }
}
