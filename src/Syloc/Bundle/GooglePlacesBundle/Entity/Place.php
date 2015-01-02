<?php

namespace Syloc\Bundle\GooglePlacesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Place
 */
class Place
{
    /**
     * @var integer
     */
    private $id;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $lat;
    /**
     * @var string
     */
    private $lng;
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $types;
    /**
     * @var string
     */
    private $formattedAddress;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->types = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Place
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get lat
     *
     * @return string
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lat
     *
     * @param string $lat
     * @return Place
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lng
     *
     * @return string
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set lng
     *
     * @param string $lng
     * @return Place
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Add types
     *
     * @param \Syloc\Bundle\GooglePlacesBundle\Entity\PlaceType $types
     * @return Place
     */
    public function addType(\Syloc\Bundle\GooglePlacesBundle\Entity\PlaceType $types)
    {
        $this->types[] = $types;

        return $this;
    }

    /**
     * Remove types
     *
     * @param \Syloc\Bundle\GooglePlacesBundle\Entity\PlaceType $types
     */
    public function removeType(\Syloc\Bundle\GooglePlacesBundle\Entity\PlaceType $types)
    {
        $this->types->removeElement($types);
    }

    /**
     * Get types
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * Get formattedAddress
     *
     * @return string
     */
    public function getFormattedAddress()
    {
        return $this->formattedAddress;
    }

    /**
     * Set formattedAddress
     *
     * @param string $formattedAddress
     * @return Place
     */
    public function setFormattedAddress($formattedAddress)
    {
        $this->formattedAddress = $formattedAddress;

        return $this;
    }
}
