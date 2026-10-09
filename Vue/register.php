<?php
// Rôle de ce fichier : Affiche le formulaire de création de compte.
 $pageprojetcookwithsoumi= "Inscription"; ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cook With Soumi — <?= htmlspecialchars($pageprojetcookwithsoumi) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= htmlspecialchars(URL, ENT_QUOTES, 'UTF-8') ?>Vue/css/auth.css">
</head>

<body>

    <main class="auth-page">
        <div class="auth-card">

            <img class="auth-logo"
                src="<?= htmlspecialchars(URL, ENT_QUOTES, 'UTF-8') ?>Vue/img/CookWithSoumi-logo.png"
                alt="Cook With Soumi Logo">

            <h1 class="auth-title">Créer un compte</h1>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <?php // Parcourt les erreurs de saisie pour les montrer sous le formulaire. ?>
                    <?php foreach ($errors as $e): ?>
                        <div><?= htmlspecialchars($e) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form class="auth-form" method="POST" action="<?= htmlspecialchars(URL, ENT_QUOTES, 'UTF-8') ?>?page=register">

                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="last_name"
                        value="<?= htmlspecialchars($old['last_name'] ?? '') ?>"
                        placeholder="Votre nom" required>
                </div>

                <div class="form-group">
                    <label>Prénom</label>
                    <input type="text" name="first_name"
                        value="<?= htmlspecialchars($old['first_name'] ?? '') ?>"
                        placeholder="Votre prénom" required>
                </div>

                <div class="form-group">
                    <label>E-mail</label>
                    <input type="email" name="email"
                        value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                        placeholder="vous@email.com" required>
                </div>

                <div class="form-group">
                    <label>Mot de passe</label>
                    <input type="password" name="password" placeholder="Au moins 8 caractères" required>
                </div>

                <div class="form-group">
                    <label>Confirmation</label>
                    <input type="password" name="confirm" placeholder="Répétez le mot de passe" required>
                </div>

                <button type="submit" class="btn-auth">Créer mon compte 🍳</button>

            </form>

            <p class="auth-link">
                Déjà un compte ?
                <a href="<?= htmlspecialchars(URL, ENT_QUOTES, 'UTF-8') ?>?page=login">Se connecter</a>
            </p>

        </div>
    </main>

    <?php include __DIR__ . '/footer.php'; ?>