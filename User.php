<?php

class User
{
    private $id;
    public $login;
    private $password;
    public $email;
    public $firtname;
    public $lastname;

    /**
     * Init
     */
    public function __construct()
    {
        //
    }

    /**
     * Inscrit l'utilisateur et l'insère dans la base de données
     */
    public function register($login, $password, $email, $firstname, $lastname) {}

    /**
     * Connecte l'utilisateur et change les attributs de la classe
     */
    public function connect($login, $password) {}

    /**
     * Déconnecte l'utilisateur
     */
    public function disconnect() {}

    /**
     * Supprime et déconnecte un l'utilisateur
     */
    public function delete() {}

    /**
     * Met à jour les informations de l'utilisateur
     */
    public function update($login, $password, $email, $firstname, $lastname) {}

    /**
     * Check si connécté
     */
    public function isConnected() {}

    /**
     * Donne les informations de l'utilisateur
     */
    public function getAllInfos() {}

    /**
     * Donne le login de l'utilisateur
     */
    public function getLogin() {}

    /**
     * Donne l'email de l'utilisateur
     */
    public function getEmail() {}

    /**
     * Donne le firstname de l'utilisateur
     */
    public function getFirstname() {}

    /**
     * Donne le lastname de l'utilisateur
     */
    public function getLastname() {}
}
