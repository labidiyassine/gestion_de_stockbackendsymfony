<?php

// src/Controller/ArticleController.php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/articles")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/create", name="api_article_create", methods={"POST"})
     */
    public function createArticle(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);


        $article = new Article();
        $article->setCodeArticle($data['codeArticle']);
        $article->setDesignation($data['designation']);
        $article->setPrixUnitaireHt($data['prixUnitaireHt']);
        $article->setTauxTva($data['tauxTva']);
        $article->setPrixUnitaireTtc($data['prixUnitaireTtc']);
        $article->setPhoto($data['photo']);
        $article->setCategory($data['category']);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($article);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Article created successfully'], Response::HTTP_CREATED);
    }
}