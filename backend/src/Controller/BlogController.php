<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Blog;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class BlogController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/blogs', name: 'blog_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $blogs = $this->entityManager->getRepository(Blog::class)->findAll();

        $data = array_map(static function ($blog) {
            return [
                'id' => $blog->getId(),
                'title' => $blog->getTitle(),
                'description' => $blog->getDescription(),
                'author' => $blog->getAuthor(),
                'date' => $blog->getDate()->format('d-m-Y'),
                'img' => $blog->getImg(),
            ];
        }, $blogs);

        return $this->json($data);
    }

    #[Route('/blog/show/{id}', name: 'blog_show', methods: ['GET'])]
    public function showBlog(int $id): JsonResponse
    {
        $blog = $this->entityManager->getRepository(Blog::class)->find($id);

        if (!$blog) {
            return $this->json(['error' => 'Blog not found'], 404);
        }
        $data = [
            'id' => $blog->getId(),
            'title' => $blog->getTitle(),
            'description' => $blog->getDescription(),
            'author' => $blog->getAuthor(),
            'date' => $blog->getDate()->format('d-m-Y'),
            'img' => $blog->getImg(),
        ];

        return $this->json($data);
    }

    /**
     * @throws \JsonException
     */
    #[Route('/add/blog', name: 'blog_create', methods: ['POST'])]
//    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $blog = new Blog();
        $blog->setTitle($data['title'])
            ->setDescription($data['description'])
//            ->setImg($data['img'])
            ->setAuthor($data['author'])
            ->setDate(new \DateTime('now'));

        $this->entityManager->persist($blog);
        $this->entityManager->flush();

        return $this->json(['message' => 'Blog created successfully'], 201);
    }

    /**
     * @throws \JsonException
     */
    #[Route('/blog/{id}', name: 'blog_update', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN')]
    public function update(Request $request, Blog $blog): JsonResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $blog->setTitle($data['title'])
            ->setDescription($data['description'])
            ->setImg($data['img'])
            ->setAuthor($data['author'])
            ->setDate((new \DateTime('now')));


        $this->entityManager->flush();

        return $this->json(['message' => 'Blog updated successfully']);
    }

    #[Route('/blog/{id}', name: 'blog_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Blog $blog): JsonResponse
    {
        $this->entityManager->remove($blog);
        $this->entityManager->flush();

        return $this->json(['message' => 'Blog deleted successfully']);
    }
}