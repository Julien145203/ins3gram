<?php
namespace App\Models;
use CodeIgniter\Model;
class FavoriteModel extends Model
{
    protected $table            = 'favorite';
    protected $primaryKey       = 'id_user';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['id_user','id_recipe'];
    protected $validationRules = [
        'id_recipe' => 'required|integer',
        'id_user'   => 'required|integer',
    ];
    protected $validationMessages = [
        'id_recipe' => [
            'required' => 'La recette est obligatoire.',
            'integer'  => 'L’ID de la recette doit être un nombre.',
        ],
        'id_user' => [
            'required' => 'L’utilisateur est obligatoire.',
            'integer'  => 'L’ID de l’utilisateur doit être un nombre.',
        ],
    ];

    function switchFavorite($id_recipe, $id_user) {
        if($this->hasFavorite( $id_recipe, $id_user)) {
            $res = $this->delete(['id_recipe' => $id_recipe, 'id_user' => $id_user]);
            return ['type' => 'delete', 'success' => $res];
        } else {
            $res = $this->insert(['id_recipe' => $id_recipe, 'id_user' => $id_user]);
            return ['type' => 'insert', 'success' => $res];
        }
    }

    function hasFavorite($id_recipe, $id_user) {
        $favorite = $this->select('COUNT(*) as count')->where('id_recipe', $id_recipe)->where('id_user', $id_user)->first();
        if ($favorite['count'] != 0) {
            return true;
        }
        return false;
    }
    public function getUserFavorites(int $userId): array
    {
        return $this
            ->select('
            recipe.id,
            recipe.name,
            recipe.slug,
            recipe.description,
            recipe.alcool,
            recipe.created_at,
            media.file_path as mea,
            COALESCE(AVG(opinion.score), 0) as score
        ')
            ->join('recipe', 'favorite.id_recipe = recipe.id')
            ->join(
                'media',
                "media.entity_id = recipe.id AND media.entity_type = 'recipe_mea'",
                'left'
            )
            ->join('opinion', 'opinion.id_recipe = recipe.id', 'left')
            ->where('favorite.id_user', $userId)
            ->where('recipe.deleted_at', null)
            ->groupBy([
                'recipe.id',
                'media.file_path'
            ])
            ->orderBy('recipe.name', 'ASC') // ou id, ou score
            ->findAll();
    }



    /**
     * Compte les favoris d’un utilisateur
     */
    public function countByUser(int $userId): int
    {
        return $this
            ->where('id_user', $userId)
            ->countAllResults();
    }
}