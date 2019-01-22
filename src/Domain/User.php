<?php

namespace fayme\Domain;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    /**
     * User id.
     *
     * @var integer
     */
    private $id;

    /**
     * User nom.
     *
     * @var string
     */
    private $nom;

    /**
     * User prenom.
     *
     * @var string
     */
    private $prenom;

    /**
     * User description.
     *
     * @var string
     */
    private $description;
    /**
     * User categorie.
     *
     * @var string
     */
    private $categorie;

    /**
     * User email.
     *
     * @var email
     */
    private $mail;

    /**
     * User codePostal.
     *
     * @var string
     */
    private $codePostal;

    /**
     * User ville.
     *
     * @var string
     */
    private $ville;

    /**
     * User photo.
     *
     * @var string
     */
    private $photo;
/**
     * User rating.
     *
     * @var string
     */
    private $rating;

    /**
     * User name.
     *
     * @var string
     */
    private $username;

    /**
     * User password.
     *
     * @var string
     */
    private $password;

    /**
     * Salt that was originally used to encode the password.
     *
     * @var string
     */
    private $salt;

    /**
     * Role.
     * Values : ROLE_USER or ROLE_ADMIN.
     *
     * @var string
     */
    private $role;



    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getnom() {
        return $this->nom;
    }

    public function setnom($nom) {
        $this->nom = $nom;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }
    
    public function getCategorie() {
        return $this->categorie;
    }
    
    public function setCategorie($categorie) {
        $this->categorie = $categorie;
    }
    
    public function getMail() {
        return $this->mail;
    }

    public function setMail($mail) {
        $this->mail = $mail;
    }

    public function getCodePostal() {
        return $this->codePostal;
    }

    public function setCodePostal($codePostal) {
        $this->codePostal = $codePostal;
    }

    public function getVille() {
        return $this->ville;
    }

    public function setVille($ville) {
        $this->ville = $ville;
    }
    
    public function getPhoto() {
        return $this->photo;
    }

    public function setPhoto($photo) {
        $this->photo = $photo;
    }
    
    public function getRating() {
        return $this->rating;
    }

    public function setRating($rating) {
        $this->rating = $rating;
    }
    /**
     * @inheritDoc
     */
    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * @inheritDoc
     */
    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return $this->salt;
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return array($this->getRole());
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials() {
        // Nothing to do here
    }
}
