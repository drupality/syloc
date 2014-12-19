<?php

namespace Syloc\Bundle\GooglePlacesBundle\Command;

use Syloc\Bundle\GooglePlacesBundle\Entity\Place;
use Syloc\Bundle\GooglePlacesBundle\Entity\PlaceType;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

//use Symfony\Component\Console\Input\InputOption;

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
        //$query = $input->getArgument('query');

        $url = 'https://maps.googleapis.com/maps/api/place/textsearch/json';
        $url .= '?' . http_build_query(
            array('query' => $input->getArgument('query'), 'key' => 'AIzaSyBjrrUBPuovmv150yVmiacEsEAv9luWMMY')
        );

        $result = $this->doRequest($url);

        //switch

        $output->writeln('https://developers.google.com/console/help/#UsingKeys');
        $output->writeln($result->status);

        switch($result->status) {
            case 'OK':

                $this->processResult($result);

                break;
            case 'ZERO_RESULTS':

                $output->writeln('No results found');

                return;
            case 'REQUEST_DENIED':

                $output->writeln('Request denied. Did you provide valid API key? You can find more info at: https://developers.google.com/console/help/#UsingKeys');

                return;
            case 'OVER_QUERY_LIMIT':
            case 'INVALID_REQUEST':

                $output->writeln('Your request data is invalid');

                return;

        }
    }

    private function processResult($result)
    {
        $em =  $this->getContainer()->get('doctrine')->getManager();
        $typeRepository = $em->getRepository('GooglePlacesBundle:PlaceType');

        foreach($result->results as $google_place) {

            $place = new Place;
            $place->setName($google_place->name);
            $place->setLat($google_place->geometry->location->lat);
            $place->setLng($google_place->geometry->location->lng);

            foreach($google_place->types as $type) {
                $placeType = $typeRepository->findOneByType($type);

                if (! $placeType) {
                    $placeType = new PlaceType;
                    $placeType->setType($type);
                    $em->persist($placeType);
                    $em->flush();
                }

                $place->addType($placeType);
            }

            $em->persist($place);

        }
        $em->flush();
    }

    private function doRequest($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . "/cacert.pem");

        $data = curl_exec($ch);

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status <> 200) {
            return false;
        }

        return json_decode($data);

    }


}