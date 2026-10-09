<?php
// Rôle de ce fichier : Recherche les comptes utilisateurs dans la base de données.


require_once __DIR__ . '/db.php';

class UserModel
{
    private $db;
    // __construct() : Récupère la connexion MySQLi pour que le modèle puisse lire les comptes.
    public function __construct()
    {
        $this->db = getDB();
    }
    // getUserByEmail() : Recherche un compte à partir de son adresse e-mail. Elle sert à vérifier l’existence du compte lors de la connexion.
    public function getUserByEmail($email)
    {
        $stmt = $this->db->prepare(
            'SELECT id, last_name, first_name, email, password FROM users WHERE email = ? LIMIT 1'
        );
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $user;
    }
}
