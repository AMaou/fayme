<?php

namespace fayme\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use fayme\Domain\User;
use fayme\Form\Type\UserType;
use fayme\Form\Type\ProfilType;

class HomeController extends LayoutController
{


    public function indexAction(Application $app)
    {
        $array = $this->renderCatEsp($app);
        return $app['twig']->render('index.html.twig', $array);
    }
    
       public function usrpageAction(Application $app)
    {
        $array = $this->renderCatEsp($app);
        return $app['twig']->render('user_profil.html.twig', $array);
    }

    public function loginAction(Application $app, Request $request)
    {
        $array = $this->renderCatEsp($app);
        $array['error'] = $app['security.last_error']($request);
        $array['last_username'] = $app['session']->get('_security.last_username');
        return $app['twig']->render('login.html.twig', $array);
    }

    public function inscriptionAction(Application $app, Request $request)
    {
        $user = new User();
        $userForm = $app['form.factory']->create(new UserType(), $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            if ($app['dao.user']->loadUserByUsername($user->getUsername())) {
                $app['session']->getFlashBag()->add('error', 'Un utilisateur existe déjà avec ce login');
            } else {
                $encoder = $app['security.encoder_factory']->getEncoder($user);
                $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                $user->setPassword($password);
                $app['dao.user']->save($user);

                $app['session']->getFlashBag()->add('success', 'Votre inscription a été validée, vous pouvez aller compléter votre profil en vous connectant !');
                return $app->redirect('/');
            }
        }
        $userFormView = $userForm->createView();
        $array = $this->renderCatEsp($app);
        $array['userForm'] = $userFormView;
        return $app['twig']->render('inscription.html.twig', $array);
    }

    public function profilAction(Application $app, Request $request)
    {
        $user = $app['user'];
        $userFormView = null;
        $userForm = $app['form.factory']->create(new ProfilType(), $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $plainPassword = $user->getPassword();
            // find the encoder for a UserInterface instance
            $encoder = $app['security.encoder_factory']->getEncoder($user);
            // compute the encoded password
            $password = $encoder->encodePassword($plainPassword, $user->getSalt());
            $user->setPassword($password);
            $app['dao.user']->save($user);
            $app['session']->getFlashBag()->add('success', 'Vos informations personnelles ont été mises à jour.');
        } else if ($userForm->isSubmitted() && !$userForm->isValid()) {
            $app['session']->getFlashBag()->add('error', 'Erreur : mise à jour impossible');
        }
        $userFormView = $userForm->createView();
        $array = $this->renderCatEsp($app);
        $array['userForm'] = $userFormView;
        return $app['twig']->render('profil.html.twig', $array);
    }

}
