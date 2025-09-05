<div class="row">
    <div class="col-md-3">
        <div class="card">
            <?= form_open('admin/category-ingredient/insert') ?>
            <div class="card-header h4">
                Créer une catégorie d'ingrédients
            </div>
            <div class="card-body">
                <div class="form-floating mb-3">
                    <input id="name" class="form-control" placeholder="Nom de la catégorie d'ingrédients" type="text" name="name" required>
                    <label for="name">Nom de la catégorie d'ingrédients</label>
                </div>
                <div class="form-floating mb-3">
                    <select class="form-select" id="id_categ_parent" name="id_categ_parent">
                        <option value="" selected>Choisir une categorie</option>
                        <?php
                        if(isset($categ) && !empty($categ)){
                            foreach($categ as $c){ ?>
                                <option value="<?= $c['id'] ?>">
                                    <?= $c['name']; ?>
                                </option>
                            <?php }
                        }
                        ?>
                    </select>
                    <label for="id_categ_parent">Catégorie parente (optionnel)</label>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Créer la catégorie d'ingrédients</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-header h4">
                Liste des catégorie d'ingrédients
            </div>
            <div class="card-body">
                <table id="categIngTable" class="table table-sm table-hover">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Catégorie Parente</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="modalCategIng" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Éditer la catégorie d'ingrédients</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="modalNameInput" placeholder="Nom de la catégorie d'ingrédients" data-id="">
                    <label for="modalNameInput">Nom de la catégorie d'ingrédients</label>
                </div>
                <div class="form-floating">
                    <select class="form-select" id="id_categ_parent" name="id_categ_parent">
                        <option value="" selected>Choisir une categorie</option>
                        <?php
                        if(isset($categ) && !empty($categ)){
                            foreach($categ as $c){ ?>
                                <option value="<?= $c['id'] ?>">
                                    <?= $c['name']; ?>
                                </option>
                            <?php }
                        }
                        ?>
                    </select>
                    <label for="id_categ_parent">Catégorie parente (optionnel)</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Annuler</button>
                <button onclick="saveCategIng()" type="button" class="btn btn-primary">Sauvegarder</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        var baseUrl = "<?= base_url(); ?>";
        var table = $('#categIngTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('datatable/searchdatatable') ?>',
                type: 'POST',
                data: {
                    model: 'CategIngModel'
                }
            },
            columns: [
                { data: 'id' },
                { data: 'name' },
                {data: 'parent_name'},
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        return `
                            <div class="btn-group" role="group">
                                <button onclick="showModal(${row.id},'${row.name}')"  class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteCategIng(${row.id})" class="btn btn-sm btn-danger" title="Supprimer">
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

    const myModal = new bootstrap.Modal('#modalCategIng');

    function showModal(id, name) {
        $('#modalNameInput').val(name);
        $('#modalNameInput').data('id', id);
        //TODO : AJAX POUR CHARGER A LA VOLEE OU DESACTIVER LA CATEG ACTUELLE
        myModal.show();
    }
    function saveCategIng() {
        let name = $('#modalNameInput').val();
        let id = $('#modalNameInput').data('id');
        // TODO : RECUPERER LA VALEUR DU SELECT
        $.ajax({
            url: '<?= base_url('/admin/category-ingredient/update') ?>',
            type: 'POST',
            data: {
                name: name,
                id: id,
                // TODO : AJOUTER LA VALEUR DU SELECT
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
                    // Actualiser la table
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

    function deleteCategIng(id) {
        //TODO : VERIFIER SI ON SUPPRIME UNE CATEGORIE PARENTE
        Swal.fire({
            title: `Êtes-vous sûr ?`,
            text: `Voulez-vous vraiment supprimer cette catégorie ?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#6c757d",
            confirmButtonText: `Oui !`,
            cancelButtonText: "Annuler",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('/admin/category-ingredient/delete') ?>',
                    type: 'POST',
                    data: {
                        id: id,
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Succès !',
                                text: response.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            // Actualiser la table
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