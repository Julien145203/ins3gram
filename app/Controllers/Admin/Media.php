<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Media extends BaseController
{
    protected $breadcrumb = [['text' => 'Tableau de Bord', 'url' => '/admin/dashboard'],['text'=>"Médias", 'url' => '']];
    public function index()
    {
        return $this->view('admin/media');
    }

    public function load() {
        $page = $this->request->getGet('page') ?? 1;
        $entity_type = $this->request->getGet('entity_type') ?? null;
        $medias = Model('MediaModel')->getMedias($page, 12, $entity_type);
        return $this->response->setJson([$medias['data'], $medias['pager']->getPageCount()]);
    }

    public function one() {
        $id = $this->request->getGet('id');
        $mm = Model('MediaModel');
        $one = $mm->find($id);
        return $this->response->setJSON($one);
    }

    public function saveMeta() {
        $data = $this->request->getPost();
        $mm = Model('MediaModel');
        $media = $mm->find($data['id']);
        if (!$media) {
            return $this->response->setJSON(["success" => false, "message" => "Media introuvable"]);
        }
        $media->fill($data);
        if($media->hasChanged()){
            if ($mm->save($media)) {
                return $this->response->setJSON(["success" => true, "message" => "Media modifié"]);
            } else {
                return $this->response->setJSON(["success" => false, "message" => "Erreur lors de la sauvegarde"]);
            }
        }
        return $this->response->setJSON(["success" => false, "message" => "Aucune modification"]);
    }
    public function delete() {
        $id = $this->request->getPost('id');
        $mm = Model('MediaModel');
        $media = $mm->find($id);
        if (!$media) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Media introuvable'
            ]);
        }
        if (!$media->delete()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la suppression'
            ]);
        }
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Media supprimé'
        ]);
    }
}