<?php

namespace SmartCore\Bundle\SitemapBundle\Service;

use Goutte\Client;
use Doctrine\ORM\EntityManager;
use SmartCore\Bundle\SitemapBundle\Entity\Url;

class SitemapService
{
    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var array
     */
    protected $ignores;

    /**
     * @var string
     */
    protected $target;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $urlRepo;

    /**
     * Constructor.
     */
    public function __construct(EntityManager $em, $baseUrl, $ignores, $target)
    {
        $this->baseUrl  = $baseUrl;
        $this->ignores  = $ignores;
        $this->target   = $target;
        $this->em       = $em;
        $this->urlRepo  = $em->getRepository('SmartSitemapBundle:Url');
    }

    /**
     * @return []|array
     */
    public function all()
    {
        return $this->urlRepo->findBy([], ['loc' => 'ASC']);
    }

    /**
     * @return Url[]|array
     */
    public function getUnvisitedUrls()
    {
        return $this->urlRepo->findBy(['is_visited' => false]);
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target . '/sitemap.xml';
    }

    /**
     * @param Url[]|array $urls
     */
    public function start()
    {
        $this->em->getConnection()->exec('TRUNCATE TABLE ' . $this->em->getClassMetadata('SmartSitemapBundle:Url')->getTableName());

        $url = new Url();
        $url->setLoc('/');
        $url->setPriority(1.0);
        $this->em->persist($url);
        $this->em->flush($url);

        return [$url];
    }

    /**
     * @param Url[]|array $urls
     */
    public function runCrawler($urls)
    {
        $client = new Client();
        /** @var Url $url */
        foreach ($urls as $url) {
            $crawler = $client->request('GET', $this->baseUrl . $url->getLoc());

            echo $this->baseUrl . $url->getLoc() . PHP_EOL;

            if ($url->getStatus() != 200) {
                continue;
            }

            try {
                $title = $crawler->filter('head > title')->text();
                $title = str_replace("\n", '', $title);
                $title = preg_replace('/[\s]{2,}/', ' ', $title);
                $title = trim($title);

                $url->setTitle($title)
                    ->setIsVisited(true);
                $this->em->persist($url);
                $this->em->flush($url);

                $links = $crawler->filter('a')->extract('href');
                $this->parseLinks($links, $url);
            } catch (\InvalidArgumentException $e) {
                echo "Bad location: " . $this->baseUrl . $url->getLoc() . PHP_EOL;

                $url->setStatus(500)
                    ->setIsVisited(true);
                $this->em->persist($url);
                $this->em->flush($url);
            }
        }
    }

    /**
     * @param array $links
     */
    protected function parseLinks(array $links, Url $referer)
    {
        $links = array_unique($links);

        $ignoresPreg = '#';

        foreach ($this->ignores as $ignore) {
            $ignoresPreg .= '|^' . str_replace('/', '\/', $ignore);
        }

        $ignoreFiles = ['zip', 'rar', 'mp3']; // @todo исключения файлов.

        foreach ($ignoreFiles as $ignore) {
            $ignoresPreg .= '|' . '.' . $ignore;
        }

        $links = preg_grep("/{$ignoresPreg}/i", $links, PREG_GREP_INVERT);

        foreach ($links as $link) {
            if (strlen($link) == 0) {
                continue;
            }

            // Линк не начинается со слеша.
            if (false === strpos($link, '/') or 0 !== strpos($link, '/')) {
                if (in_array($referer->getLoc() . $link, $links)) {
                    continue;
                }

                $link = $referer->getLoc() . $link;
            }

            $url = $this->urlRepo->findOneBy(['loc' => $link]);

            if ($url) {
                continue;
            }

            $url = new Url();
            $url->setLoc($link)
                ->setReferer($referer->getLoc());

            $this->em->persist($url);
            $this->em->flush($url);
        }
    }
}
