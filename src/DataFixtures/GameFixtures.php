<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

use Faker\Factory;
use Faker\Generator;

use App\Entity\Profile;
use App\Entity\Game;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProfileRepository;

class GameFixtures extends Fixture implements FixtureGroupInterface
{
    /* 
    * Add random games
    * winners and losers are picked from existing profiles
    */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        
        $arr = $manager->createQueryBuilder('profile')
        ->select('p.nick')
        ->from('App\Entity\Profile', 'p')
        ->where('p.nick is not NULL')
        ->getQuery()
        ->getArrayResult();

        $nickArray = array_map(function($a){ return $a['nick']; }, $arr);

        for ($i = 0; $i < 100; $i++) 
        {
            $game = new Game();
            
            $game->setWinner($faker->randomElement($nickArray));
            $game->setLoser($faker->randomElement($nickArray));
            $game->setWinnerColor($faker->randomElement(['white', 'black']));

            if($game->getWinnerColor() == 'white')
            {
                $game->setLoserColor('black');
            }
            else
            {
                $game->setLoserColor('white');
            }
            
            $game->setMoveCount(mt_rand(3, 75));
            $game->setDateTime(date_create($time = "now"));

            $winner = $manager->getRepository(Profile::class)->findOneBySomeField($game->getWinner());
            $loser = $manager->getRepository(Profile::class)->findOneBySomeField($game->getLoser());
            $winner->setWins($winner->getWins() + 1);
            $loser->setDefeats($winner->getDefeats() + 1);

            if($game->getWinnerColor() == 'white')
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

            $manager->persist($game);
            $manager->persist($winner);
            $manager->persist($loser);
            $manager->flush();
        }
        
    }

    public static function getGroups(): array
    {
        return ['userGroup'];
    }
}
