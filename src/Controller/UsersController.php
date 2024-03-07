<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Controller\ProfileController;
use App\Config\Routes\Routes\Routes;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ProfileRepository;

class UsersController extends AbstractController
{
    /**
     * Shows one registered user by Profile.Nickname
     */
    public function showUser(EntityManagerInterface $entityManager, string $name): Response
    {
        $user = $entityManager->getRepository(Profile::class)->findOneBySomeField($name);

        if (!$user) 
        {
            return new Response('No profile named -'.$name.'-  found...');
        }

        return new Response('User: '.$user->getNick());

        /*return $this->render('homepage/home.html.twig', [
        ]);*/
    } 
    
    /**
     * Shows all registered users
     */
    public function showUsers(EntityManagerInterface $entityManager): Response
    {
        $profileRepository = $entityManager->getRepository(Profile::class);

        $profiles = $profileRepository->findAll();

        return $this->render('profile/profiles_all.html.twig', [
            'profiles' => $profiles,
        ]);
    } 
}