<?php
// Rôle de ce fichier : Affiche les ingrédients et les étapes d'une recette.
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cook With Soumi — <?= e($recipe['name']) ?></title>
  <link rel="stylesheet" href="<?= e(URL) ?>Vue/css/Accueil.css">
  <link rel="stylesheet" href="<?= e(URL) ?>Vue/css/navbar.css">
  <link rel="stylesheet" href="<?= e(URL) ?>Vue/css/categories.css">
  <link rel="stylesheet" href="<?= e(URL) ?>Vue/css/favoris.css">
  <link rel="stylesheet" href="<?= e(URL) ?>Vue/css/detaille.css">
  <link rel="stylesheet" href="<?= e(URL) ?>Vue/css/pages.css">
</head>
<body data-base="<?= e(URL) ?>" data-page="detaille">
  <?php require __DIR__ . '/navbar.php'; ?>
  <div class="breadcrumb">
    <a href="<?= e(URL) ?>?page=accueil">Accueil</a><span>›</span>
    <a href="<?= e(URL) ?>?page=categories">Catégories</a><span>›</span>
    <span><?= e($recipe['tag']) ?></span><span>›</span><span><?= e($recipe['name']) ?></span>
  </div>
  <section class="recipe-hero">
    <div class="recipe-hero-thumb">
      <?php if (!empty($recipe['image_path'])): ?>
        <img id="recipePhoto" src="<?= e(URL . $recipe['image_path']) ?>" alt="<?= e($recipe['name']) ?>">
      <?php else: ?><span id="recipeThumb"><?= e($recipe['emoji']) ?></span><?php endif; ?>
    </div>
    <div class="recipe-hero-content reveal">
      <span class="recipe-tag-big"><?= e($recipe['tag']) ?></span>
      <h1 class="recipe-hero-title"><?= e($recipe['name']) ?></h1>
      <div class="recipe-hero-meta">
        <div class="meta-pill">⏱️ <strong><?= e($recipe['time']) ?></strong></div>
        <div class="meta-pill">👥 <strong><?= (int)$recipe['people'] ?> personnes</strong></div>
        <div class="meta-pill">🌿 <strong>Halal</strong></div>
      </div>
      <div class="recipe-hero-actions">
        <button type="button" class="btn-primary" data-favorite="<?= (int)$recipe['id'] ?>" aria-pressed="<?= in_array($recipe['id'],$favoriteIds,true) ? 'true' : 'false' ?>">
          <?= in_array($recipe['id'],$favoriteIds,true) ? '♥ Retirer des favoris' : '♡ Ajouter aux favoris' ?>
        </button>
        <button class="btn-outline" id="printRecipe" type="button">🖨️ Imprimer</button>
      </div>
    </div>
  </section>
  <div class="recipe-layout">
    <aside class="recipe-aside reveal">
      <div class="aside-card">
        <h2 class="aside-title">🛒 Ingrédients</h2>
        <div class="serving-control" data-original-servings="<?= (int)$recipe['people'] ?>">
          <span>Pour</span><button type="button" id="servingLess" aria-label="Diminuer le nombre de personnes">−</button>
          <span id="servingCount"><?= (int)$recipe['people'] ?></span>
          <button type="button" id="servingMore" aria-label="Augmenter le nombre de personnes">+</button><span>personnes</span>
        </div>
        <ul class="ingredient-list" id="ingredientList">
          <?php // Parcourt les ingrédients de la recette et affiche les quantités. ?>
          <?php foreach ($ingredients as $ingredient): ?>
          <li data-qty="<?= e($ingredient['qty']) ?>" data-unit="<?= e($ingredient['unit']) ?>">
            <span><?= e($ingredient['name']) ?></span>
            <span class="ing-qty"><?= $ingredient['qty'] !== null ? e((float)$ingredient['qty'] == (int)$ingredient['qty'] ? (int)$ingredient['qty'] : (float)$ingredient['qty']) . ' ' : '' ?><?= e($ingredient['unit']) ?></span>
          </li>
          <?php endforeach; ?>
        </ul>
        <?php if (!$ingredients): ?><p>Les ingrédients seront bientôt disponibles.</p><?php endif; ?>
      </div>
    </aside>
    <main class="recipe-main">
      <h2 class="steps-title reveal">👩‍🍳 Préparation</h2>
      <ol class="steps-list" id="stepsList">
        <?php // Parcourt les étapes dans le bon ordre pour expliquer la préparation. ?>
        <?php foreach ($steps as $step): ?>
        <li class="step-item"><div class="step-num"><?= (int)$step['step_order'] ?></div><div class="step-text"><?= e($step['instruction']) ?></div></li>
        <?php endforeach; ?>
      </ol>
      <?php if (!$steps): ?><p>La préparation sera bientôt disponible.</p><?php endif; ?>
    </main>
  </div>
  <?php if ($related): ?>
  <section class="related-section">
    <h2 class="section-title">À découvrir aussi</h2>
    <div id="relatedGrid" class="fav-grid">
      <?php // Parcourt les recettes similaires pour proposer d’autres idées. ?>
      <?php foreach ($related as $recipe): ?>
      <?php require __DIR__ . '/partials/recipe_card.php'; ?>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>
  <footer class="footer-bottom">© 2026 Cook With Soumi — La cuisine à partager.</footer>
  <div id="siteMessage" class="site-message" role="status" hidden></div>
  <script src="<?= e(URL) ?>Vue/js/pages.js" defer></script>
</body>
</html>
