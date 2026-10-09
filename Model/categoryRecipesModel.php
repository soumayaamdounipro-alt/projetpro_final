<?php
// Rôle de ce fichier : Récupère les recettes associées aux catégories.

require_once __DIR__ . '/recipesModel.php';

/** Adaptateur conservé pour l'ancienne organisation MVC. */
class CategorieRecipesModel
{
    // getRecipesAvecCategory() : Demande au modèle principal la liste des recettes et de leurs catégories. Elle conserve la compatibilité avec l’ancienne organisation du site.
    public function getRecipesAvecCategory(): array
    {
        return (new RecipesModel())->getAllRecipes();
    }
    // getCategories() : Demande au modèle principal la liste des catégories. Elle permet aux anciens fichiers d’utiliser les catégories MySQL.
    public function getCategories(): array
    {
        return (new RecipesModel())->getCategories();
    }
}
