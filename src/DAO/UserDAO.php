<?php

namespace fayme\DAO;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use fayme\Domain\User;

class UserDAO extends DAO implements UserProviderInterface
{
    /**
     * Returns a user matching the supplied id.
     *
     * @param integer $id The user id.
     *
     * @return \fayme\Domain\User|throws an exception if no matching user is found
     */
    public function find($id) {
        $sql = "select * from t_user where usr_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No user matching id " . $id);
    }

    /**
     * Retourne tous les utilisateurs
     *
     * @return un tableau d'utilisateurs
     */
    public function findAll()
    {
        $sql = "select * from t_user";
        $result = $this->getDb()->fetchAll($sql);

        // Convert query result to an array of domain objects
        $users = array();
        foreach ($result as $row) {
            $idUser = $row['usr_id'];
            $users[$idUser] = $this->buildDomainObject($row);
        }
        return $users;
    }

    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username)
    {
        $sql = "select * from t_user where usr_username=?";
        $row = $this->getDb()->fetchAssoc($sql, array($username));

        if ($row)
            return $this->buildDomainObject($row);
        else
            return false;
    }

    /**
     * {@inheritDoc}
     */
    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
        }
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        return 'fayme\Domain\User' === $class;
    }

    /**
     * Saves an user into the database.
     *
     * @param \fayme\Domain\User $user The user to save
     */
    public function save(User $user) {
        $userData = null;

        if ($user->getId()) {
            $userData = array(
            'usr_nom' => $user->getNom(),
            'usr_prenom' => $user->getPrenom(),
            'usr_description' => $user->getDescription(),
            'usr_categorie' => $user->getCategorie(),
            'usr_mail' => $user->getMail(),
            'usr_cp' => $user->getCodePostal(),
            'usr_ville' => $user->getVille(),
            'photo_url' => $user->getPhoto(),   
            'rating' => $user->getRating(),
            'usr_username' => $user->getUsername(),
            'usr_password' => $user->getPassword(),
            'usr_role' => 'ROLE_USER',
            'usr_salt'=> base_convert(sha1(uniqid(mt_rand(), true)), 16, 36),
        );

            $this->getDb()->update('t_user', $userData, array('usr_id' => $user->getId()));
        } else {
            $userData = array(
            'usr_username' => $user->getUsername(),
            'usr_password' => $user->getPassword(),
            'usr_role' => 'ROLE_USER',
            'usr_salt'=> base_convert(sha1(uniqid(mt_rand(), true)), 16, 36),
        );

            $this->getDb()->insert('t_user', $userData);
            $id = $this->getDb()->lastInsertId();
            $user->setId($id);
        }
    }

    /**
     * Creates a User object based on a DB row.
     *
     * @param array $row The DB row containing User data.
     * @return \fayme\Domain\User
     */
    protected function buildDomainObject($row) {
        $user = new User();
        $user->setId($row['usr_id']);
        $user->setNom($row['usr_nom']);
        $user->setPrenom($row['usr_prenom']);
        $user->setDescription($row['usr_description']);
        $user->setCategorie($row['usr_categorie']);
        $user->setMail($row['usr_mail']);
        $user->setCodepostal($row['usr_cp']);
        $user->setVille($row['usr_ville']);
        $user->setPhoto($row['photo_url']);
        $user->setRating($row['rating']);
        $user->setUsername($row['usr_username']);
        $user->setPassword($row['usr_password']);
        $user->setSalt($row['usr_salt']);
        $user->setRole($row['usr_role']);
        return $user;
    }

    /**
     * Removes a user from the database.
     *
     * @param @param integer $id The user id.
     */
    public function delete($id) {
        // Delete the user
        $this->getDb()->delete('t_user', array('usr_id' => $id));
    }
}
