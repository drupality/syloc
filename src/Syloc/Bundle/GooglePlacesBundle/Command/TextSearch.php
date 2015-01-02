<?php

namespace Syloc\Bundle\GooglePlacesBundle\Command;

use Doctrine\Common\Persistence\ObjectManager;
use Syloc\Bundle\GooglePlacesBundle\Entity\Place;
use Syloc\Bundle\GooglePlacesBundle\Entity\PlaceType;

class TextSearch {

    private $objectManager;

    private $searchQuery;

    private $resultMessage;

    public function  __construct(ObjectManager $ob, $searchQuery)
    {
        $this->objectManager = $ob;
        $this->searchQuery = $searchQuery;
        return $this;
    }

    public function search()
    {
        $url = 'https://maps.googleapis.com/maps/api/place/textsearch/json';
        //TODO: extract key to config file
        $url .= '?' . http_build_query(
                array('query' => $this->searchQuery, 'key' => 'AIzaSyBjrrUBPuovmv150yVmiacEsEAv9luWMMY')
            );

        $result = $this->doRequest($url);

        switch($result->status) {
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
        $typeRepository = $this->objectManager->getRepository('GooglePlacesBundle:PlaceType');

        foreach($result->results as $googlePlace) {

            $place = new Place;
            $place->setName($googlePlace->name);
            $place->setLat($googlePlace->geometry->location->lat);
            $place->setLng($googlePlace->geometry->location->lng);
            $place->setFormattedAddress($googlePlace->formatted_address);

            foreach($googlePlace->types as $type) {
                $placeType = $typeRepository->findOneByType($type);

                if (! $placeType) {
                    $placeType = new PlaceType;
                    $placeType->setType($type);
                    $this->objectManager->persist($placeType);
                }

                $place->addType($placeType);
            }

            $this->objectManager->persist($place);

        }
        $this->objectManager->flush();
    }

    public function getResultMessage()
    {
        return $this->resultMessage;
    }


}