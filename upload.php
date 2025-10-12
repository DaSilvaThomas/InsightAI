<?php
    // Gestion des erreurs
    session_start();
    $errors = [];

    // Paramètres Omeka-s
    $omeka_api_url = "http://localhost/omeka-s/api/media";
    $key_identity = "tsrnVerMAuYYYBaDq7hKupmiRk7vEAqR";
    $key_credential = "AoBHL9vkmPLVaHcfPgyKYNKbW0gZUwnt";

    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];

        try {
            // Créer un item Omeka-s
            $itemData = [
                'o:resource_template' => '/api/resource_templates/1', // Remplace par ton template "Document"
                'o:title' => [['@value' => $fileName, '@language' => 'fr']]
            ];

            $itemId = createItem($itemData, $omeka_api_url, $key_identity, $key_credential);
            uploadMedia($itemId, $fileTmpPath, $fileName, $omeka_api_url, $key_identity, $key_credential);

            $_SESSION['success'][] = "Fichier envoyé avec succès.";
        
        } catch (Exception $e) {
            $errors[] = "Erreur : " . $e->getMessage();
        }
        
    } else {
        $errors[] = "Aucun fichier reçu.";
    }

    // Stocker les erreurs dans la session pour les envoyer vers index.php
    $_SESSION['errors'] = $errors;
    header("Location: index.php");
    exit;

    
    function createItem($data, $api_url, $key_id, $key_cred) {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $api_url . "/items",
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ],
            CURLOPT_POSTFIELDS => json_encode($data + [
                'key_identity' => $key_id,
                'key_credential' => $key_cred
            ])
        ]);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status !== 201) throw new Exception("Impossible de créer l'Item (HTTP $status): $response");

        $res = json_decode($response, true);
        return $res['o:id'] ?? null;
    }

    function uploadMedia($itemId, $filePath, $fileName, $api_url, $key_id, $key_cred) {
        $ch = curl_init();
        $postData = [
            'o:item' => "/api/items/$itemId",
            'file[0]' => new CURLFile($filePath, mime_content_type($filePath), $fileName),
            'key_identity' => $key_id,
            'key_credential' => $key_cred
        ];
        curl_setopt_array($ch, [
            CURLOPT_URL => $api_url . "/media",
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $postData
        ]);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status !== 201) throw new Exception("Impossible d'uploader le Media (HTTP $status): $response");
    }

?>