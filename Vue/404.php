<?php
// Rôle de ce fichier : Affiche un message quand une page n'existe pas.
?>
<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Recette introuvable — Cook With Soumi</title>
<link rel="stylesheet" href="<?= e(URL) ?>Vue/css/Accueil.css"><link rel="stylesheet" href="<?= e(URL) ?>Vue/css/navbar.css"><link rel="stylesheet" href="<?= e(URL) ?>Vue/css/pages.css"></head>
<body data-base="<?= e(URL) ?>" data-page="404"><?php require __DIR__.'/navbar.php'; ?>
<main class="catalogue-section" style="min-height:65vh;text-align:center;padding-top:90px"><h1>Cette page n'existe pas 🍽️</h1><p>La recette recherchée est introuvable.</p><p><a href="<?= e(URL) ?>?page=recette" class="btn-primary">Découvrir nos recettes</a></p></main>
<footer class="footer-bottom">© 2026 Cook With Soumi — La cuisine à partager.</footer><script src="<?= e(URL) ?>Vue/js/pages.js" defer></script></body></html>
