<?php

namespace App\Models;

use App\Entities\User;
use CodeIgniter\Model;
use App\Traits\DataTableTrait;
class UserModel extends Model
{
    use DataTableTrait;

    protected $table            = 'user';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'App\Entities\User';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['email','password','username','first_name','last_name', 'birthdate', 'id_permission'];
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $beforeInsert = ['setCreateRules'];
    protected $beforeUpdate = ['setUpdateRules'];

    protected function setCreateRules(array $data)
    {
        $this->validationRules = [
            'email'    => 'required|valid_email|max_length[255]|is_unique[user.email]',
            'password' => 'required|min_length[8]|max_length[255]',
            'username' => 'required|min_length[3]|max_length[255]|is_unique[user.username]',
            'first_name' => 'permit_empty|max_length[255]',
            'last_name'  => 'permit_empty|max_length[255]',
            'birthdate'  => 'required|valid_date',
            'id_permission' => 'required|integer',
        ];
        return $data;
    }

    protected function setUpdateRules(array $data)
    {
        $id = $data['data']['id'] ?? null; // l’ID de l’utilisateur à mettre à jour
        $this->validationRules = [
            'email'    => "required|valid_email|max_length[255]|is_unique[user.email,id,$id]",
            'password' => 'permit_empty|min_length[8]|max_length[255]',
            'username' => "required|min_length[3]|max_length[255]|is_unique[user.username,id,$id]",
            'first_name' => 'permit_empty|max_length[255]',
            'last_name'  => 'permit_empty|max_length[255]',
            'birthdate'  => 'required|valid_date',
            'id_permission' => 'required|integer',
        ];
        return $data;
    }

    protected $validationMessages = [
        'email' => [
            'required'   => 'L’email est obligatoire.',
            'valid_email'=> 'Veuillez saisir une adresse email valide.',
            'max_length' => 'L’email ne peut pas dépasser 255 caractères.',
            'is_unique'  => 'Cet email est déjà utilisé.',
        ],
        'password' => [
            'required'   => 'Le mot de passe est obligatoire.',
            'min_length' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'max_length' => 'Le mot de passe ne peut pas dépasser 255 caractères.',
        ],
        'username' => [
            'required'   => 'Le nom d’utilisateur est obligatoire.',
            'min_length' => 'Le nom d’utilisateur doit contenir au moins 3 caractères.',
            'max_length' => 'Le nom d’utilisateur ne peut pas dépasser 255 caractères.',
            'is_unique'  => 'Ce nom d’utilisateur est déjà pris.',
        ],
        'first_name' => [
            'max_length' => 'Le prénom ne peut pas dépasser 255 caractères.',
        ],
        'last_name' => [
            'max_length' => 'Le nom ne peut pas dépasser 255 caractères.',
        ],
        'birthdate' => [
            'required'   => 'La date de naissance est obligatoire.',
            'valid_date' => 'Veuillez saisir une date valide.',
        ],
        'id_permission' => [
            'required' => 'Un rôle doit être attribué.',
            'integer'  => 'L’ID du rôle doit être un nombre.',
        ],
    ];

    public function findByEmail(string $email): ?User
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Réactive un utilisateur soft deleted
     */
    public function reactive(int $id): bool
    {
        return $this->builder()
            ->where('id', $id)
            ->update(['deleted_at' => null, 'updated_at' => date('Y-m-d H:i:s')]);
    }

    protected function getDataTableConfig(): array
    {
        return [
            'searchable_fields' => [
                'first_name',
                'last_name',
                'username',
                'email',
                'user_permission.name'
            ],
            'joins' => [
                [
                    'table' => 'user_permission',
                    'condition' => 'user.id_permission = user_permission.id',
                    'type' => 'left'
                ]
            ],
            'select' => 'user.*, user_permission.name as permission_name',
            'with_deleted' => true
        ];
    }
}
