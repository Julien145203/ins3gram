<?php
if (!isset($recipe)):
    echo form_open('/admin/recipe/insert');
else:
    echo form_open('/admin/recipe/update');?>
    <input type="hidden" name="id_recipe" value="<?= $recipe['id'];?>">
<?php
endif;
?>
<div class="row mb-3">
    <div class="col">
       <div class="card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div class="flex-fill me-3">
                    <input type="text" class="form-control" id="name" placeholder="Nom de la recette" name="name" value="<?= isset($recipe)?$recipe['name']:'';?>" required>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="switchActif" name="active">
                    <label class="form-check-label" for="switchCheckDefault"> Active </label>
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
                        <a href="#" class="nav-link active" data-bs-toggle="tab" data-bs-target="#general-tab-pane"
                         >Général</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link " data-bs-toggle="tab" data-bs-target="#ingredient-tab-pane"
                        >Ingredients</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link " data-bs-toggle="tab" data-bs-target="#keyword-tab-pane"
                        >Mots clés</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link " data-bs-toggle="tab" data-bs-target="#step-tab-pane"
                        >Etapes</a>
                    </li>
                    <?php if (isset($recipe)): ?>
                    <li class="nav-item">
                        <a href="#" class="nav-link " data-bs-toggle="tab" data-bs-target="#comment-tab-pane"
                        >Commentaires</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link " data-bs-toggle="tab" data-bs-target="#fav-tab-pane"
                        >Favoris</a>
                    </li>
                    <?php endif; ?>
                </ul>
                <!--END: TABS-LINKS -->
                <!--START: TABS-PANE -->
                <div class="tab-content p-3 border border-1 border-top-0 rounded rounded-top-0 " id="tabsRecipeContent">
                    <!--START: GENERAL -->
                    <div class="tab-pane fade show active" id="general-tab-pane" role="tabpanel">
                        <div class="mb-3">
                            <label class="form-label">Description :</label>
                            <textarea class="form-control" id="description" rows="3"  name="description" placeholder="Description globale de la recette :"></textarea>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="switchActif" name="alcool" checked>
                            <label class="form-check-label" for="switchCheckDefault"> Active </label>
                        </div>
                    </div>
                    <!--END: GENERAL -->

                    <!--START: INGREDIENT-->
                    <div class="tab-pane fade" id="ingredient-tab-pane" role="tabpanel">
                        <div class="mb-3">
                            <span class="btn btn-primary" id="add-ingredient">Ajouter un ingrédient</span>
                        </div>
                        <div id="zone-ingredients">

                        </div>
                    </div>
                    <!--END: INGREDIENT-->
                    <!--START: MOTS CLES-->
                    <div class="tab-pane fade " id="keyword-tab-pane" role="tabpanel">
                        MOTS CLES
                    </div>
                    <!--END:MOTS CLES-->
                    <!--START: ETAPES-->
                        <div class="tab-pane fade " id="step-tab-pane" role="tabpanel">
                            ETAPES
                        </div>
                    <!--END:ETAPES-->
                    <?php if (isset($recipe)): ?>
                    <!--START: COMMENTAIRES-->
                    <div class="tab-pane fade " id="comment-tab-pane" role="tabpanel">
                        COMMENTAIRES
                    </div>
                    <!--END:COMMENTAIRES-->
                    <!--START: FAVORIS-->
                        <div class="tab-pane fade " id="fav-tab-pane" role="tabpanel">
                             FAVORIS
                        </div>
                    <!--END:FAVORIS-->
                    <?php endif; ?>
                </div>
                <!--END: TABS-PANE -->
            </div>
        </div>
    </div>
    <!--END: COLONNE PRINCIPALE-->
    <!--START: COLONNE ACTIONS -->
    <div class="col">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary">Valider</button>
                </div>
                <?php if (isset($recipe)): ?>
                <div class="ms-2">
                    <div><span class="fw-bold">Crée le:</span></div>
                    <div><span class="fw-bold">Modifiée le:</span></div>
                </div>
                <?php endif; ?>
                <div>
                    <label class="form-label">Créateur</label>
                    <select class="form-select" id="id_user" name="id_user">
                        <?php foreach ($users as $u): ?>
                        <option value="<?= $u ->id ?>">
                             <?= $u ->username ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <!--END: COLONNE ACTIONS -->
</div>
<?php echo form_close(); ?>
<script>
    $(document).ready(function(){
        //Activation de TinyMCE pour la description
        tinymce.init({
            selector: '#description',
            height : "200",
            language: 'fr_FR',
            menubar: false,
            plugins: [
                'preview', 'code', 'fullscreen','wordcount', 'link','lists',
            ],
            skin: 'oxide',
            content_encoding: 'text',
            toolbar: 'undo redo | formatselect | ' +
                'bold italic link forecolor backcolor removeformat | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +' fullscreen  preview code'
        });
        let cpt_ing = 0
        $('#add-ingredient').on('click',function(){
            cpt_ing++;
            let row = `
            <div class="row mb-3 row-ingredient">
                    <div class="col-md-1 text-center">
                        <i class="fas fa-trash-alt text-danger supp-ingredient" style="position: relative; top: 6px;" ></i>
                    </div>
                    <div class="col">
                      <select class="form-select select-ingredient" name="ingredient[${cpt_ing}][id_ingredient]">
                        <option value="">A</option>
                        <option value="">B</option>
                      </select>
                </div>
                <div class="col">
                    <input class="form-control" type="number" min="0.1" step="0.1" name='ingredient[${cpt_ing}]quantity]'>
                </div>
                <div class="col">
                    <select class="form-select select-unit" name="ingredient[${cpt_ing}][id_unit]>
                        <option value="">A</option>
                        <option value="">B</option>
                      </select>
                </div>
            </div>
            `;
        $('#zone-ingredients').append(row)
            $('.select-ingredient').select2();
            $('select-unit').select2();
        })
        //Action du bouton de suppression
        $('#zone-ingredients').on('click','.supp-ingredient',function(){
            $(this).closest('.row-ingredient').remove('')
        })
        //Ajout de SELECT2 à notre select user
        $('#id_user').select2();
    })
</script>