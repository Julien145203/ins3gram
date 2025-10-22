<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class User extends Entity
{
    protected $attributes = [
        'id'            => null,
        'email'         => null,
        'password'      => null,
        'username'      => null,
        'first_name'    => null,
        'last_name'     => null,
        'birthdate'     => null,
        'id_permission' => 2,
        'created_at'    => null,
        'updated_at'    => null,
        'deleted_at'    => null,
    ];
    protected $casts = [
        'id'            => 'integer',
        'email'         => 'string',
        'password'      => 'string',
        'username'      => 'string',
        'first_name'    => 'string',
        'last_name'     => 'string',
        'birthdate'     => 'datetime',
        'id_permission' => 'integer',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'deleted_at'    => 'datetime',
    ];

    protected $hidden = ['password'];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];

    public function getFullName():string
    {
        return trim($this->attributes['first_name'] . ' ' . $this->attributes['last_name']);
    }

    public function isActive(): bool
    {
        return $this->attributes['deleted_at'] === null;
    }

    public function setPassword(string $password)
    {
        $this->attributes['password'] = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->attributes['password']);
    }

    public function isAdmin(): bool
    {
        return $this->check('administrateur');
    }

    // Vérifier les permissions (avec hiérarchie)
    public function check(string $slug): bool
    {
        $userPermissionSlug = $this->getPermissionSlug();

        if ($userPermissionSlug === $slug) {
            return true;
        }
        return false;
    }

    public function getPermissionSlug(): string
    {
        $upm = model('UserPermissionModel');
        $permission = $upm->find($this->attributes['id_permission']);

        return $permission ? $permission['slug'] : 'utilisateur';
    }

    public function getPermissionName(): string
    {
        $upm = model('UserPermissionModel');
        $permission = $upm->find($this->attributes['id_permission']);

        return $permission ? $permission['name'] : 'Utilisateur';
    }

    public function hasFavorite(int $recipeId): bool
    {
        $fm = model('FavoriteModel');
        return $fm->hasFavorite($this->attributes['id'], $recipeId);
    }
    /**
     * Récupère l'avatar de l'utilisateur
     *
     * @return Media|null L'instance Media de l'avatar ou null
     */
    public function getAvatar(): ?Media
    {
        $mediaModel = model('MediaModel');

        $avatar = $mediaModel
            ->where('entity_type', 'user')
            ->where('entity_id', $this->id)
            ->first();

        return $avatar;
    }

    /**
     * Retourne l'URL de l'avatar ou une image par défaut
     *
     * @param string $default URL de l'image par défaut
     * @return string URL de l'avatar
     */
    public function getAvatarUrl(string $default = 'assets/img/1.png'): string
    {
        $avatar = $this->getAvatar();

        if ($avatar && $avatar->fileExists()) {
            return $avatar->getUrl();
        }

        return base_url($default);
    }

    /**
     * Vérifie si l'utilisateur a un avatar valide
     *
     * @return bool
     */
    public function hasAvatar(): bool
    {
        $avatar = $this->getAvatar();
        return $avatar !== null && $avatar->fileExists();
    }

    /**
     * Supprime l'avatar de l'utilisateur
     *
     * @return bool Succès de la suppression
     */
    public function deleteAvatar(): bool
    {
        $avatar = $this->getAvatar();

        if ($avatar === null) {
            return false;
        }

        return $avatar->delete();
    }
}