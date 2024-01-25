<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/clients")
 */
class ClientController extends AbstractController
{
    /**
     * @Route("/", name="get_all_clients", methods={"GET"})
     */
    public function getAllClients(ClientRepository $clientRepository): JsonResponse
    {
        $clients = $clientRepository->findAll();

        return $this->json($clients, Response::HTTP_OK, [], ['groups' => 'client']);
    }

    /**
     * @Route("/{id}", name="get_client_by_id", methods={"GET"})
     */
    public function getClientById(Client $client): JsonResponse
    {
        return $this->json($client, Response::HTTP_OK, [], ['groups' => 'client']);
    }

    /**
     * @Route("/add", name="add_client", methods={"POST"})
     */
    public function addClient(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $client = new Client();
        $client->setNom($data['nom']);
        $client->setPrenom($data['prenom']);
        $client->setPhoto($data['photo']);
        $client->setNumTel($data['numTel']);
        $client->setIdEntreprise($data['idEntreprise']);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($client);
        $entityManager->flush();

        return $this->json(['message' => 'Client created successfully'], Response::HTTP_CREATED);
    }

   /**
 * @Route("/delete/{id}", name="delete_client", methods={"DELETE"})
 */
public function deleteClient(Client $client): JsonResponse
{
    dump($client); // Add this line for debugging

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($client);
    $entityManager->flush();

    return new JsonResponse(['message' => 'client deleted successfully'], Response::HTTP_OK);
}


    /**
     * @Route("/find-by-id/{id}", name="find_client_by_id", methods={"GET"})
     */
    public function findClientById(ClientRepository $clientRepository, int $id): JsonResponse
    {
        $client = $clientRepository->find($id);
        $clientData = [
            'id' => $client->getId(),
                    'nom' => $client->getNom(),
                    'prenom' => $client->getPrenom(),
                    'Photo' => $client->getPhoto(),
                    'numtel' => $client->getNumTel(),
                    'identreprise' => $client->getIdEntreprise(),
                    
                    
        ];

        if (!$client) {
            return $this->json(['message' => 'Client not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($clientData, Response::HTTP_OK);
    }
}
