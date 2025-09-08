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
                    <tbody>
                    </tbody>
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
                { data: 'name' },
                { data: 'creator',
                    render : function(data, type, row, meta) {
                        return `<a class=" link-underline link-underline-opacity-0" href=<?= base_url('admin/user/') ?>${row.id_user}>${data}</a>`
                    }
                },
                { data: 'updated_at',
                    render : function(data, type, row, meta) {
                        let date = new Date(data);
                        return date.toLocaleDateString("fr") + " " + date.toLocaleTimeString("fr");
                    }
                },
                {
                    data: 'deleted_at',
                    render: function(data, type, row) {
                        if (data === 'active' || row.deleted_at === null) {
                            return '<span class="badge text-bg-success">Active</span>';
                        } else {
                            return '<span class="badge text-bg-danger">Inactive</span>';
                        }
                    }
                },
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        return `
                            <div class="btn-group" role="group">
                                <a href="<?= base_url('admin/recipe/') ?>${row.id}") class="btn btn-sm btn-warning text-white" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="deleteBrand(${row.id})" class="btn btn-sm btn-danger text-white" title="Supprimer">
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
</script>