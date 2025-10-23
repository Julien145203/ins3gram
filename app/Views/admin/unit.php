<div class="row d-flex  justify-content-between">
    <!-- Colonne gauche -->
    <div class="col-md-3">
        <!-- Card Total Unités -->
        <div class="card text-white bg-primary shadow-sm mb-3">
            <div class="card-body d-flex justify-content-evenly">
                <div class="display-4">
                    <i class="nav-icon fas fa-balance-scale"></i>
                </div>
                <div class="text-center">
                    <h5 class="card-title mb-1">Total Unités</h5>
                    <h2 class="card-text mb-0"><?= esc($totalUnits) ?></h2>
                </div>
            </div>
        </div>

        <!-- Formulaire création -->
        <div class="card shadow-sm border-0">
            <?= form_open('admin/unit/insert', ['id' => 'unitForm']) ?>
            <div class="card-header bg-primary text-white h4">
                Créer une unité
            </div>
            <div class="card-body">
                <div class="form-floating mb-3">
                    <input id="name" class="form-control" placeholder="Nom de l'unité" type="text" name="name" required>
                    <label for="name">Nom de l'unité</label>
                    <div id="nameFeedback" class="invalid-feedback"></div>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary" id="submitUnit">
                    <i class="fas fa-plus"></i> Créer
                </button>
            </div>
            <?= form_close() ?>
        </div>
    </div>

    <!-- Colonne droite (Tableau) -->
    <div class="col-md-8 ">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white h4">
                Liste des unités
            </div>
            <div class="card-body  ">
                <table id="unitTable" class="table table-hover table-striped align-middle table-bordered">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal édition -->
<div class="modal fade" id="modalUnit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Éditer l'unité</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="modalNameInput" placeholder="Nom de l'unité" data-id="">
                    <label for="modalNameInput">Nom de l'unité</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Annuler</button>
                <button id="saveUnitBtn" type="button" class="btn btn-primary">
                    <span class="spinner-border spinner-border-sm d-none" role="status" id="loaderEdit"></span>
                    Sauvegarder
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        const baseUrl = "<?= base_url(); ?>";

        //  Initialisation du modal Bootstrap
        const myModal = new bootstrap.Modal('#modalUnit')

        // Initialisation de la DataTable
        var table = $('#unitTable').DataTable({
            processing: true,         // Affiche un indicateur de chargement
            serverSide: true,         // Active le traitement côté serveur
            ajax: {
                url: '<?= base_url('datatable/searchdatatable') ?>', // URL d’appel AJAX
                type: 'POST',
                data: { model: 'UnitModel' }  // Envoie le modèle à utiliser côté serveur
            },
            columns: [
                { data : 'id' }, // Affiche l'id
                { data: 'name' }, // Affiche le nom
                {
                    // Colonne d’actions : boutons édition/suppression
                    data: null,
                    orderable: false,
                    render: function(data, type, row){
                        return `
                        <div class="" role="group">
                            <button onclick="showModal(${row.id},'${row.name}')" class="btn btn-sm btn-warning" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteUnit(${row.id})" class="btn btn-sm btn-danger" title="Supprimer">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    `;
                    }
                }
            ],
            order: [[0, 'desc']],  // Tri par ID décroissant
            pageLength: 10,        // 10 lignes par page
            language: { url: baseUrl + 'js/datatable/datatable-2.1.4-fr-FR.json' }, // Traduction FR
        });

        // Fonction pour rafraîchir la table sans recharger la page
        window.refreshTable = function() {
            table.ajax.reload(null, false);
        };

        // Fonction pour afficher le modal avec les données existantes
        window.showModal = function(id, name){
            $('#modalNameInput').val(name).data('id', id);
            myModal.show();
        };

        // Sauvegarde de la modification
        $('#saveUnitBtn').click(function(){
            let id = $('#modalNameInput').data('id');
            let name = $('#modalNameInput').val();
            $.ajax({
                url: '<?= base_url('/admin/unit/update') ?>', // Endpoint de mise à jour
                type: 'POST',
                data: { id, name },
                success: function(response){
                    myModal.hide();
                    if(response.success){
                        // Message succès
                        Swal.fire({title:'Succès !', text: response.message, icon:'success', timer:2000, showConfirmButton:false});
                        refreshTable();
                    } else {
                        // Gestion d'erreur côté serveur (validation)
                        let msg = response.message;
                        if(typeof msg === 'object') msg = Object.values(msg).flat().join("\n");
                        Swal.fire({title:'Erreur !', text: msg || 'Une erreur est survenue', icon:'error'});
                    }
                },
                error: function(){
                    // Erreur AJAX (réseau ou serveur)
                    Swal.fire({title:'Erreur !', text:'Problème réseau', icon:'error'});
                }
            });
        });

        // Suppression d'une unité
        window.deleteUnit = function(id){
            Swal.fire({
                title: `Êtes-vous sûr ?`,
                text: `Voulez-vous vraiment supprimer cette unité ?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#6c757d",
                confirmButtonText: `Oui !`,
                cancelButtonText: "Annuler",
            }).then((result) => {
                if(result.isConfirmed){
                    $.post('<?= base_url('/admin/unit/delete') ?>',{id},function(response){
                        if(response.success){
                            Swal.fire({title:'Succès !', text:response.message, icon:'success', timer:2000, showConfirmButton:false});
                            refreshTable();
                        } else {
                            Swal.fire({title:'Erreur !', text:response.message || 'Une erreur est survenue', icon:'error'});
                        }
                    }).fail(()=>Swal.fire({title:'Erreur !', text:'Problème réseau', icon:'error'}));
                }
            });
        };
    });
</script>

<style>
    #unitTable, #unitTable th {
        text-align : center
    }
</style>