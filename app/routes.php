<?php

use Symfony\Component\HttpFoundation\Request;
use fayme\Domain\User;
use fayme\Form\Type\ProfilType;
use fayme\Form\Type\UserType;

$users = $app['dao.user']->findAll();


// Page d'acceuil
$app->get('/', "fayme\Controller\HomeController::indexAction")->bind('home');

// Back-office
$app->get('/admin', "fayme\Controller\AdminController::adminAction")->bind('admin');

// Ajout d'un utilisateur
$app->match('/admin/user/add', "fayme\Controller\AdminController::addUserAction")->bind('admin_user_add');

// Modification d'un utilisateur
$app->match('/admin/user/{id}/edit', "fayme\Controller\AdminController::editUserAction")->bind('admin_user_edit');

// Supression d'un utilisateur
$app->match('/admin/user/{id}/delete', "fayme\Controller\AdminController::deleteUserAction")->bind('admin_user_delete');

// Connexion
$app->get('/login', "fayme\Controller\HomeController::loginAction")->bind('login');

// Inscription
$app->match('/inscription', "fayme\Controller\HomeController::inscriptionAction")->bind('inscription');

// Profil 
$app->match('/profil', "fayme\Controller\HomeController::profilAction" )->bind('profil');

// Profil utilisateur
$app->match('/usrpage', "fayme\Controller\HomeController::usrpageAction" )->bind('usrpage');

// API : get all users
$app->get('/api/users', "fayme\Controller\ApiController::getUsersAction")
->bind('api_users');

// API : get a user
$app->get('/api/user/{id}', "fayme\Controller\ApiController::getUserAction")
->bind('user');

// API : create a user
$app->post('/api/user', "fayme\Controller\ApiController::addUserAction")
->bind('api_user_add');

// API : remove a user
$app->delete('/api/user/{id}', "fayme\Controller\ApiController::deleteUserAction")
->bind('api_user_delete');


//
//
// API : get all users
$app->get('/api/users', function() use ($app) {
    $users = $app['dao.user']->findAll();
    // Convert an array of objects ($users) into an array of associative arrays ($responseData)
    $responseData = array();
    foreach ($users as $user) {
        $responseData[] = array(
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'ville' => $user->getVille()
            );
    }
    // Create and return a JSON response
    return $app->json($responseData);
})->bind('api_users');

// API : get a user
$app->get('/api/user/{id}', function($id) use ($app) {
    $user = $app['dao.user']->find($id);
    // Convert an object ($user) into an associative array ($responseData)
    $responseData = array(
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'ville' => $user->getVille()
        );
    // Create and return a JSON response
    return $app->json($responseData);
})->bind('api_user');


// API : create a new user
$app->post('/api/user', function(Request $request) use ($app) {
    // Check request parameters
    if (!$request->request->has('username')) {
        return $app->json('Missing required parameter: username', 400);
    }
    if (!$request->request->has('ville')) {
        return $app->json('Missing required parameter: ville', 400);
    }
    // Build and save the new user
    $user = new User();
    $user->setUsername($request->request->get('username'));
    $user->setVille($request->request->get('ville'));
    $app['dao.user']->save($user);
    // Convert an object ($user) into an associative array ($responseData)
    $responseData = array(
        'id' => $user->getId(),
        'username' => $user->getUsername(),
        'ville' => $user->getVille()
        );
    return $app->json($responseData, 201);  // 201 = Created
})->bind('api_user_add');

// API : delete an existing user
$app->delete('/api/user/{id}', function ($id, Request $request) use ($app) {
    // Delete the user
    $app['dao.user']->delete($id);
    return $app->json('No Ville', 204);  // 204 = No ville
})->bind('api_user_delete');