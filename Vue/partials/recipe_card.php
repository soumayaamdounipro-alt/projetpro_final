<?php
// Rôle de ce fichier : Construit une carte de recette à partir de ses données.

/** Attendu : $recipe, $favoriteIds, constantes URL et e(). */
$isFavorite = in_array((int)$recipe['id'], $favoriteIds, true);
$detailHref = URL . '?page=detaille&id=' . (int)$recipe['id'];
?>
<article class="fav-card recipe-item" data-name="<?= e($recipe['name']) ?>" data-categories="<?= e(implode(' ', $recipe['categories'])) ?>">
    <a class="fav-thumb recipe-image-link" href="<?= e($detailHref) ?>" aria-label="Voir <?= e($recipe['name']) ?>">
        <?php if (!empty($recipe['image_path'])): ?>
            <img src="<?= e(URL . $recipe['image_path']) ?>" alt="<?= e($recipe['name']) ?>" loading="lazy">
        <?php else: ?>
            <span><?= e($recipe['emoji']) ?></span>
        <?php endif; ?>
    </a>
    <div class="fav-body">
        <span class="fav-tag"><?= e($recipe['tag']) ?></span>
        <h2 class="fav-name"><?= e($recipe['name']) ?></h2>
        <div class="fav-meta"><span>⏱ <?= e($recipe['time']) ?></span><span>👥 <?= (int)$recipe['people'] ?> personnes</span></div>
        <div class="fav-actions">
            <a class="fav-btn-view" href="<?= e($detailHref) ?>">Voir la recette →</a>
            <button type="button" class="fav-btn-remove" data-favorite="<?= (int)$recipe['id'] ?>" aria-pressed="<?= $isFavorite ? 'true' : 'false' ?>">
                <?= $isFavorite ? '♥ Retirer des favoris' : '♡ Ajouter aux favoris' ?>
            </button>
        </div>
    </div>
</article>
