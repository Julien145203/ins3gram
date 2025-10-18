<?php

if (!function_exists('generate_slug')) {
    function generateSlug($string)
    {
        // Normaliser la chaîne pour enlever les accents
        $string = \Normalizer::normalize($string, \Normalizer::FORM_D);
        $string = preg_replace('/[\p{Mn}]/u', '', $string);

        // Convertir les caractères spéciaux en minuscules et les espaces en tirets
        $string = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));

        return $string;
    }
}

if (!function_exists('upload_file')) {
    /**
     * Upload d’un fichier média avec gestion de l’Entity Media
     *
     * @param \CodeIgniter\Files\File $file - Fichier à uploader
     * @param string $subfolder - Sous-dossier (ex: avatars, recipes)
     * @param string|null $customName - Nom personnalisé du fichier
     * @param array|null $mediaData - Données associées (entity_id, entity_type, title, alt)
     * @param bool $isMultiple - Si false, remplace l’ancien média lié
     * @param array $acceptedMimeTypes - Types MIME autorisés
     * @param int $maxSize - Taille max en Ko
     * @return \App\Entities\Media|array - L’Entity Media ou un tableau d’erreur
     */
    function upload_file(
        \CodeIgniter\Files\File $file,
        string $subfolder = '',
        string $customName = null,
        array $mediaData = null,
        bool $isMultiple = false,
        array $acceptedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
        int $maxSize = 2048
    ) {
        // 1️⃣ Vérification du fichier
        if ($file->getError() !== UPLOAD_ERR_OK) {
            return ['status' => 'error', 'message' => getUploadErrorMessage($file->getError())];
        }

        if ($file->hasMoved()) {
            return ['status' => 'error', 'message' => 'Le fichier a déjà été déplacé.'];
        }

        if (!in_array($file->getMimeType(), $acceptedMimeTypes)) {
            return ['status' => 'error', 'message' => 'Type de fichier non accepté.'];
        }

        if ($file->getSizeByUnit('kb') > $maxSize) {
            return ['status' => 'error', 'message' => 'Fichier trop volumineux.'];
        }

        // 2️⃣ Définir le dossier de destination
        $year  = date('Y');
        $month = date('m');
        $uploadPath = FCPATH . 'uploads/' . trim($subfolder, '/') . '/' . $year . '/' . $month;

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }

        // 3️⃣ Générer un nom propre
        helper('text');
        $baseName = $customName ? url_title($customName, '-', true) : pathinfo($file->getClientName(), PATHINFO_FILENAME);
        $ext = $file->getExtension();
        $newName = $baseName . '-' . uniqid() . '.' . $ext;

        // 4️⃣ Déplacer le fichier
        $file->move($uploadPath, $newName);
        $relativePath = 'uploads/' . trim($subfolder, '/') . '/' . $year . '/' . $month . '/' . $newName;

        // 5️⃣ Enregistrer ou mettre à jour le média
        $mediaModel = model('MediaModel');

        if (!$isMultiple && isset($mediaData['entity_id'], $mediaData['entity_type'])) {
            $existing = $mediaModel
                ->where('entity_id', $mediaData['entity_id'])
                ->where('entity_type', $mediaData['entity_type'])
                ->first();

            if ($existing) {
                // Supprimer l’ancien fichier
                if ($existing->fileExists()) {
                    @unlink($existing->getAbsolutePath());
                }

                // Mettre à jour l’existant
                $mediaModel->update($existing->id, ['file_path' => $relativePath] + $mediaData);
                return $mediaModel->find($existing->id);
            }
        }

        // 6️⃣ Insertion d’un nouveau média
        $data = array_merge(['file_path' => $relativePath], $mediaData ?? []);
        $mediaId = $mediaModel->insert($data, true);

        return $mediaModel->find($mediaId);
    }
}

if (!function_exists('getUploadErrorMessage')) {
    /**
     * Convertit le code d'erreur d'upload en message explicite.
     *
     * @param int $errorCode - Le code d'erreur
     * @return string - Le message d'erreur correspondant
     */
    function getUploadErrorMessage(int $errorCode): string
    {
        switch ($errorCode) {
            case UPLOAD_ERR_OK:
                return 'Aucune erreur, le fichier est valide.';
            case UPLOAD_ERR_INI_SIZE:
                return 'Le fichier dépasse la taille maximale autorisée par la configuration PHP.';
            case UPLOAD_ERR_FORM_SIZE:
                return 'Le fichier dépasse la taille maximale autorisée par le formulaire HTML.';
            case UPLOAD_ERR_PARTIAL:
                return 'Le fichier n\'a été que partiellement téléchargé.';
            case UPLOAD_ERR_NO_FILE:
                return 'Aucun fichier n\'a été téléchargé.';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Le dossier temporaire est manquant.';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Échec de l\'écriture du fichier sur le disque.';
            case UPLOAD_ERR_EXTENSION:
                return 'Une extension PHP a arrêté l\'upload du fichier.';
            default:
                return 'Une erreur inconnue est survenue lors de l\'upload.';
        }
    }

}

