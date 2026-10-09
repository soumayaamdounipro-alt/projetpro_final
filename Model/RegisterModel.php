<?php
// Rôle de ce fichier : Enregistre les nouveaux comptes utilisateurs.


// Importe le fichier db.php qui contient probablement
// la fonction getDB() permettant de se connecter à la base de données.
require_once(__DIR__ . '/db.php');


/**
 * Classe RegisterModel
 *
 * Cette classe contient les fonctions nécessaires
 * pour gérer l'inscription d'un nouvel utilisateur.
 */
class RegisterModel
{
    // Variable privée qui contiendra la connexion à la base de données.
    private $db;


    /**
     * Constructeur de la classe.
     *
     * Il est exécuté automatiquement lorsqu'on crée
     * un nouvel objet RegisterModel.
     */
    // __construct() : Récupère la connexion MySQLi nécessaire pour vérifier les e-mails et créer des comptes.
    public function __construct()
    {
        // Récupère la connexion à la base de données
        // grâce à la fonction getDB().
        $this->db = getDB();
    }


    /**
     * Vérifie si une adresse e-mail existe déjà dans la base de données.
     *
     * @param string $email Adresse e-mail à vérifier
     * @return bool Retourne true si l'e-mail existe,
     *              false dans le cas contraire.
     */
    // emailExists() : Vérifie si une adresse e-mail est déjà présente dans la table users. Elle évite de créer deux comptes avec la même adresse.
    public function emailExists($email)
    {
        // Prépare une requête SQL.
        //
        // Le "?" est un paramètre qui sera remplacé
        // par la valeur de $email.
        //
        // L'utilisation de prepare() permet notamment
        // d'éviter les injections SQL.
        $stmt = $this->db->prepare("
            SELECT id
            FROM users
            WHERE email = ?
            LIMIT 1
        ");


        // Associe la variable $email au "?"
        // présent dans la requête SQL.
        //
        // "s" signifie que la valeur est de type string (chaîne de caractères).
        $stmt->bind_param("s", $email);


        // Exécute la requête SQL.
        $stmt->execute();


        // Récupère le résultat de la requête.
        $result = $stmt->get_result();


        // Vérifie si la requête a trouvé au moins un utilisateur.
        //
        // num_rows contient le nombre de lignes trouvées.
        // Si ce nombre est supérieur à 0, l'e-mail existe.
        $exists = $result->num_rows > 0;


        // Ferme la requête préparée
        // afin de libérer les ressources utilisées.
        $stmt->close();


        // Retourne true si l'e-mail existe,
        // sinon false.
        return $exists;
    }


    /**
     * Crée un nouvel utilisateur dans la base de données.
     *
     * @param string $lastName Nom de famille
     * @param string $firstName Prénom
     * @param string $email Adresse e-mail
     * @param string $hash Mot de passe déjà hashé
     *
     * @return bool Retourne true si l'insertion réussit,
     *              false en cas d'échec.
     */
    // createUser() : Ajoute un nouveau compte dans la table users avec un mot de passe déjà chiffré par hachage. Elle sert à terminer une inscription.
    public function createUser(
        string $lastName,
        string $firstName,
        string $email,
        string $hash
    )
    {
        // Prépare la requête SQL permettant d'insérer
        // un nouvel utilisateur dans la table Users.
        //
        // Les "?" seront remplacés par les valeurs
        // fournies dans bind_param().
        $stmt = $this->db->prepare("
            INSERT INTO users
            (last_name, first_name, email, password)
            VALUES (?, ?, ?, ?)
        ");


        // Associe les variables aux différents "?"
        // de la requête SQL.
        //
        // Les 4 "s" indiquent que les 4 valeurs
        // sont des chaînes de caractères (string).
        //
        // L'ordre doit correspondre à l'ordre des "?"
        // dans la requête SQL :
        //
        // 1. $lastName
        // 2. $firstName
        // 3. $email
        // 4. $hash
        $stmt->bind_param(
            "ssss",
            $lastName,
            $firstName,
            $email,
            $hash
        );


        // Exécute la requête INSERT.
        //
        // execute() retourne true si l'insertion
        // s'est correctement déroulée.
        $success = $stmt->execute();


        // Ferme la requête préparée
        // pour libérer les ressources.
        $stmt->close();


        // Retourne le résultat de l'insertion :
        // true  = utilisateur créé
        // false = erreur lors de l'insertion
        return $success;
    }
}