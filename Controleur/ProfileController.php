<?php
// Ce contrôleur vérifie la connexion, traite les modifications et affiche le profil.
require_once __DIR__ . '/../Model/ProfileModel.php';

class ProfileController {
    // index() : prépare la fiche du profil et vérifie les données du formulaire.
    public function index(): void {
        // Sans session, personne ne peut consulter ni modifier ce profil.
        if (empty($_SESSION['user_id'])) {
            header('Location: ' . URL . '?page=login');
            exit;
        }

        $id = (int) $_SESSION['user_id'];
        $model = new ProfileModel();
        $user = $model->getUserById($id);
        if (!$user) {
            unset($_SESSION['user_id']);
            header('Location: ' . URL . '?page=login');
            exit;
        }

        $erreur = '';
        $succes = isset($_GET['saved']);

        // Traite les changements uniquement après l'envoi du formulaire.
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['edit'])) {
            $token = (string)($_POST['csrf_token'] ?? '');
            if (!hash_equals((string)($_SESSION['csrf_token'] ?? ''), $token)) {
                $erreur = 'Session expirée. Recharge la page et réessaie.';
            } else {
                // Supprime les espaces inutiles et contrôle les trois champs autorisés.
                $nom = trim((string)($_POST['last_name'] ?? ''));
                $prenom = trim((string)($_POST['first_name'] ?? ''));
                $email = trim((string)($_POST['email'] ?? ''));

                if ($nom === '' || $prenom === '' || $email === '') {
                    $erreur = 'Tous les champs sont obligatoires.';
                } elseif (mb_strlen($nom) > 100 || mb_strlen($prenom) > 100 || mb_strlen($email) > 200) {
                    $erreur = 'Un champ est trop long.';
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $erreur = 'L’adresse e-mail est invalide.';
                } elseif ($model->emailUsedByAnother($email, $id)) {
                    $erreur = 'Cette adresse e-mail est déjà utilisée.';
                } else {
                    // Enregistre les trois champs sans toucher à la date ou au mot de passe.
                    try {
                        $model->updateProfile($id, $nom, $prenom, $email);
                        $_SESSION['prenom'] = $prenom;
                        header('Location: ' . URL . '?page=profile&saved=1');
                        exit;
                    } catch (mysqli_sql_exception $e) {
                        $erreur = 'Impossible d’enregistrer les modifications.';
                    }
                }
                // Garde les valeurs saisies lorsque le formulaire contient une erreur.
                $user['last_name'] = $nom;
                $user['first_name'] = $prenom;
                $user['email'] = $email;
            }
        }
        // Affiche la fiche ou le formulaire dans deux pages au même thème.
        require __DIR__ . (isset($_GET['edit']) ? '/../Vue/modif_profile.php' : '/../Vue/profile.php');
    }
}

// Lance l'affichage et le traitement du profil.
(new ProfileController())->index();
