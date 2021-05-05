<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\BlogPost;
use Opis\JsonSchema\MediaTypes\Json;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/{page}", name="blog_list", requirements={"page"="\d+"}, methods={"GET"})
     */
    public function list($page = 1, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(BlogPost::class);
        $items = $repository->findAll();
        $limit = $request->get('limit', 10);
        return $this->json([
            'page' => $page,
            'limit' => $limit,
            'data' => array_map(function(BlogPost $item) {
                return $this->generateUrl('blog_by_id', ['id' => $item->getId()]);
            }, $items)
        ]);
    }

    /**
     * @Route("/id/{id}", name="blog_by_id", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function post($id) 
    {
        return $this->json(
            $this->getDoctrine()->getRepository(BlogPost::class)->find($id)
        );
    }

    /**
     * @Route("/slug/{slug}", name="blog_by_slug", methods={"GET"})
     */
    public function postBySlug($slug)
    {
        return $this->json(
            $this->getDoctrine()->getRepository(BlogPost::class)->findBy(['slug' => $slug])
        );
    }

    /**
     * @Route("/add", name="blog_add", methods={"POST"})
     */
    public function add(Request $request)
    {
        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');

        $blogPost = $serializer->deserialize($request->getContent(), BlogPost::class, 'json');
        $em = $this->getDoctrine()->getManager();
        $em->persist($blogPost);
        $em->flush();

        return $this->json($blogPost);
    }

    /**
     * @Route("/delete/{id}", name="blog_delete", methods={"DELETE"})
     */
    public function delete(BlogPost $post) 
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}