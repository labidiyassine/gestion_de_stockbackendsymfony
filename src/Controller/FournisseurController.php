<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/fournisseur")
 */
class FournisseurController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="fournisseur_index", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        $fournisseurs = $this->entityManager->getRepository(Fournisseur::class)->findAll();
        $data = [];

        foreach ($fournisseurs as $fournisseur) {
            $data[] = $this->serializeFournisseur($fournisseur);
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/{id}", name="fournisseur_show", methods={"GET"})
     */
    public function show(int $id): JsonResponse
    {
        $fournisseur = $this->entityManager->getRepository(Fournisseur::class)->find($id);

        if (!$fournisseur) {
            return new JsonResponse(['message' => 'Fournisseur not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($this->serializeFournisseur($fournisseur));
    }

    /**
     * @Route("/create", name="fournisseur_create", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $fournisseur = new Fournisseur();
        $fournisseur->setNom($data['nom']);
        $fournisseur->setPrenom($data['prenom']);
        $fournisseur->setAdresse($data['adresse']);
        $fournisseur->setPhoto($data['photo']);
        $fournisseur->setMail($data['mail']);
        $fournisseur->setNumTel($data['numTel']);
        $fournisseur->setIdEntreprise($data['idEntreprise']);

        $this->entityManager->persist($fournisseur);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Fournisseur created successfully'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}/delete", name="fournisseur_delete", methods={"DELETE"})
     */
    public function delete(int $id): JsonResponse
    {
        $fournisseur = $this->entityManager->getRepository(Fournisseur::class)->find($id);

        if (!$fournisseur) {
            return new JsonResponse(['message' => 'Fournisseur not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($fournisseur);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Fournisseur deleted successfully'], Response::HTTP_OK);
    }

    /**
     * Serialize a Fournisseur entity to an array.
     *
     * @param Fournisseur $fournisseur
     * @return array
     */
    private function serializeFournisseur(Fournisseur $fournisseur): array
    {
        return [
            'id' => $fournisseur->getId(),
            'nom' => $fournisseur->getNom(),
            'prenom' => $fournisseur->getPrenom(),
            'adresse' => $fournisseur->getAdresse(),
            'photo' => $fournisseur->getPhoto(),
            'mail' => $fournisseur->getMail(),
            'numTel' => $fournisseur->getNumTel(),
            'idEntreprise' => $fournisseur->getIdEntreprise(),
        ];
    }
}
