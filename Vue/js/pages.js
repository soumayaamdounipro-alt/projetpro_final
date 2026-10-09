// Ce fichier gère les interactions de la page : clics, affichage et actions de l'utilisateur.
/* Cook With Soumi – pages PHP rendues depuis MySQL, sans données JSON/localStorage. */
// Cette fonction s’exécute dès le chargement du script pour installer les interactions du site.
(() => {
  'use strict';
  const navbar = document.getElementById('navbar');
  const base = document.body.dataset.base || navbar?.dataset.base || './';
  const page = document.body.dataset.page || 'accueil';
  const csrf = navbar?.dataset.csrf || '';
  const links = document.getElementById('navigationLinks');
  const burger = document.getElementById('navBurger');
  // Quand on clique sur le bouton du menu, cette fonction ouvre ou ferme la navigation mobile.
  burger?.addEventListener('click', () => {
    const open = links.classList.toggle('open');
    burger.setAttribute('aria-expanded', String(open));
    burger.setAttribute('aria-label', open ? 'Fermer le menu' : 'Ouvrir le menu');
  });
  // Quand une touche est pressée, cette fonction ferme le menu si la touche est Échap.
  document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && links) {
      links.classList.remove('open');
      burger?.setAttribute('aria-expanded', 'false');
    }
  });

  // message() : Affiche un petit message à l’écran pour confirmer une action ou signaler une erreur.
  function message(text) {
    let node = document.getElementById('siteMessage');
    if (!node) {
      node = document.createElement('div');
      node.id = 'siteMessage'; node.className = 'site-message'; node.setAttribute('role','status');
      document.body.appendChild(node);
    }
    node.hidden = false; node.textContent = text;
  }
  // normalize() : Retire les accents et les majuscules pour que la recherche trouve plus facilement les recettes.
  const normalize = text => String(text).normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();

  // toggleFavorite() : Envoie une demande au serveur pour ajouter ou retirer une recette des favoris enregistrés dans MySQL.
  async function toggleFavorite(button) {
    const recipeId = Number(button.dataset.favorite);
    if (!Number.isInteger(recipeId) || recipeId < 1 || button.disabled) return;
    const old = button.getAttribute('aria-pressed') === 'true';
    button.disabled = true;
    try {
      const params = new URLSearchParams({
        recipe_id: String(recipeId), action: old ? 'remove' : 'add', csrf_token: csrf
      });
      const response = await fetch(base + '?page=toggle-favorite', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8', 'Accept': 'application/json'},
        body: params.toString(), credentials: 'same-origin'
      });
      const result = await response.json();
      if (response.status === 401) {
        message(result.error || 'Connectez-vous pour enregistrer un favori.');
        window.location.href = result.login || base + '?page=login';
        return;
      }
      if (!response.ok || !result.success) throw new Error(result.error || 'Enregistrement impossible.');
      // Cette fonction met à jour tous les boutons correspondant à la même recette après la réponse du serveur.
      document.querySelectorAll('[data-favorite="' + recipeId + '"]').forEach(other => {
        other.setAttribute('aria-pressed', String(result.favorite));
        other.textContent = result.favorite ? '♥ Retirer des favoris' : '♡ Ajouter aux favoris';
      });
      message(result.favorite ? 'Recette ajoutée à vos favoris.' : 'Recette retirée de vos favoris.');
      if (page === 'favoris') {
        const card = button.closest('.recipe-item');
        card?.remove();
        document.getElementById('favEmpty').hidden = !!document.querySelector('#favGrid .recipe-item');
      }
    } catch (error) { message(error.message || 'Une erreur est survenue.'); }
    finally { button.disabled = false; }
  }
  // Cette fonction repère les clics sur les boutons Favoris et appelle toggleFavorite().
  document.addEventListener('click', event => {
    const button = event.target.closest('[data-favorite]');
    if (button) toggleFavorite(button);
  });

  if (page === 'categories') {
    const cards = [...document.querySelectorAll('.cat-big-card')];
    const search = document.getElementById('searchInput');
    const empty = document.getElementById('noResults');
    // Quand on écrit dans la recherche des catégories, cette fonction masque les cartes qui ne correspondent pas.
    search?.addEventListener('input', () => {
      const query = normalize(search.value.trim());
      // Cette fonction vérifie le texte de chaque carte de catégorie pour décider de l’afficher ou non.
      cards.forEach(card => { card.hidden = !normalize(card.dataset.name + ' ' + card.textContent).includes(query); });
      // Cette fonction vérifie si une catégorie est encore visible pour afficher ou cacher le message « Aucun résultat ».
      empty.hidden = cards.some(card => !card.hidden);
      document.getElementById('searchTerm').textContent = search.value;
    });
  }

  if (page === 'recette') {
    const search = document.getElementById('recipeSearch');
    const select = document.getElementById('categoryFilter');
    const cards = [...document.querySelectorAll('#recipeGrid .recipe-item')];
    const params = new URLSearchParams(window.location.search);
    const cat = params.get('cat') || '';
    // Cette fonction vérifie que la catégorie demandée dans l’adresse existe parmi les choix du filtre.
    if ([...select.options].some(option => option.value === cat)) select.value = cat;
    search.value = params.get('q') || '';
    // update() : Applique les filtres de nom et de catégorie et met à jour le nombre de recettes affichées.
    function update() {
      const text = normalize(search.value.trim());
      let count = 0;
      // Cette fonction teste chaque recette et cache celles qui ne correspondent pas aux filtres.
      cards.forEach(card => {
        const categories = card.dataset.categories.split(' ');
        const visible = (!select.value || categories.includes(select.value)) && normalize(card.dataset.name).includes(text);
        card.hidden = !visible;
        if (visible) count++;
      });
      document.getElementById('recipeCount').textContent = count + (count === 1 ? ' recette' : ' recettes');
      document.getElementById('recipeEmpty').hidden = count !== 0;
      const params = new URLSearchParams({page:'recette'});
      if (select.value) params.set('cat', select.value);
      if (search.value.trim()) params.set('q', search.value.trim());
      window.history.replaceState(null, '', base + '?' + params.toString());
    }
    // Cette fonction empêche le formulaire de recharger la page, car les filtres travaillent directement sur les cartes.
    document.getElementById('recipeFilters')?.addEventListener('submit', event => event.preventDefault());
    search.addEventListener('input', update);
    select.addEventListener('change', update);
    update();
  }

  if (page === 'detaille') {
    const control = document.querySelector('[data-original-servings]');
    if (control) {
      const original = Number(control.dataset.originalServings) || 4;
      let servings = original;
      const less = document.getElementById('servingLess');
      const more = document.getElementById('servingMore');
      const count = document.getElementById('servingCount');
      // updateIngredients() : Recalcule les quantités des ingrédients selon le nombre de personnes choisi.
      function updateIngredients() {
        count.textContent = servings;
        less.disabled = servings <= 1;
        more.disabled = servings >= 30;
        // Cette fonction parcourt chaque ingrédient pour afficher la quantité recalculée.
        document.querySelectorAll('#ingredientList li[data-qty]').forEach(row => {
          const number = row.dataset.qty;
          const unit = row.dataset.unit || '';
          const scaled = number === '' ? '' : (Number(number) * servings / original).toLocaleString('fr-FR', {maximumFractionDigits:2}) + ' ';
          row.querySelector('.ing-qty').textContent = scaled + unit;
        });
      }
      // Cette fonction diminue le nombre de personnes, puis recalcule les ingrédients.
      less.addEventListener('click', () => { servings = Math.max(1, servings - 1); updateIngredients(); });
      // Cette fonction augmente le nombre de personnes, puis recalcule les ingrédients.
      more.addEventListener('click', () => { servings = Math.min(30, servings + 1); updateIngredients(); });
      updateIngredients();
    }
    // Cette fonction lance l’impression de la recette quand on clique sur le bouton.
    document.getElementById('printRecipe')?.addEventListener('click', () => window.print());
  }
})();
