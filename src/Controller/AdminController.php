<?php

namespace fayme\Controller;

use fayme\Domain\Animal;
use fayme\Domain\Espece;
use fayme\Form\Type\animalType;
use fayme\Form\Type\EspeceType;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use fayme\Form\Type\UserAdminType;
use fayme\Domain\User;

class AdminController extends layoutController
{

    public function adminAction(Application $app)
    {
        $array = $this->renderCatEsp($app);
        $users = $app['dao.user']->findAll();
        $array['users'] = $users;
        return $app['twig']->render('admin.html.twig', $array);
    }



    public function addUserAction(Application $app, Request $request)
    {
        $array = $this->renderCatEsp($app);
        $user = new User();
        $userForm = $app['form.factory']->create(new userAdminType(), $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            // generate a random salt value
            $salt = substr(md5(time()), 0, 23);
            $user->setSalt($salt);
            $plainPassword = $user->getPassword();
            // find the default encoder
            $encoder = $app['security.encoder.digest'];
            // compute the encoded password
            $password = $encoder->encodePassword($plainPassword, $user->getSalt());
            $user->setPassword($password);
            $app['dao.user']->save($user);
            $app['session']->getFlashBag()->add('success', 'The user was successfully created.');
        }
        $array['title'] = 'Ajout d\'un utilisateur';
        $array['userForm'] = $userForm->createView();
        return $app['twig']->render('user_form.html.twig', $array);
    }

    public function editUserAction(Application $app, Request $request, $id)
    {
        $array = $this->renderCatEsp($app);
        $user = $app['dao.user']->find($id);
        $userForm = $app['form.factory']->create(new userAdminType(), $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            // generate a random salt value
            $salt = substr(md5(time()), 0, 23);
            $user->setSalt($salt);
            $plainPassword = $user->getPassword();
            // find the default encoder
            $encoder = $app['security.encoder.digest'];
            // compute the encoded password
            $password = $encoder->encodePassword($plainPassword, $user->getSalt());
            $user->setPassword($password);
            $app['dao.user']->save($user);
            $app['session']->getFlashBag()->add('success', 'The user was successfully created.');
        }
        $array['title'] = 'Ajout d\'un utilisateur';
        $array['userForm'] = $userForm->createView();
        return $app['twig']->render('user_form.html.twig', $array);
    }

    public function deleteUserAction(Application $app, $id)
    {
        $app['dao.user']->delete($id);
        $app['session']->getFlashBag()->add('success', 'L\'utilisateur a été supprimé.');
        // Redirect to admin home page
        return $app->redirect($app['url_generator']->generate('admin'));
    }
}