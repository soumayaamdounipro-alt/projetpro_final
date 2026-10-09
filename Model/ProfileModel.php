<?php
// Ce modèle lit et modifie uniquement les informations autorisées du compte.
require_once __DIR__ . '/db.php';

class ProfileModel {
    private mysqli $db;

    // __construct() : récupère la connexion MySQLi pour les requêtes du profil.
    public function __construct() {
        $this->db = getDB();
    }

    // getUserById() : charge le profil de la personne connectée, sans son mot de passe.
    public function getUserById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT id, first_name, last_name, email, registration_date FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $user ?: null;
    }

    // emailUsedByAnother() : empêche de choisir une adresse déjà utilisée par un autre compte.
    public function emailUsedByAnother(string $email, int $id): bool {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ? AND id <> ? LIMIT 1");
        $stmt->bind_param("si", $email, $id);
        $stmt->execute();
        $exists = $stmt->get_result()->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    // updateProfile() : enregistre seulement le nom, le prénom et l'e-mail.
    // La date d'inscription et le mot de passe ne sont jamais modifiés ici.
    public function updateProfile(int $id, string $lastName, string $firstName, string $email): bool {
        $stmt = $this->db->prepare("UPDATE users SET last_name = ?, first_name = ?, email = ? WHERE id = ?");
        $stmt->bind_param("sssi", $lastName, $firstName, $email, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
