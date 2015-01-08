<?php

namespace Syloc\Bundle\GooglePlacesBundle\Command;

use Syloc\Bundle\GooglePlacesBundle\Services\TextSearch;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TextSearchCommand
 * @package Syloc\Bundle\GooglePlacesBundle\Command
 */
class TextSearchCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('places:textsearch')
            ->setDescription('Text search of places in Google Maps')
            ->addArgument('query', InputArgument::REQUIRED, 'Search query');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $textSearch = $this->getContainer()->get('google_places.textsearch');

        $textSearch->search($input->getArgument('query'));

        $output->writeln($textSearch->getResultMessage());

    }
}