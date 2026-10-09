<?php
// Rôle de ce fichier : Affiche les recettes enregistrées en favoris.
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cook With Soumi — Mes Favoris</title>

<link rel="stylesheet" href="<?= e(URL) ?>Vue/css/Accueil.css">
<link rel="stylesheet" href="<?= e(URL) ?>Vue/css/navbar.css">
<link rel="stylesheet" href="<?= e(URL) ?>Vue/css/categories.css">
<link rel="stylesheet" href="<?= e(URL) ?>Vue/css/favoris.css">
<link rel="stylesheet" href="<?= e(URL) ?>Vue/css/pages.css">
</head>
<body data-base="<?= e(URL) ?>" data-page="favoris">

  <?php require __DIR__ . "/navbar.php"; ?>

  <section class="page-hero">
    <div class="page-hero-content reveal">
      <span class="section-label">Ma collection</span>
      <h1 class="page-hero-title">Mes recettes <em>favorites</em></h1>
      <p class="page-hero-desc">Retrouvez les recettes sauvegardées dans votre compte, sur tous vos appareils.</p>
    </div>
  </section>

  <section class="fav-section">

    <div id="favGrid" class="fav-grid">
      <?php // Parcourt uniquement les recettes enregistrées par le membre dans ses favoris. ?>
      <?php foreach ($recipes as $recipe): ?>
        <?php require __DIR__ . '/partials/recipe_card.php'; ?>
      <?php endforeach; ?>
    </div>

    <div class="fav-empty" id="favEmpty" <?= $recipes ? "hidden" : "" ?>>
      <div class="fav-empty-icon">💔</div>
      <h2 class="fav-empty-title">Aucun favori pour l'instant</h2>
      <p class="fav-empty-desc"><?php if (empty($_SESSION['user_id'])): ?>
        <a href="<?= e(URL) ?>?page=login">Connectez-vous</a> pour sauvegarder vos recettes favorites dans votre compte.
      <?php else: ?>Explorez nos recettes et cliquez sur le cœur ❤️ pour les sauvegarder ici.<?php endif; ?></p>
      <a href="<?= e(URL) ?>?page=categories" class="layout-14 btn-primary">
        🍽️ Découvrir les recettes
      </a>
    </div>

  </section>

  <footer class="footer-bottom">© 2026 Cook With Soumi — La cuisine à partager.</footer>


<div id="siteMessage" class="site-message" role="status" hidden></div>
<script src="<?= e(URL) ?>Vue/js/pages.js" defer></script>
</body>
</html>
