<?php
// Rôle de ce fichier : Affiche les cartes des recettes et les filtres.
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cook With Soumi — Recettes</title>
    <link rel="stylesheet" href="<?= e(URL) ?>Vue/css/Accueil.css">
    <link rel="stylesheet" href="<?= e(URL) ?>Vue/css/navbar.css">
    <link rel="stylesheet" href="<?= e(URL) ?>Vue/css/categories.css">
    <link rel="stylesheet" href="<?= e(URL) ?>Vue/css/favoris.css">
    <link rel="stylesheet" href="<?= e(URL) ?>Vue/css/recette.css">
    <link rel="stylesheet" href="<?= e(URL) ?>Vue/css/pages.css">
</head>

<body data-base="<?= e(URL) ?>" data-page="recette">
    <?php require __DIR__ . "/navbar.php"; ?><main>
        <section class="page-hero">
            <div class="page-hero-content"><span class="section-label">À cuisiner aujourd'hui</span>
                <h1 class="page-hero-title">Toutes nos <em>recettes</em></h1>
                <p class="page-hero-desc">Des recettes à explorer, à préparer et à garder dans vos favoris.</p>
            </div>
        </section>
        <section class="catalogue-section">
            <form class="catalogue-filters" id="recipeFilters" role="search"><label>Rechercher une recette<input
                        type="search" id="recipeSearch" placeholder="Tajine, crêpes…"></label><label>Catégorie<select
                        id="categoryFilter">
                        <option value="">Toutes les catégories</option>
                        <?php // Parcourt les catégories pour remplir le filtre de recherche. ?>
                        <?php foreach ($categories as $category): ?>
                        <option value="<?= e($category['slug']) ?>"><?= e($category['label']) ?> (<?= (int)$category['recipe_count'] ?>)</option>
                        <?php endforeach; ?>
                    </select></label></form>
            <p id="recipeCount" role="status"><?= count($recipes) ?> recettes</p>
            <div id="recipeGrid" class="fav-grid">
                <?php // Parcourt les recettes de MySQL pour fabriquer toutes les cartes. ?>
                <?php foreach ($recipes as $recipe): ?>
                  <?php require __DIR__ . '/partials/recipe_card.php'; ?>
                <?php endforeach; ?>
            </div>
            <p id="recipeEmpty" <?= $recipes ? "hidden" : "" ?>>Aucune recette ne correspond à votre recherche.</p>
        </section>
    </main>
    <footer class="footer-bottom">© 2026 Cook With Soumi — La cuisine à partager.</footer>
    <div id="siteMessage" class="site-message" role="status" hidden></div>
    <script src="<?= e(URL) ?>Vue/js/pages.js" defer></script>
</body>

</html>