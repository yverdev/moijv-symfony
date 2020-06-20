<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user-list", name="list_user")
     */
    public function index(UserRepository $userRepository)
    {
        // $userList = $pdo->query("SELECT * from user")->fetchAll();
        $userList = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'user_list' => $userList,
        ]);
    }

    /**
     * @Route("/user/{id<\d+>}", name="user")
     * @Route("/profile", name="profile")
     */
    public function detail(User $user = null)
    {
        // si on n'a pas de contrôleur, cela signifie qu'on est passé par le profil
        if( $user === null ) {
            // on récupère l'utilisateur connecté
            $user = $this->getUser();
        }
        // si malgré tout on n'a pas réussi à récupérer un utilisateur
        // on redirige vers la page d'accueil
        if( $user === null ) {
            // header('Location: home'); exit;
            return $this->redirectToRoute('home');
        }
        return $this->render('user/detail.html.twig', [
            'user' => $user,
        ]);
    }
}