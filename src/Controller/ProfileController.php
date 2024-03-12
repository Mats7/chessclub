<?php

namespace App\Controller;

use App\Config\Routes\Routes\Routes;
use App\Controller\PageController;
use App\Entity\Profile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class ProfileController extends AbstractController
{
    /**
     * Confirmation of succesful registration
     * Redirects to Sign-In
     */
    public function addedProfile(EntityManagerInterface $entityManager, Request $request): Response
    {
        //register success confirmation
        $form = $this->createFormBuilder()
        ->add('send', SubmitType::class,[
            'label' => 'Sign In',
            'attr'=> array('class'=>'btn btn-primary mt-2'),
        ])
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()){
            return $this->redirectToRoute('app_login');
        }
            
        return $this->render('profile/success.html.twig', [
            'form' => $form,
        ]);
        
    }

    /**
     * Confirmation of succesful profile change
     * Redirects back to Profile view
     */
    public function changedProfile(EntityManagerInterface $entityManager, Request $request): Response
    {
        //register success confirmation
        $form = $this->createFormBuilder()
        ->add('send', SubmitType::class,[
            'label' => 'Back to profile',
            'attr'=> array('class'=>'btn btn-primary mt-2'),
        ])
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()){
            return $this->redirectToRoute('app_profile_view');
        }
            
        return $this->render('profile/change_success.html.twig', [
            'form' => $form,
        ]);
        
    }

    /**
     * Renders a register form for new users
     * Persists user data into the database
     */
    function index(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, Request $request): Response
    {
        //register form
        $default = array('message' => 'Default input value');
        $form = $this->createFormBuilder($default)
        ->setAction($this->generateUrl('app_register'))
        ->setMethod('POST')
        ->add('name', TextType::class,[
            'label' => 'Nickname',
            'constraints' =>[
                new Assert\NotBlank([
                    'message' => 'This field can not be blank'
                ])
            ],
        ])
        ->add('email', TextType::class,[
            'label' => 'Email',
            'constraints' =>[
                new Assert\Email([
                    'message'=>'This is not the correct email format'
                ]),
                new Assert\NotBlank([
                    'message' => 'This field can not be blank'
                ])
            ],
        ])
        ->add('phone', TextType::class,[
            'label' => 'Phone number',
        ])
        ->add('password', PasswordType::class,[
            'label' => 'Password',
            'attr' => array('type' => 'password'),
            'mapped' => false
        ])
        ->add('send', SubmitType::class,[
            'label' => 'Submit',
            'attr'=> array('class'=>'btn-primary')
        ])
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $profile = new Profile();
            $profile->setNick($form->get('name')->getData());
            $profile->setPhone($form->get('phone')->getData());
            $profile->setEmail($form->get('email')->getData());
            $profile->setWins(null);
            $profile->setDefeats(null);
            $profile->setWhiteWinrate(null);
            $profile->setBlackWinrate(null);
            $profile->setWhiteGames(null);
            $profile->setBlackGames(null);
            $profile->setDateJoined(date_create($time = "now"));

            $plaintextPassword = $form->get('password')->getData();

            $hashedPassword = $passwordHasher->hashPassword(
                $profile,
                $plaintextPassword
            );
            $profile->setPassword($hashedPassword);
    
            $entityManager->persist($profile);

            $entityManager->flush();

            return $this->redirectToRoute('app_register_success');
        }

        return $this->render('profile/form.html.twig', [
            'form' => $form,
        ]);  

    }

    /**
     * Shows profile info
     * Available only for the registered user
     */
    public function viewProfile(EntityManagerInterface $entityManager, Request $request): Response
    {   
            $user = $this->getUser();

            if($user)
            {
                $nick = $user->getNick();
                $email = $user->getEmail();
                $phone = $user->getPhone();
                $wins = $user->getWins();
                $defeats = $user->getDefeats();
                $whiteWr = $user->getWhiteWinrate();
                $blackWr = $user->getBlackWinrate();
                $dateJoined = $user->getDateJoined();
            }
            else
            {
                $nick = null;
                $email = null;
                $phone = null;
                $wins = null;
                $defeats = null;
                $whiteWr = null;
                $blackWr = null;
                $dateJoined = null;
            }
            
            return $this->render('profile/view.html.twig', [
                'nick' => $nick,
                'email' => $email,
                'phone' => $phone,
                'wins' => $wins,
                'defeats' => $defeats,
                'whiteWr' => $whiteWr,
                'blackWr' => $blackWr,
                'dateJoined' => $dateJoined,
            ]);
    }

    /**
     * Updates user info (similar to registration)
     */
    public function saveProfile(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, Request $request): Response
    {   
            $profile = $this->getUser();
            
            $nick = $request->request->get('inputNick');
            $email = $request->request->get('inputEmail');
            $phone = $request->request->get('inputPhone');
            $wins = $profile->getWins();
            $defeats = $profile->getDefeats();
            $whiteWr = $profile->getWhiteWinrate();
            $blackWr = $profile->getBlackWinrate();
            $dateJoined = $profile->getDateJoined();
            $password = $request->request->get('inputPassword');

            $profile->setNick($nick);
            $profile->setPhone($phone);
            $profile->setEmail($email);
            $profile->setWins($wins);
            $profile->setDefeats($defeats);
            $profile->setWhiteWinrate($whiteWr);
            $profile->setBlackWinrate($blackWr);
            $profile->setDateJoined($dateJoined);

            $plaintextPassword = $password;

            $hashedPassword = $passwordHasher->hashPassword(
                $profile,
                $plaintextPassword
            );

            if(!empty($password))
            {
                $profile->setPassword($hashedPassword);
            }
            
            $entityManager->persist($profile);

            $entityManager->flush();

            return $this->redirectToRoute('app_profile_change_success');
            
            return $this->render('profile/view.html.twig', [
                'nick' => $nick,
                'email' => $email,
                'phone' => $phone,
                'wins' => $wins,
                'defeats' => $defeats,
                'whiteWr' => $whiteWr,
                'blackWr' => $blackWr,
                'dateJoined' => $dateJoined,
            ]);
    }
}
