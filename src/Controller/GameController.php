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
        ->add('losercolor', ChoiceType::class,[
            'label' => 'Losers color',
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
        ->add('movecount', TextType::class,[
            'label' => 'Winners move count',
            'constraints' =>[
                new Assert\NotBlank([
                    'message' => 'This field can not be blank'
                ])
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
            $game->setLoserColor($form->get('losercolor')->getData());
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
        $nick = $user->getNick();

        $query = $entityManager->createQuery(
            'SELECT game
            FROM App\Entity\Game game
            WHERE game.Winner = :Nick OR game.Loser = :Nick'
        )->setParameter('Nick', $nick);

        $gamearray = $query->getResult();

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
            WHERE profile.WhiteGames >= 10 AND profile.BlackGames >= 10
            ORDER BY profile.Wins DESC'
        );

        //select first 10 results
        $query->setFirstResult(0);
        $query->setMaxResults(10);

        
        $profilearray = $query->getResult();

        return $this->render('game/leaderboard.html.twig', [
            'profilearray' => $profilearray,
        ]);
    } 

}