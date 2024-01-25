<?php

// src/Controller/ArticleController.php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/Articles")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/create", name="api_Article_create", methods={"POST"})
     */
    public function createArticle(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
    
        if (!isset($data['CategoryId'])) {
            return new JsonResponse(['error' => 'CategoryId is missing in the request data'], Response::HTTP_BAD_REQUEST);
        }
    
        $CategoryId = $data['CategoryId'];
    
        $entityManager = $this->getDoctrine()->getManager();
    
        $existingCategory = $entityManager->getRepository(Category::class)->find($CategoryId);
    
        if (!$existingCategory) {
            return new JsonResponse(['error' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }
    
        $Article = new Article();
        $Article->setCodeArticle($data['codeArticle']);
        $Article->setDesignation($data['designation']);
        $Article->setPrixUnitaireHt($data['prixUnitaireHt']);
        $Article->setTauxTva($data['tauxTva']);
        $Article->setPrixUnitaireTtc($data['prixUnitaireTtc']);
        $Article->setPhoto($data['photo']);
        $Article->setCategory($existingCategory);
    
        $entityManager->persist($Article);
        $entityManager->flush();
    
        return new JsonResponse(['message' => 'Article created successfully'], Response::HTTP_CREATED);
    }
    
    /**
     * @Route("/articles/{id}", name="get_article_by_id", methods={"GET"})
     */
    public function getArticleById($id): Response
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        if (!$article) {
            return new JsonResponse(['message' => 'Article not found'], Response::HTTP_NOT_FOUND);
        }

        $articleData = [
            'codeArticle' => $article->getCodeArticle(),
            'designation' => $article->getDesignation(),
            'prixUnitaireHt' => $article->getPrixUnitaireHt(),
            'tauxTva' => $article->getTauxTva(),
            'prixUnitaireTtc' => $article->getPrixUnitaireTtc(),
            'photo' => $article->getPhoto(),
            'category' => $article->getCategory(),
        ];

        return new JsonResponse($articleData, Response::HTTP_OK);
    }
    /**
     * @Route("/articles", name="get_all_articles", methods={"GET"})
     */
    public function getAllArticles(): Response
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

        if (!$articles) {
            return new JsonResponse(['message' => 'No articles found'], Response::HTTP_NOT_FOUND);
        }

        $articlesData = [];

        foreach ($articles as $article) {
            $articleData = [
                'codeArticle' => $article->getCodeArticle(),
                'designation' => $article->getDesignation(),
                'prixUnitaireHt' => $article->getPrixUnitaireHt(),
                'tauxTva' => $article->getTauxTva(),
                'prixUnitaireTtc' => $article->getPrixUnitaireTtc(),
                'photo' => $article->getPhoto(),
                'category' => $article->getCategory(),
            ];

            $articlesData[] = $articleData;
        }

        return new JsonResponse($articlesData, Response::HTTP_OK);
    }
    /**
 * @Route("/articles/search/{codeArticle}", name="search_article_by_code", methods={"GET"})
 */
public function searchArticleByCode($codeArticle): Response
{
    $article = $this->getDoctrine()->getRepository(Article::class)->findOneBy(['codeArticle' => $codeArticle]);

    if (!$article) {
        return new JsonResponse(['message' => 'Article not found'], Response::HTTP_NOT_FOUND);
    }

    $articleData = [
        'codeArticle' => $article->getCodeArticle(),
        'designation' => $article->getDesignation(),
        'prixUnitaireHt' => $article->getPrixUnitaireHt(),
        'tauxTva' => $article->getTauxTva(),
        'prixUnitaireTtc' => $article->getPrixUnitaireTtc(),
        'photo' => $article->getPhoto(),
        'category' => $article->getCategory(),
    ];

    return new JsonResponse($articleData, Response::HTTP_OK);
}
/**
     * @Route("/delete/{id}", name="delete_article_by_id", methods={"POST"})
     */
    public function deleteArticleById($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            return new JsonResponse(['message' => 'Article not found'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($article);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Article deleted successfully'], Response::HTTP_OK);
    }
    
}