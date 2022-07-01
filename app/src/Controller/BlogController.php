<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog")
 */
class BlogController
{
    private ManagerRegistry $doctrine;
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }
    /**
     * @Route("/")
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $blogsQuery = $this->doctrine->getRepository(BlogPost::class)->findAll();
        $blogs = [];

        foreach ($blogsQuery as $blog) {
            $blogs[] = [
                'id' => $blog->getId(),
                'title' => $blog->getTitle(),
                'content' => $blog->getContent(),
                'author' => $blog->getAuthor(),
                'published' => $blog->getPublished(),
                'slug' => $blog->getSlug(),
            ];
        }

        return new JsonResponse(['data' => $blogs]);
    }

    /**
     * @Route("/post/{slug}", methods={"GET"})
     * @param BlogPost $post
     * @return JsonResponse
     */
    public function post(BlogPost $post): JsonResponse
    {
        $data = [
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'content' => $post->getContent(),
            'author' => $post->getAuthor(),
            'published' => $post->getPublished(),
            'slug' => $post->getSlug(),
        ];
        return new JsonResponse(['data' => $data]);
    }

    /**
     * @Route("/post/{id}", methods={"DELETE"})
     * @param BlogPost $post
     * @return JsonResponse
     */
    public function delete(BlogPost $post): JsonResponse
    {
        $em = $this->doctrine->getManager();
        $em->remove($post);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/add", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent());
        $entityManager = $this->doctrine->getManager();
        $blogPost = new BlogPost();

        $blogPost->setTitle($requestData->title);
        $blogPost->setAuthor($requestData->author);
        $blogPost->setContent($requestData->content);
        $blogPost->setPublished(New \DateTime($requestData->published));
        $blogPost->setSlug($requestData->slug);

        $entityManager->persist($blogPost);
        $entityManager->flush();

        return new JsonResponse($blogPost);
    }
}
