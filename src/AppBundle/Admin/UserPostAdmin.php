<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;


use Knp\Menu\ItemInterface as MenuItemInterface;
use AppBundle\Entity\UserPost;

class UserPostAdmin extends Admin
{

    public function toString($object)
    {
        return $object instanceof UserPost
            ? $object->getTitle()
            : 'Blog Post'; // shown in the breadcrumb on the create view
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
//            ->add('author')
            ->add('author', null, array(), 'entity', array(
                'class' => 'AppBundle\Entity\User',
                'property' => 'username',
            ))
            ->add('shortText')
            ->add('text')
            ->add('createdDate')
//            ->add('slug')
            ->add('status', null, array(), 'choice', array('choices' => array(
                UserPost::STATUS_PUBLISH => 'PUBLISH',
                UserPost::STATUS_HIDDEN => 'HIDDEN')))
            ->add('title')
            ->add('isPopular', null, array(), 'choice', array('choices' => array(
                UserPost::STATUS_POPULAR => 'POPULAR',
                UserPost::STATUS_NOT_POPULAR => 'NOT POPULAR'
            )));
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('id')
            ->add('author')
            ->add('shortText')
            ->add('text')
            ->add('createdDate')
//            ->add('slug')
            ->add('status', 'choice', array('choices' => array(
                UserPost::STATUS_PUBLISH => 'PUBLISH',
                UserPost::STATUS_HIDDEN => 'HIDDEN')))
            ->add('isPopular', 'choice', array('choices' => array(
                UserPost::STATUS_POPULAR => 'POPULAR',
                UserPost::STATUS_NOT_POPULAR => 'NOT POPULAR')))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ));
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->tab('Text')
            ->with('Text')
//            ->add('id')
            ->add('author')
            ->add('shortText')
            ->add('text')
            ->add('createdDate')
//            ->add('slug')
            ->add('status', 'choice', array('choices' => array(
                UserPost::STATUS_PUBLISH => 'PUBLISH',
                UserPost::STATUS_HIDDEN => 'HIDDEN')))
            ->add('title')
            ->add('isPopular', 'choice', array('choices' => array(
                UserPost::STATUS_POPULAR => 'POPULAR',
                UserPost::STATUS_NOT_POPULAR => 'NOT POPULAR')))
            ->end()
            ->end();

//            ->tab('Text2')
////            ->add('author', 'text', array('label' => 'Post Title'))
//            ->end();


    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
//            ->add('id')
            ->add('author')
            ->add('shortText')
            ->add('text')
            ->add('createdDate')
//            ->add('slug')
            ->add('status', 'choice', array('choices' => array(
                UserPost::STATUS_PUBLISH => 'PUBLISH',
                UserPost::STATUS_HIDDEN => 'HIDDEN')))
            ->add('title')
            ->add('isPopular', 'choice', array('choices' => array(
                UserPost::STATUS_POPULAR => 'POPULAR',
                UserPost::STATUS_NOT_POPULAR => 'NOT POPULAR')));
    }
}
