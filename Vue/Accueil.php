<?php
// Rôle de ce fichier : Affiche l'accueil et les cartes des recettes.
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cook With Soumi</title>
    <link rel="stylesheet" href="<?= htmlspecialchars(URL, ENT_QUOTES, 'UTF-8') ?>Vue/css/Accueil.css" />
    <link rel="stylesheet" href="<?= htmlspecialchars(URL, ENT_QUOTES, 'UTF-8') ?>Vue/css/navbar.css" />

<link rel="stylesheet" href="<?= htmlspecialchars(URL, ENT_QUOTES, 'UTF-8') ?>Vue/css/pages.css"></head>

<body data-base="<?= e(URL) ?>" data-page="accueil">
    <!-- NAV -->
    <?php
    require_once(__DIR__ . '/navbar.php');

    ?>

    <!-- HERO -->
    <section class="hero">
        <div class="hero-bg-circle"></div>
        <div class="hero-content">
            <div class="hero-badge">
                <span>✨</span> Cuisine maison & authentique
            </div>
            <h1 class="hero-title">
                Des recettes faites<br />avec <em>amour</em> &<br />passion
            </h1>
            <p class="hero-desc">
                Bienvenue dans la cuisine de Soumi ! Découvrez des recettes
                savoureuses, faciles à réaliser, inspirées de la cuisine
                orientale et méditerranéenne.
            </p>
            <div class="hero-buttons">
                <a href="<?= htmlspecialchars(URL, ENT_QUOTES, 'UTF-8') ?>?page=recette" class="btn-primary">🍽️ Voir les recettes</a>
                <a href="<?= htmlspecialchars(URL, ENT_QUOTES, 'UTF-8') ?>?page=about" class="btn-outline">En savoir plus →</a>
            </div>
            <div class="hero-stats">
                <div class="stat-item"><span class="stat-num"><?= (int)$counts['recipes'] ?></span><span class="stat-label">Recettes</span></div>
                <div class="stat-divider"></div>
                <div class="stat-item"><span class="stat-num"><?= (int)$counts['categories'] ?></span><span class="stat-label">Catégories</span></div>
                <div class="stat-divider"></div>
                <div class="stat-item"><span class="stat-num"><?= (int)$counts['members'] ?></span><span class="stat-label">Membres</span></div>
            </div>
        </div>
        <div class="hero-image">
            <div class="hero-logo-wrap">

                <div class="deco-pill deco-1">
                    <span class="icon">🕐</span> Prêt en 30 min
                </div>
                <div class="deco-pill deco-2">
                    <span class="icon">❤️</span> À découvrir
                </div>
                <div class="deco-pill deco-3">
                    <span class="icon">🌿</span> 100% halal
                </div>
            </div>
        </div>
    </section>

    <!-- WAVE -->
    <div class="wave-divider">
        <svg
            viewBox="0 0 1440 60"
            xmlns="http://www.w3.org/2000/svg"
            preserveAspectRatio="none">
            <path
                d="M0,30 C360,60 1080,0 1440,30 L1440,60 L0,60 Z"
                fill="#ffffff" />
        </svg>
    </div>

    <!-- CATEGORIES -->
    <section class="categories" id="categories">
        <div class="reveal">
            <span class="section-label">Explorer</span>
            <h2 class="section-title">Toutes les catégories</h2>
            <p class="section-sub">
                Des entrées aux desserts, retrouvez toutes les saveurs qui
                font le bonheur de votre table.
            </p>
        </div>
        <div class="categories-grid reveal">
            <?php // Parcourt les catégories pour créer les cartes de l’accueil. ?>
            <?php foreach ($categories as $category): ?>
            <a class="cat-card" href="<?= e(URL) ?>?page=recette&amp;cat=<?= e($category['slug']) ?>">
                <span class="cat-icon"><?= e($category['emoji']) ?></span>
                <div class="cat-name"><?= e($category['label']) ?></div>
                <div class="cat-count"><?= (int)$category['recipe_count'] ?> <?= (int)$category['recipe_count'] === 1 ? 'recette' : 'recettes' ?></div>
            </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- RECETTES -->
    <section class="recettes" id="recettes">
        <div class="recettes-header reveal">
            <div>
                <span class="section-label">À la une</span>
                <h2 class="section-title">Recettes à découvrir</h2>
            </div>
            <a href="<?= htmlspecialchars(URL, ENT_QUOTES, 'UTF-8') ?>?page=recette" class="btn-outline">Voir tout →</a>
        </div>
        <div class="recettes-grid">
            <?php // Parcourt les recettes mises en avant pour afficher leurs cartes. ?>
            <?php foreach ($featured as $recipe): ?>
            <div class="recipe-card reveal">
                <a href="<?= e(URL) ?>?page=detaille&amp;id=<?= (int)$recipe['id'] ?>" class="recipe-thumb recipe-image-link" aria-label="Voir <?= e($recipe['name']) ?>">
                    <?php if (!empty($recipe['image_path'])): ?>
                      <img src="<?= e(URL . $recipe['image_path']) ?>" alt="<?= e($recipe['name']) ?>" loading="lazy">
                    <?php else: ?><?= e($recipe['emoji']) ?><?php endif; ?>
                </a>
                <div class="recipe-body">
                    <span class="recipe-tag"><?= e($recipe['tag']) ?></span>
                    <div class="recipe-name"><a href="<?= e(URL) ?>?page=detaille&amp;id=<?= (int)$recipe['id'] ?>"><?= e($recipe['name']) ?></a></div>
                    <div class="recipe-meta"><span>⏱️ <?= e($recipe['time']) ?></span><span>👥 <?= (int)$recipe['people'] ?> pers.</span></div>
                    <button type="button" class="fav-btn-remove" data-favorite="<?= (int)$recipe['id'] ?>" aria-pressed="<?= in_array($recipe['id'],$favoriteIds,true) ? 'true' : 'false' ?>">
                    <?= in_array($recipe['id'],$favoriteIds,true) ? '♥ Retirer des favoris' : '♡ Ajouter aux favoris' ?></button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ABOUT -->
    <section class="about" id="about">
        <div class="reveal">
            <span class="section-label about-label">Mon histoire</span>
            <h2 class="section-title">
                La cuisine, ma passion depuis toujours
            </h2>
            <p class="section-sub">
                Je suis Soumi, passionnée de cuisine orientale et
                méditerranéenne. Mon objectif ? Vous partager des recettes
                authentiques, accessibles et savoureuses pour régaler toute
                la famille.
            </p>
            <div class="about-features">
                <?php // Parcourt les informations de l’accueil enregistrées dans MySQL. ?>
                <?php foreach ($homeFeatures as $card): ?>
                <div class="feat-item">
                    <div class="feat-icon"><?= e($card['icon']) ?></div>
                    <div class="feat-title"><?= e($card['title']) ?></div>
                    <div class="feat-desc"><?= e($card['description']) ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="about-image-side reveal">
            <img
                class="img"
                src="<?= htmlspecialchars(URL, ENT_QUOTES, 'UTF-8') ?>Vue/image/Cook With Soumi logo.png"
                alt="Cook With Soumi" />
        </div>
    </section>

    <!-- NEWSLETTER -->
    <section class="newsletter" id="newsletter">
        <div class="reveal">
            <h2 class="section-title newsletter-title">
                Ne manquez aucune recette !
            </h2>
            <p>
                Inscrivez-vous pour être informé des prochaines recettes 💌
            </p>
            <?php if (($_GET['newsletter'] ?? '') === 'ok'): ?>
                <p role="status">Merci ! Votre adresse a bien été enregistrée.</p>
            <?php elseif (($_GET['newsletter'] ?? '') === 'invalid'): ?>
                <p role="alert">Veuillez saisir une adresse e-mail valide.</p>
            <?php endif; ?>
            <form class="newsletter-form" id="newsletterForm" action="<?= e(URL) ?>?page=newsletter" method="post">
                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                <input name="email" type="email" placeholder="Votre adresse email..." maxlength="200" required>
                <button type="submit">Je m'abonne ✨</button>
            </form>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <div>
            <img
                class="img"
                src="<?= htmlspecialchars(URL, ENT_QUOTES, 'UTF-8') ?>Vue/image/Cook With Soumi logo.png"
                alt="Cook With Soumi" />
            <p>
                La cuisine faite avec amour,<br />partagée avec générosité.
            </p>
            <div class="footer-social">
                <a href="#" class="social-btn" title="Instagram">📸</a>
                <a href="#" class="social-btn" title="YouTube">▶️</a>
                <a href="#" class="social-btn" title="TikTok">🎵</a>
                <a href="#" class="social-btn" title="Facebook">👍</a>
            </div>
        </div>
        <div class="footer-col">
            <h4>Recettes</h4>
            <ul>
                <?php // Parcourt les catégories pour remplir une autre section de l’accueil. ?>
                <?php foreach ($categories as $category): ?>
                  <li><a href="<?= e(URL) ?>?page=recette&amp;cat=<?= e($category['slug']) ?>"><?= e($category['label']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Navigation</h4>
            <ul>
                <li><a href="<?= htmlspecialchars(URL, ENT_QUOTES, 'UTF-8') ?>?page=accueil">Accueil</a></li>
                <li><a href="<?= htmlspecialchars(URL, ENT_QUOTES, 'UTF-8') ?>?page=about">À propos</a></li>
                <li><a href="#">Blog</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Légal</h4>
            <ul>
                <li><a href="#">Mentions légales</a></li>
                <li><a href="#">Confidentialité</a></li>
                <li><a href="#">Cookies</a></li>
            </ul>
        </div>
    </footer>
    <div class="footer-bottom">
        © 2026 Cook With Soumi — Tous droits réservés ❤️
    </div>

    <script src="<?= htmlspecialchars(URL, ENT_QUOTES, 'UTF-8') ?>Vue/js/Accueil.js"></script>
<script src="<?= htmlspecialchars(URL, ENT_QUOTES, 'UTF-8') ?>Vue/js/pages.js" defer></script></body>

</html>