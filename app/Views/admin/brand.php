<div class="row">
    <div class="col-md-3">
        <div class="card">
            <?= form_open('admin/brand/insert') ?>
            <div class="card-header h4">
                Créer une marque
            </div>
            <div class="card-body">
                <div class="form-floating">
                    <input id="name" class="form-control" placeholder="Nom de la marque" type="text" name="name" required>
                    <label for="name">Nom de la marque</label>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Créer la marque</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-header h4">
                Liste des marques
            </div>
            <div class="card-body">
                <table id="brandsTable" class="table table-sm table-hover">
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
<div class="modal" id="modalBrand" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Éditer la marque</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-floating">
                    <input type="text" class="form-control" id="modalNameInput" placeholder="Nom de la marque" data-id="">
                    <label for="modalNameInput">Nom de la marque</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Annuler</button>
                <button onclick="saveBrand()" type="button" class="btn btn-primary">Sauvegarder</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var baseUrl = "<?= base_url(); ?>";
        var table = $('#brandsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('datatable/searchdatatable') ?>',
                type: 'POST',
                data: {
                    model: 'BrandModel'
                }
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
                                <button onclick="showModal(${row.id},'${row.name}')"  class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteBrand(${row.id})" class="btn btn-sm btn-danger" title="Supprimer">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ],
            order: [[0, 'desc']],
            pageLength: 10,
            language: {
                url: baseUrl + 'js/datatable/datatable-2.1.4-fr-FR.json',
            }
        });

        // Fonction pour actualiser la table
        window.refreshTable = function() {
            table.ajax.reload(null, false); // false pour garder la pagination
        };
    });

    const myModal = new bootstrap.Modal('#modalBrand');

    function showModal(id, name) {
        $('#modalNameInput').val(name);
        $('#modalNameInput').data('id', id);
        myModal.show();
    }

    function saveBrand() {
        let name = $('#modalNameInput').val();
        let id = $('#modalNameInput').data('id');
        $.ajax({
            url: '<?= base_url('/admin/brand/update') ?>',
            type: 'POST',
            data: {
                name: name,
                id: id,
            },
            success: function(response) {
                myModal.hide();
                if (response.success) {
                    Swal.fire({
                        title: 'Succès !',
                        text: response.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    refreshTable();
                } else {
                    console.log(response.message)
                    Swal.fire({
                        title: 'Erreur !',
                        text: 'Une erreur est survenue',
                        icon: 'error'
                    });
                }
            }
        })
    }

    function deleteBrand(id){
        Swal.fire({
            title: `Êtes-vous sûr ?`,
            text: `Voulez-vous vraiment supprimer cette marque ?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#6c757d",
            confirmButtonText: `Oui !`,
            cancelButtonText: "Annuler",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('/admin/brand/delete') ?>',
                    type: 'POST',
                    data: {
                        id: id,
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Succès !',
                                text: response.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            refreshTable();
                        } else {
                            console.log(response.message)
                            Swal.fire({
                                title: 'Erreur !',
                                text: 'Une erreur est survenue',
                                icon: 'error'
                            });
                        }
                    }
                })
            }
        });
    }
</script>
