<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Brand extends BaseController
{
    protected $breadcrumb = [['text' => 'Tableau de Bord', 'url' => "/admin/dashboard"], ['text' => "Marques", 'url' => '']];

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
        // -------------------- Upload image --------------------
        $brandModel = model('BrandModel'); // naming explicite
        $data = $this->request->getPost(); // données du formulaire

        if ($id_brand = $brandModel->insert($data)) {

            // Upload image via Media //Modifier getName pour le nom de l'ingredient
            $image = $this->request->getFile('image');
            if ($image && $image->isValid() && $image->getError() !== UPLOAD_ERR_NO_FILE) {
                helper('upload');

                $uploadResult = upload_file($image, 'brand', $image->getName(), [
                    'entity_type' => 'brand',
                    'entity_id' => $id_brand,
                    'created_at' => date('Y-m-d H:i:s'),
                ], false);

                if (is_array($uploadResult) && $uploadResult['status'] === 'error') {
                    // Afficher un message d'erreur détaillé
                    $this->error("Une erreur est survenue lors de l'upload de l'image : " . $uploadResult['message']);
                }
            }

            $this->success('La marque a bien été créée'); // message succès
        } else {
            foreach ($brandModel->errors() as $error) { // gestion erreurs BDD
                $this->error($error);
            }
        }

        return $this->redirect('admin/brand'); // redirection
    } //Insert valider

    /**Modifier une marque*/
    // -------------------- Mettre à jour une marque --------------------
    public function update()
    {
        $brandModel = model('BrandModel');
        $mediaModel = model('MediaModel');

        $data = $this->request->getPost();
        $id = $data['id'] ?? null;

        unset($data['id']); // éviter l’écrasement en BDD

        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "ID manquant pour la mise à jour"
            ]);
        }

        // -------------------- Mise à jour BDD avec validation --------------------
        if (!$brandModel->saveBrand($data, $id)) {
            $errors = $brandModel->errors();
            $msg = is_array($errors) ? implode("\n", $errors) : $errors;
            return $this->response->setJSON([
                'success' => false,
                'message' => $msg
            ]);
        }

        // -------------------- Upload / Remplacement image --------------------
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && $image->getError() !== UPLOAD_ERR_NO_FILE) {
            helper('upload');

            // Supprimer ancienne image si existante
            $oldMedia = $mediaModel
                ->where('entity_type','brand')
                ->where('entity_id', $id)
                ->first();
            if ($oldMedia) {
                if (file_exists(FCPATH . $oldMedia['file_path'])) {
                    unlink(FCPATH . $oldMedia['file_path']);
                }
                $mediaModel->delete($oldMedia['id']);
            }

            // Upload nouvelle image
            $mediaData = [
                'entity_type' => 'brand',
                'entity_id'   => $id,
                'created_at'  => date('Y-m-d H:i:s')
            ];

            $uploadResult = upload_file($image, 'brand', $image->getName(), $mediaData, false);
            if (is_array($uploadResult) && $uploadResult['status'] === 'error') {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => "Erreur lors de l'upload de l'image : " . $uploadResult['message']
                ]);
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => "La marque a été modifiée avec succès !"
        ]);
    }

    /**Supprimer une marque*/
    public function delete()
    {
        $brandModel = model('BrandModel');
        $id = $this->request->getPost('id');

        if ($brandModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => "La marque a été supprimée avec succès !",
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => $brandModel->errors(),
            ]);
        }
    } //Delete valider

    /**Recherche pour Select2*/
    public function search()
    {
        $request = $this->request;

        if (!$request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Requête non autorisée']);
        }

        $brandModel = model('BrandModel');
        $search = $request->getGet('search') ?? '';
        $page = (int)($request->getGet('page') ?? 1);
        $limit = 20;

        $result = $brandModel->quickSearchForSelect2($search, $page, $limit);

        return $this->response->setJSON($result);
    }//Search valider
}
