<?php

namespace Syloc\Bundle\GooglePlacesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaceType extends AbstractType
{

    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Syloc\Bundle\GooglePlacesBundle\Entity\PlaceType',
            'multiple' => true,
//            'option1' => [],
//            'option2' => 3,
//            'intention' => 'xxx',
//            'cascade_validation' => true
        ));
    }


    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'place_type';
    }
}