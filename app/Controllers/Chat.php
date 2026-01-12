<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Chat extends BaseController
{
    public function index()
    {
        $this->title = "Chat";
        $cm = Model('ChatModel');
        $histo = $cm->getHistorique($this->session->get('user')->id);
        return $this->view('/front/chat', ['historique' => $histo], false);
    }

    public function send()
    {
        $data = $this->request->getPost();

        // Validation basique
        if (empty($data['content']) || empty($data['id_receiver'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Données manquantes'
            ]);
        }

        $cm = Model('ChatModel');

        // Échapper les données pour la sécurité
        $insertData = [
            'content' => esc($data['content']),
            'id_sender' => $this->session->get('user')->id,
            'id_receiver' => (int)$data['id_receiver']
        ];

        if($cm->insert($insertData)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Message envoyé',
                'data' => $insertData
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => $cm->errors()
            ]);
        }
    }

    public function conversation()
    {
        $id_1 = $this->request->getGet('id_1');
        $id_2 = $this->request->getGet('id_2');
        $page = $this->request->getGet('page') ?? 1;

        $cm = Model('ChatModel');
        $conversation = $cm->getConversation($id_1, $id_2, $page);

        return $this->response->setJSON($conversation);
    }

    public function newMessages()
    {
        $id_1 = $this->request->getGet('id_1');
        $id_2 = $this->request->getGet('id_2');
        $date = $this->request->getGet('date');

        $cm = Model('ChatModel');
        $newMessages = $cm->getNewMessages($id_1, $id_2, $date);

        return $this->response->setJSON($newMessages);
    }

    public function historique()
    {
        $id = $this->request->getGet('id');

        $cm = Model('ChatModel');
        $histo = $cm->getHistorique($id);

        return $this->response->setJSON($histo);
    }
}