<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Déconnexion</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
</head>
<body>
    <?php
    // Dans le fichier logout.php

    // Démarrer la session
    session_start();
    // Vérifier si l'utilisateur est connecté
    if (isset($_SESSION['id_utilisateur'])) {
        // Détruire la session
        session_destroy();

        // Afficher un message de succès et rediriger l'utilisateur vers la page de connexion
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Déconnexion réussie',
                    text: 'Vous avez été déconnecté avec succès.'
                }).then(function() {
                    window.location.href = 'login.php';
                });
              </script>";
              session_unset();
              header("Location: connexion.php");
        exit;
    } else {
        // Afficher un message d'erreur si l'utilisateur n'est pas connecté
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur de déconnexion',
                    text: 'Vous n'êtes pas connecté.'
                });
              </script>";
        exit;
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.js"></script>
</body>
</html>