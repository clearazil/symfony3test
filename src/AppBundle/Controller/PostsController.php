<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

use AppBundle\Entity\Post;

class PostsController extends Controller
{
    public function indexAction()
    {
        $posts = $this->getDoctrine()
            ->getRepository('AppBundle:Post')
            ->findAll();

        return $this->render('posts/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    public function createAction(Request $request)
    {
        $post = new Post();

        $form = $this->createFormBuilder($post)
            ->setMethod('POST')
            ->add('title', TextType::class)
            ->add('message', TextareaType::class)
            ->add('create', SubmitType::class, ['label' => 'Create'])
            ->getForm();

        $form->handleRequest($request);

        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            $aclProvider = $this->get('security.acl.provider');
            $objectIdentity = ObjectIdentity::fromDomainObject($post);
            $acl = $aclProvider->createAcl($objectIdentity);

            $tokenStorage = $this->get('security.token_storage');
            $user = $tokenStorage->getToken()->getUser();
            $securityIdentity = UserSecurityIdentity::fromAccount($user);

            $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
            $aclProvider->updateAcl($acl);

            return $this->redirectToRoute('posts_index');
        }

        return $this->render('posts/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $post = $em->getRepository('AppBundle:Post')
            ->find($id);

        $authorizationChecker = $this->get('security.authorization_checker');

        if($authorizationChecker->isGranted('EDIT', $post) === false) {
            throw new AccessDeniedException();
        }

        $form = $this->createFormBuilder($post)
            ->setMethod('POST')
            ->add('title', TextType::class)
            ->add('message', TextareaType::class)
            ->add('create', SubmitType::class, ['label' => 'Create'])
            ->getForm();

        $form->handleRequest($request);

        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('posts_index');
        }

        return $this->render('posts/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}