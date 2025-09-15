<?php
if(!isset($recipe)) :
    echo form_open('/admin/recipe/insert');
else:
    echo form_open('/admin/recipe/update'); ?>
    <input type="hidden" name="id_recipe" value="<?= $recipe['id']; ?>">
<?php
endif;
?>
<div class="row mb-3">
    <div class="col">
        <div class="card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div class="flex-fill me-3">
                    <input type="text" class="form-control" id="name" placeholder="Nom de la recette" name="name" value="<?= isset($recipe) ? $recipe['name'] : '' ?>" required>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="switchActive" name="active" <?= isset($recipe) && $recipe['deleted_at'] ? '': 'checked'; ?> >
                    <label class="form-check-label" for="switchActive">Active</label>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <!--START: COLONNE PRINCIPALE -->
    <div class="col-md-10">
        <div class="card h-100">
            <div class="card-body">
                <!--START: TABS-LINKS -->
                <ul class="nav nav-tabs" id="tabsRecipe">
                    <li class="nav-item">
                        <a href="#" class="nav-link active" data-bs-toggle="tab" data-bs-target="#general-tab-pane">Général</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" data-bs-toggle="tab" data-bs-target="#ingredient-tab-pane">Ingrédients <span id="badge-ingredient" class="badge rounded-pill text-bg-primary"><?= (isset($recipe['ingredients'])) ? count($recipe['ingredients']) : '0' ;?></span></a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" data-bs-toggle="tab" data-bs-target="#tag-tab-pane">Mots Clés <span id="badge-tag" class="badge rounded-pill text-bg-primary"><?= (isset($recipe['tags'])) ? count($recipe['tags']) : '0' ;?></span></a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" data-bs-toggle="tab" data-bs-target="#step-tab-pane">Étapes <span  id="badge-step" class="badge rounded-pill text-bg-primary"><?= (isset($recipe['steps'])) ? count($recipe['steps']) : '0' ;?></span></a>
                    </li>
                    <?php if(isset($recipe)) : ?>
                        <li class="nav-item">
                            <a href="#" class="nav-link" data-bs-toggle="tab" data-bs-target="#opinion-tab-pane">Commentaires <span id="badge-opinion" class="badge rounded-pill text-bg-primary"><?= (isset($recipe['opinions'])) ? count($recipe['opinions']) : '0' ;?></span></a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link" data-bs-toggle="tab" data-bs-target="#fav-tab-pane">Favoris <span id="badge-fav" class="badge rounded-pill text-bg-primary"><?= (isset($recipe['fav'])) ? count($recipe['fav']) : '0' ;?></span></a>
                        </li>
                    <?php endif; ?>
                </ul>
                <!--END: TABS-LINKS -->
                <!--START: TABS-PANE -->
                <div class="tab-content p-3 border border-1 border-top-0 rounded rounded-top-0" id="tabsRecipeContent">
                    <!--START: GENERAL -->
                    <div class="tab-pane fade show active" id="general-tab-pane" role="tabpanel">
                        <div class="mb-3">
                            <label class="form-label">Description globale de la recette</label>
                            <textarea class="form-control" rows="3" id="description" name="description">
                                <?= $recipe['description'] ?? ''; ?>
                            </textarea>
                        </div>
                        <div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="switchAlcool" name="alcool" checked>
                                <label class="form-check-label" for="switchAlcool">Avec Alcool</label>
                            </div>
                        </div>
                    </div>
                    <!--END:GENERAL -->
                    <!--START: INGREDIENTS -->
                    <div class="tab-pane fade" id="ingredient-tab-pane" role="tabpanel">
                        <div class="mb-3">
                            <span class="btn btn-primary" id="add-ingredient">
                                <i class="fas fa-plus"></i> Ajouter un ingrédient
                            </span>
                        </div>
                        <div id="zone-ingredients">
                            <?php
                            if(isset($recipe['ingredients'])) :
                                $cpt_ing = 0;
                                foreach($recipe['ingredients'] as $ingredient) :
                                    $cpt_ing++;
                                    ?>
                                    <div class="row mb-3 row-ingredient">
                                        <div class="col">
                                            <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-trash-alt text-danger supp-ingredient"></i>
                                        </span>
                                                <select class="form-select flex-fill select-ingredient" name="ingredients[<?= $cpt_ing; ?>][id_ingredient]">
                                                    <option value="<?= $ingredient['id_ingredient'] ?>" selected><?= $ingredient['ingredient'] ?></option>
                                                </select>
                                                <input class="form-control flex-fill" type="number" min="0.1" step="0.1" name="ingredients[<?= $cpt_ing; ?>][quantity]" placeholder="Quantité" value="<?= $ingredient['quantity'] ?>">
                                                <select class="form-select flex-fill select-unit" name="ingredients[<?= $cpt_ing; ?>][id_unit]">
                                                    <option value="<?= $ingredient['id_unit'] ?>" selected><?= $ingredient['unit'] ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                endforeach;
                            endif;
                            ?>
                        </div>
                    </div>
                    <!--END: INGREDIENTS -->
                    <!--START: MOTS CLÉS -->
                    <div class="tab-pane fade" id="tag-tab-pane" role="tabpanel">
                        <div class="row">
                            <!-- Champ de recherche -->
                            <div class="col">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-magnifying-glass"></i></span>
                                    <input type="text" id="search-tag" class="form-control" placeholder="Rechercher un mot clé">
                                </div>
                            </div>
                        </div>
                        <div class="row row-cols-2 row-cols-md-4">
                            <?php if(isset($tags)) :
                                foreach($tags as $tag) :?>
                                    <div class="col mb-2 tag">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="<?= $tag['id'] ?>" id="tag-<?= $tag['id'] ?>" name="tags[]" <?= (isset($recipe['tags']) && in_array($tag['id'], $recipe['tags'])) ? 'checked': ''; ?>>
                                            <label for="tag-<?= $tag['id'] ?>" class="form-check-label"><?= $tag['name'] ?></label>
                                        </div>
                                    </div>
                                <?php endforeach;
                            endif; ?>
                        </div>
                    </div>
                    <!--END: MOTS CLÉS -->
                    <!--START: ÉTAPES -->
                    <div class="tab-pane fade" id="step-tab-pane" role="tabpanel">
                        <div class="mb-3">
                            <span class="btn btn-primary" id="add-step">
                                <i class="fas fa-plus"></i> Ajouter une étape
                            </span>
                        </div>
                        <div class="accordion" id="zone-steps">
                            <?php if (isset($recipe['steps'])) : ?>
                                <?php foreach($recipe['steps'] as $step) : ?>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header d-flex">
                                            <i class="fas fa-arrows-up-down fa-2xs sort-handle align-self-center p-3"></i>
                                            <button class="accordion-button flex-fill collapsed" data-bs-toggle="collapse" data-bs-target="#step-<?= $step['order']; ?>" type="button">
                                                Étape #<?= $step['order']; ?>
                                            </button>
                                        </h2>
                                        <div id="step-<?= $step['order']; ?>" class="accordion-collapse collapse" data-bs-parent="#zone-steps">
                                            <div class="accordion-body">
                                                <input type="hidden" value="<?= $step['id']; ?>" name="steps[<?= $step['order']; ?>][id]">
                                                <textarea class="form-control" id="steptextarea-step-<?= $step['order']; ?>" name='steps[<?= $step['order']; ?>][description]'><?= $step['description'] ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!--END: ÉTAPES -->
                    <?php if(isset($recipe)) : ?>
                        <!--START: COMMENTAIRES -->
                        <div class="tab-pane fade" id="opinion-tab-pane" role="tabpanel">
                            COMMENTAIRES
                        </div>
                        <!--END: COMMENTAIRES -->
                        <!--START: FAVORIS -->
                        <div class="tab-pane fade" id="fav-tab-pane" role="tabpanel">
                            FAVORIS
                        </div>
                        <!--END: FAVORIS -->
                    <?php endif; ?>
                </div>
                <!--END: TABS-PANE -->
            </div>
        </div>
    </div>
    <!--END: COLONNE PRINCIPALE -->
    <!--START: COLONNE ACTIONS -->
    <div class="col-md-2">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary">Valider</button>
                </div>
                <?php if (isset($recipe)) :  ?>
                    <div class="ms-2 mb-3">
                        <div>
                            <span class="fw-bold">Créée le: </span><?= $recipe['created_at'] ?>
                        </div>
                        <div>
                            <span class="fw-bold">Modifiée le: </span><?= $recipe['updated_at'] ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div>
                    <?php
                    if(isset($recipe['user'])){
                        $id = $recipe['user']->id;
                        $username = $recipe['user']->username;
                    } else {
                        $session = session();
                        $id = $session->user->id;
                        $username = $session->user->username;
                    }
                    ?>
                    <label for="id_user" class="form-label">Créateur</label>
                    <select class="form-select" id="id_user" name="id_user">
                        <option value="<?= $id ?>" selected><?= $username ?></option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <!--END: COLONNE ACTIONS -->
