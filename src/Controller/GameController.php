<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\Game;
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
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Repository\GameRepository;

class GameController extends AbstractController
{
    /**
     * Renders the form for adding a new game
     * Winner and loser is selected from the list of registered users
     */
    public function addGame(EntityManagerInterface $entityManager, Request $request): Response
    {
        //addgame form
        $default = array('message' => 'Default input value');
        $form = $this->createFormBuilder($default)
        ->setAction($this->generateUrl('app_game_add'))
        ->setMethod('POST')
        ->add('winner', EntityType::class,[
            'label' => 'Winner',
            'constraints' =>[
                new Assert\NotBlank([
                    'message' => 'This field can not be blank'
                ])
            ],
            'placeholder' => 'Select the Winner :)',
            'class' => Profile::class,
            'choice_label' => function (Profile $profile) {
                return sprintf(
                    '%s',
                    $profile->getNick()
                );
            },
        ])
        ->add('loser', EntityType::class,[
            'label' => 'Loser',
            'constraints' =>[
                new Assert\NotBlank([
                    'message' => 'This field can not be blank'
                ])
            ],
            'placeholder' => 'Select the loser :(',
            'class' => Profile::class,
            'choice_label' => function (Profile $profile) {
                return sprintf(
                    '%s',
                    $profile->getNick()
                );
            },
        ])
        ->add('winnercolor', ChoiceType::class,[
            'label' => 'Winners color',
            'constraints' =>[
                new Assert\NotBlank([
                    'message' => 'This field can not be blank'
                ])
            ],
            'attr' => array(
                'placeholder' => 'white/black'
            ),
            'choices' => [
                'White' => 'white',
                'Black' => 'black',
            ],
        ])
        ->add('movecount', IntegerType::class,[
            'label' => 'Winners move count',
            'constraints' =>[
                new Assert\NotBlank([
                    'message' => 'This field can not be blank'
                ]),
            ],
            'attr' => array(
                'placeholder' => 'number'
            ),
        ])
        ->add('send', SubmitType::class,[
            'label' => 'Submit',
            'attr'=> array('class'=>'btn-primary')
        ])
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $game = new Game();
          
            $game->setWinner($form->get('winner')->getData()->getNick());
            $game->setLoser($form->get('loser')->getData()->getNick());
            $game->setWinnerColor($form->get('winnercolor')->getData());

            if($form->get('winner')->getData()->getNick() == $form->get('loser')->getData()->getNick())
            {
                return $this->redirectToRoute('app_game_add_failure');
            }

            //set the other color accordingly
            if($form->get('winnercolor')->getData() == 'white')
            {
                $game->setWinnerColor('white');
                $game->setLoserColor('black');
            }
            else
            {
                $game->setWinnerColor('black');
                $game->setLoserColor('white');
            }

            $game->setMoveCount($form->get('movecount')->getData());
            $game->setDateTime(date_create($time = "now"));
            
            $winner = $form->get('winner')->getData();
            $winner->setWins($winner->getWins() + 1);
            $loser = $form->get('loser')->getData();
            $loser->setDefeats($loser->getDefeats() + 1);

            //increase winrate for colors + increase game count by colors (for leaderboard requirements)
            if($form->get('winnercolor')->getData() == 'white')
            {
                $winner->setWhiteWinrate($winner->getWhiteWinrate() + 1);
                $winner->setWhiteGames($winner->getWhiteGames() + 1);
                $loser->setBlackGames($loser->getWhiteGames() + 1);
            }
            else
            {
                $winner->setBlackWinrate($winner->getBlackWinrate() + 1);
                $winner->setBlackGames($winner->getBlackGames() + 1);
                $loser->setWhiteGames($loser->getWhiteGames() + 1);
            }
    
            $entityManager->persist($game);

            $entityManager->flush();

            return $this->redirectToRoute('app_game_add_success');
        }

        return $this->render('game/addgame.html.twig', [
            'form' => $form,
        ]); 
    } 

    /**
     * Shows all the games featuring the current logged-in user
     */
    public function showMyGames(EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $this->getUser();
        $nick = null;
        $gamearray = null;

        if($user)
        {
            $nick = $user->getNick();
            $query = $entityManager->createQuery(
                'SELECT game
                FROM App\Entity\Game game
                WHERE game.winner = :nick OR game.loser = :nick'
            )->setParameter('nick', $nick);
    
            $gamearray = $query->getResult();
        }

        return $this->render('game/mygames.html.twig', [
            'gamearray' => $gamearray,
        ]);
    } 

    /**
     * Shows the winner leaderboard
     */
    public function showLeaderboard(EntityManagerInterface $entityManager, Request $request): Response
    {
        
        //select by the minimum games condition
        $query = $entityManager->createQuery(
            'SELECT profile
            FROM App\Entity\Profile profile
            WHERE profile.whiteGames >= 10 AND profile.blackGames >= 10
            ORDER BY profile.wins DESC'
        );

        //select first 10 results
        $query->setFirstResult(0);
        $query->setMaxResults(10);

        
        $profileArray = $query->getResult();

        return $this->render('game/leaderboard.html.twig', [
            'profilearray' => $profileArray,
        ]);
    } 

    /**
     * Confirmation of succesful game creation
     * Redirects to Add Game form
     */
    public function addedGame(EntityManagerInterface $entityManager, Request $request): Response
    {
        //register success confirmation
        $form = $this->createFormBuilder()
        ->add('send', SubmitType::class,[
            'label' => 'Add another one',
            'attr'=> array('class'=>'btn btn-primary mt-2'),
        ])
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            return $this->redirectToRoute('app_game_add');
        }
            
        return $this->render('game/game_success.html.twig', [
            'form' => $form,
        ]);
        
    }

    /**
     * Confirmation of unsuccesful game creation
     * Redirects to Add Game form
     */
    public function addedGameFailed(EntityManagerInterface $entityManager, Request $request): Response
    {
        //register success confirmation
        $form = $this->createFormBuilder()
        ->add('send', SubmitType::class,[
            'label' => 'Add another one',
            'attr'=> array('class'=>'btn btn-primary mt-2'),
        ])
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            return $this->redirectToRoute('app_game_add');
        }
            
        return $this->render('game/game_failure.html.twig', [
            'form' => $form,
        ]);
        
    }

}