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

* id - treated as primary key, should be unique.
* title - holds title of a post
* body - holds detailed information of a post
* setters and getters - because member variables are private, we need them.

##Create controller, actions and templates

First, let us implement list posts feature.

Create *PostController* in Controller folder of the bundle.

```
<?php
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

Here, we create memory Post objects in constructor, then all actions in this controller can access these posts.

In *indexAction*, there is only one line code which renders a template with all posts. So, we need create a template
named *index.html.twig' in Resources/views/Post folder of KendoctorCmsBundle.

```
{% for message in app.session.flashbag.get('notice') %}
    <div class="alert">{{ message }}</div>
{% endfor %}

<table class="table">
    <thead>
        <tr>
            <th>Id</th>
            <th>Title</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        {% for post in posts %}
        <tr>
            <td><a href="{{ path('cms_post_show', {id: post.id}) }}" title="{{ post.title }}">{{ post.id }}</a></td>
            <td>{{ post.title }}</td>
            <td><a href="{{ path('cms_post_update', {id: post.id} ) }}">Edit</a>
                <a href="{{ path('cms_post_show', {id: post.id}) }}" title="{{ post.title }}">Show</a></td>
        </tr>
        {% endfor %}
    </tbody>
</table>
<div>
    <a href="{{ path('cms_post_create') }}">New a post</a>
</div>
```

Up to now , we know about twig templating little. Take it easy, we will learn more and more syntax about twig in the procedure.

* {{ }} - outputs a variable or expression or return value of function
* {% %} - contains logics, statements, such looping, assignment etc.
* {{ post.id }} - post is an object, so you can access its id via post.id
* for item in collection_or_array -  looping for iterating items in collection or array
* app - app level variables
* app.session - accesses session of the app
* app.session.flashbag - flash bag is a special key-array data, which can get only once and cleared after get. It's very
useful to display error messages in next request.
* path - is a twig function which generates url by giving a router name. If router has parameters, push values in the second argument.
* { key : value } - this is a twig object expression, the same as javascript object expression.

After you finished these codes, you can try *symfony-cms.com/post*

Next, let us learn how to display a post by its id.

Append codes in the PostController as following

```
...

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
...
```

* createNotFoundException -  creates a Response which has a 404 status code by throwing a exception.
* createFormBuilder - creates a FormBuilder which can add field via its add method
* setAction - It's a FormBuilder's method which sets <form action="?" ...>.
* setMethod - It's a FormBuilder's method which sets <form method="?" ...>, but for DELETE or PUT type,
form method is still 'POST' while inserting a hidden field named '_method' with value 'DELETE' in the form.
You can check the source code to see what's the fact.
* generateUrl - generates url by giving a router, the same usage with path() which is only used in template.
* getForm - It's a FormBuilder's method which returns a Form type object.
* createView - It's a Form's method which creates a FormView object to be rendered in template.

In template *show.html.twig* :

```
<h1>{{ post.title }}</h1>
<p>{{ post.body }}</p>

<div>
    <a href="{{ path('cms_post_index') }}">Return to list</a>
    {{ form(form_delete) }}
</div>
```

Here , form() is a twig function which output a FormView object.

Next, let us learn how to create a new post, continuing our codes

```
    ....
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
                $this->get('session')->getFlashBag()->add('notice', 'You have successfully create a post.');

                return $this->redirect($this->generateUrl('cms_post_index'));
            }
        }
        return $this->render('KendoctorCmsBundle:Post:new.html.twig',array(
            'form' => $form->createView(),
            'post' => $post
        ));
   }
   ....
```

* Symfony\Component\HttpFoundation\Request - if you want to access Request object in controller, you can make it as
an argument of action, alos you can use getRequest() method of controller.
* FormBuilder - createFormBuilder, here, takes a $post data object. The data will be used as form field default value.
* add - is method FormBuilder object.
    * title - the name should be accessed from data object via getter and setter or public member variable
    * text - is a kind of form type, here, it will render this field as <input type="text" ..../>
    * array(...) - the third argument is an array which can hold lots of options, here, *constraints* means binding
validation contraints with this field, when $form's handleRequest is called, these constraints will check whether this
field value is valid or not. if not, $form->isValid will be false.
        * NotBlank - is a contraint for not allowing blank field
        * Length - is a contraint for limiting the length of text content in the field
* textarea - renders a field as <textarea>...</textarea>
* getMethod - determines which type of method the request is.
* get('session') - session is service name, about symfony DI service we will discuss it later, here, you should know
get('servicename') returns a service. getFlashBag is a method of session object, which is mentioned before in template :
app.session.flashbag. *add* is a method of flashbag object which adds a key-value into flashbag's key-array.
* redirect - return a Symfony\Component\HttpFoundation\RedirectResponse object which will redirect the page with the first argument.
* handleRequest - gets data from request and binds the data with the form, while applying the fields constraints.
* isValid - verify whether the form data is valid or not.