</div>
<?php echo form_close(); ?>
<script>
    $(document).ready(function () {
        //Activation de TinyMCE
        initTinymce("#description");
        initTinymce("#zone-steps textarea");

        //Compteur pour nos ingrédients
        let cpt_ing = $('#zone-ingredients .row-ingredient').length;
        //Compteur pour nos étapes
        let cpt_step = $('#zone-steps .accordion-item').length;
        //url pour les requetes Ajax
        baseUrl = "<?= base_url(); ?>";

        //Action du clique sur l'ajout d'un ingrédient
        $('#add-ingredient').on('click', function () {
            cpt_ing++; //augmente le compteur de 1
            $('#badge-ingredient').html(parseInt($('#badge-ingredient').html())+1);
            let row = `
                <div class="row mb-3 row-ingredient">
                    <div class="col">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-trash-alt text-danger supp-ingredient"></i>
                            </span>
                            <select class="form-select flex-fill select-ingredient" name="ingredients[${cpt_ing}][id_ingredient]">
                            </select>
                            <input class="form-control flex-fill" type="number" min="0.1" step="0.1" name="ingredients[${cpt_ing}][quantity]" placeholder="Quantité">
                            <select class="form-select flex-fill select-unit" name="ingredients[${cpt_ing}][id_unit]">
                            </select>
                        </div>
                    </div>
                </div>
            `;
            $('#zone-ingredients').append(row);
            initAjaxSelect2('#zone-ingredients .row-ingredient:last-child .select-ingredient', {
                url: baseUrl + 'admin/ingredient/search',
                placeholder: 'Rechercher un ingrédient...',
                searchFields: 'name,description',
                showDescription: true,
                delay: 250
            });
            initAjaxSelect2('#zone-ingredients .row-ingredient:last-child .select-unit', {
                url: baseUrl + 'admin/unit/search',
                placeholder: 'Rechercher une unité...',
                searchFields: 'name',
                delay: 250
            });
        });
        //Action du bouton de suppression des ingrédients
        $('#zone-ingredients').on('click','.supp-ingredient',function() {
            $(this).closest('.row-ingredient').remove();
            $('#badge-ingredient').html(parseInt($('#badge-ingredient').html())-1);
        });
        //Action du bouton de suppression des étapes
        $('#zone-steps').on('click', '.supp-step', function() {
            $(this).closest('.accordion-item').remove();
            $('#badge-step').html(parseInt($('#badge-step').html())-1);
        })
        //Action du clique sur l'ajout d'une étape
        $('#add-step').on('click', function() {
            cpt_step++;
            $('#badge-step').html(parseInt($('#badge-step').html())+1);
            $("#zone-steps .accordion-button").addClass('collapsed');
            $("#zone-steps .show").removeClass('show');
            let step = `
            <div class="accordion-item">
                <h2 class="accordion-header d-flex">
                  <i class="fas fa-arrows-up-down fa-2xs sort-handle align-self-center p-3"></i>
                  <button class="accordion-button flex-fill" data-bs-toggle="collapse" data-bs-target="#step-${cpt_step}" type="button">
                    Étape #${cpt_step}
                  </button>
                </h2>
                <div id="step-${cpt_step}" class="accordion-collapse collapse show" data-bs-parent="#zone-steps">
                  <div class="accordion-body">
                    <textarea class="form-control" id="steptextarea-step-${cpt_step}" name='steps[${cpt_step}][description]'></textarea>
                  </div>
                </div>
              </div>
            `;
            $('#zone-steps').append(step);
            initTinymce("#steptextarea-step-"+cpt_step);
        });
        //Action de la recherche de mot clés
        $('#search-tag').on('input', function() {
            let search = $(this).val().toLowerCase();
            $('.tag').each(function () {
                let tagText = $(this).find('label').text().toLowerCase();
                if(tagText.includes(search)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
        //Action sur la selection d'un mot clés
        $('.tag .form-check-input').on('change', function() {
            let badge = $('#badge-tag');
            if($(this).is(':checked')) {
                badge.html(parseInt(badge.html()) + 1);
            } else {
                badge.html(parseInt(badge.html()) - 1);
            }
        });
        //Ajout de SELECT2 à notre select user
        initAjaxSelect2('#id_user', {
            url: baseUrl + 'admin/user/search',
            placeholder: 'Rechercher un utilisateur...',
            searchFields: 'username',
            delay: 250
        });
        //Initialisation dés le départ de nos Select pour ingrédient
        initAjaxSelect2('#zone-ingredients .select-ingredient', {
            url: baseUrl + 'admin/ingredient/search',
            placeholder: 'Rechercher un ingrédient...',
            searchFields: 'name,description',
            showDescription: true,
            delay: 250
        });
        initAjaxSelect2('#zone-ingredients .select-unit', {
            url: baseUrl + 'admin/unit/search',
            placeholder: 'Rechercher une unité...',
            searchFields: 'name',
            delay: 250
        });
        // Rendre les étapes réordonnables
        $('#zone-steps').sortable({
            handle: '.sort-handle',
            placeholder: 'ui-state-highlight',
            cursor: 'move',
            opacity: 0.8,
            axis: 'y',
            containment: '#zone-steps',
            tolerance: 'pointer',

            helper: function(e, ui) {
                $("#zone-steps .accordion-collapse.show").removeClass('show');
                $("#zone-steps .accordion-button").addClass('collapsed');

                // Helper minimal pour éviter les conflits
                let $helper = $('<div class="accordion-item" style="background: #f8f9fa; border: 2px dashed #007bff;"><div style="padding: 15px;">Déplacement de l\'étape...</div></div>');
                return $helper;
            },

            // Tout se passe après le stop pour éviter les perturbations
            stop: function(event, ui) {
                // Sauvegarder TinyMCE avant manipulations
                $('#zone-steps textarea').each(function() {
                    let textareaId = $(this).attr('id');
                    let editor = tinymce.get(textareaId);
                    if (editor) {
                        editor.save();
                        editor.destroy();
                    }
                });

                // Réorganiser les éléments
                let $items = $('#zone-steps .accordion-item');
                $items.each(function(index) {
                    let $item = $(this);
                    let newIndex = index + 1;
                    let $button = $item.find('.accordion-button');
                    let $collapse = $item.find('.accordion-collapse');
                    let $textarea = $item.find('textarea');

                    $button.text('Étape #' + newIndex);

                    let newId = 'step-' + newIndex;
                    let newTextareaId = 'steptextarea-step-' + newIndex;

                    $collapse.attr('id', newId);
                    $button.attr('data-bs-target', '#' + newId);
                    $textarea.attr('name', 'steps[' + newIndex + '][description]');
                    $textarea.attr('id', newTextareaId);
                });

                // Rouvrir l'accordéon déplacé
                let $button = ui.item.find('.accordion-button');
                let $collapse = ui.item.find('.accordion-collapse');
                $button.removeClass('collapsed');
                $collapse.addClass('show');

                // Réinitialiser TinyMCE
                setTimeout(() => {
                    initTinymce("#zone-steps textarea");
                }, 200);
            }
        });


    });
</script>
<style>
    .sort-handle {
        cursor: move !important;
        color: #6c757d;
        transition: color 0.3s;
        display: flex;
        align-items: center;
    }

    .sort-handle:hover {
        color: #007bff !important;
    }


    .ui-sortable-helper {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 1000;
        background: white;
        border-radius: 0.375rem;
    }

    .ui-sortable-placeholder {
        background-color: rgba(88, 86, 214, 0.2) !important;
        border: 2px dashed #4b49b6 !important;
        margin-bottom: 1rem;
        border-radius: 0.375rem;
        visibility: visible !important;
        height: 60px;
    }

    .ui-sortable-placeholder * {
        visibility: hidden;
    }

    #zone-steps {
        min-height: 50px;
    }

    /* Animation pour le drag */
    .ui-sortable-helper .accordion-collapse {
        display: none !important;
    }
</style>