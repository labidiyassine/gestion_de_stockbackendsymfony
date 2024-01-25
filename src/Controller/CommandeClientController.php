<?php

namespace App\Controller;

use App\Entity\CommandeClient;
use App\Entity\LigneCommandeClients;
use App\Entity\Client;
use App\Entity\Article;
use App\Repository\CommandeClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/commande")
 */
class CommandeClientController extends AbstractController
{
   
/**
 * @Route("/save", name="save_commande", methods={"POST"})
 */
public function saveCommande(Request $request, EntityManagerInterface $entityManager): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    

   

    $clientId = $data['clientId'];

    $existingClient = $entityManager->getRepository(Client::class)->find($clientId);

    if (!$existingClient) {
        return new JsonResponse(['message' => 'Client not found'], Response::HTTP_NOT_FOUND);
    }

   
    $commande = new CommandeClient();
    $commande->setClient($existingClient);
    $commande->setCode($data['code']);
    $commande->setDateCommande(new \DateTime($data['dateCommande']));
    $commande->setEtatCommande($data['etatCommande']);
    $commande->setIdEntreprise($data['idEntreprise']);

    $entityManager->persist($commande);

    $articleErrors = [];

    if (!empty($data['ligneCommandeClients'])) {
        foreach ($data['ligneCommandeClients'] as $ligCmdClt) {
            $articleId = $ligCmdClt['articleId'];
            $existingArticle = $entityManager->getRepository(Article::class)->find($articleId);
        
            if (!$existingArticle) {
                $articleErrors[] = "L'article avec l'ID " . $articleId . " n'existe pas";
                continue;
            }
        
            $ligneCommandeId = isset($ligCmdClt['id']) ? $ligCmdClt['id'] : null;
        
            if ($ligneCommandeId === null) {
                $ligneCommande = new LigneCommandeClients();
            } else {
                $ligneCommande = $entityManager->getRepository(LigneCommandeClients::class)->find($ligneCommandeId);
            }
        
            $ligneCommande->setArticle($existingArticle);
            $ligneCommande->setCommandeClient($commande);
            $ligneCommande->setIdEntreprise($data['idEntreprise']);
            $ligneCommande->setPrixUnitaire($ligCmdClt['prixUnitaire']);
            $ligneCommande->setQuantite($ligCmdClt['quantite']);
        
            $entityManager->persist($ligneCommande);
        }
        
    }

    if (!empty($articleErrors)) {
        return new JsonResponse(['message' => 'Article n\'existe pas dans la BDD', 'errors' => $articleErrors], Response::HTTP_BAD_REQUEST);
    }

    $entityManager->flush();

    return new JsonResponse(['message' => 'Commande saved'], Response::HTTP_CREATED);
}

    /**
     * @Route("/{id}/updateEtat", name="update_etat_commande", methods={"PATCH"})
     */
public function updateEtatCommande(CommandeClient $commande, Request $request, EntityManagerInterface $entityManager): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    if (isset($data['etatCommande'])) {
        $commande->setEtatCommande($data['etatCommande']);

        $entityManager->flush();

        return new JsonResponse(['message' => 'Commande state updated']);
    }

    return new JsonResponse(['message' => 'Invalid data provided'], Response::HTTP_BAD_REQUEST);
}


    
   

    /**
     * @Route("/{id}/updateClient", name="update_client_commande", methods={"PATCH"})
     */
    public function updateClient(CommandeClient $commande, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
       
        $data = json_decode($request->getContent(), true);
        $clientId = $data['clientId'];

        $existingClient = $entityManager->getRepository(Client::class)->find($clientId);
    
        if (!$existingClient) {
            return new JsonResponse(['message' => 'Client not found'], Response::HTTP_NOT_FOUND);
        }
            $commande->setClient($existingClient);
    
            $entityManager->flush();
    
            return new JsonResponse(['message' => ' idClient updated']);
        
    }

    /**
     * @Route("/{id}/updateArticle", name="update_article_commande", methods={"PATCH"})
     */
    public function updateArticle(LigneCommandeClients $commande, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
       

        $data = json_decode($request->getContent(), true);
        $ArticleId = $data['ArticleId'];

        $existingClient = $entityManager->getRepository(Article::class)->find($ArticleId);
    
        if (!$existingClient) {
            return new JsonResponse(['message' => 'Article not found'], Response::HTTP_NOT_FOUND);
        }
            $commande->setArticle($existingClient);
    
            $entityManager->flush();
    
            return new JsonResponse(['message' => ' idArticle updated']);
    }

    /**
     * @Route("/{id}/deleteArticle/{articleId}", name="delete_article_commande", methods={"DELETE"})
     */
    public function deleteArticle(CommandeClient $commande, $articleId, EntityManagerInterface $entityManager): JsonResponse
    {
        

        $entityManager->flush();

        return new JsonResponse(['message' => 'Article deleted from Commande']);
    }

    /**
     * @Route("/{id}", name="find_by_id", methods={"GET"})
     */
    public function findById(CommandeClient $commande): JsonResponse
    {
        return $this->json($commande);
    }

    /**
     * @Route("/findByCode/{code}", name="find_by_code", methods={"GET"})
     */
    public function findByCode(string $code, CommandeClientRepository $repository): JsonResponse
    {
        $commande = $repository->findOneBy(['code' => $code]);

        if (!$commande) {
            return new JsonResponse(['message' => 'Commande not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($commande);
    }

    /**
     * @Route("/findAll", name="find_all", methods={"GET"})
     */
    public function findAll(CommandeClientRepository $repository): JsonResponse
    {
        $commandes = $repository->findAll();

        return $this->json($commandes);
    }
    /**
 * @Route("/{idCommande}/ligneCommande/{idLigneCommande}/updateQuantite", name="update_quantite_ligne_commande", methods={"PATCH"})
 */
public function updateQuantiteCommande($idCommande, $idLigneCommande, Request $request, EntityManagerInterface $entityManager): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    $this->checkIdCommande($idCommande);
    $this->checkIdLigneCommande($idLigneCommande);

    $quantite = isset($data['quantite']) ? $data['quantite'] : null;
    if ($quantite === null || $quantite == 0) {
        return new JsonResponse(['message' => "Impossible de modifier la quantité de la ligne commande avec une quantité null ou zéro"], Response::HTTP_BAD_REQUEST);
    }

    $commandeClient = $entityManager->getRepository(CommandeClient::class)->find($idCommande);

    if (!$commandeClient) {
        return new JsonResponse(['message' => 'Commande not found'], Response::HTTP_NOT_FOUND);
    }

    $ligneCommandeClientOptional = $this->findLigneCommandeClient($idLigneCommande);

    if (!$ligneCommandeClientOptional->isPresent()) {
        return new JsonResponse(['message' => 'Ligne commande non trouvée'], Response::HTTP_NOT_FOUND);
    }

    $ligneCommandeClient = $ligneCommandeClientOptional->get();
    $ligneCommandeClient->setQuantite($quantite);
    $entityManager->flush();

    return new JsonResponse(['message' => 'Quantité de la ligne commande mise à jour']);
}

}
