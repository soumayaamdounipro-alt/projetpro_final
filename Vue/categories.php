<?php
// Rôle de ce fichier : Affiche les catégories et leurs recettes.
?>
<!DOCTYPE html> 
<html lang="fr"> 
<head> 
  <meta charset="UTF-8"/> 
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/> 
  <title>Cook With Soumi — Catégories</title> 

<link rel="stylesheet" href="<?= e(URL) ?>Vue/css/Accueil.css">
<link rel="stylesheet" href="<?= e(URL) ?>Vue/css/navbar.css">
<link rel="stylesheet" href="<?= e(URL) ?>Vue/css/categories.css">
<link rel="stylesheet" href="<?= e(URL) ?>Vue/css/favoris.css">
<link rel="stylesheet" href="<?= e(URL) ?>Vue/css/pages.css">
</head> 

<body data-base="<?= e(URL) ?>" data-page="categories"> 


  <?php require __DIR__ . "/navbar.php"; ?> 

  <section class="page-hero"> 

    <div class="page-hero-content reveal"> 

      <span class="section-label">Explorer</span> 

      <h1 class="page-hero-title">Toutes les <em>catégories</em></h1> 

      <p class="page-hero-desc">Trouvez l'inspiration parmi nos familles de recettes soigneusement sélectionnées.</p> 

    </div> 

  </section> 

  <section class="search-section"> 

    <div class="search-bar reveal"> 

      <span class="search-icon">🔍</span> 

      <input type="text" id="searchInput" placeholder="Rechercher une catégorie…"/> 

    </div> 

  </section> 

  <section class="cat-section"> 

    <div class="cat-big-grid" id="catGrid"> 

      <?php // Parcourt les catégories de MySQL pour créer automatiquement leurs cartes. ?>
      <?php foreach ($categories as $category): ?>
      <a href="<?= e(URL) ?>?page=recette&amp;cat=<?= e($category['slug']) ?>" class="cat-big-card reveal"
         data-name="<?= e($category['label']) ?>">
        <div class="cat-big-thumb"><?= e($category['emoji']) ?></div>
        <div class="cat-big-body">
          <div class="cat-big-name"><?= e($category['label']) ?></div>
          <div class="cat-big-desc"><?= e($category['description']) ?></div>
          <div class="cat-big-count"><?= (int)$category['recipe_count'] ?> <?= (int)$category['recipe_count'] === 1 ? 'recette' : 'recettes' ?></div>
        </div>
      </a>
      <?php endforeach; ?>
    </div> 

    <div class="layout-12 cat-no-results" id="noResults"> 

      <span>😕</span> 

      <p>Aucune catégorie trouvée pour "<span id="searchTerm"></span>"</p> 

    </div> 

  </section> 

  <footer class="footer-bottom">© 2026 Cook With Soumi — La cuisine à partager.</footer> 


<div id="siteMessage" class="site-message" role="status" hidden></div>
<script src="<?= e(URL) ?>Vue/js/pages.js" defer></script>
</body> 

</html> 

