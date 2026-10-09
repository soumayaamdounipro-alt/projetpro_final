<?php
// Rôle de ce fichier : Termine la session de l'utilisateur.


// Vide toutes les variables actuellement présentes dans la session.
//
// Par exemple, si la session contient :
// $_SESSION['user_id'] = 15;
//
// après cette ligne, $_SESSION devient un tableau vide.
//
// Cela permet de supprimer les informations
// enregistrées dans la session de l'utilisateur.
$_SESSION = [];


// Vérifie si PHP utilise des cookies pour gérer les sessions.
//
// ini_get('session.use_cookies') retourne une valeur indiquant
// si les sessions PHP utilisent des cookies.
if (ini_get('session.use_cookies')) {

    // Récupère les paramètres actuels du cookie de session.
//
// Ces paramètres peuvent contenir :
// - path      : chemin concerné par le cookie
// - domain    : domaine concerné
// - secure    : cookie transmis uniquement en HTTPS
// - httponly  : empêche JavaScript d'accéder au cookie
    $params = session_get_cookie_params();


    // Supprime le cookie de session du navigateur.
//
// Pour supprimer un cookie, on lui donne une date d'expiration
// située dans le passé.
//
// session_name() récupère le nom du cookie utilisé par PHP
// pour identifier la session.
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}


// Détruit complètement la session côté serveur.
//
// Cela permet de supprimer les données associées
// à la session actuelle.
session_destroy();


// Redirige ensuite l'utilisateur vers la page de connexion.
//
// URL correspond à la constante contenant
// l'adresse principale de ton application.
//
// ?page=login permet d'indiquer au routeur
// qu'il faut afficher la page de connexion.
header('Location: ' . URL . '?page=login');


// Arrête immédiatement l'exécution du script.
//
// C'est important après une redirection pour éviter
// que PHP continue à exécuter du code.
exit;
