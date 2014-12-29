<?php
/**
 * Created by PhpStorm.
 * User: drupality
 * Date: 29.12.14
 * Time: 11:14
 */

namespace Syloc\Bundle\LocationBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Syloc\Bundle\LocationBundle\Entity\Location;

class LocationsFixtures implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $places = $manager->getRepository('GooglePlacesBundle:Place')->findAll();

        foreach($places as $place) {
            $club = new Location;
            $club->setName($place->getName());
            $club->setLat($place->getLat());
            $club->setLng($place->getLng());
            $manager->persist($club);
        }

        $manager->flush();
    }
}