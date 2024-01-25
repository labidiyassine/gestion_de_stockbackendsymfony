<?php

namespace App\Controller;

use App\Entity\Ventes;
use App\Entity\LigneVente;
use App\Entity\Article; // Add this line for the Article entity
use Doctrine\ORM\EntityManagerInterface; // Add this line for EntityManagerInterface
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/vente")
 */
class VenteController extends AbstractController
{
    /**
     * @Route("/create-vente", name="create_vente", methods={"POST"})
     */
    public function createVente(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $vente = new Ventes();
        $vente->setCode($data['code']);
        $vente->setDateVente(new \DateTime($data['dateVente']));
        $vente->setCommentaire($data['commentaire']);
        $vente->setIdEntreprise($data['idEntreprise']);

        $entityManager->persist($vente);
        $entityManager->flush();  

        if (isset($data['ligneVentes']) && is_array($data['ligneVentes'])) {
            foreach ($data['ligneVentes'] as $ligneVenteData) {
                $articleId = $ligneVenteData['articleId'];
                $existingArticle = $entityManager->getRepository(Article::class)->find($articleId);

                if (!$existingArticle) {
                    $articleErrors[] = "L'article avec l'ID " . $articleId . " n'existe pas";
                    continue;
                }

                if (
                    isset($ligneVenteData['articleId'], $ligneVenteData['quantite'], $ligneVenteData['prixUnitaire'], $ligneVenteData['idEntreprise'])
                ) {
                    $ligneVente = new LigneVente(); // Replace 'LigneVente' with your actual entity class

                    $article = $entityManager->getReference('App\Entity\Article', $ligneVenteData['articleId']);
                    $ligneVente->setArticle($article);
                    $ligneVente->setQuantite($ligneVenteData['quantite']);
                    $ligneVente->setPrixUnitaire($ligneVenteData['prixUnitaire']);
                    $ligneVente->setIdEntreprise($ligneVenteData['idEntreprise']);
                    $ligneVente->setVente($vente);

                    $entityManager->persist($ligneVente);
                }
            }

            $entityManager->flush();
        }

        return new JsonResponse(['message' => 'Vente created successfully'], Response::HTTP_CREATED);
    }
   
    
    /**
     * @Route("/{code}", name="find_vente_by_code", methods={"GET"})
     */
    public function findVenteByCode(string $code, EntityManagerInterface $entityManager): JsonResponse
    {
        $vente = $entityManager->getRepository(Ventes::class)->findOneBy(['code' => $code]);
    
        if (!$vente) {
            return new JsonResponse(['message' => 'Vente not found'], Response::HTTP_NOT_FOUND);
        }
    
        // Optionally, you can serialize the vente to customize the response
        $venteData = [
            'id' => $vente->getId(),
            'code' => $vente->getCode(),
            'dateVente' => $vente->getDateVente()->format('Y-m-d H:i:s'),
            'commentaire' => $vente->getCommentaire(),
            'idEntreprise' => $vente->getIdEntreprise(),
        ];
    
        return new JsonResponse($venteData);
    }
     /**
     * @Route("/", name="find_all_ventes", methods={"GET"})
     */
    public function findAllVentes(EntityManagerInterface $entityManager): JsonResponse
    {
        $ventes = $entityManager->getRepository(Ventes::class)->findAll();

        $ventesData = [];
        foreach ($ventes as $vente) {
            $ventesData[] = [
                'id' => $vente->getId(),
                'code' => $vente->getCode(),
                'dateVente' => $vente->getDateVente()->format('Y-m-d H:i:s'),
                'commentaire' => $vente->getCommentaire(),
                'idEntreprise' => $vente->getIdEntreprise(),
            ];
        }

        return new JsonResponse($ventesData);
    }
    
    /**
     * @Route("/{id}", name="delete_vente_by_id", methods={"DELETE"})
     */
    public function deleteVenteById(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $vente = $entityManager->getRepository(Ventes::class)->find($id);

        if (!$vente) {
            return new JsonResponse(['message' => 'Vente not found'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($vente);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Vente deleted successfully'], Response::HTTP_OK);
    }
    

    
}
