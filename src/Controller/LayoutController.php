<?php

namespace fayme\Controller;

use Silex\Application;

class LayoutController {

    protected function renderCatEsp(Application $app){
        $users = $app['dao.user']->findAll();
        return array('users' => $users);
    }

}