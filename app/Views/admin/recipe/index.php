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
                        <th class="text-center">Date modif.</th>
                        <th>Alcool</th>
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
    $(document).ready(function(){
        let table = $('#tableRecipe').DataTable({
            processing: true,
            serverSide: false,
            ajax: '<?= base_url("admin/recipe/list") ?>',
            columns: [
                { data: 'id' },
                { data: 'name' },
                { data: 'username' },
                { data: 'updated_at', className: 'text-center' },
                { data: 'alcool', render: function(data) {
                        return data == 1
                            ? '<span class="badge bg-info">Avec alcool</span>'
                            : '<span class="badge bg-info ">Sans alcool</span>';
                    }},
                { data: 'deleted_at', render: function(data) {
                        return !data
                            ? '<span class="badge bg-success">Actif</span>'
                            : '<span class="badge bg-danger">Inactif</span>';
                    }},
                { data: null, orderable: false, searchable: false, render: function(data) {
                        return `
                    <div class="btn-group" role="group">
                        <a href="<?= base_url("admin/recipe/") ?>${data.id}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="${data.id}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                `;
                    }}
            ]
        });

        $('#tableRecipe').on('click', '.btn-delete', function(){
            let id = $(this).data('id');
            if(confirm("Voulez-vous vraiment supprimer cette recette ?")){
                $.post("<?= base_url('admin/recipe/delete') ?>", {id: id}, function(res){
                    if(res.success){
                        alert(res.message);
                        table.ajax.reload();
                    } else {
                        alert("Erreur: " + JSON.stringify(res.message));
                    }
                }, 'json');
            }
        });
    });
</script>
