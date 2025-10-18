<div class="row">
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
    <div class="col-md-9">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white h4">
                Liste des unités
            </div>
            <div class="card-body">
                <table id="unitTable" class="table table-hover table-striped align-middle">
                    <thead>
                    <tr>
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
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
        const myModal = new bootstrap.Modal('#modalUnit');

        // DataTable
        var table = $('#unitTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('datatable/searchdatatable') ?>',
                type: 'POST',
                data: { model: 'UnitModel' }
            },
            columns: [
                { data: 'name' },
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row){
                        return `
                        <div class="btn-group" role="group">
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
            order: [[0, 'desc']],
            pageLength: 10,
            language: { url: baseUrl + 'js/datatable/datatable-2.1.4-fr-FR.json' },
        });

        window.refreshTable = function() {
            table.ajax.reload(null, false);
        };

        window.showModal = function(id, name){
            $('#modalNameInput').val(name).data('id', id);
            myModal.show();
        };

        $('#saveUnitBtn').click(function(){
            let id = $('#modalNameInput').data('id');
            let name = $('#modalNameInput').val();
            $.ajax({
                url: '<?= base_url('/admin/unit/update') ?>',
                type: 'POST',
                data: { id, name },
                success: function(response){
                    myModal.hide();
                    if(response.success){
                        Swal.fire({title:'Succès !', text: response.message, icon:'success', timer:2000, showConfirmButton:false});
                        refreshTable();
                    } else {
                        let msg = response.message;
                        if(typeof msg === 'object') msg = Object.values(msg).flat().join("\n");
                        Swal.fire({title:'Erreur !', text: msg || 'Une erreur est survenue', icon:'error'});
                    }
                },
                error: function(){
                    Swal.fire({title:'Erreur !', text:'Problème réseau', icon:'error'});
                }
            });
        });

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