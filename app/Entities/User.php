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
        'id_permission' => null,
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


}
