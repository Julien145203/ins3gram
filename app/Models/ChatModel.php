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

    public function getConversation($user1, $user2, $page) {
        $data = $this->groupStart()
            ->where('id_sender', $user1)
            ->where('id_receiver', $user2)
            ->groupEnd()
            ->orGroupStart()
            ->where('id_sender', $user2)
            ->where('id_receiver', $user1)
            ->groupEnd()
            ->orderBy('created_at', 'DESC')
            ->paginate(10, 'default', $page);
        return [
            'data' => $data,
            'max_page' => $this->pager->getPageCount()
        ];
    }

    public function getNewMessages($user1, $user2, $date) {
        $data = $this->where('id_sender', $user2)
            ->where('id_receiver', $user1)
            ->where('created_at >', $date)
            ->orderBy('created_at', 'ASC');
        return $data->findAll();
    }

    public function getHistorique($userId) {
        return $this->select('u.id, u.username, MAX(chat.created_at) AS last_message')
            ->join('user u', '(u.id = chat.id_sender AND chat.id_receiver = ' . $userId . ') 
                              OR (u.id = chat.id_receiver AND chat.id_sender = ' . $userId . ')')
            ->groupBy('u.username')
            ->orderBy('last_message', 'DESC')
            ->findAll();
    }
}