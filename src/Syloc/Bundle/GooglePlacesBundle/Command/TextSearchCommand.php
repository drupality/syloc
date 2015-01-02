<?php

namespace Syloc\Bundle\GooglePlacesBundle\Command;

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
        $textSearch =
            new TextSearch($this->getContainer()->get('doctrine')->getManager(), $input->getArgument('query'));

        $textSearch->search();

        $output->writeln($textSearch->getResultMessage());

        return;

    }
}