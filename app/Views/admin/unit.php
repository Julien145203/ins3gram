<div class="row">
    <!-- Formulaire pour ajouter une nouvelle unité -->
    <div class="col-md-3">
        <div class="card">
            <?= form_open('admin/unit/insert') ?>
            <div class="card-header h4">
                Ajouter une nouvelle unité
            </div>
            <div class="card-body">
                <div class="form-floating">
                    <input id="name" class="form-control" placeholder="Nom de l'unité" type="text" name="name" required>
                    <label for="name">Nom de l'unité</label>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Créer l'unité</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>

    <!-- Liste des unités -->
    <div class="col-md-9">
        <div class="card">
            <div class="card-header h4">
                Liste des unités
            </div>
            <div class="card-body">
                <table id="unitTable" class="table table-sm table-hover">
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

<!-- Modal pour éditer une unité -->
<div class="modal" id="modalUnit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Éditer l'unité</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-floating">
                    <input type="text" class="form-control" id="modalNameInput" placeholder="Nom de l'unité" data-id="">
                    <label for="modalNameInput">Nom de l'unité</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Annuler</button>
                <button onclick="saveUnit()" type="button" class="btn btn-primary">Sauvegarder</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var table = $('#unitTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('datatable/searchdatatable') ?>',
                type: 'POST',
                data: { model: 'UnitModel' }
            },
            columns: [
                { data: 'id' },
                { data: 'name' },
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        return `
                        <div class="btn-group" role="group">
                            <button onclick="showUnitModal(${row.id},'${row.name}')" class="btn btn-sm btn-warning" title="Modifier">
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
            language: { url: '<?= base_url() ?>js/datatable/datatable-2.1.4-fr-FR.json' }
        });

        // Actualiser la table
        window.refreshTable = function() { table.ajax.reload(null, false); };
    });

    // Modal bootstrap
    const myModal = new bootstrap.Modal('#modalUnit');

    function showUnitModal(id, name) {
        $('#modalNameInput').val(name).data('id', id);
        myModal.show();
    }

    function saveUnit() {
        let name = $('#modalNameInput').val();
        let id = $('#modalNameInput').data('id');
        $.ajax({
            url: '<?= base_url('/admin/unit/update') ?>',
            type: 'POST',
            data: { id: id, name: name },
            success: function(response) {
                myModal.hide();
                if (response.success) {
                    Swal.fire({ title: 'Succès !', text: response.message, icon: 'success', timer: 2000, showConfirmButton: false });
                    refreshTable();
                } else {
                    Swal.fire({ title: 'Erreur !', text: 'Une erreur est survenue', icon: 'error' });
                    console.log(response.message);
                }
            }
        });
    }

    function deleteUnit(id) {
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: "Voulez-vous vraiment supprimer cette unité ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Oui !",
            cancelButtonText: "Annuler",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('/admin/unit/delete') ?>',
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({ title: 'Succès !', text: response.message, icon: 'success', timer: 2000, showConfirmButton: false });
                            refreshTable();
                        } else {
                            Swal.fire({ title: 'Erreur !', text: 'Une erreur est survenue', icon: 'error' });
                            console.log(response.message);
                        }
                    }
                });
            }
        });
    }
</script>

