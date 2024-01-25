<?php

namespace App\Controller;
use App\Entity\Fournisseur;
use App\Entity\Article;
use App\Entity\CommandeFournisseur;
use App\Entity\LigneCommandeFournisseur;
use App\Repository\FournisseurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/comandefournisseur")
 */
class CommandeFournisseurController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/save-commande-fournisseur", name="fournisseur_save_commande_fournisseur", methods={"POST"})
     */
    public function saveCommandeFournisseur(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['fournisseurId'])) {
            return new JsonResponse(['message' => 'Fournisseur ID is not provided'], Response::HTTP_BAD_REQUEST);
        }
        $fournisseurId = $data['fournisseurId'];

        $existingFournisseur = $this->entityManager->getRepository(Fournisseur::class)->find($fournisseurId);

if (!$existingFournisseur) {
    return new JsonResponse(['message' => 'Fournisseur not found'], Response::HTTP_NOT_FOUND);
}

$commandeFournisseur = new CommandeFournisseur();
$commandeFournisseur->setCode($data['code']);
$commandeFournisseur->setDateCommande(new \DateTime($data['dateCommande']));
$commandeFournisseur->setEtatCommande($data['etatCommande']);
$commandeFournisseur->setIdEntreprise($existingFournisseur); // Set the Fournisseur entity

$this->entityManager->persist($commandeFournisseur);

        $ligneCommandeFournisseurErrors = [];

        if (!empty($data['ligneCommandeFournisseurs'])) {
            foreach ($data['ligneCommandeFournisseurs'] as $ligneCmdFournisseur) {
                $articleId = $ligneCmdFournisseur['articleId'];
                $existingArticle = $this->entityManager->getRepository(Article::class)->find($articleId);

                if (!$existingArticle) {
                    $ligneCommandeFournisseurErrors[] = "L'article avec l'ID " . $articleId . " n'existe pas";
                    continue;
                }

                $ligneCommandeFournisseurEntity = new LigneCommandeFournisseur();
                $ligneCommandeFournisseurEntity->setIdArticle($existingArticle);
                $ligneCommandeFournisseurEntity->setIdCommandeFournisseur($commandeFournisseur);
                $ligneCommandeFournisseurEntity->setPrixUnitaire($ligneCmdFournisseur['prixUnitaire']);
                $ligneCommandeFournisseurEntity->setQuantite($ligneCmdFournisseur['quantite']);
                $ligneCommandeFournisseurEntity->setIdEntreprise($data['idEntreprise']);

                $this->entityManager->persist($ligneCommandeFournisseurEntity);
            }
        }

        if (!empty($ligneCommandeFournisseurErrors)) {
            return new JsonResponse(['message' => 'Article n\'existe pas dans la BDD', 'errors' => $ligneCommandeFournisseurErrors], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Commande Fournisseur saved'], Response::HTTP_CREATED);
    }
    /**
 * @Route("/update-etat-commande/{id}", name="fournisseur_update_etat_commande", methods={"PUT"})
 */
public function updateEtatCommande(int $id, Request $request): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    $commandeFournisseur = $this->entityManager->getRepository(CommandeFournisseur::class)->find($id);

    if (!$commandeFournisseur) {
        return new JsonResponse(['message' => 'Commande Fournisseur not found'], Response::HTTP_NOT_FOUND);
    }

    if (!isset($data['etatCommande'])) {
        return new JsonResponse(['message' => 'Etat Commande is not provided'], Response::HTTP_BAD_REQUEST);
    }

    $commandeFournisseur->setEtatCommande($data['etatCommande']);
    $this->entityManager->flush();

    return new JsonResponse(['message' => 'Etat Commande updated successfully'], Response::HTTP_OK);
}
/**
 * @Route("/update-quantite-commande/{id}", name="fournisseur_update_quantite_commande", methods={"PUT"})
 */
public function updateQuantiteCommande(int $id, Request $request): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    $ligneCommandeFournisseur = $this->entityManager->getRepository(LigneCommandeFournisseur::class)->find($id);

    if (!$ligneCommandeFournisseur) {
        return new JsonResponse(['message' => 'LigneCommandeFournisseur not found'], Response::HTTP_NOT_FOUND);
    }

    if (!isset($data['quantite'])) {
        return new JsonResponse(['message' => 'Quantite is not provided'], Response::HTTP_BAD_REQUEST);
    }

    $ligneCommandeFournisseur->setQuantite($data['quantite']);
    $this->entityManager->flush();

    return new JsonResponse(['message' => 'Quantite updated successfully'], Response::HTTP_OK);
   


}
 /**
 * @Route("/update-fournisseur/{id}", name="fournisseur_update_fournisseur", methods={"PUT"})
 */
public function updateFournisseur(int $id, Request $request): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    $commandeFournisseur = $this->entityManager->getRepository(CommandeFournisseur::class)->find($id);

    if (!$commandeFournisseur) {
        return new JsonResponse(['message' => 'Commande Fournisseur not found'], Response::HTTP_NOT_FOUND);
    }

    if (!isset($data['fournisseurId'])) {
        return new JsonResponse(['message' => 'Fournisseur ID is not provided'], Response::HTTP_BAD_REQUEST);
    }

    $fournisseurId = $data['fournisseurId'];
    $existingFournisseur = $this->entityManager->getRepository(Fournisseur::class)->find($fournisseurId);

    if (!$existingFournisseur) {
        return new JsonResponse(['message' => 'Fournisseur not found'], Response::HTTP_NOT_FOUND);
    }

    $commandeFournisseur->setIdEntreprise($existingFournisseur);
    $this->entityManager->flush();

    return new JsonResponse(['message' => 'Fournisseur updated successfully'], Response::HTTP_OK);
}
/**
 * @Route("/update-article/{id}", name="fournisseur_update_article", methods={"PUT"})
 */
