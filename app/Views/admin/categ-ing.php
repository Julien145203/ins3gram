<div class="row">


    <!-- Colonne gauche : formulaire d’ajout d’une nouvelle catégorie d’ingrédient -->
    <div class="col-md-3 ">
        <div class="card">
            <!-- Formulaire CodeIgniter pour insérer une catégorie -->
            <?= form_open('admin/category-ingredient/insert') ?>
            <div class="card-header h4 bg-primary text-white">
                Créer une catégorie d'ingrédients
            </div>
            <div class="card-body">
                <!-- Champ pour le nom -->
                <div class="form-floating mb-3">
                    <input id="name" class="form-control" placeholder="Nom de la catégorie" type="text" name="name" required>
                    <label for="name">Nom de la catégorie</label>
                </div>

                <!-- Sélecteur pour la catégorie parente (optionnelle) -->
                <div class="form-floating mb-3">
                    <select class="form-select" id="id_categ_parent_insert" name="id_categ_parent">
                        <option value="">Choisir une catégorie</option>
                        <!-- Liste des catégories disponibles (injection PHP) -->
                        <?php if(isset($categories) && !empty($categories)): ?>
                            <?php foreach($categories as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= esc($c['name']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <label for="id_categ_parent_insert">Catégorie parente (optionnel)</label>
                </div>
            </div>

            <div class="card-footer text-end">
                <!-- Bouton de soumission -->
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Créer
                </button>
            </div>
            <?= form_close() ?>
        </div>
    </div>

    <!-- Colonne droite : affichage de la liste des catégories -->
    <div class="col-md-9">
        <div class="card">
            <div class="card-header h4 bg-primary text-white">
                Liste des catégories
            </div>
            <div class="card-body">
                <!-- Table DataTables qui sera alimentée par AJAX -->
                <table id="categIngTable" class="table table-sm table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Catégorie parente</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <!-- Corps rempli dynamiquement par DataTables -->
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="modalCategIng" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- En-tête du modal -->
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Éditer la catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Corps du modal : champ de nom et choix de la catégorie parente -->
            <div class="modal-body">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="modalNameInput" data-id="">
                    <label for="modalNameInput">Nom de la catégorie</label>
                </div>

                <div class="form-floating">
                    <select class="form-select" id="modalParentInput">
                        <option value="">Choisir une catégorie</option>
                    </select>
                    <label for="modalParentInput">Catégorie parente (optionnel)</label>
                </div>
            </div>

            <!-- Pied du modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="saveCategIng()">Sauvegarder</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Base URL pour les appels AJAX (CodeIgniter)
    const baseUrl = "<?= base_url(); ?>";

    // Initialisation du modal Bootstrap
    const myModal = new bootstrap.Modal('#modalCategIng');

    $(document).ready(function(){
        // Initialisation du DataTable
        var table = $('#categIngTable').DataTable({
            processing: true,   // Affiche un indicateur de chargement
            serverSide: true,   // Active le mode serveur
            ajax: {
                url: baseUrl + '/admin/category-ingredient/datatable', // Endpoint serveur
                type: 'GET' // Méthode HTTP utilisée
            },
            // Définition des colonnes
            columns: [
                { data: 'id' }, // ID de la catégorie
                { data: 'name' }, // Nom
                { data: 'parent_name', defaultContent: 'Aucune' }, // Nom de la catégorie parente
                {
                    data: null, // Colonne personnalisée pour les actions
                    orderable: false, // Pas de tri sur cette colonne
                    render: function(data,type,row){
                        // Protection du nom contre les quotes pour éviter les erreurs JS
                        let safeName = row.name.replace(/'/g,"\\'").replace(/"/g,'\\"');
                        // Boutons Modifier / Supprimer
                        return `<div class="" role="group">
                    <button class="btn btn-sm btn-warning" onclick="showModal(${row.id},'${safeName}', ${row.id_categ_parent ?? 'null'})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteCategIng(${row.id})">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>`;
                    }
                }
            ],
            order: [[0,'desc']], // Tri par ID décroissant
            pageLength: 10, // 10 lignes par page
            language: {
                // Traduction française
                url: baseUrl + 'js/datatable/datatable-2.1.4-fr-FR.json'
            }
        });

        // Fonction globale pour rafraîchir la table sans rechargement de page
        window.refreshTable = function(){
            table.ajax.reload(null,false);
        };
    });

    // Fonction d’ouverture du modal d’édition
    function showModal(id,name,currentParentId){
        // Remplit le champ de nom
        $('#modalNameInput').val(name).data('id',id);
        // Vide et réinitialise le select des parents
        $('#modalParentInput').empty().append('<option value="">Choisir une catégorie</option>');
        // Récupère via AJAX la liste des catégories valides (pour éviter qu’une catégorie soit sa propre parente)
        $.getJSON(baseUrl + '/admin/category-ingredient/getValidParents',{id:id},function(data){
            $.each(data,function(i,c){
                let selected = c.id == currentParentId ? 'selected' : '';
                $('#modalParentInput').append(`<option value="${c.id}" ${selected}>${c.name}</option>`);
            });
            // Affiche le modal après avoir rempli la liste
            myModal.show();
        });
    }

    // Sauvegarde d’une catégorie modifiée
    function saveCategIng(){
        let id = $('#modalNameInput').data('id');
        let name = $('#modalNameInput').val();
        let id_categ_parent = $('#modalParentInput').val() || null;

        // Envoi des données via AJAX (POST)
        $.post(baseUrl + '/admin/category-ingredient/update',
            {id,name,id_categ_parent},
            function(response){
                myModal.hide();
                if(response.success){
                    // Message succès avec SweetAlert
                    Swal.fire({
                        title:'Succès !',
                        text:response.message,
                        icon:'success',
                        timer:2000,
                        showConfirmButton:false
                    });
                    refreshTable(); // Rafraîchit le tableau
                } else {
                    // Message d’erreur
                    Swal.fire({
                        title:'Erreur !',
                        text:JSON.stringify(response.message),
                        icon:'error'
                    });
                }
            },'json');
    }

    // Suppression d’une catégorie
    function deleteCategIng(id){
        Swal.fire({
            title:'Êtes-vous sûr ?',
            text:'Voulez-vous vraiment supprimer cette catégorie ?',
            icon:'warning',
            showCancelButton:true,
            confirmButtonColor:'#28a745',
            cancelButtonColor:'#6c757d',
            confirmButtonText:'Oui !',
            cancelButtonText:'Annuler'
        }).then((result)=>{
            if(result.isConfirmed){
                // Envoi AJAX de la suppression
                $.post(baseUrl + '/admin/category-ingredient/delete',{id},function(response){
                    if(response.success){
                        Swal.fire({
                            title:'Succès !',
                            text:response.message,
                            icon:'success',
                            timer:2000,
                            showConfirmButton:false
                        });
                        refreshTable();
                    } else {
                        Swal.fire({
                            title:'Erreur !',
                            text:'Une erreur est survenue',
                            icon:'error'
                        });
                    }
                },'json');
            }
        });
    }
</script>

<!-- Centrage du texte dans le tableau -->
<style>
    #categIngTable, #categIngTable th {
        text-align: center
    }
</style>
