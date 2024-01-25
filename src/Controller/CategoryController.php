<?php

// src/Controller/CategoryController.php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/Categorys")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/create", name="api_Category_create", methods={"POST"})
     */
    public function createCategory(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);


        $Category = new Category();
        $Category->setCode($data['code']);
        $Category->setDesignation($data['designation']);
        $Category->setIdEntreprise($data['idEntreprise']);
       
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($Category);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Category created successfully'], Response::HTTP_CREATED);
    }
    
    /**
     * @Route("/Categorys/{id}", name="get_Category_by_id", methods={"GET"})
     */
    public function getCategoryById($id): Response
    {
        $Category = $this->getDoctrine()->getRepository(Category::class)->find($id);

        if (!$Category) {
            return new JsonResponse(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        $CategoryData = [
            'id' => $Category->getId(),
            'designation' => $Category->getDesignation(),
            'code' => $Category->getCode(),
            'Designation' => $Category->getDesignation(),
            
        ];

        return new JsonResponse($CategoryData, Response::HTTP_OK);
    }
    /**
     * @Route("/Categorys", name="get_all_Categorys", methods={"GET"})
     */
    public function getAllCategorys(): Response
    {
        $Categorys = $this->getDoctrine()->getRepository(Category::class)->findAll();

        if (!$Categorys) {
            return new JsonResponse(['message' => 'No Categorys found'], Response::HTTP_NOT_FOUND);
        }

        $CategorysData = [];

        foreach ($Categorys as $Category) {
            $CategoryData = [
                'id' => $Category->getId(),
                'designation' => $Category->getDesignation(),
                'code' => $Category->getCode(),
                'Designation' => $Category->getDesignation(),
                ];

            $CategorysData[] = $CategoryData;
        }

        return new JsonResponse($CategorysData, Response::HTTP_OK);
    }
    /**
 * @Route("/Categorys/search/{codeCategory}", name="search_Category_by_code", methods={"GET"})
 */
public function searchCategoryByCode($codeCategory): Response
{
    $Category = $this->getDoctrine()->getRepository(Category::class)->findOneBy(['code' => $codeCategory]);

    if (!$Category) {
        return new JsonResponse(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
    }

    $CategoryData = [
        'id' => $Category->getId(),
                'designation' => $Category->getDesignation(),
                'code' => $Category->getCode(),
                'Designation' => $Category->getDesignation(),
    ];

    return new JsonResponse($CategoryData, Response::HTTP_OK);
}
/**
     * @Route("/delete/{id}", name="delete_Category_by_id", methods={"POST"})
     */
    public function deleteCategoryById($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Category = $entityManager->getRepository(Category::class)->find($id);

        if (!$Category) {
            return new JsonResponse(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($Category);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Category deleted successfully'], Response::HTTP_OK);
    }
    
}