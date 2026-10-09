<?php
// Rôle de ce fichier : Récupère les ingrédients et leurs quantités.

require_once __DIR__ . '/recipesModel.php';

class RecetteIngredientsModel
{
    // getIngredientsParRecipe() : Demande au modèle principal les ingrédients d’une recette. Elle garde un point d’accès simple pour les anciennes pages.
    public function getIngredientsParRecipe(int $recipeId): array
    {
        return (new RecipesModel())->getIngredientsByRecipe($recipeId);
    }
}
