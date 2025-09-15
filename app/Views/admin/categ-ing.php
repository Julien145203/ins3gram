<div class="row">
    <div class="col-md-3">
        <div class="card">
            <?= form_open('admin/category-ingredient/insert') ?>
            <div class="card-header h4">Créer une catégorie d'ingrédients</div>
            <div class="card-body">
                <div class="form-floating mb-3">
                    <input id="name" class="form-control" placeholder="Nom de la catégorie" type="text" name="name" required>
                    <label for="name">Nom de la catégorie</label>
                </div>
                <div class="form-floating mb-3">
                    <select class="form-select" id="id_categ_parent_insert" name="id_categ_parent">
                        <option value="">Choisir une catégorie</option>
                        <?php if(isset($categories) && !empty($categories)): ?>
                            <?php foreach($categories as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= esc($c['name']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <label for="id_categ_parent_insert">Catégorie parente (optionnel)</label>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Créer</button>
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

<!-- Modal -->
<div class="modal" id="modalCategIng" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Éditer la catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="modalNameInput" data-id="">
                    <label for="modalNameInput">Nom de la catégorie</label>
                </div>
                <div class="form-floating">
                    <select class="form-select" id="modalParentInput">
                        <option value="">Choisir une catégorie</option>
                    </select>
                    <label for="modalParentInput">Catégorie parente (optionnel)</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="saveCategIng()">Sauvegarder</button>
            </div>
        </div>
    </div>
</div>

<script>
    const baseUrl = "<?= base_url(); ?>";
    const myModal = new bootstrap.Modal('#modalCategIng');

    $(document).ready(function(){
        var table = $('#categIngTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: { url: baseUrl + '/admin/category-ingredient/datatable', type: 'GET' },
            columns: [
                { data: 'id' },
                { data: 'name' },
                { data: 'parent_name', defaultContent: 'Aucune' },
                { data: null, orderable: false,
                    render: function(data,type,row){
                        let safeName = row.name.replace(/'/g,"\\'").replace(/"/g,'\\"');
                        return `<div class="btn-group" role="group">
                    <button class="btn btn-sm btn-warning" onclick="showModal(${row.id},'${safeName}', ${row.id_categ_parent ?? 'null'})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteCategIng(${row.id})">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>`;
                    }
                }
            ],
            order: [[0,'desc']],
            pageLength: 10,
            language: { url: baseUrl + 'js/datatable/datatable-2.1.4-fr-FR.json' }
        });
        window.refreshTable = function(){ table.ajax.reload(null,false); };
    });

    function showModal(id,name,currentParentId){
        $('#modalNameInput').val(name).data('id',id);
        $('#modalParentInput').empty().append('<option value="">Choisir une catégorie</option>');
        $.getJSON(baseUrl + '/admin/category-ingredient/getValidParents',{id:id},function(data){
            $.each(data,function(i,c){
                let selected = c.id == currentParentId ? 'selected' : '';
                $('#modalParentInput').append(`<option value="${c.id}" ${selected}>${c.name}</option>`);
            });
            myModal.show();
        });
    }

    function saveCategIng(){
        let id = $('#modalNameInput').data('id');
        let name = $('#modalNameInput').val();
        let id_categ_parent = $('#modalParentInput').val() || null;
        $.post(baseUrl + '/admin/category-ingredient/update',{id,name,id_categ_parent},function(response){
            myModal.hide();
            if(response.success){ Swal.fire({title:'Succès !',text:response.message,icon:'success',timer:2000,showConfirmButton:false}); refreshTable(); }
            else { Swal.fire({title:'Erreur !',text:JSON.stringify(response.message),icon:'error'}); }
        },'json');
    }

    function deleteCategIng(id){
        Swal.fire({
            title:'Êtes-vous sûr ?',
            text:'Voulez-vous vraiment supprimer cette catégorie ?',
            icon:'warning',
            showCancelButton:true,
            confirmButtonColor:'#28a745',
            cancelButtonColor:'#6c757d',
            confirmButtonText:'Oui !',
            cancelButtonText:'Annuler'
        }).then((result)=>{
            if(result.isConfirmed){
                $.post(baseUrl + '/admin/category-ingredient/delete',{id},function(response){
                    if(response.success){ Swal.fire({title:'Succès !',text:response.message,icon:'success',timer:2000,showConfirmButton:false}); refreshTable(); }
                    else { Swal.fire({title:'Erreur !',text:'Une erreur est survenue',icon:'error'}); }
                },'json');
            }
        });
    }
</script>