public function updateArticle(int $id, Request $request): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    $ligneCommandeFournisseur = $this->entityManager->getRepository(LigneCommandeFournisseur::class)->find($id);

    if (!$ligneCommandeFournisseur) {
        return new JsonResponse(['message' => 'LigneCommandeFournisseur not found'], Response::HTTP_NOT_FOUND);
    }

    if (!isset($data['articleId'])) {
        return new JsonResponse(['message' => 'Article ID is not provided'], Response::HTTP_BAD_REQUEST);
    }

    $articleId = $data['articleId'];
    $existingArticle = $this->entityManager->getRepository(Article::class)->find($articleId);

    if (!$existingArticle) {
        return new JsonResponse(['message' => 'Article not found'], Response::HTTP_NOT_FOUND);
    }

    $ligneCommandeFournisseur->setIdArticle($existingArticle);
    $this->entityManager->flush();

    return new JsonResponse(['message' => 'Article updated successfully'], Response::HTTP_OK);
}
/**
 * @Route("/find-commande-fournisseur/{id}", name="fournisseur_find_commande_fournisseur", methods={"GET"})
 */
public function findCommandeFournisseur(int $id): JsonResponse
{
    $commandeFournisseur = $this->entityManager->getRepository(CommandeFournisseur::class)->find($id);

    if (!$commandeFournisseur) {
        return new JsonResponse(['message' => 'Commande Fournisseur not found'], Response::HTTP_NOT_FOUND);
    }

    $serializedCommandeFournisseur = [
        'id' => $commandeFournisseur->getId(),
        'code' => $commandeFournisseur->getCode(),
        'dateCommande' => $commandeFournisseur->getDateCommande()->format('Y-m-d'),
        'etatCommande' => $commandeFournisseur->getEtatCommande(),
        'idEntreprise' => $commandeFournisseur->getIdEntreprise() ? $commandeFournisseur->getIdEntreprise()->getId() : null,
        'ligneCommandeFournisseur' => [], // You may include LigneCommandeFournisseur data similarly
    ];

    return new JsonResponse($serializedCommandeFournisseur, Response::HTTP_OK);
}

/**
 * @Route("/find-commande-fournisseur-by-code/{code}", name="fournisseur_find_commande_fournisseur_by_code", methods={"GET"})
 */
public function findCommandeFournisseurByCode(string $code): JsonResponse
{
    $commandeFournisseur = $this->entityManager->getRepository(CommandeFournisseur::class)->findOneBy(['code' => $code]);

    if (!$commandeFournisseur) {
        return new JsonResponse(['message' => 'Commande Fournisseur not found'], Response::HTTP_NOT_FOUND);
    }

    $serializedCommandeFournisseur = [
        'id' => $commandeFournisseur->getId(),
        'code' => $commandeFournisseur->getCode(),
        'dateCommande' => $commandeFournisseur->getDateCommande()->format('Y-m-d'),
        'etatCommande' => $commandeFournisseur->getEtatCommande(),
        'idEntreprise' => $commandeFournisseur->getIdEntreprise() ? $commandeFournisseur->getIdEntreprise()->getId() : null,
        'ligneCommandeFournisseur' => [], // You may include LigneCommandeFournisseur data similarly
    ];

    return new JsonResponse($serializedCommandeFournisseur, Response::HTTP_OK);
}
/**
 * @Route("/find-all-lignes-commandes-fournisseur/{commandeFournisseurId}", name="fournisseur_find_all_lignes_commandes_fournisseur", methods={"GET"})
 */
public function findAllLignesCommandesFournisseurByCommandeFournisseurId(int $commandeFournisseurId): JsonResponse
{
    $commandeFournisseur = $this->entityManager->getRepository(CommandeFournisseur::class)->find($commandeFournisseurId);

    if (!$commandeFournisseur) {
        return new JsonResponse(['message' => 'Commande Fournisseur not found'], Response::HTTP_NOT_FOUND);
    }

    $lignesCommandesFournisseur = $commandeFournisseur->getLigneCommandeFournisseur();

    // Manually serialize the LignesCommandesFournisseur using getter methods or Symfony Serializer
    $serializedLignesCommandesFournisseur = [];

    foreach ($lignesCommandesFournisseur as $ligneCommandeFournisseur) {
        $serializedLigneCommandeFournisseur = [
            'id' => $ligneCommandeFournisseur->getId(),
            'articleId' => $ligneCommandeFournisseur->getIdArticle()->getId(),
            'prixUnitaire' => $ligneCommandeFournisseur->getPrixUnitaire(),
            'quantite' => $ligneCommandeFournisseur->getQuantite(),
        ];

        $serializedLignesCommandesFournisseur[] = $serializedLigneCommandeFournisseur;
    }

    return new JsonResponse($serializedLignesCommandesFournisseur, Response::HTTP_OK);
}
/**
 * @Route("/delete-commande-fournisseur/{id}", name="fournisseur_delete_commande_fournisseur", methods={"DELETE"})
 */
public function deleteCommandeFournisseur(int $id): JsonResponse
{
    $commandeFournisseur = $this->entityManager->getRepository(CommandeFournisseur::class)->find($id);

    if (!$commandeFournisseur) {
        return new JsonResponse(['message' => 'Commande Fournisseur not found'], Response::HTTP_NOT_FOUND);
    }

    try {
        $this->entityManager->remove($commandeFournisseur);
        $this->entityManager->flush();
    } catch (\Exception $e) {
        return new JsonResponse(['message' => 'Failed to delete Commande Fournisseur', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    return new JsonResponse(['message' => 'Commande Fournisseur deleted successfully'], Response::HTTP_OK);
}




}
