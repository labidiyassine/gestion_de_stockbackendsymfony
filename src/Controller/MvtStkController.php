<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MvtStkRepository;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;

class MvtStkController extends AbstractController
{
    /**
     * @Route("/mvtstk/stock-reel/{idArticle}", name="stock_reel_article")
     */
    public function stockReelArticle(
        MvtStkRepository $mvtStkRepository,
        EntityManagerInterface $entityManager,
        Article $article
    ): Response {
        $query = $entityManager->createQuery(
            'SELECT SUM(m.quantite) 
            FROM App\Entity\MvtStk m 
            WHERE m.article = :article'
        );
        $query->setParameter('article', $article);

        $sumQuantite = $query->getSingleScalarResult();

        $mvtStkEntries = $mvtStkRepository->findBy(['Article' => $article]);


        return $this->render('mvtstk/stock_reel_article.html.twig', [
            'mvtStkEntries' => $mvtStkEntries,
            'sumQuantite' => $sumQuantite,
        ]);
    }
}