if (!function_exists('build_filter_url')) {
    /**
     * Construit une URL complète avec les paramètres GET actuels + nouveaux paramètres
     * Gère automatiquement la persistance des filtres et évite les doublons
     *
     * @param array $new_params Nouveaux paramètres à ajouter/modifier
     * @param bool $reset_page Si true, remet la page à 1 (utile lors de changement de filtres)
     * @param string|null $route Route de destination (null = route actuelle)
     * @param array $exclude_params Paramètres à exclure de l'URL finale
     * @return string URL complète avec base_url()
     *
     * Exemples d'utilisation :
     * build_filter_url(['per_page' => 16])
     * build_filter_url(['alcool' => 1], true, 'recette')
     * build_filter_url(['page' => 2], false)
     */
    function build_filter_url(array $new_params = [], bool $reset_page = true, ?string $route = null, array $exclude_params = []): string
    {
        // Récupération des paramètres GET actuels
        $current_params = $_GET ?? [];

        // Si reset_page est true et qu'on ne définit pas explicitement 'page' dans new_params
        if ($reset_page && !array_key_exists('page', $new_params)) {
            $current_params['page'] = 1;
        }

        // Fusion des paramètres : les nouveaux écrasent les anciens
        $final_params = array_merge($current_params, $new_params);

        // Exclusion de paramètres spécifiques si demandé
        foreach ($exclude_params as $key => $param) {
            if( is_array($param)){
                foreach ($param as $value) {
                    unset($final_params[$key][$value]);
                }
            } else {
                unset($final_params[$param]);
            }
        }


        // Nettoyage : suppression des valeurs vides/nulles
        $final_params = array_filter($final_params, function($value) {
            return $value !== null && $value !== '' && $value !== [];
        });

        // Détermination de la route
        if ($route === null) {
            // Route actuelle
            $base_route = uri_string() ?: 'recette'; // Fallback si uri_string() est vide
        } else {
            $base_route = $route;
        }

        // Construction de l'URL finale
        $url = base_url($base_route);

        // Ajout des paramètres GET si présents
        if (!empty($final_params)) {
            $url .= '?' . http_build_query($final_params, '', '&', PHP_QUERY_RFC3986);
        }

        return $url;
    }
}

if (!function_exists('get_current_filter_value')) {
    /**
     * Récupère la valeur actuelle d'un paramètre GET (utile pour les vues)
     *
     * @param string $param_name Nom du paramètre
     * @param mixed $default Valeur par défaut si le paramètre n'existe pas
     * @return mixed Valeur du paramètre ou valeur par défaut
     */
    function get_current_filter_value(string $param_name, $default = null)
    {
        return $_GET[$param_name] ?? $default;
    }
}

if (!function_exists('is_filter_active')) {
    /**
     * Vérifie si un filtre spécifique est actif
     *
     * @param string $param_name Nom du paramètre
     * @param mixed $value Valeur à vérifier
     * @return bool True si le filtre est actif avec cette valeur
     */
    function is_filter_active(string $param_name, $value): bool
    {
        $current = get_current_filter_value($param_name);

        // Gestion des tableaux (pour les filtres multi-sélection)
        if (is_array($current)) {
            return in_array($value, $current);
        }
        if (is_array($value)) {
            return in_array($current, $value);
        }

        return $current == $value;
    }
}

if (!function_exists('get_pagination_url')) {
    /**
     * Construit une URL de pagination en gardant tous les filtres actuels
     *
     * @param int $page Numéro de page
     * @return string URL complète
     */
    function get_pagination_url(int $page): string
    {
        return build_filter_url(['page' => $page], false);
    }
}

if (!function_exists('remove_filter_url')) {
    /**
     * Construit une URL en supprimant un ou plusieurs filtres spécifiques
     *
     * @param array|string $filters_to_remove Filtre(s) à supprimer
     * @return string URL complète
     */
    function remove_filter_url($filters_to_remove): string
    {
        $filters_array = is_array($filters_to_remove) ? $filters_to_remove : [$filters_to_remove];
        return build_filter_url([], true, null, $filters_array);
    }
}

if (!function_exists('social_share_links')) {
    /**
     * Génère les liens de partage pour une URL donnée
     * @param string $url - Lien de la page à partager (par défaut l'URL actuelle)
     * @param string $title - Titre à partager (optionnel)
     * @param array $networks - Réseaux à inclure (optionnel)
     * @return string - HTML des liens
     */
    function social_share_links($url = '', $title = '', $networks = ['facebook', 'twitter', 'linkedin', 'whatsapp'])
    {

        // Si aucun URL n'est passé, on prend l'URL actuelle
        if (empty($url)) {
            $url = current_url();
        }

        // Encodage pour utilisation dans les URLs
        $encoded_url = urlencode($url);
        $encoded_title = urlencode($title);

        $links = [];

        foreach ($networks as $network) {
            switch ($network) {
                case 'facebook':
                    $links[] = '<a href="https://www.facebook.com/sharer/sharer.php?u=' . $encoded_url . '" target="_blank" title="Partager sur Facebook"><i class="fab fa-xl fa-facebook"></i></a>';
                    break;

                case 'twitter':
                case 'x':
                    $links[] = '<a href="https://twitter.com/intent/tweet?url=' . $encoded_url . '&text=' . $encoded_title . '" target="_blank" title="Partager sur X (Twitter)"><i class="fab  fa-xl fa-x-twitter"></i></a>';
                    break;

                case 'linkedin':
                    $links[] = '<a href="https://www.linkedin.com/shareArticle?mini=true&url=' . $encoded_url . '&title=' . $encoded_title . '" target="_blank" title="Partager sur LinkedIn"><i class="fab fa-xl fa-linkedin"></i></a>';
                    break;

                case 'whatsapp':
                    $links[] = '<a href="https://api.whatsapp.com/send?text=' . $encoded_title . '%20' . $encoded_url . '" target="_blank" title="Partager sur WhatsApp"><i class="fab fa-xl fa-whatsapp"></i></a>';
                    break;
            }
        }

        return '<div class="social-share-links">' . implode(' ', $links) . '</div>';
    }
}