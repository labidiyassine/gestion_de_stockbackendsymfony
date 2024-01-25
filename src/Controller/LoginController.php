<?php

namespace App\Controller;

use App\Entity\Utilisateurs; // Include the appropriate User entity class
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="login", methods={"POST"})
     */
    public function login(Request $request, UserPasswordEncoderInterface $passwordEncoder, JWTTokenManagerInterface $jwtManager)
    {
        $data = json_decode($request->getContent(), true);

        $user = $this->getDoctrine()->getRepository(Utilisateurs::class)->findOneBy(['email' => $data['email']]);

        if (!$user || !$passwordEncoder->isPasswordValid($user, $data['motDePasse'])) {
            throw new BadCredentialsException('Invalid credentials');
        }

        $token = $jwtManager->create($user);

        return new JsonResponse(['token' => $token]);
    }
}
