<?php

use App\Controller\ProfileController;
use App\Controller\PageController;
use App\Controller\UsersController;
use App\Controller\SecurityController;
use App\Controller\GameController;
use App\DataFixtures\AppFixtures;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    $routes->add('app_register_success', '/register/success')->controller([ProfileController::class, 'addedProfile']);
    $routes->add('app_register', '/register')->controller([ProfileController::class, 'index']);
    $routes->add('app_profile_view', '/profile')->controller([ProfileController::class, 'viewProfile']);
    $routes->add('app_profile_change_success', '/profile/success')->controller([ProfileController::class, 'changedProfile']);
    $routes->add('app_profile_save', '/profile/save')->controller([ProfileController::class, 'saveProfile']);
    $routes->add('homepage', '/')->controller([PageController::class, 'homepage']);
    $routes->add('profile', '/profile/{name}')->controller([UsersController::class, 'showUser']);
    $routes->add('app_profiles', '/profiles')->controller([UsersController::class, 'showUsers']);
    $routes->add('app_login', '/login')->controller([SecurityController::class, 'login']);
    $routes->add('app_logout', '/logout')->controller([SecurityController::class, 'logout']);

    $routes->add('app_game_add', '/addgame')->controller([GameController::class, 'addGame']);
    $routes->add('app_game_add_form', '/addgameform')->controller([GameController::class, 'addGameForm']);
    $routes->add('app_game_add_success', '/addgame/success')->controller([GameController::class, 'addedGame']);
    $routes->add('app_game_add_failure', '/addgame/failure')->controller([GameController::class, 'addedGameFailed']);

    $routes->add('app_mygames', '/mygames')->controller([GameController::class, 'showMyGames']);
    $routes->add('app_leaderboard', '/leaderboard')->controller([GameController::class, 'showLeaderboard']);
    
    $routes->add('app_test_add_profiles', '/addprofiles')->controller([AppFixtures::class, 'load']);
};


?>