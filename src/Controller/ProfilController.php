<?php

namespace fayme\Controller;

use Silex\Application;

class ProfilController extends layoutController {

    public function profilAction(Application $app, $id){
        $user = $app['dao.user']->find($id);
        $array = $this->renderCatEsp($app);
        $array['profil'] = $user;
        return $app['twig']->render('profil.html.twig', $array);
    }
}