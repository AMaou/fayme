<?php

namespace fayme\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use fayme\Domain\user;

class ApiController {

    /**
     * API users controller.
     *
     * @param Application $app Silex application
     *
     * @return All users in JSON format
     */
    public function getUsersAction(Application $app) {
        $users = $app['dao.user']->findAll();
        // Convert an array of objects ($users) into an array of associative arrays ($responseData)
        $responseData = array();
        foreach ($users as $user) {
            $responseData[] = $this->builduserArray($user);
        }
        // Create and return a JSON response
        return $app->json($responseData);
    }

    /**
     * API user details controller.
     *
     * @param integer $id user id
     * @param Application $app Silex application
     *
     * @return user details in JSON format
     */
    public function getUserAction($id, Application $app) {
        $user = $app['dao.user']->find($id);
        $responseData = $this->builduserArray($user);
        // Create and return a JSON response
        return $app->json($responseData);
    }

    /**
     * API create user controller.
     *
     * @param Request $request Incoming request
     * @param Application $app Silex application
     *
     * @return user details in JSON format
     */
    public function addUserAction(Request $request, Application $app) {
        // Check request parameters
        if (!$request->request->has('username')) {
            return $app->json('Missing required parameter: username', 400);
        }
        if (!$request->request->has('ville')) {
            return $app->json('Missing required parameter: ville', 400);
        }
        // Build and save the new user
        $user = new user();
        $user->setUsername($request->request->get('username'));
        $user->setVille($request->request->get('ville'));
        $app['dao.user']->save($user);
        $responseData = $this->builduserArray($user);
        return $app->json($responseData, 201);  // 201 = Created
    }

    /**
     * API delete user controller.
     *
     * @param integer $id user id
     * @param Application $app Silex application
     */
    public function deleteUserAction($id, Application $app) {
        // Delete the user
        $app['dao.user']->delete($id);
        return $app->json('No content', 204);  // 204 = No ville
    }

    /**
     * Converts an user object into an associative array for JSON encoding
     *
     * @param user $user user object
     *
     * @return array Associative array whose fields are the user properties.
     */
    private function buildUserArray(user $user) {
        $data  = array(
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'ville' => $user->getVille()
            );
        return $data;
    }
}
