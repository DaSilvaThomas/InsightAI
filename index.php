<?php
    session_start();

    // Récupération des erreurs de la session
    $errors = $_SESSION['errors'] ?? [];
    unset($_SESSION['errors']);

    // Récupération des succès et nettoyage de la session
    $success = $_SESSION['success'] ?? '';
    unset($_SESSION['success']);

    require 'resume.php';  // Lien vers le fichier resume.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>rAIsume</title>
    <!-- CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS personnalisé -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light py-5 d-flex flex-column gap-4">

    <!-- Titre -->
    <div class="container d-flex justify-content-center align-items-center">
        <div class="col-md-6 text-center">
            <h1>Bienvenue sur rAIsume !</h1>
        </div>
    </div>

    <!-- Gestion des messages -->
    <div class="container d-flex justify-content-center">
        <div class="col-md-6">

            <!-- Erreurs -->
            <?php if (!empty($errors)): ?>
                <?php foreach ($errors as $error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <!-- Succès -->
            <?php if (!empty($success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($success) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

         </div>
    </div>

    <!-- Chargement fichiers -->
    <div class="container d-flex justify-content-center align-items-center">
        <div class="col-md-6 card shadow-sm border-0">
            <div class="card-body">
                <h4 class="card-title mb-4 text-center">Importer un fichier</h4>

                <form action="upload.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="file" class="form-label">Choisir un fichier :</label>
                        <input class="form-control" type="file" name="file" id="file" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="reset" class="btn btn-secondary">Annuler</button>
                        <button type="submit" class="btn btn-primary">Envoyer</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- JS Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JS personnalisé -->
    <script src="js/script.js"></script>
</body>
</html>