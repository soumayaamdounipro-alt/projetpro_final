<?php
// Rôle de ce fichier : Présente le site et son contenu.
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cook With Soumi — À propos</title>

<link rel="stylesheet" href="<?= e(URL) ?>Vue/css/Accueil.css">
<link rel="stylesheet" href="<?= e(URL) ?>Vue/css/navbar.css">
<link rel="stylesheet" href="<?= e(URL) ?>Vue/css/categories.css">
<link rel="stylesheet" href="<?= e(URL) ?>Vue/css/favoris.css">
<link rel="stylesheet" href="<?= e(URL) ?>Vue/css/about.css">
<link rel="stylesheet" href="<?= e(URL) ?>Vue/css/pages.css">
</head>
<body data-base="<?= e(URL) ?>" data-page="about">

  <?php require __DIR__ . "/navbar.php"; ?>

  <section class="about-hero">
    <div class="about-hero-content reveal">
      <span class="section-label">Mon histoire</span>
      <h1 class="page-hero-title">Bienvenue dans <em>ma cuisine</em></h1>
      <p class="page-hero-desc">Je suis Soumi, passionnée de cuisine depuis toute petite. Ici, je partage avec vous les recettes qui ont bercé mon enfance et celles que j'invente au fil des saisons.</p>
    </div>
    <div class="about-hero-img reveal">
      <img src="<?= e(URL) ?>Vue/img/CookWithSoumi-logo.png" alt="Soumi"/>
    </div>
  </section>

  <section class="values-section">
    <div class="layout-1 reveal">
      <span class="section-label">Ce qui me guide</span>
      <h2 class="section-title">Mes valeurs en cuisine</h2>
    </div>
    <div class="values-grid">
      <?php // Parcourt les cartes de présentation enregistrées dans MySQL. ?>
      <?php foreach ($values as $card): ?>
      <div class="value-card reveal">
        <div class="value-icon"><?= e($card['icon']) ?></div>
        <h3 class="value-title"><?= e($card['title']) ?></h3>
        <p class="value-desc"><?= e($card['description']) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="timeline-section">
    <div class="layout-2 reveal">
      <span class="section-label">Mon parcours</span>
      <h2 class="section-title">Mon aventure culinaire</h2>
    </div>
    <div class="timeline">
      <?php // Parcourt les moments importants de la présentation pour créer la chronologie. ?>
      <?php foreach ($timeline as $card): ?>
      <div class="timeline-item reveal">
        <div class="timeline-dot"><?= e($card['icon']) ?></div>
        <div class="timeline-content">
          <div class="timeline-year"><?= e($card['subtitle']) ?></div>
          <h3><?= e($card['title']) ?></h3>
          <p><?= e($card['description']) ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="stats-section">
    <div class="stats-grid">
      <div class="stat-big reveal"><span class="stat-big-num"><?= (int)$counts['recipes'] ?></span><span class="stat-big-label">Recettes publiées</span></div>
      <div class="stat-big reveal"><span class="stat-big-num"><?= (int)$counts['members'] ?></span><span class="stat-big-label">Membres inscrits</span></div>
      <div class="stat-big reveal"><span class="stat-big-num"><?= (int)$counts['categories'] ?></span><span class="stat-big-label">Catégories</span></div>
    </div>
  </section>

  <section class="about-cta reveal">
    <h2>Prête à cuisiner avec moi ?</h2>
    <p>Rejoignez la communauté Cook With Soumi et découvrez régulièrement de nouvelles recettes.</p>
    <div class="layout-3">
      <a href="<?= e(URL) ?>?page=categories" class="btn-primary">🍽️ Voir les recettes</a>
      <a href="<?= e(URL) ?>?page=register" class="btn-outline">Créer mon compte</a>
    </div>
  </section>

  <footer class="footer-bottom">© 2026 Cook With Soumi — La cuisine à partager.</footer>


<div id="siteMessage" class="site-message" role="status" hidden></div>
<script src="<?= e(URL) ?>Vue/js/pages.js" defer></script>
</body>
</html>
