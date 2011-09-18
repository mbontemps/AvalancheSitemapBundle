<?php

namespace Avalanche\Bundle\SitemapBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Avalanche\Bundle\SitemapBundle\Sitemap\Provider;

class PopulateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('sitemap:populate')
            ->setDescription('Populate sitemap, using its data providers.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $sitemap = $this->getContainer()->get('sitemap');

        $this->getContainer()->get('sitemap.provider.chain')->populate($sitemap);
        $output->write('<info>Sitemap was sucessfully populated!</info>', true);
    }
}
