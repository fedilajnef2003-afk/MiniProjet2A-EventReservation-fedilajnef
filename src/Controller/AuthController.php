<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Admin;
use App\Form\AdminRegistrationType;
use App\Form\UserRegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    #[Route(path: '/register', name: 'app_register')]
    public function auth(

        Request $request, 
        AuthenticationUtils $authenticationUtils,
        UserPasswordHasherInterface $userPasswordHasher, 
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // ── LOGIN LOGIC ──────────────────────────────────────
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        // ── REGISTRATION LOGIC ────────────────────────────────
        // We create temporary objects for form bound
        $client = new User();
        $adminObj = new Admin();
        
        $clientRegForm = $this->createForm(UserRegistrationType::class, $client, ['attr' => ['id' => 'client_reg_form']]);
        $adminRegForm = $this->createForm(AdminRegistrationType::class, $adminObj, ['attr' => ['id' => 'admin_reg_form']]);
        
        // Handle Register POST
        $clientRegForm->handleRequest($request);
        $adminRegForm->handleRequest($request);
        
        if ($clientRegForm->isSubmitted() && $clientRegForm->isValid()) {
            return $this->processRegistration($client, $clientRegForm, $userPasswordHasher, $entityManager, false);
        }
        
        if ($adminRegForm->isSubmitted() && $adminRegForm->isValid()) {
            return $this->processRegistration($adminObj, $adminRegForm, $userPasswordHasher, $entityManager, true);
        }

        // Determine initial state based on route or request query
        $route = $request->attributes->get('_route');
        $isRegister = strpos($route, 'register') !== false;
        $isAdmin = $request->query->get('admin') === '1';


        return $this->render('auth/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'clientRegForm' => $clientRegForm->createView(),
            'adminRegForm' => $adminRegForm->createView(),
            'is_register' => $isRegister,
            'is_admin' => $isAdmin,
        ]);
    }

    private function processRegistration($user, $form, $hasher, $em, bool $isAdmin): Response
    {
        $user->setRoles($isAdmin ? ['ROLE_ADMIN'] : ['ROLE_USER']);
        $user->setPassword($hasher->hashPassword($user, $form->get('plainPassword')->getData()));
        $em->persist($user);
        $em->flush();

        $this->addFlash('success', 'Account created successfully. Please log in.');
        return $this->redirectToRoute('app_login', ['admin' => $isAdmin ? '1' : '0']);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // This method can be empty - it will be intercepted by the logout key on your firewall
    }
}
