<?php
// Rôle de ce fichier : Reçoit les demandes du navigateur, choisit la page et lance les actions nécessaires.

session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/Controleur/PageController.php';

// Crée un jeton de sécurité pour vérifier les formulaires envoyés par le visiteur.
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
// Récupère le nom de la page demandée ; si rien n’est demandé, affiche l’accueil.
$page = is_string($_GET['page'] ?? null) ? $_GET['page'] : 'accueil';
$aliases = ['recettes'=>'recette','detail'=>'detaille','details'=>'detaille','a-propos'=>'about'];
$page = $aliases[$page] ?? $page;
if ($page === 'recette' && isset($_GET['id'])) $page = 'detaille';

// Les pages suivantes sont réservées aux personnes connectées.
$pagesPrivees = ['recette', 'categories', 'favoris', 'about', 'detaille', 'profile', 'recettes-data'];

// Empêche un visiteur d'ouvrir directement une page privée par son adresse.
if (in_array($page, $pagesPrivees, true) && empty($_SESSION['user_id'])) {
    header('Location: ' . URL . '?page=accueil');
    exit();
}


// Oriente chaque demande vers son contrôleur ou sa page.
switch ($page) {
    case 'accueil':
        $model = new RecipesModel();
        $categories = $model->getCategories();
        $counts = $model->getCounts();
        $featured = array_slice($model->getAllRecipes(), 0, 6);
        $homeFeatures = $model->getSiteCards('home_features');
        $favoriteIds = $model->getFavoriteIds((int)($_SESSION['user_id'] ?? 0));
        require __DIR__ . '/Vue/Accueil.php';
        break;
    case 'login': require __DIR__ . '/Controleur/LoginController.php'; break;
    case 'register': require __DIR__ . '/Controleur/RegisterController.php'; break;
    case 'profile': require __DIR__ . '/Controleur/ProfileController.php'; break;
    case 'logout': require __DIR__ . '/Controleur/logout.php'; break;
    case 'about': case 'categories': case 'favoris': case 'recette': case 'detaille':
        renderPage($page); break;
    case 'recettes-data':
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode((new RecipesModel())->getAllRecipes(), JSON_UNESCAPED_UNICODE); break;
    // Vérifie la connexion, les données et le jeton de sécurité avant de modifier les favoris.
    case 'toggle-favorite':
        header('Content-Type: application/json; charset=utf-8');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); echo json_encode(['error'=>'Méthode interdite']); break;
        }
        if (empty($_SESSION['user_id'])) {
            http_response_code(401); echo json_encode(['error'=>'Connectez-vous pour enregistrer un favori.', 'login'=>URL.'?page=login']); break;
        }
        if (!hash_equals($_SESSION['csrf_token'], (string)($_POST['csrf_token'] ?? ''))) {
            http_response_code(403); echo json_encode(['error'=>'Session expirée : rechargez la page.']); break;
        }
        $recipeId=filter_var($_POST['recipe_id'] ?? null, FILTER_VALIDATE_INT);
        $action=$_POST['action'] ?? '';
        if (!$recipeId || !in_array($action,['add','remove'],true)) {
            http_response_code(422); echo json_encode(['error'=>'Données invalides.']); break;
        }
        $model=new RecipesModel();
        if (!$model->getRecipeById($recipeId)) {
            http_response_code(404); echo json_encode(['error'=>'Recette introuvable.']); break;
        }
        $model->setFavorite((int)$_SESSION['user_id'],$recipeId,$action==='add');
        echo json_encode(['success'=>true,'favorite'=>$action==='add']); break;
    // Vérifie le formulaire newsletter avant d’enregistrer l’adresse e-mail dans MySQL.
    case 'newsletter':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: '.URL.'?page=accueil'); exit; }
        if (!hash_equals($_SESSION['csrf_token'], (string)($_POST['csrf_token'] ?? ''))) { http_response_code(403); exit('Session expirée.'); }
        $email=trim((string)($_POST['email'] ?? ''));
        if (!filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($email)>200) { header('Location: '.URL.'?page=accueil&newsletter=invalid#newsletter'); exit; }
        (new RecipesModel())->subscribe($email);
        header('Location: '.URL.'?page=accueil&newsletter=ok#newsletter'); exit;
    default: renderPage('404',404);
}
