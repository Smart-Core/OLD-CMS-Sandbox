<?php

namespace SmartCore\Module\Blog\EventListener;

use Psr\Log\LoggerInterface;
use SmartCore\Module\Blog\Event\FilterArticleEvent;
use SmartCore\Module\Blog\Model\SignedArticleInterface;
use SmartCore\Module\Blog\SmartBlogEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\SecurityContextInterface;

class ArticleAuthorListener implements EventSubscriberInterface
{
    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext
     */
    public function __construct(SecurityContextInterface $securityContext, LoggerInterface $logger = null)
    {
        $this->securityContext = $securityContext;
        $this->logger          = $logger;
    }

    /**
     * @param FilterArticleEvent $event
     */
    public function setArticleAuthor(FilterArticleEvent $event)
    {
        if (null === $this->securityContext) {
            if ($this->logger) {
                $this->logger->debug('The security.context service is NULL.');
            }

            return;
        }

        if (null === $this->securityContext->getToken()) {
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

        if (null === $article->getAuthor() and $this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $article->setAuthor($this->securityContext->getToken()->getUser());
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
