<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\UserPost;
use AppBundle\Form\Type\UserPostType;
use AppBundle\Entity\BlogComments;
use AppBundle\Form\Type\BlogCommentsType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use AppBundle\Entity\Document;
use AppBundle\Form\Type\DocumentType;

class DefaultController extends Controller
{
    public function uploadPhotoAction(Request $request)
    {

        $doc = new Document();
        $form = $this->createForm(DocumentType::class, $doc, array(
            'action' => $this->generateUrl('photo'),
        ));

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($doc);
                $em->flush();
                return $this->redirectToRoute('photo');
            }
        }
        return $this->render('AppBundle:Photo:index.html.twig', array('form' => $form->createView()));
    }

    public function removeAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $postItems = $em->getRepository('AppBundle:UserPost')->findOneBy(array('slug' => $slug));
        if (!$postItems) {
            throw $this->createNotFoundException('No guest found ');
        }
        $em->remove($postItems);
        $em->flush();
        return new RedirectResponse($this->generateUrl('blog_homepage'));
    }

    /*Count Comments*/
    public function partialCountCommentsAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $postComments = $em->getRepository('AppBundle:BlogComments')->findALLPostsOrderBy('DESC', $id);

        return $this->render('@App/Default/partial_count_comments.html.twig', array(
            'comments' => $postComments['comments']
        ));
    }

    /*Popular Post*/
    public function partialPopularItemsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $popularItems = $em->getRepository('AppBundle:UserPost')
            ->findALLPopularPostsOrderBy('DESC');

        return $this->render('@App/Default/partial_popular_posts.html.twig', array(
            'popularItems' => $popularItems
        ));
    }

    /*Recent Post*/
    public function partialRecentPostsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $recentPosts = $em->getRepository('AppBundle:UserPost')
            ->findLatestPosts();

        return $this->render('@App/Default/partial_recent_posts.html.twig', array(
            'recentPosts' => $recentPosts
        ));
    }

    /*Latest Post*/
    public function partialLatestPostsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $latestPosts = $em->getRepository('AppBundle:UserPost')
            ->findALLPostsOrderByLimit('DESC', 5);
        return $this->render('@App/Default/partial_latest_posts.html.twig', array(
            'latestPosts' => $latestPosts
        ));
    }

    /**
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $postItems = new UserPost();

        $form = $this->createForm(UserPostType::class, $postItems, array(
            'action' => $this->generateUrl('blog_create'),
            'attr' => array('id' => 'create-form')
        ));

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $postItems->setAuthor($this->get('security.context')->getToken()->getUsername());
                $em->persist($postItems);
                $em->flush();
                return new JsonResponse(array(
                    'view' => $this->renderView('@App/Default/comment.html.twig', array('item' => $postItems))
                ));
            }
        }

        return new JsonResponse(array(
            'status' => true,
            'view' => $this->renderView('@App/Default/create.html.twig', array('form' => $form->createView()))
        ));
    }

    /*blog_view*/
    public function viewAction($slug, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $postItems = $em->getRepository('AppBundle:UserPost')
            ->findOneBy(
                array('slug' => $slug,
                    'status' => UserPost::STATUS_PUBLISH
                ));

        $item = new BlogComments();
        $form = $this->createForm(BlogCommentsType::class, $item, array(
            'action' => $this->generateUrl('blog_view', array('slug' => $slug)),
            'method' => 'POST',
            'attr' => array('id' => 'create-comment')
        ));

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $item->setUser($this->get('security.context')->getToken()->getUsername());
                $item->setPost($postItems);
                $em->persist($item);
                $em->flush();

                if ($request->isXmlHttpRequest()) {

                    $postComments = $this->getDoctrine()
                        ->getRepository('AppBundle:BlogComments')
                        ->findALLPostsOrder('DESC', $postItems->getId());

                    return new JsonResponse(array(
                        'status' => true,
                        'view' => $this->renderView('@App/Default/partial_comments.html.twig', array(
                            'comments' => $postComments
                        ))
                    ));
                }
                return $this->redirect($this->generateUrl('blog_view', array('slug' => $slug)));
            }
        }

        $postComments = $this->getDoctrine()
            ->getRepository('AppBundle:BlogComments')
            ->findALLPostsOrder('DESC', $postItems->getId());

        return $this->render('AppBundle:Default:view.html.twig', array('result' => $postItems,
            'form' => $form->createView(), 'comments' => $postComments));
    }

    public function indexAction($page = 1, Request $request)
    {
//
//        $message = \Swift_Message::newInstance()
//            ->setSubject('Hello Email')
//            ->setFrom('BMihor@gmail.com')
//            ->setTo('BMihor@mail.ru')
//            ->setBody('Привет'
//            )
//        ;
//        $this->get('mailer')->send($message);

        $em = $this->getDoctrine()->getManager();
        $postItems = $em->getRepository('AppBundle:UserPost')->findALLPostsOrderBy('DESC');
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $postItems, /* query NOT result */
            $request->query->getInt('page', $page)/*page number*/,
            5/*limit per page*/
        );
        $pagination->setUsedRoute('blog_view_development_route');

        return $this->render('AppBundle:Default:index.html.twig', array('pagination' => $pagination));
    }
}
