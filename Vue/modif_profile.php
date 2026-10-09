<?php
// Cette vue affiche le profil et son formulaire dans une carte.
$h = static fn($value) => htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Modifier mon profil — Cook With Soumi</title>
<link rel="stylesheet" href="<?= $h(URL) ?>Vue/css/Accueil.css">
<link rel="stylesheet" href="<?= $h(URL) ?>Vue/css/navbar.css">
<link rel="stylesheet" href="<?= $h(URL) ?>Vue/css/pages.css">
<link rel="stylesheet" href="<?= $h(URL) ?>Vue/css/profil.css">
</head>
<body data-base="<?= $h(URL) ?>">
<!-- Utilise la même barre de navigation que les autres pages. -->
<?php require __DIR__ . '/navbar.php'; ?>
<main class="profile-page">
  <section class="profile-card">
    <span class="profile-eyebrow">✦ MODIFICATION DU PROFIL</span>
    <div class="profile-avatar" aria-hidden="true">👩‍🍳</div>
    <h1>Modifier mon profil</h1>
    <p class="profile-intro">Mets à jour tes informations personnelles.</p>

    <!-- La date d'inscription est visible mais ne peut pas être modifiée. -->
    <div class="profile-date">Membre depuis le <strong><?= $h(date('d/m/Y', strtotime($user['registration_date']))) ?></strong></div>

    <?php if ($succes): ?><p class="profile-success" role="status">Profil mis à jour avec succès.</p><?php endif; ?>
    <?php if ($erreur): ?><p class="profile-error" role="alert"><?= $h($erreur) ?></p><?php endif; ?>

    <!-- Ce formulaire permet de modifier uniquement le nom, le prénom et l'e-mail. -->
    <h2>Modifier mes informations</h2>
    <form action="<?= $h(URL) ?>?page=profile&amp;edit=1" method="post" class="profile-form">
      <!-- Le jeton empêche l'envoi d'un formulaire non autorisé. -->
      <input type="hidden" name="csrf_token" value="<?= $h($_SESSION['csrf_token'] ?? '') ?>">
      <label for="last_name">Nom</label>
      <input id="last_name" name="last_name" value="<?= $h($user['last_name']) ?>" maxlength="100" required>
      <label for="first_name">Prénom</label>
      <input id="first_name" name="first_name" value="<?= $h($user['first_name']) ?>" maxlength="100" required>
      <label for="email">Adresse e-mail</label>
      <input id="email" type="email" name="email" value="<?= $h($user['email']) ?>" maxlength="200" required>
      <p class="profile-note">La date d'inscription ne peut pas être modifiée.</p>
      <button type="submit" class="btn-profile">Enregistrer les modifications</button>
      <a class="profile-secondary" href="<?= $h(URL) ?>?page=profile">Annuler et revenir au profil</a>
    </form>
  </section>
</main>
<!-- Affiche le pied de page commun au site. -->
<?php require __DIR__ . '/footer.php'; ?>
