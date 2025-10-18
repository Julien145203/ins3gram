<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Media extends Entity
{
    protected $attributes = [
        'id'          => null,
        'file_path'   => null,
        'entity_id'   => null,
        'entity_type' => null,
        'title'       => null,
        'alt'         => null,
        'created_at'  => null,
        'updated_at'  => null,
        'deleted_at'  => null,
    ];

    protected $casts = [
        'id'          => 'integer',
        'entity_id'   => 'integer',
        'entity_type' => 'string',
        'file_path'   => 'string',
        'title'       => 'string',
        'alt'         => 'string',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'deleted_at'  => 'datetime',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * Retourne l'URL complète du fichier média
     */
    public function getUrl(): string
    {
        return base_url($this->file_path);
    }

    /**
     * Retourne l'extension du fichier en minuscules
     */
    public function getFileExtension(): string
    {
        return strtolower(pathinfo($this->file_path, PATHINFO_EXTENSION));
    }

    /**
     * Vérifie si le média est une image
     */
    public function isImage(): bool
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        return in_array($this->getFileExtension(), $imageExtensions);
    }

    /**
     * Vérifie si le type d'entité est valide
     */
    public function isValidEntityType(): bool
    {
        $validTypes = ['user', 'recipe', 'recipe_mea', 'step', 'ingredient', 'brand'];
        return in_array($this->entity_type, $validTypes);
    }

    /**
     * Retourne le chemin absolu du fichier sur le serveur
     */
    public function getAbsolutePath(): string
    {
        return FCPATH . $this->file_path;
    }

    /**
     * Vérifie si le fichier existe physiquement
     */
    public function fileExists(): bool
    {
        return file_exists($this->getAbsolutePath());
    }

    /**
     * Retourne la taille du fichier en octets (ou false si inexistant)
     */
    public function getFileSize(): int|false
    {
        if (!$this->fileExists()) {
            return false;
        }
        return filesize($this->getAbsolutePath());
    }

    /**
     * Retourne la taille du fichier formatée (ex: "1.5 MB")
     */
    public function getFormattedFileSize(): string
    {
        $size = $this->getFileSize();

        if ($size === false) {
            return 'N/A';
        }

        $units = ['o', 'Ko', 'Mo', 'Go'];
        $power = $size > 0 ? floor(log($size, 1024)) : 0;

        return round($size / pow(1024, $power), 2) . ' ' . $units[$power];
    }

    /**
     * Supprime le média (délègue au MediaModel)
     *
     * @return bool Succès de la suppression
     */
    public function delete(): bool
    {
        if (empty($this->id)) {
            return false;
        }

        $mediaModel = model('MediaModel');

        // Déléguer la suppression au Model qui gère la transaction
        return $mediaModel->deleteMedia($this->id);
    }
}