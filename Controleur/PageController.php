<?php
// Rôle de ce fichier : Prépare les données nécessaires à l'affichage des pages.

require_once __DIR__ . '/../Model/recipesModel.php';

/** Échappement HTML de toutes les données provenant de MySQL. */
// e() : Protège un texte avant de le placer dans du HTML. Elle empêche qu’un texte venu de MySQL soit interprété comme du code HTML.
function e($value): string
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
// renderPage() : Choisit la vue demandée et prépare ses recettes, catégories ou favoris. Elle sert à afficher une page avec les bonnes données MySQL.
function renderPage(string $page, int $status = 200): void
{
    $allowed = ['about', 'categories', 'favoris', 'recette', 'detaille', '404'];
    if (!in_array($page, $allowed, true)) { $page = '404'; $status = 404; }
    $model = new RecipesModel();
    $favoriteIds = $model->getFavoriteIds((int)($_SESSION['user_id'] ?? 0));
    $categories = $model->getCategories();
    $counts = $model->getCounts();

    if ($page === 'detaille') {
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
        $recipe = $id ? $model->getRecipeById($id) : null;
        if (!$recipe) { $page='404'; $status=404; }
        else {
            $ingredients=$model->getIngredientsByRecipe($id);
            $steps=$model->getStepsByRecipe($id);
            $all=$model->getAllRecipes();
            // Cette petite fonction garde seulement les recettes liées à la recette affichée.
            $related = array_values(array_filter($all, static fn($r) =>
                $r['id'] !== $id && count(array_intersect($r['categories'], $recipe['categories'])) > 0));
            $related = array_slice($related, 0, 3);
        }
    } elseif ($page === 'recette') {
        $recipes = $model->getAllRecipes();
    } elseif ($page === 'favoris') {
        // Cette petite fonction ne garde que les recettes ajoutées aux favoris du membre.
        $recipes = array_values(array_filter($model->getAllRecipes(),
            static fn($r) => in_array($r['id'], $favoriteIds, true)));
    } elseif ($page === 'about') {
        $values = $model->getSiteCards('about_values');
        $timeline = $model->getSiteCards('about_timeline');
    }

    http_response_code($status);
    require __DIR__ . '/../Vue/' . $page . '.php';
}
