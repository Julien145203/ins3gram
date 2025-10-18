<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3>Liste des recettes</h3>
                <a href="<?= base_url("admin/recipe/new") ?>" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Nouvelle Recette
                </a>
            </div>
            <div class="card-body">
                <table class="table table-sm table-bordered table-striped" id="tableRecipe">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Créateur</th>
                        <th>Date modif.</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var baseUrl = "<?= base_url(); ?>";
        var table = $('#tableRecipe').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('datatable/searchdatatable') ?>',
                type: 'POST',
                data: {
                    model: 'RecipeModel'
                }
            },
            columns: [
                { data: 'id' },
                {
                    data: 'name',
                    render : function(data, type, row) {
                        return `<a class="link-underline link-underline-opacity-0"
                                    href="<?= base_url('admin/recipe/') ?>${row.id}">
                                    ${data}
                                </a>`;
                    }
                },
                { data: 'creator' },
                {
                    data: 'updated_at',
                    render : function(data) {
                        let date = new Date(data);
                        return date.toLocaleDateString("fr") + " " + date.toLocaleTimeString("fr");
                    }
                },
                {
                    data: 'deleted_at',
                    render: function(data, type, row) {
                        const isActive = (data === 'active' || row.deleted_at === null);
                        return `
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                    id="switch-${row.id}"
                                    ${isActive ? 'checked' : ''}
                                    onchange="toggleRecipeStatus(${row.id}, this.checked ? 'activate' : 'deactivate')">
                                <label class="form-check-label" for="switch-${row.id}">
                                    ${isActive ? 'Active' : 'Inactive'}
                                </label>
                            </div>
                        `;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        return `
                            <div class="btn-group" role="group">
                                <a href="<?= base_url('/admin/recipe/') ?>${row.id}"
                                   class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= base_url('/recette/') ?>${row.slug}"
                                    class="btn btn-sm btn-primary" target="_blank" title="Voir la recette">
                                    <i class="fas fa-eye"></i>
                                </a>
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
            table.ajax.reload(null, false);
        };
    });

    function toggleRecipeStatus(id, action) {
        const actionText = action === 'activate' ? 'activer' : 'désactiver';
        const actionColor = action === 'activate' ? '#28a745' : '#dc3545';

        Swal.fire({
            title: `Êtes-vous sûr ?`,
            text: `Voulez-vous vraiment ${actionText} cette recette ?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: actionColor,
            cancelButtonColor: "#6c757d",
            confirmButtonText: `Oui, ${actionText} !`,
            cancelButtonText: "Annuler",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url('/admin/recipe/switch-active'); ?>",
                    type: "POST",
                    data: { 'id_recipe': id },
                    success: function (response) {
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
                            Swal.fire({
                                title: 'Erreur !',
                                text: response.message || 'Une erreur est survenue',
                                icon: 'error'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Erreur !',
                            text: 'Erreur de communication avec le serveur',
                            icon: 'error'
                        });
                    }
                });
            } else {
                // Si annulé, on refresh pour remettre le switch dans son état initial
                refreshTable();
            }
        });
    }
</script>