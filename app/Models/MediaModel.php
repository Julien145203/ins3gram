<?php

namespace App\Models;

use CodeIgniter\Model;

class MediaModel extends Model
{
    protected $table            = 'media';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'App\Entities\Media'; // ← Modifié pour utiliser l'Entity
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['file_path','entity_id', 'entity_type','title','alt'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'file_path'   => 'required|string|is_unique[media.file_path,id,{id}]',
        'entity_id'   => 'required|integer',
        'entity_type' => 'required|in_list[user,recipe,recipe_mea,step,ingredient,brand]',
        'title'       => 'permit_empty|max_length[255]',
        'alt'         => 'permit_empty|max_length[255]',
    ];

    protected $validationMessages = [
        'file_path' => [
            'required'  => 'Le chemin du fichier est obligatoire.',
            'is_unique' => 'Ce fichier existe déjà.',
        ],
        'entity_id' => [
            'required' => 'L\'ID de l\'entité est obligatoire.',
            'integer'  => 'L\'ID de l\'entité doit être un nombre.',
        ],
        'entity_type' => [
            'required' => 'Le type d\'entité est obligatoire.',
            'in_list'  => 'Le type d\'entité doit être parmi : user, recipe, step, ingredient ou brand.',
        ],
        'title' => [
            'max_length' => 'Le titre ne peut pas dépasser 255 caractères.',
        ],
        'alt' => [
            'max_length' => 'Le texte alternatif ne peut pas dépasser 255 caractères.',
        ],
    ];

    /**
     * Supprime un média (fichier + BDD) avec transaction
     *
     * @param int $id ID du média à supprimer
     * @return bool Succès de la suppression
     */
    public function deleteMedia($id): bool
    {
        // Récupérer le média
        $media = $this->find($id);

        if (!$media) {
            return false;
        }

        // Démarrer une transaction
        $this->db->transStart();

        try {
            // 1. Supprimer l'entrée en BDD d'abord
            if (!$this->delete($id)) {
                throw new \Exception("Échec de la suppression en base de données");
            }

            // 2. Ensuite supprimer le fichier physique
            if ($media->fileExists()) {
                $filePath = $media->getAbsolutePath();

                if (!unlink($filePath)) {
                    throw new \Exception("Échec de la suppression du fichier physique");
                }
            }

            // Finaliser la transaction
            $this->db->transComplete();

            // Vérifier le statut
            if ($this->db->transStatus() === false) {
                log_message('error', "Transaction échouée pour le média ID {$id}");
                return false;
            }

            return true;

        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            $this->db->transRollback();
            log_message('error', 'Erreur suppression média : ' . $e->getMessage());
            return false;
        }
    }

    public function getMedias($page = 1, $perPage = 10, $entity_type = null) {
        if($entity_type != null) {
            $this->where('entity_type', $entity_type);
            if($entity_type == 'recipe') {
                $this->OrWhere('entity_type', 'recipe_mea');
            }
        }
        $data = $this->paginate($perPage, 'default', $page);
        return [
            'data' => $data,
            'pager' => $this->pager
        ];
    }

}