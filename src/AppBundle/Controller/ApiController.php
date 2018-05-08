<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;

use AppBundle\Entity\Post;

Class ApiController extends FOSRestController {

    /**
     * @Rest\Get("/post")
     */
    public function getPostsAction()
    {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:Post')->findAll();
        if ($restresult === null) {
            $response = array(
              'status' => 'NOk',
              'message' => 'There are no posts exist'
            );
            return $response;
        }
        return $restresult;
    }

    /**
     * @Rest\Get("/post/{id}")
     */
    public function getPostByIdAction($id)
    {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:Post')->find($id);
        if ($singleresult === null) {
            $response = array(
              'status' => 'NOk',
              'message' => 'Post not found'
            );
            return $response;
        }
        return $singleresult;
    }

    /**
     * @Rest\Post("/post")
     */
    public function addPostAction(Request $request)
    {
        $data = new Post();
        $title = $request->get('title');
        $description = $request->get('description');

        if(empty($title) || empty($description))
        {
            $response = array(
              'status' => 'NOk',
              'message' => 'Null values are not allowed'
            );
            return $response;
        }

        $data->setTitle($title);
        $data->setDescription($description);

        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();

        $response = array(
          'status' => 'Ok',
          'message' => 'Post Added Successfully'
        );
        return $response;
    }

    /**
     * @Rest\Put("/post/{id}")
     */
    public function updatePostAction($id,Request $request)
    {
        $data = new Post();
        $title = $request->get('title');
        $description = $request->get('description');

        $sn = $this->getDoctrine()->getManager();
        $post = $this->getDoctrine()->getRepository('AppBundle:Post')->find($id);
        if (empty($post)) {
            $response = array(
              'status' => 'NOk',
              'message' => 'Post not found'
            );
            return $response;
        }
        elseif(!empty($title) && !empty($description)){
            $post->setTitle($title);
            $post->setDescription($description);
            $sn->flush();

            $response = array(
              'status' => 'Ok',
              'message' => 'Post Updated Successfully'
            );
            return $response;
        }
        elseif(empty($title) && !empty($description)){
           $post->setDescription($description);
           $sn->flush();

           $response = array(
             'status' => 'Ok',
             'message' => 'Description Updated Successfully'
           );
           return $response;
        }
        elseif(!empty($title) && empty($description)){
          $post->setTitle($title);
          $sn->flush();

          $response = array(
           'status' => 'Ok',
           'message' => 'Title Updated Successfully'
          );
          return $response;
        }
        else {
          $response = array(
           'status' => 'Ok',
           'message' => 'Post title or description cannot be empty'
          );
          return $response;
        }
    }

    /**
     * @Rest\Delete("/post/{id}")
     */
    public function deletePostAction($id)
    {
        $data = new Post();
        $sn = $this->getDoctrine()->getManager();
        $post = $this->getDoctrine()->getRepository('AppBundle:Post')->find($id);
        if (empty($post)) {
            $response = array(
              'status' => 'NOk',
              'message' => 'Post not found'
            );
            return $response;
        }
        else {
            $sn->remove($post);
            $sn->flush();
            $response = array(
              'status' => 'Ok',
              'message' => 'Post deleted successfully'
            );
        }

        return $response;
    }


}
