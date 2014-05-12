<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Kendoctor
 * Date: 14-5-12
 * Time: 下午10:55
 * To change this template use File | Settings | File Templates.
 */

namespace Kendoctor\CmsBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Kendoctor\CmsBundle\Entity\Post;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostController extends  Controller{
    private $posts = array();


    public function __construct()
    {
     //   parent::__construct();

        $post = new Post();
        $post->setId(1);
        $post->setTitle("How to use composer!");
        $post->setBody("Composer is a php dependencies management.");
        $this->posts[$post->getId()] = $post;

        $post = new Post();
        $post->setId(2);
        $post->setTitle("How to use git!");
        $post->setBody("Git is a version control tool.");
        $this->posts[$post->getId()] = $post;

        $post = new Post();
        $post->setId(3);
        $post->setTitle("How to establish a php dev environment!");
        $post->setBody("Set up a php dev, we need install php, mysql and apache.");
        $this->posts[$post->getId()] = $post;
    }

    /**
     * List all posts
     */
    public function indexAction()
    {
        return $this->render('KendoctorCmsBundle:Post:index.html.twig',array('posts'=>$this->posts));
    }

    /**
     * Show a post by id
     *
     * @param $id integer
     */
    public function showAction($id)
    {
        if(!isset($this->posts[$id]))
        {
            throw $this->createNotFoundException(sprintf("No post found for %s", $id));
        }

        return $this->render('KendoctorCmsBundle:Post:show.html.twig',array(
            'post'=>$this->posts[$id],
            'form_delete' => $this->createDeleteForm($id)->createView()
        ));
    }

    /**
     *  create a new post
     */
    public function createAction(Request $request)
    {
        $post = new Post();
        $form = $this->createFormBuilder($post)
            ->setAction($this->generateUrl('cms_post_create'))
            ->add('title', 'text', array('constraints' => array(
                new NotBlank(),
                new Length(array('min'=>2,'max'=>255))
            )))
            ->add('body', 'textarea')
            ->add('save', 'submit')
            ->getForm();

        if($request->getMethod() == 'POST')
        {
            $form->handleRequest($request);
            if($form->isValid()) {
          //  $request->getSession()->setF
                $this->get('session')->getFlashBag()->add('notice', 'You have successfully create a post.');

                return $this->redirect($this->generateUrl('cms_post_index'));
            }
        }
        return $this->render('KendoctorCmsBundle:Post:new.html.twig',array(
            'form' => $form->createView(),
            'post' => $post
        ));
   }

    /**
     * update post by id
     *
     * @param $id
     */
    public function updateAction($id, Request $request)
    {
        if(!isset($this->posts[$id]))
        {
            throw $this->createNotFoundException(sprintf("No post found for %s", $id));
        }

        $post = $this->posts[$id];
        $form = $this->createFormBuilder($post)
            ->setAction($this->generateUrl('cms_post_update', array('id'=>$id)))
            ->setMethod('PUT')
            ->add('title', 'text', array('constraints' => array(
                new NotBlank(),
                new Length(array('min'=>2,'max'=>255))
            )))
            ->add('body', 'textarea')
            ->add('save', 'submit')
            ->getForm();

        if($request->getMethod() == 'PUT')
        {
            $form->handleRequest($request);
            if($form->isValid()) {

                $this->get('session')->getFlashBag()->add('notice', 'You have successfully update the post.');

                return $this->redirect($this->generateUrl('cms_post_index'));
            }
        }
        return $this->render('KendoctorCmsBundle:Post:edit.html.twig',array(
            'form' => $form->createView(),
            'post' => $post
        ));
    }

    /**
     * delete post by id
     *
     * @param $id
     */
    public function deleteAction($id, Request $request)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);
        if($form->isValid())
        {
            if(!isset($this->posts[$id]))
            {
                throw $this->createNotFoundException(sprintf("No post found for %s", $id));
            }
            //delete this post
            unset($this->posts[$id]);
            $this->get('session')->getFlashBag()->add('notice', 'You have successfully delete the post.');

        }
        return $this->redirect($this->generateUrl('cms_post_index'));
    }

    /**
     * create a form for delete post
     *
     * @param $id
     * @return \Symfony\Component\Form\Form
     */
    public function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cms_post_delete', array('id'=>$id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label'=>'Delete'))
            ->getForm();
    }


}