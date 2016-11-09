<?php

namespace SmartCore\Module\Blog\EventListener;

use Psr\Log\LoggerInterface;
use SmartCore\Module\Blog\Event\FilterArticleEvent;
use SmartCore\Module\Blog\Model\SignedArticleInterface;
use SmartCore\Module\Blog\SmartBlogEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;

/**
 * @todo FIX IT !!!
 */
class ArticleAuthorListener implements EventSubscriberInterface
{
    /**
     */
    protected $tokenStorage;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param                               $tokenStorage
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param LoggerInterface|null          $logger
     */
    public function __construct($tokenStorage, AuthorizationCheckerInterface $authorizationChecker, LoggerInterface $logger = null)
    {
        $this->tokenStorage = $tokenStorage;
        $this->logger       = $logger;
    }

    /**
     * @param FilterArticleEvent $event
     */
    public function setArticleAuthor(FilterArticleEvent $event)
    {
        if (null === $this->tokenStorage) {
            if ($this->logger) {
                $this->logger->debug('The security.context service is NULL.');
            }

            return;
        }

        if (null === $this->tokenStorage->getToken()) {
            if ($this->logger) {
                $this->logger->debug('No authentication information is available, please configure a firewall for this route.');
            }

            return;
        }

        /** @var SignedArticleInterface $article */
        $article = $event->getArticle();

        if (!$article instanceof SignedArticleInterface) {
            if ($this->logger) {
                $this->logger->debug('Post does not implement SignedPostInterface.');
            }

            return;
        }

        if (null === $article->getAuthor() and $this->tokenStorage->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $article->setAuthor($this->tokenStorage->getToken()->getUser());
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            SmartBlogEvents::ARTICLE_CREATE => 'setArticleAuthor',
        ];
    }
}
