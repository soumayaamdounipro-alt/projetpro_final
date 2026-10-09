<?php
// Rôle de ce fichier : Vérifie le formulaire et crée un compte.

require_once __DIR__ . '/../Model/RegisterModel.php';

class RegisterController
{
    private $model;
    // __construct() : Crée le modèle d’inscription. Il permet au contrôleur de vérifier et d’enregistrer un nouveau compte.
    public function __construct()
    {
        $this->model = new RegisterModel();
    }
    // index() : Affiche le formulaire d’inscription et contrôle les informations envoyées. Elle sert à créer un compte valide dans MySQL.
    public function index()
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . URL . '?page=profile');
            exit;
        }

        $errors  = [];
        $success = '';
        $old = ['last_name' => '', 'first_name' => '', 'email' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $lastName  = trim($_POST['last_name']  ?? '');
            $firstName = trim($_POST['first_name'] ?? '');
            $email     = trim($_POST['email']      ?? '');
            $password  =      $_POST['password']   ?? '';
            $confirm   =      $_POST['confirm']    ?? '';

            $old = ['last_name' => $lastName, 'first_name' => $firstName, 'email' => $email];

            if (empty($lastName))  $errors[] = 'Le nom est obligatoire.';
            if (empty($firstName)) $errors[] = 'Le prénom est obligatoire.';
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
                $errors[] = 'Adresse e-mail invalide.';
            if (strlen($password) < 8)
                $errors[] = 'Le mot de passe doit contenir au moins 8 caractères.';
            if ($password !== $confirm)
                $errors[] = 'Les mots de passe ne correspondent pas.';

            if (empty($errors) && $this->model->emailExists($email))
                $errors[] = 'Cette adresse e-mail est déjà utilisée.';

            if (empty($errors)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                if ($this->model->createUser($lastName, $firstName, $email, $hashedPassword)) {
                    $_SESSION['registration_success'] = 'Votre compte a bien été créé ! Vous pouvez maintenant vous connecter.';
                    header('Location: ' . URL . '?page=login');
                    exit;
                } else {
                    $errors[] = 'Erreur lors de la création du compte.';
                }
            }
        }

        require_once __DIR__ . '/../Vue/register.php';
    }
}

$controller = new RegisterController();
$controller->index();
