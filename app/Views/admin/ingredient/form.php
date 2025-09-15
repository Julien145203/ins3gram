<?php
$action = isset($ingredient) ? "/admin/ingredient/update" : "/admin/ingredient/insert";
echo form_open($action, ['class' => 'needs-validation', 'novalidate' => true]);
?>

<?php if(isset($ingredient)): ?>
    <input type="hidden" name="id" value="<?= $ingredient['id'] ?>">
<?php endif; ?>

<div class="row mb-3">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <!-- Nom de l'ingrédient -->
                <div class="mb-3">
                    <label for="name" class="form-label">Nom de l'ingrédient <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= isset($ingredient) ? esc($ingredient['name']) : '' ?>" required>
                    <div class="invalid-feedback"><?= validation_show_error('name') ?></div>
                </div>

                <!-- Marque / Catégorie -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="id_brand" class="form-label">Marque</label>
                        <select class="form-select select2" name="id_brand" id="id_brand">
                            <?php if(isset($ingredient) && $ingredient['id_brand']): ?>
                                <option value="<?= $ingredient['id_brand'] ?>" selected><?= esc($brands[$ingredient['id_brand']] ?? '') ?></option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="id_categ" class="form-label">Catégorie</label>
                        <select class="form-select select2" name="id_categ" id="id_categ">
                            <?php if(isset($ingredient) && $ingredient['id_categ']): ?>
                                <option value="<?= $ingredient['id_categ'] ?>" selected><?= esc($categories[$ingredient['id_categ']] ?? '') ?></option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" rows="3" id="description" name="description"><?= isset($ingredient) ? esc($ingredient['description']) : '' ?></textarea>
                </div>

                <!-- BOUTONS -->
                <div class="d-flex justify-content-end mt-4">
                    <a href="<?= base_url('admin/ingredient'); ?>" class="btn btn-secondary me-2">Retour</a>
                    <button type="submit" class="btn btn-primary"><?= isset($ingredient) ? 'Mettre à jour' : 'Créer' ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo form_close(); ?>

<script>
    $(document).ready(function () {
        const baseUrl = "<?= base_url(); ?>";

        $('#id_brand').select2({
            theme: 'bootstrap-5',
            placeholder: 'Rechercher une marque...',
            allowClear: true,
            ajax: {
                url: baseUrl + 'admin/brand/search',
                dataType: 'json',
                delay: 250,
                data: params => ({ search: params.term, page: params.page||1 }),
                processResults: data => ({ results: data.results, pagination: { more: data.pagination.more } }),
                cache: true
            }
        });

        $('#id_categ').select2({
            theme: 'bootstrap-5',
            placeholder: 'Rechercher une catégorie...',
            allowClear: true,
            ajax: {
                url: baseUrl + 'admin/category-ingredient/search',
                dataType: 'json',
                delay: 250,
                data: params => ({ search: params.term, page: params.page||1 }),
                processResults: data => ({ results: data.results, pagination: { more: data.pagination.more } }),
                cache: true
            }
        });

        tinymce.init({
            selector: '#description',
            height : "200",
            language: 'fr_FR',
            menubar: false,
            plugins: ['preview', 'code', 'fullscreen','wordcount', 'link','lists'],
            toolbar: 'undo redo | formatselect | bold italic link forecolor backcolor removeformat | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | fullscreen preview code'
        });
    });
</script>
