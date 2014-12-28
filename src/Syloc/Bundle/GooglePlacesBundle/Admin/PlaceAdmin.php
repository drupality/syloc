<?php

namespace Syloc\Bundle\GooglePlacesBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class PlaceAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', 'text', array('label' => 'Place Name'))
            ->add('lat', 'number', array('label' => 'Latitude', 'precision' => 18))
            ->add('lng', 'number', array('label' => 'Longitude', 'precision' => 18))
            ->add('types', 'entity', array(
                'class'         => 'GooglePlacesBundle:PlaceType',
                'property' => 'type',
                'multiple' => true,
            ));


        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('types')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('types')
            ->add('lat')
            ->add('lng')
        ;
    }
}