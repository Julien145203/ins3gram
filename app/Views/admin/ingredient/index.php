<div class="row">
    <div class="col">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Liste des ingrédients</h3>
                <a href="<?= base_url("admin/ingredient/new") ?>" class="btn btn-sm btn-light">
                    <i class="fas fa-plus"></i> Nouvel ingrédient
                </a>
            </div>
            <div class="card-body">
                <table class="table table-hover table-striped align-middle table-bordered" id="tableIngredient">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Marque</th>
                        <th>Catégorie</th>
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
        const baseUrl = "<?= base_url(); ?>";

        var table = $('#tableIngredient').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: baseUrl + 'datatable/searchdatatable',
                type: 'POST',
                data: { model: 'IngredientModel' }
            },
            columns: [
                {data: 'id'},
                {
                    data: 'image',
                    render: function(data, type, row) {
                        if (data) {
                            return `<img src="${baseUrl}/${data}" style="height:40px; width:40px; object-fit:cover; border-radius:5px;">`;
                        }
                        return `<span class="text-muted">Aucune</span>`;
                    },
                    orderable: false
                },
                { data: 'name' },
                { data: 'description' },
                { data: 'brand_name' },
                { data: 'categ_name' },
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        return `
                    <div class="btn-group" role="group">
                        <a href="${baseUrl}admin/ingredient/edit/${row.id}" class="btn btn-sm btn-warning text-white" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="deleteIngredient(${row.id})" class="btn btn-sm btn-danger text-white" title="Supprimer">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>`;
                    }
                }
            ],
            order: [[1, 'asc']], // trier par nom
            pageLength: 10,
            language: {
                url: baseUrl + 'js/datatable/datatable-2.1.4-fr-FR.json',
            }
        });

        window.refreshTable = function() {
            table.ajax.reload(null, false);
        };
    });

    function deleteIngredient(id) {
        if (!confirm("Supprimer cet ingrédient ?")) return;
        $.ajax({
            url: "<?= base_url('admin/ingredient/delete') ?>",
            type: "POST",
            data: { id: id },
            success: function() {
                refreshTable();
            },
            error: function() {
                alert("Erreur lors de la suppression !");
            }
        });
    }
</script>
<style>
    #tableIngredient, #tableIngredient th
    {text-align: center}
</style>