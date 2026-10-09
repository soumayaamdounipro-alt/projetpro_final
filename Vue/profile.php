<?php
// Cette vue affiche le profil et son formulaire dans une carte.
$h = static fn($value) => htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Mon profil — Cook With Soumi</title>
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
    <span class="profile-eyebrow">✦ MON ESPACE PERSONNEL</span>
    <div class="profile-avatar" aria-hidden="true">👩‍🍳</div>
    <h1>Mon profil</h1>
    <p class="profile-intro">Consulte et modifie tes informations personnelles.</p>

    <!-- Présente les informations du compte sans afficher son identifiant. -->
    <div class="profile-info">
      <div class="info-box"><span>Nom</span><strong><?= $h($user['last_name']) ?></strong></div>
      <div class="info-box"><span>Prénom</span><strong><?= $h($user['first_name']) ?></strong></div>
      <div class="info-box"><span>E-mail</span><strong><?= $h($user['email']) ?></strong></div>
      <div class="info-box"><span>Inscription</span><strong><?= $h(date('d/m/Y', strtotime($user['registration_date']))) ?></strong></div>
    </div>

    <?php if ($succes): ?><p class="profile-success" role="status">Profil mis à jour avec succès.</p><?php endif; ?>
    <?php if ($erreur): ?><p class="profile-error" role="alert"><?= $h($erreur) ?></p><?php endif; ?>

    <!-- Ouvre la page de modification sans changer les informations affichées. -->
    <a class="btn-profile profile-action" href="<?= $h(URL) ?>?page=profile&amp;edit=1">✎ Modifier mon profil</a>
  </section>
</main>
<!-- Affiche le pied de page commun au site. -->
<?php require __DIR__ . '/footer.php'; ?>
