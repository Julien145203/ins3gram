<?php

namespace App\Models;

use CodeIgniter\Model;

class ChatModel extends Model
{
    protected $table            = 'chat';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['content','id_sender','id_receiver'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'content'    => 'required|string|max_length[255]',
        'id_sender'  => 'required|integer',
        'id_receiver'=> 'required|integer',
    ];

    protected $validationMessages = [
        'content' => [
            'required'   => 'Le message est obligatoire.',
            'max_length' => 'Le message ne peut pas dépasser 255 caractères.',
        ],
        'id_sender' => [
            'required' => 'L’expéditeur est obligatoire.',
            'integer'  => 'L’ID de l’expéditeur doit être un nombre.',
        ],
        'id_receiver' => [
            'required' => 'Le destinataire est obligatoire.',
            'integer'  => 'L’ID du destinataire doit être un nombre.',
        ],
    ];

}
