<?php
// Rôle de ce fichier : Vérifie les identifiants et connecte l'utilisateur.


// Importe le modèle UserModel.
//
// Le modèle contient les méthodes permettant
// de communiquer avec la base de données,
// notamment pour rechercher un utilisateur.
require_once __DIR__ . '/../Model/UserModel.php';


/**
 * Classe LoginController
 *
 * Ce contrôleur gère la connexion des utilisateurs.
 *
 * Il va notamment :
 * - afficher le formulaire de connexion ;
 * - récupérer les données envoyées par le formulaire ;
 * - vérifier les informations saisies ;
 * - rechercher l'utilisateur dans la base de données ;
 * - vérifier son mot de passe ;
 * - créer sa session s'il est correctement authentifié.
 */
class LoginController
{
    // Contient une instance de UserModel.
    //
    // Cette variable permet au contrôleur
    // d'utiliser les méthodes du modèle.
    private $userModel;


    /**
     * Constructeur de la classe.
     *
     * Il est automatiquement appelé lorsqu'on crée
     * un objet LoginController.
     */
    // __construct() : Crée le modèle qui recherche les utilisateurs en base. Il permet au contrôleur de vérifier une connexion.
    public function __construct()
    {
        // Création d'un objet UserModel.
        //
        // On pourra ensuite utiliser :
        // $this->userModel->...
        //
        // pour accéder aux méthodes du modèle.
        $this->userModel = new UserModel();
    }


    /**
     * Gère la connexion de l'utilisateur.
     */
    // login() : Traite le formulaire de connexion, vérifie l’e-mail et le mot de passe, puis crée la session. Elle sert à autoriser l’accès aux pages privées.
    public function login()
    {
        // Variable qui contiendra éventuellement
        // un message d'erreur à afficher à l'utilisateur.
        //
        // Au départ, il n'y a aucune erreur.
        $error = '';


        // Variable utilisée pour conserver l'adresse e-mail
        // saisie par l'utilisateur.
        //
        // Cela permet par exemple de remettre l'e-mail
        // dans le formulaire si la connexion échoue.
        $oldEmail = '';


        // Vérifie si l'utilisateur est déjà connecté.
        //
        // $_SESSION['user_id'] contient normalement
        // l'identifiant de l'utilisateur connecté.
        if (!empty($_SESSION['user_id'])) {

            // Si l'utilisateur est déjà connecté,
            // il n'a pas besoin de revenir sur la page de login.
            //
            // On le redirige directement vers la page d'accueil.
            header('Location: ' . URL . '?page=accueil');

            // Arrête le script après la redirection.
            exit;
        }


        // Vérifie si le formulaire a été envoyé.
        //
        // $_SERVER['REQUEST_METHOD'] contient la méthode HTTP utilisée
        // pour accéder à cette page.
        //
        // Si le formulaire utilise method="POST",
        // alors cette condition sera vraie.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {


            // Récupère l'adresse e-mail envoyée par le formulaire.
            //
            // $_POST['email'] contient la valeur saisie.
            //
            // Le "?? ''" signifie :
            // si la clé "email" n'existe pas,
            // on utilise une chaîne vide.
            //
            // trim() permet de supprimer les espaces
            // au début et à la fin de l'adresse e-mail.
            $email = trim($_POST['email'] ?? '');


            // Récupère le mot de passe envoyé par le formulaire.
            //
            // Ici aussi, si "password" n'existe pas,
            // une chaîne vide est utilisée.
            $password = $_POST['password'] ?? '';


            // Prépare l'adresse e-mail pour être réaffichée
            // sans permettre l'injection de code HTML.
            //
            // htmlspecialchars() transforme par exemple :
            // < en &lt;
            // > en &gt;
            //
            // C'est utile lorsqu'on réaffiche une donnée
            // saisie par l'utilisateur dans une page HTML.
            $oldEmail = $email;


            // -----------------------------
            // Vérification des champs
            // -----------------------------

            // Vérifie si l'e-mail OU le mot de passe est vide.
            if (empty($email) || empty($password)) {

                // Si un champ est vide,
                // on affiche un message d'erreur.
                $error = 'Veuillez remplir tous les champs.';


            // Vérifie ensuite si l'adresse e-mail
            // possède un format valide.
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

                // Si l'adresse n'est pas valide,
                // on affiche un message d'erreur.
                $error = 'Adresse e-mail invalide.';


            } else {

                // -----------------------------
                // Recherche de l'utilisateur
                // -----------------------------

                // Appelle le modèle pour rechercher
                // l'utilisateur correspondant à l'adresse e-mail.
                //
                // Le contrôleur ne fait pas directement
                // la requête SQL : il demande au modèle
                // de s'en charger.
                $user = $this->userModel->getUserByEmail($email);


                // -----------------------------
                // Vérification des identifiants
                // -----------------------------

                // Vérifie deux choses :
                //
                // 1. !$user
                //    → aucun utilisateur n'a été trouvé.
                //
                // 2. !password_verify(...)
                //    → le mot de passe saisi ne correspond
                //      pas au mot de passe hashé enregistré.
                //
                // Le || signifie "OU".
                //
                // Donc si l'utilisateur n'existe PAS
                // OU si le mot de passe est incorrect,
                // la connexion est refusée.
                if (
                    !$user ||
                    !password_verify($password, $user['password'])
                ) {

                    // Message volontairement général.
                    //
                    // On ne précise pas si c'est l'e-mail
                    // ou le mot de passe qui est incorrect.
                    $error = 'E-mail ou mot de passe incorrect.';


                } else {

                    // -----------------------------
                    // Connexion réussie
                    // -----------------------------

                    // Génère un nouvel identifiant de session.
                    //
                    // Le "true" permet également de supprimer
                    // l'ancienne session.
                    //
                    // Cette opération est une protection importante
                    // contre la fixation de session (session fixation).
                    session_regenerate_id(true);


                    // Enregistre l'identifiant de l'utilisateur
                    // dans la session.
                    //
                    // Il pourra ensuite être utilisé
                    // sur les autres pages du site.
                    $_SESSION['user_id'] = $user['id'];


                    // Enregistre le nom de famille
                    // dans la session.
                    $_SESSION['username'] = $user['last_name'];


                    // Enregistre le prénom
                    // dans la session.
                    $_SESSION['prenom'] = $user['first_name'];


                    // Enregistre l'adresse e-mail
                    // dans la session.
                    $_SESSION['email'] = $user['email'];


                    // La connexion est réussie.
                    //
                    // On redirige donc l'utilisateur
                    // vers la page d'accueil.
                    header('Location: ' . URL . '?page=accueil');


                    // Arrête le script après la redirection.
                    exit;
                }
            }
        }


        // Si aucune redirection n'a eu lieu,
        // on charge la vue du formulaire de connexion.
        //
        // Les variables :
        // - $error
        // - $oldEmail
        //
        // sont accessibles dans login.php.
        require_once __DIR__ . '/../Vue/login.php';
    }
}


// Création d'une instance du contrôleur.
$controller = new LoginController();


// Lance la méthode login()
// afin de gérer la page de connexion.
$controller->login();