<?php

require_once "./database.php";
require_once "./helpers.php";
db_connect();

class User
{
    private $id;
    public $login;
    protected $password;
    public $email;
    public $firstname;
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
    public function register($login, $password, $email, $firstname, $lastname)
    {
        //Vérifie si login existe déjà
        $query = "SELECT login FROM utilisateurs WHERE utilisateurs.login = ? ";
        $exist = db_select_one($query, [$login]);
        if (empty($exist)) {
            //Si n'existe pas rentre les infos dans la base de données
            $query = "INSERT INTO utilisateurs (login, password, email, firstname, lastname) 
            VALUES (?, ?, ?, ?, ?)";
            db_execute($query, [$login, $password, $email, $firstname, $lastname]);
            echo "Inscrit ", $this->login, " avec succès !";
            br();
        } else {
            echo "ERREUR : Cet utilisateur existe déjà!";
            br();
        }
    }

    /**
     * Connecte l'utilisateur et change les attributs de la classe
     */
    public function connect($login, $password)
    {
        //vérifie si existe
        $query = "SELECT * FROM utilisateurs WHERE utilisateurs.login = ?";
        $user_info = db_select_one($query, [$login]);
        if (empty($user_info)) {
            echo "Cet utilisateur n'existe pas.";
            br();
        } else {
            //Vérifie si pass ok
            if ($user_info["password"] == $password) {
                echo "Connexion ok !";
                br();

                //change attributs
                $this->login = $login;
                $this->password = $password;
                $this->email = $user_info["email"];
                $this->firstname = $user_info["firstname"];
                $this->lastname = $user_info["lastname"];

                //Set la session
                $_SESSION["user"] = $login;
            } else {
                echo "Mauvais mot de passe.";
                br();
            }
        }
    }

    /**
     * Déconnecte l'utilisateur
     */
    public function disconnect()
    {
        if (isset($_SESSION["user"])) {
            $this->login = NULL;
            $this->password = NULL;
            $this->email = NULL;
            $this->firstname = NULL;
            $this->lastname = NULL;
            session_destroy();
            session_start();
            echo "Utilisateur déconnecté.";
            br();
        } else {
            echo "Il n'y a pas d'utilisateur connecté !";
            br();
        }
    }

    /**
     * Supprime et déconnecte un l'utilisateur
     */
    public function delete()
    {
        if (!empty($this->login)) {
            session_destroy();

            $query = "DELETE FROM utilisateurs WHERE utilisateurs.login = ?";
            db_execute($query, [$this->login]);
            echo "Utilisateur supprimé !";
            br();

            $this->login = NULL;
            $this->password = NULL;
            $this->email = NULL;
            $this->firstname = NULL;
            $this->lastname = NULL;
            session_start();
        } else {
            echo "Il n'y a pas d'utilisateurs inscrit. Impossible de supprimer.";
            br();
        }
    }

    /**
     * Met à jour les informations de l'utilisateur
     */
    public function update($login, $password, $email, $firstname, $lastname)
    {
        //Vérifie si connecté
        if (!empty($this->login)) {
            //Vérifie si login existe
            $query = "SELECT * FROM utilisateurs WHERE utilisateurs.login = ?";
            $exist = db_select_one($query, [$login]);
            if (!empty($exist)) {
                echo "Ce login : ", $login, ", existe déjà.";
                br();
                echo "Mais les autres informations seront tout de même mises à jour.";
                br();
                $query = "UPDATE utilisateurs 
            SET utilisateurs.password = ?, utilisateurs.email = ?, 
            utilisateurs.firstname = ?, utilisateurs.lastname = ? 
            WHERE utilisateurs.login = ?";
                db_execute($query, [$password, $email, $firstname, $lastname, $this->login]);
                $this->password = $password;
                $this->email = $email;
                $this->firstname = $firstname;
                $this->lastname = $lastname;
                echo "Informations mises à jour avec succès !";
                br();
            } else {
                echo "Mise à jour de vos informations...";
                br();
                $id_get = "SELECT id FROM utilisateurs WHERE utilisateurs.login = ?";
                $id = db_select_one($id_get, [$this->login]);
                $id = $id["id"];
                $query = "UPDATE utilisateurs 
            SET utilisateurs.login = ?, utilisateurs.password = ?, utilisateurs.email = ?, 
            utilisateurs.firstname = ?, utilisateurs.lastname = ? 
            WHERE utilisateurs.id = ?";
                db_execute($query, [$login, $password, $email, $firstname, $lastname, $id]);
                $this->login = $login;
                $this->password = $password;
                $this->email = $email;
                $this->firstname = $firstname;
                $this->lastname = $lastname;
                echo "Informations mises à jour avec succès !";
                br();
                $_SESSION["user"] = $login;
            }
        } else {
            echo "ERREUR : Aucun utilisateur connecté.";
            br();
        }
    }

    /**
     * Check si connécté
     */
    public function isConnected()
    {
        if (isset($_SESSION["user"])) {
            echo "Utilisateur connecté en tant que : ", $this->login;
            return true;
        } else {
            echo "Utilisateur non connecté.";
            return false;
        }
    }

    /**
     * Donne les informations de l'utilisateur
     */
    public function getAllInfos()
    {
        $br = "<br>";
        if (
            empty($this->login)
            and empty($this->email)
            and empty($this->lastname)
            and empty($this->firstname)
        ) {
            echo "Aucunes informations utilisateur !", $br;
        } else {
            echo "INFORMATIONS UTILISATEUR : ", $br;
            echo "Login de l'utilisateur = ", $this->login, $br;
            echo "Email de l'utilisateur = ", $this->email, $br;
            echo "Prénom de l'utilisateur = ", $this->firstname, $br;
            echo "Nom de l'utilisateur = ", $this->lastname, $br;
        }
    }

    /**
     * Donne le login de l'utilisateur
     */
    public function getLogin()
    {
        $br = "<br>";
        if (empty($this->login)) {
            echo "Aucun login.", $br;
        } else {
            echo "Login = ", $this->login, $br;
        }
    }

    /**
     * Donne l'email de l'utilisateur
     */
    public function getEmail()
    {
        if (empty($this->email)) {
            echo "Aucun email trouvé.";
            br();
        } else {
            echo "Email = ", $this->email;
            br();
        }
    }

    /**
     * Donne le firstname de l'utilisateur
     */
    public function getFirstname()
    {
        if (empty($this->firstname)) {
            echo "Aucun prénom trouvé.";
            br();
        } else {
            echo "Prénom de l'utilisateur = ", $this->firstname;
            br();
        }
    }

    /**
     * Donne le lastname de l'utilisateur
     */
    public function getLastname()
    {
        if (empty($this->lastname)) {
            echo "Aucun nom trouvé.";
            br();
        } else {
            echo "Nom de l'utilisateur = ", $this->lastname;
            br();
        }
    }
}
