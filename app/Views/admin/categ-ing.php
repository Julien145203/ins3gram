<div class="row">
    <div class="col-md-3">
        <div class="card">
            <?= form_open('admin/categ-ing/insert') ?>
            <div class="card-header h4">Ajouter une nouvelle catégorie</div>
            <div class="card-body">
                <div class="form-floating">
                    <input id="name" class="form-control" placeholder="Nom de la catégorie" type="text" name="name" required>
                    <label for="name">Nom de la catégorie</label>
                </div>
                <div class="form-floating mt-2">
                    <select id="id_categ_parent" class="form-select" name="id_categ_parent">
                        <option value="">Aucune</option>
                        <?php foreach($categories as $categ): ?>
                            <?php if (empty($categ['id_categ_parent'])): ?>
                                <option value="<?= $categ['id'] ?>"><?= esc($categ['name']) ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                    <label for="id_categ_parent">Catégorie parente</label>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Créer la catégorie</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-header h4">Liste des catégories</div>
            <div class="card-body">
                <table id="categIngTable" class="table table-sm table-hover">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Catégorie parente</th>
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
                <h5 class="modal-title">Éditer la catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-floating">
                    <input type="text" class="form-control" id="modalNameInput" placeholder="Nom de la catégorie" data-id="">
                    <label for="modalNameInput">Nom de la catégorie</label>
                </div>

                <div class="form-floating mt-2">
                    <select class="form-select" id="modalParentInput">
                        <option value="">Aucune</option>
                    </select>
                    <label for="modalParentInput">Catégorie parente</label>
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
    $(document).ready(function() {
        var baseUrl = "<?= base_url(); ?>";
        var parentMap = {};
        <?php foreach($categories as $c): ?>
        parentMap[<?= $c['id'] ?>] = "<?= esc($c['name']) ?>";
        <?php endforeach; ?>
        var table = $('#categIngTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('datatable/searchdatatable') ?>',
                type: 'POST',
                data: { model: 'CategIngModel' }
            },
            columns: [
                { data: 'id' },
                { data: 'name' },
                {
                    data: 'id_categ_parent',
                    render: function(data, type, row) {
                        return data ? parentMap[data] || data : 'Aucune';
                    }
                },
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        let safeName = row.name.replace(/'/g, "\\'").replace(/"/g, '\\"');
                        return `
                    <div class="btn-group" role="group">
                        <button onclick="showModal(${row.id}, '${safeName}', ${row.id_categ_parent ?? 'null'})" class="btn btn-sm btn-warning" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteCategIng(${row.id})" class="btn btn-sm btn-danger" title="Supprimer">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>`;
                    }
                }
            ],
            order: [[0, 'desc']],
            pageLength: 10,
            language: { url: baseUrl + 'js/datatable/datatable-2.1.4-fr-FR.json' }
        });
        window.refreshTable = function() { table.ajax.reload(null, false); };
    });

    const myModal = new bootstrap.Modal('#modalCategIng');

    window.showModal = function(id, name, currentParentId) {
        $('#modalNameInput').val(name).data('id', id);
        $('#modalParentInput').empty().append('<option value="">Aucune</option>');
        $.getJSON('<?= base_url('/admin/categ-ing/getValidParents') ?>', { id: id }, function(data){
            $.each(data, function(i, categ){
                let selected = categ.id == currentParentId ? 'selected' : '';
                $('#modalParentInput').append(`<option value="${categ.id}" ${selected}>${categ.name}</option>`);
            });
            myModal.show();
        });
    };

    function saveCategIng() {
        let id = $('#modalNameInput').data('id');
        let name = $('#modalNameInput').val();
        let id_categ_parent = $('#modalParentInput').val() || null;
        $.post('<?= base_url('/admin/categ-ing/update') ?>', {id, name, id_categ_parent}, function(response) {
            myModal.hide();
            if(response.success){
                Swal.fire({ title:'Succès !', text:response.message, icon:'success', timer:2000, showConfirmButton:false });
                refreshTable();
            } else {
                Swal.fire({ title:'Erreur !', text:JSON.stringify(response.message), icon:'error' });
            }
        }, 'json');
    }

    function deleteCategIng(id){
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: "Voulez-vous vraiment supprimer cette catégorie ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Oui !",
            cancelButtonText: "Annuler"
        }).then((result)=>{
            if(result.isConfirmed){
                $.post('<?= base_url('/admin/categ-ing/delete') ?>', {id}, function(response){
                    if(response.success){
                        Swal.fire({ title:'Succès !', text:response.message, icon:'success', timer:2000, showConfirmButton:false });
                        refreshTable();
                    } else {
                        Swal.fire({ title:'Erreur !', text:'Une erreur est survenue', icon:'error' });
                    }
                }, 'json');
            }
        });
    }
</script>
