<?php
// Rôle de ce fichier : Récupère les recettes, catégories et favoris depuis MySQL.

/** Lecture des recettes et des catégories depuis MySQL (plus de fichier JSON). */
require_once __DIR__ . '/db.php';

class RecipesModel
{
    private mysqli $db;
    // __construct() : Prépare le modèle en récupérant la connexion MySQLi. Ainsi, les autres méthodes peuvent interroger la base.
    public function __construct()
    {
        $this->db = getDB();
    }

    /** @return array<int,array<string,mixed>> */
    // getAllRecipes() : Lit toutes les recettes et leurs catégories dans MySQL. Elle sert à remplir les cartes sur l’accueil et la page Recettes.
    public function getAllRecipes(): array
    {
        $sql = "SELECT r.recipe_id AS id, r.name, r.preparation_time, r.time_label,
                       r.people, r.emoji, r.image_path,
                       COALESCE(c0.label, 'Autres') AS tag,
                       COALESCE(GROUP_CONCAT(DISTINCT c.slug ORDER BY c.slug SEPARATOR ','), '') AS category_slugs
                FROM recipes r
                LEFT JOIN category c0 ON c0.id = r.category_id
                LEFT JOIN recipe_categories rc ON rc.recipe_id = r.recipe_id
                LEFT JOIN category c ON c.id = rc.category_id
                GROUP BY r.recipe_id, r.name, r.preparation_time, r.time_label,
                         r.people, r.emoji, r.image_path, c0.label
                ORDER BY r.recipe_id DESC";
        $rows = $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);
        return array_map([$this, 'normalizeRecipe'], $rows);
    }
    // getRecipeById() : Cherche une seule recette avec son identifiant. Elle sert à afficher sa fiche complète et à vérifier son existence.
    public function getRecipeById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT r.recipe_id AS id, r.name, r.preparation_time,
                   r.time_label, r.people, r.emoji, r.image_path,
                   COALESCE(c0.label,'Autres') AS tag,
                   COALESCE(GROUP_CONCAT(DISTINCT c.slug ORDER BY c.slug SEPARATOR ','),'') AS category_slugs
            FROM recipes r LEFT JOIN category c0 ON c0.id=r.category_id
            LEFT JOIN recipe_categories rc ON rc.recipe_id=r.recipe_id
            LEFT JOIN category c ON c.id=rc.category_id
            WHERE r.recipe_id=?
            GROUP BY r.recipe_id,r.name,r.preparation_time,r.time_label,
                     r.people,r.emoji,r.image_path,c0.label LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $recipe = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $recipe ? $this->normalizeRecipe($recipe) : null;
    }
    // normalizeRecipe() : Met les données SQL dans un format commun (identifiant, personnes, catégories, durée). Elle facilite leur affichage dans les pages.
    private function normalizeRecipe(array $recipe): array
    {
        $recipe['id'] = (int)$recipe['id'];
        $recipe['people'] = max(1, (int)$recipe['people']);
        $recipe['categories'] = array_values(array_filter(explode(',', (string)$recipe['category_slugs'])));
        $recipe['time'] = $recipe['time_label'] ?: $this->formatTime($recipe['preparation_time']);
        return $recipe;
    }
    // formatTime() : Transforme une heure MySQL, par exemple 01:15:00, en durée lisible. Elle sert à afficher « 1 h 15 min » sur les cartes.
    private function formatTime(string $time): string
    {
        [$h,$m] = array_map('intval', explode(':',$time));
        return ($h ? $h.' h' : '') . ($m ? ($h ? ' ' : '').$m.' min' : ($h ? '' : '0 min'));
    }
    // getCategories() : Lit les catégories et compte les recettes de chacune. Elle sert à construire la liste et les cartes des catégories.
    public function getCategories(): array
    {
        return $this->db->query("SELECT c.id, c.label, c.slug, c.emoji, c.description,
                   COUNT(DISTINCT rc.recipe_id) AS recipe_count
            FROM category c LEFT JOIN recipe_categories rc ON rc.category_id=c.id
            GROUP BY c.id,c.label,c.slug,c.emoji,c.description ORDER BY c.id")
            ->fetch_all(MYSQLI_ASSOC);
    }
    // getIngredientsByRecipe() : Récupère les ingrédients et leurs quantités pour une recette. Elle sert à afficher ce qu’il faut pour la préparer.
    public function getIngredientsByRecipe(int $id): array
    {
        $stmt=$this->db->prepare("SELECT i.name, i.unity AS unit, ri.quantity AS qty
            FROM recipe_ingredients ri INNER JOIN ingredients i ON i.ingredient_id=ri.ingredient_id
            WHERE ri.recipe_id=? ORDER BY ri.id");
        $stmt->bind_param('i',$id);
        $stmt->execute();
        $result=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }
    // getStepsByRecipe() : Lit les étapes de préparation dans leur ordre. Elle sert à guider la personne qui réalise la recette.
    public function getStepsByRecipe(int $id): array
    {
        $stmt=$this->db->prepare('SELECT step_order, instruction FROM recipe_steps WHERE recipe_id=? ORDER BY step_order');
        $stmt->bind_param('i',$id);
        $stmt->execute();
        $result=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }
    // getSiteCards() : Lit les cartes d’une section du site, comme l’accueil ou « À propos ». Elle évite de saisir ces textes directement dans le HTML.
    public function getSiteCards(string $section): array
    {
        $stmt=$this->db->prepare('SELECT icon,subtitle,title,description FROM site_cards WHERE section=? ORDER BY sort_order,id');
        $stmt->bind_param('s',$section);
        $stmt->execute();
        $rows=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }
    // getCounts() : Compte les recettes, catégories et membres dans MySQL. Elle sert à afficher les chiffres de l’accueil.
    public function getCounts(): array
    {
        return [
            'recipes' => (int)$this->db->query('SELECT COUNT(*) AS total FROM recipes')->fetch_assoc()['total'],
            'categories' => (int)$this->db->query('SELECT COUNT(*) AS total FROM category')->fetch_assoc()['total'],
            'members' => (int)$this->db->query('SELECT COUNT(*) AS total FROM users')->fetch_assoc()['total'],
        ];
    }
    // getFavoriteIds() : Récupère les identifiants des recettes favorites d’un membre. Elle sert à savoir quels boutons « Favoris » activer.
    public function getFavoriteIds(int $userId): array
    {
        if ($userId <= 0) return [];
        $stmt=$this->db->prepare('SELECT id_recipes FROM favorites WHERE id_user=?');
        $stmt->bind_param('i',$userId);
        $stmt->execute();
        $rows=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return array_map('intval',array_column($rows,'id_recipes'));
    }
    // setFavorite() : Ajoute ou retire une recette des favoris du membre connecté. Elle enregistre réellement le changement dans MySQL.
    public function setFavorite(int $userId, int $recipeId, bool $add): void
    {
        if ($add) {
            $stmt=$this->db->prepare('INSERT INTO favorites (id_user,id_recipes) VALUES (?,?) ON DUPLICATE KEY UPDATE id_recipes=VALUES(id_recipes)');
        } else {
            $stmt=$this->db->prepare('DELETE FROM favorites WHERE id_user=? AND id_recipes=?');
        }
        $stmt->bind_param('ii',$userId,$recipeId);
        $stmt->execute();
        $stmt->close();
    }
    // subscribe() : Enregistre une adresse e-mail dans la newsletter. Elle permet de conserver l’inscription dans MySQL.
    public function subscribe(string $email): void
    {
        $stmt=$this->db->prepare('INSERT IGNORE INTO newsletter_subscribers (email) VALUES (?)');
        $stmt->bind_param('s',$email);
        $stmt->execute();
        $stmt->close();
    }
}
