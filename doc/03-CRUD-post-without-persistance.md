#CURD post without persistance

As you have already learned how to create pages in symfony framework. Now, we exercise a very common CRUD task.

* C - create a entity
* R - list existing entities
* U - update a existing entity
* D - delete a existing entity

This time, we only demostrate how to use memory entity objects.

OK, let us start our CMS system now.

##Create a batch of routers for CRUD

As you know, if you want to have different pages, you need define different routers.

Open routing.yml in the Resources/config folder of KendoctorCmsBundle and edit it as below

```
cms_post_index:
    pattern:  /post
    defaults: { _controller: KendoctorCmsBundle:Post:index }

cms_post_show:
    pattern:  /post/{id}/show
    defaults: { _controller: KendoctorCmsBundle:Post:show }

cms_post_create:
    pattern:  /post/create
    defaults: { _controller: KendoctorCmsBundle:Post:create }

cms_post_update:
    pattern:  /post/{id}/update
    defaults: { _controller: KendoctorCmsBundle:Post:update }

cms_post_delete:
    pattern:  /post/{id}/delete
    defaults: { _controller: KendoctorCmsBundle:Post:delete }
```

*cms_post_index* is router name. Its convention is like a variable's name. Router name can be used to generate url.

* cms_post_index - for listing posts
* cms_post_show - for displaying a existing post by id
* cms_post_create - for displaying a form to collect post data. When submitted, creates a post using the data filled.
* cms_post_update - for displaying a form to edit. When submitted, updates the post.
* cms_post_delete - for deleting a existing post by id

Because we have not create PostController yet, if you navigate these url, you will get errors.

##Create a entity class - Post

A post commonly has a title and body. Here is the class definition.

```
<?php

namespace Kendoctor\CmsBundle\Entity;

class Post {
    private $id;
    private $title;
    private $body;

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }
}
```

##Create controller and actions

First, let us implement list posts feature.

Create *PostController* in Controller folder of the bundle.

```
<?php

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Kendoctor\CmsBundle\Entity\Post;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostController extends  Controller{
    private $posts = array();


    public function __construct()
    {
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

    ....
```