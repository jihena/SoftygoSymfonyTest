<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Post;

Class ApiController extends Controller {

    /**
     * @Route("/posts/{id}", name="post_show")
     * @Method({"GET"})
     */
    public function showPost(Post $post) {

        $data = $this -> get('jms_serializer') -> serialize($post, 'json');

        $response = new Response($data);
        $response -> headers -> set('Content-Type', 'application/json');
        $response -> headers -> set('Access-Control-Allow-Origin', '*');

        return $response;

    }

    /**
     * @Route("/posts", name="post_list")
     * @Method({"GET"})
     */
    public function listPost() {

        $posts = $this -> getDoctrine() -> getRepository('AppBundle:Post') -> findAll();

        $data = $this -> get('jms_serializer') -> serialize($posts, 'json');

        $response = new Response($data);
        $response -> headers -> set('Content-Type', 'application/json');
        $response -> headers -> set('Access-Control-Allow-Origin', '*');

        return $response;

    }

    /**
     * @Route("/posts/create", name="post_create")
     * @Method({"POST"})
     */
    public function createPost(Request $request) {

        $data = $request -> getContent();
        $post = $this -> get('jms_serializer') -> deserialize($data, 'AppBundle\Entity\Post', 'json');

        $em = $this -> getDoctrine() -> getManager();
        $em -> persist($post);
        $em -> flush();

        return new Response('', Response::HTTP_CREATED);

    }

    /**
     * @Route("/posts/update/{id}", name="post_update")
     * @Method({"PUT"})
     */
    public function updatePost(Request $request, Post $post) {

        $data = $request -> getContent();

        $newpost = $this -> get('jms_serializer') -> deserialize($data, 'AppBundle\Entity\Post', 'json');

        $post -> setTitle($newpost -> getTitle());
        $post -> setDescription($newpost -> getDescription());

        $em = $this -> getDoctrine() -> getManager();
        $em -> persist($post);
        $em -> flush();

        // return new Response('', Response::HTTP_CREATED);

        $data = $this -> get('jms_serializer') -> serialize($post, 'json');

        $response = new Response($data);
        $response -> headers -> set('Content-Type', 'application/json');
        $response -> headers -> set('Access-Control-Allow-Origin', '*');

        return $response;

    }

    /**
     * @Route("/posts/delete/{id}", name="post_delete")
     * @Method({"DELETE"})
     */
    public function deletePost(Post $post) {

        $em = $this -> getDoctrine() -> getManager();
        $em -> remove($post);
        $em -> flush();

        // return new Response('', Response::HTTP_CREATED);
        $response = new Response();
        $response -> headers -> set('Content-Type', 'application/json');
        $response -> headers -> set('Access-Control-Allow-Origin', '*');

        return $response;

    }


}
