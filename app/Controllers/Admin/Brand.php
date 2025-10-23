<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Brand extends BaseController
{
    protected $breadcrumb = [['text'=>'Tableau de Bord', 'url' => "/admin/dashboard"],['text'=>"Marques", 'url' => '']];

    public function index()
    {
        helper(['form']); // Utilisation de url à supprimer
        $brandModel = model('BrandModel'); // Pour la carte totale marque
        $totalBrands = $brandModel->countAllResults(); // Pour la carte totale marque
        return $this->view('admin/brand', ['totalBrands' => $totalBrands]); // Ajout des infos
    }

    /**Créer une marque*/
    public function insert()
    {
        $bm = model('BrandModel');
        $data = $this->request->getPost();
        $image = $this->request->getFile('image');
        if ($id_brand = $bm->insert($data)) {
            $this->success('Marque bien créée');
            if($image && $image->getError() !== UPLOAD_ERR_NO_FILE){
                $mediaData = [
                    'entity_type' => 'brand',
                    'entity_id' => $id_brand,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                // Utiliser la fonction upload_file() de l'utils_helper pour gérer l'upload et les données du média
                $uploadResult = upload_file($image, 'brand', $image->getName(), $mediaData,false);
                // Vérifier le résultat de l'upload
                if (is_array($uploadResult) && $uploadResult['status'] === 'error') {
                    // Afficher un message d'erreur détaillé
                    $this->error("Une erreur est survenue lors de l'upload de l'image : " . $uploadResult['message']);
                }
            }
        } else {
            foreach ($bm->errors() as $error) {
                $this->error($error);
            }
        }
        return $this->redirect('admin/brand');
    }

    public function update()
    {
        $bm = model('BrandModel');
        $data = $this->request->getPost();
        $id = $data['id'];
        unset($data['id']); // On retire l'ID pour update

        // Récupérer le brand actuel
        $brand = $bm->find($id);
        if (!$brand) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Marque introuvable'
            ]);
        }

        // --- Gestion du nom ---
        if (isset($data['name'])) {
            $name = trim($data['name']);
            if ($name !== $brand['name']) {
                // Validation dynamique pour le name unique
                $rules = [
                    'name' => "required|max_length[255]|is_unique[brand.name,id,{$id}]",
                ];
                $bm->setValidationRules($rules);
                if (!$bm->update($id, ['name' => $name])) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => $bm->errors()
                    ]);
                }
            }
        }

        // --- Gestion de l'image ---
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && $image->getError() === UPLOAD_ERR_OK) {
            $mediaData = [
                'entity_type' => 'brand',
                'entity_id'   => $id,
                'updated_at'  => date('Y-m-d H:i:s')
            ];
            $uploadResult = upload_file($image, 'brand', $image->getName(), $mediaData, false);

            if (is_array($uploadResult) && isset($uploadResult['status']) && $uploadResult['status'] === 'error') {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => "Erreur lors de l'upload de l'image : " . $uploadResult['message']
                ]);
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'La marque a été modifiée avec succès.'
        ]);
    }




    public function delete() {
        $bm = model('BrandModel');
        $id = $this->request->getPost('id');
        if ($bm->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => "La marque à été supprimée avec succés !",
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => $bm->errors(),
            ]);
        }
    }
}