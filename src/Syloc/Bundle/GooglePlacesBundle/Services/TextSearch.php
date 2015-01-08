<?php

namespace Syloc\Bundle\GooglePlacesBundle\Services;

use Exception;
use Syloc\Bundle\GooglePlacesBundle\Entity\Place;
use Syloc\Bundle\GooglePlacesBundle\Entity\PlaceType;
use Symfony\Component\DependencyInjection\ContainerAware;

class TextSearch extends ContainerAware
{

    private $resultMessage;

    public function search($searchQuery)
    {
        $url = 'https://maps.googleapis.com/maps/api/place/textsearch/json';
        //TODO: extract key to config file
        $url .= '?' . http_build_query(
                array('query' => $searchQuery, 'key' => 'AIzaSyBjrrUBPuovmv150yVmiacEsEAv9luWMMY')
            );

        $result = $this->doRequest($url);

        if (! $result) {
            throw new Exception('Search request failed');
        }

        switch ($result->status) {
            case 'OK':

                $this->resultMessage = 'Found results. Processing..';
                $this->processResult($result);

                break;
            case 'ZERO_RESULTS':

                $this->resultMessage = 'No results found';

                return;
            case 'REQUEST_DENIED':

                $this->resultMessage = 'Request denied. Did you provide valid API key? You can find more info at: https://developers.google.com/console/help/#UsingKeys';

                return;
            case 'OVER_QUERY_LIMIT':
            case 'INVALID_REQUEST':

                $this->resultMessage = 'Your request data is invalid';

                return;

        }


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

    private function processResult($result)
    {
        $om = $this->container->get('doctrine')->getManager();
        $typeRepository = $om->getRepository('GooglePlacesBundle:PlaceType');

        foreach ($result->results as $googlePlace) {

            $place = new Place;
            $place->setName($googlePlace->name);
            $place->setLat($googlePlace->geometry->location->lat);
            $place->setLng($googlePlace->geometry->location->lng);
            $place->setFormattedAddress($googlePlace->formatted_address);

            foreach ($googlePlace->types as $type) {
                $placeType = $typeRepository->findOneByType($type);

                if (!$placeType) {
                    $placeType = new PlaceType;
                    $placeType->setType($type);
                    $om->persist($placeType);
                }

                $place->addType($placeType);
            }

            if (isset($googlePlace->photos)) {
                //TODO:  photos download here
            }



            //$om->persist($place);

        }
        //$om->flush();
    }

    public function getResultMessage()
    {
        return $this->resultMessage;
    }


}