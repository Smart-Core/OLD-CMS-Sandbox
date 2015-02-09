<?php

namespace SmartCore\Bundle\SitemapBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class SitemapCrawlerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('smart:sitemap:crawler')
            ->setDescription('Run sitemap crawler.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sitemapService = $this->getContainer()->get('smart_sitemap');

        $output->writeln('Start grabbing '.$sitemapService->getBaseUrl());
        $urls = $sitemapService->start();

        $startTime = microtime(true);
        $urlsCount = 0;
        while (count($urls) > 0) {
            $urlsCount += count($urls);
            $sitemapService->runCrawler($urls);
            $urls = $sitemapService->getUnvisitedUrls();
        }

        $output->writeln("Found {$urlsCount} urls.");

        $filename = $sitemapService->getTarget();
        $output->writeln("Generating {$filename}.");

        $sitemapXML = $this->getContainer()->get('templating')->render('SmartSitemapBundle::sitemap.xml.twig', [
            'baseUrl' => $sitemapService->getBaseUrl(),
            'urls'    => $sitemapService->all(),
        ]);

        file_put_contents($filename, $sitemapXML);

        $time = round(microtime(true) - $startTime, 2);
        $output->writeln("Done in {$time} seconds. Size ".strlen($sitemapXML)." bytes.");
    }
}
