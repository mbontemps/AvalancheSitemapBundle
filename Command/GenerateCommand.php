<?php

namespace Avalanche\Bundle\SitemapBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Avalanche\Bundle\SitemapBundle\Sitemap\Provider;

class GenerateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('sitemap:generate')
            ->setDescription('Generate sitemap, using its data providers.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $sitemap = $this->getContainer()->get('sitemap');

        $webPath = $this->getContainer()->getParameter('kernel.root_dir').'/../web';
        $templating = $this->getContainer()->get('templating');
        
        $this->getContainer()->get('sitemap.provider.chain')->populate($sitemap);
        $output->write('<info>Sitemap was sucessfully populated!</info>', true);

        // TODO: Extract this to a service
        
        // Sitemaps
        $pages = $sitemap->pages();
        for ($page = 1 ; $page <= $pages ; $page++) {
            $sitemap->setPage($page);
            $data = $templating->render('AvalancheSitemapBundle:Sitemap:sitemap.twig.xml', array(
                'sitemap' => $sitemap
            ));
            file_put_contents($webPath.'/sitemap-'.$page.'.xml', $data);
            $output->write("<info>Sitemap page $page was sucessfully generated!</info>", true);
        }
        
        // Siteindex
        $data = $templating->render('AvalancheSitemapBundle:Sitemap:siteindex.twig.xml', array(
            'pages'   => $sitemap->pages(),
            'sitemap' => $sitemap,
        ));
        file_put_contents($webPath.'/siteindex.xml', $data);
        
        $output->write('<info>Siteindex was sucessfully generated!</info>', true);
    }
}
