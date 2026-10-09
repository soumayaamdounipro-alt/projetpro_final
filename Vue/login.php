<?php
// Rôle de ce fichier : Affiche le formulaire de connexion.
?>

<?php
// Définition du titre de la page
$pageTitle = "Connexion";
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <!-- Encodage des caractères utilisé sur la page -->
    <meta charset="UTF-8">

    <!-- Permet d'adapter l'affichage aux différents écrans (ordinateur, tablette, mobile) -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Titre affiché dans l'onglet du navigateur -->
    <title>
        Cook With Soumi — <?php echo htmlspecialchars($pageTitle); ?>
    </title>

    <!-- Importation des polices Google utilisées par le site -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    <!-- Feuille de style CSS dédiée aux pages d'authentification -->
    <link rel="stylesheet" href="<?= htmlspecialchars(URL, ENT_QUOTES, 'UTF-8') ?>Vue/css/auth.css">
</head>

<body>

    <!-- Conteneur principal de la page de connexion -->
    <main class="auth-page">

        <!-- Carte contenant le formulaire de connexion -->
        <div class="auth-card">

            <!-- Logo du site -->
            <img class="auth-logo"
                src="<?= htmlspecialchars(URL, ENT_QUOTES, 'UTF-8') ?>Vue/img/CookWithSoumi-logo.png"
                alt="Cook With Soumi Logo">

            <!-- Titre principal de la page -->
            <h1 class="auth-title">Bienvenue !</h1>

            <?php if (!empty($_SESSION['registration_success'])): ?>
                <div class="alert alert-success" role="status">
                    <?= htmlspecialchars($_SESSION['registration_success'], ENT_QUOTES, 'UTF-8') ?>
                </div>
                <?php unset($_SESSION['registration_success']); ?>
            <?php endif; ?>
            <!-- Texte d'introduction du formulaire -->
            <p class="auth-subtitle">
                Connectez-vous à votre compte Cook With Soumi
            </p>

            <!--
                Affichage du message d'erreur uniquement si la variable
                $error existe et n'est pas vide.
            -->
            <?php if (!empty($error)): ?>
                <div class="alert alert-error">

                    <!--
                        htmlspecialchars() permet de sécuriser l'affichage
                        du message en empêchant l'interprétation de code HTML.
                    -->
                    ⚠ <?php echo htmlspecialchars($error); ?>

                </div>
            <?php endif; ?>

            <!--
                Formulaire de connexion.
                Les données sont envoyées avec la méthode POST
                vers la page de connexion.
            -->
            <form class="auth-form"
                method="POST"
                action="<?= htmlspecialchars(URL, ENT_QUOTES, 'UTF-8') ?>?page=login">

                <!-- Champ permettant de saisir l'adresse e-mail -->
                <div class="form-group">

                    <label for="email">E-mail</label>

                    <input
                        type="email"
                        id="email"
                        name="email"

                        
                        value="<?php echo htmlspecialchars($oldEmail ?? ''); ?>"

                        placeholder="vous@email.com"
                        required
                        autocomplete="email">
                </div>

                <!-- Champ permettant de saisir le mot de passe -->
                <div class="form-group">

                    <label for="password">Mot de passe</label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Votre mot de passe"
                        required
                        autocomplete="current-password">
                </div>

                <!-- Bouton permettant d'envoyer le formulaire -->
                <button type="submit" class="btn-auth">
                    Se connecter 🍽️
                </button>

            </form>

            <!--
                Lien permettant aux utilisateurs qui n'ont pas encore
                de compte d'accéder à la page d'inscription.
            -->
            <p class="auth-link">
                Pas encore de compte ?

                <a href="<?= htmlspecialchars(URL, ENT_QUOTES, 'UTF-8') ?>?page=register">
                    Créer un compte
                </a>
            </p>

        </div>
    </main>

    <!--
        Inclusion du fichier footer.php.
        __DIR__ correspond au dossier actuel du fichier PHP.
    -->
    <?php include __DIR__ . '/footer.php'; ?>
