<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Chat extends BaseController
{
    public function index()
    {
        $this->title = "Chat";
        $histo = Model('ChatModel')->getHistorique($this->session->get('user')->id);
        return $this->view('/front/chat', ['historique' => $histo], false);
    }

    public function send()
    {
        $data = esc($this->request->getPost());
        $cm = Model('ChatModel');
        if($cm->insert($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'message envoyé',
                'data' => $data
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => $cm->errors()
            ]);
        }
    }

    public function conversation() {
        $data = $this->request->getGet();
        $cm = Model('ChatModel');
        $conversation = $cm->getConversation($data['id_1'], $data['id_2'], $data['page']);
        return $this->response->setJSON($conversation);
    }

    public function newMessages() {
        $data = $this->request->getGet();
        $cm = Model('ChatModel');
        $newMessages = $cm->getNewMessages($data['id_1'], $data['id_2'], $data['date']);
        return $this->response->setJSON($newMessages);
    }

    public function historique() {
        $id = $this->request->getGet('id');
        $cm = Model('ChatModel');
        $histo = $cm->getHistorique($id);
        return $this->response->setJSON($histo);
    }
}