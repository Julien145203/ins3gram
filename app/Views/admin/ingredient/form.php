<?php
echo form_open_multipart(isset($ingredient) ? "/admin/ingredient/update" : "/admin/ingredient/insert"); ?>

<?php if(isset($ingredient)): ?>
    <input type="hidden" name="id" value="<?= $ingredient['id'] ?>">
<?php endif; ?>

<div class="row">
    <!-- Colonne gauche : Détails et Image -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white h5">Détails de l’ingrédient</div>
            <div class="card-body">
                <!-- Nom -->
                <div class="mb-3">
                    <label for="name" class="form-label">Nom <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= isset($ingredient) ? esc($ingredient['name']) : '' ?>" required>
                    <div class="invalid-feedback"><?= validation_show_error('name') ?></div>
                </div>

                <!-- Marque -->
                <div class="mb-3">
                    <label for="id_brand" class="form-label">Marque</label>
                    <select class="form-select select2" name="id_brand" id="id_brand">
                        <?php if(isset($ingredient) && $ingredient['id_brand']): ?>
                            <option value="<?= $ingredient['id_brand'] ?>" selected>
                                <?= esc($brands[$ingredient['id_brand']] ?? '') ?>
                            </option>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Catégorie -->
                <div class="mb-3">
                    <label for="id_categ" class="form-label">Catégorie</label>
                    <select class="form-select select2" name="id_categ" id="id_categ">
                        <?php if(isset($ingredient) && $ingredient['id_categ']): ?>
                            <option value="<?= $ingredient['id_categ'] ?>" selected>
                                <?= esc($categories[$ingredient['id_categ']] ?? '') ?>
                            </option>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4"><?= isset($ingredient) ? esc($ingredient['description']) : '' ?></textarea>
                </div>

                <!-- Image -->
                <div class="mb-3">
                    <label class="form-label">Image</label>
                    <input type="file" class="form-control" name="image" id="image" accept="image/*">
                    <?php
                    $imagePath = isset($ingredient) ? $ingredientModel->getImage($ingredient['id']) : null;
                    if($imagePath): ?>
                        <img src="<?= base_url($imagePath) ?>" alt="Prévisualisation" class="img-fluid mt-2" style="max-height:120px; border-radius:8px;">
                    <?php endif; ?>
                    <img id="previewImage" class="img-fluid mt-2 d-none" style="max-height:120px; border-radius:8px;">
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <a href="<?= base_url('admin/ingredient') ?>" class="btn btn-secondary me-2">Annuler</a>
                    <button type="submit" class="btn btn-primary"><?= isset($ingredient) ? 'Mettre à jour' : 'Créer' ?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Colonne droite : Substituts et Utilisé comme substitut -->
    <div class="col-md-8">
        <!-- Substituts -->
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-primary text-white h5 d-flex justify-content-between align-items-center">
                <span>Substituts</span>
                <button type="button" class="btn btn-sm btn-light" id="addSubstitute">
                    <i class="fas fa-plus"></i> Ajouter
                </button>
            </div>
            <div class="card-body">
                <table class="table table-hover table-striped" id="tableSubstitute">
                    <thead>
                    <tr>
                        <th>Substitut</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(isset($substitutes) && !empty($substitutes)): ?>
                        <?php foreach($substitutes as $sub): ?>
                            <tr>
                                <td>
                                    <select class="form-select substituteSelect" name="substitutes[]">
                                        <option value="<?= $sub['id_ingredient_sub'] ?>" selected>
                                            <?= esc($sub['substitute_name']) ?>
                                        </option>
                                    </select>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger removeSub">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Utilisé comme substitut -->
        <?php if(!empty($usedIn)): ?>
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white h6">
                    Utilisé comme substitut dans :
                    <span class="badge bg-light text-dark" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= count($usedIn) ?> ingrédients">
                    <?= count($usedIn) ?>
                </span>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <?php foreach($usedIn as $item): ?>
                            <li class="list-group-item">
                                <a href="<?= base_url('admin/ingredient/edit/'.$item['id']) ?>">
                                    <?= esc($item['name']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php echo form_close(); ?>

<script>
    $(document).ready(function() {
        const baseUrl = "<?= base_url(); ?>";

        // Activer les tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Prévisualisation image
        $("#image").change(function() {
            let file = this.files[0];
            if(file){
                let reader = new FileReader();
                reader.onload = function(e){
                    $("#previewImage").attr("src", e.target.result).removeClass("d-none");
                }
                reader.readAsDataURL(file);
            }
        });

        // Select2 Marque / Catégorie
        function initSelect2($el, url){
            $el.select2({
                theme: 'bootstrap-5',
                placeholder: 'Rechercher...',
                allowClear: true,
                ajax: {
                    url: baseUrl + url,
                    dataType: 'json',
                    delay: 250,
                    data: params => ({ search: params.term, page: params.page||1 }),
                    processResults: data => ({ results: data.results, pagination: { more: data.pagination.more } }),
                    cache: true
                },
                width: '100%'
            });
        }
        initSelect2($('#id_brand'),'admin/brand/search');
        initSelect2($('#id_categ'),'admin/category-ingredient/search');

        // TinyMCE description
        tinymce.init({
            selector:'#description',
            height: 200,
            menubar:false,
            plugins:['preview','code','fullscreen','wordcount','link','lists','image'],
            toolbar:'undo redo | formatselect | bold italic link forecolor backcolor removeformat | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | image | fullscreen preview code',
            setup: function (editor) {
                <?php if($imagePath): ?>
                editor.on('init', function () {
                    editor.setContent(`<?= isset($ingredient['description']) ? addslashes($ingredient['description']) : '' ?>`);
                });
                <?php endif; ?>
            }
        });

        // Substituts
        function initSubstituteSelect($el){
            $el.select2({
                theme:'bootstrap-5',
                placeholder:'Rechercher un ingrédient...',
                allowClear:true,
                width: '100%',
                ajax:{
                    url: baseUrl + 'admin/ingredient/search',
                    dataType:'json',
                    delay:250,
                    data: params => ({ search: params.term, page: params.page||1 }),
                    processResults: data => ({ results: data.results, pagination:{more: data.pagination.more} }),
                    cache:true
                }
            });
        }

        $('.substituteSelect').each(function(){ initSubstituteSelect($(this)); });

        $('#addSubstitute').click(function(){
            let row = `<tr>
    <td><select class="form-select substituteSelect" name="substitutes[]"></select></td>
    <td><button type="button" class="btn btn-sm btn-danger removeSub"><i class="fas fa-trash-alt"></i></button></td>
    </tr>`;
            $('#tableSubstitute tbody').append(row);
            initSubstituteSelect($('#tableSubstitute tbody tr:last .substituteSelect'));
        });

        $(document).on('click','.removeSub',function(){ $(this).closest('tr').remove(); });
    });
</script>
