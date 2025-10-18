<div class="row">
    <div class="col text-center">
        <h1>Liste des recettes</h1>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="d-flex align-items-center justify-content-end">
            <div class="ms-2">Trier par </div>
            <div class="ms-2">
                <select name="sort" class="form-select" onchange="window.location.href=this.value" >
                    <option value="<?= build_filter_url(['sort' => 'name_asc']) ?>" <?= is_filter_active('sort', 'name_asc') ? 'selected' : '' ?>>Nom (A-Z)</option>
                    <option  value="<?= build_filter_url(['sort' => 'name_desc']) ?>" <?= is_filter_active('sort', 'name_desc') ? 'selected' : '' ?>>Nom (Z-A)</option>
                    <option  value="<?= build_filter_url(['sort' => 'score_desc']) ?>" <?= is_filter_active('sort', 'score_desc') ? 'selected' : '' ?>>Meilleure note</option>
                </select>
            </div>

            <div class="ms-2 btn-group">
                <div class="btn-group">
                    <a href="<?= build_filter_url(['per_page' => 8]) ?>"
                       class="btn <?= is_filter_active('per_page', 8) || ($per_page == 8) ? 'btn-primary' : 'btn-secondary' ?>">8</a>
                    <a href="<?= build_filter_url(['per_page' => 16]) ?>"
                       class="btn <?= is_filter_active('per_page', 16)|| ($per_page == 16) ? 'btn-primary' : 'btn-secondary' ?>">16</a>
                    <a href="<?= build_filter_url(['per_page' => 24]) ?>"
                       class="btn <?= is_filter_active('per_page', 24)|| ($per_page == 24) ? 'btn-primary' : 'btn-secondary' ?>">24</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!--START: PAGE -->
<div class="row">
    <!--START: FILTRE -->
    <div class="col-lg-3 ">
        <!--START: FILTRE ACTIF -->
        <?php
        if(!empty(array_diff_key($_GET, array_flip(['page', 'per_page', 'sort'])))) { ?>
            <div class="card mt-4">
                <div class="card-header">
                    <span class="h5">Filtres Actifs</span>
                </div>
                <div class="card-body">
                    <?php
                    foreach($_GET as $key => $value):
                        if( $key !== "per_page" && $key !== "page" && $key !== "sort"):
                            switch ($key) :
                                case 'alcool' :
                                    ?>
                                    <a class="btn btn-sm btn-primary mb-1" href="<?= build_filter_url([],true,null,[$key]);?>">
                                        <?= $value == '0' ? 'Sans Alcool' : "Avec Alcool"; ?> <i class="fas fa-xmark"></i>
                                    </a>
                                    <?php
                                    break;
                                case 'ingredients':
                                    //Nettoie les doublons dans la liste des ingrédients
                                    $value = array_unique($value);
                                    foreach($value as $key2 => $ing) : ?>
                                        <a class="btn btn-sm btn-primary mb-1" href="<?= build_filter_url([],true,null,['ingredients' => [$key2]]);?>">
                                            <?= Model('IngredientModel')->select("name")->where('id', $ing)->first()['name'] ?? "???" ?> <i class="fas fa-xmark"></i>
                                        </a>
                                    <?php
                                    endforeach;
                                    break;
                                default:
                                    ?>
                                    <a class="btn btn-sm btn-primary mb-1" href="<?= build_filter_url([],true,null,[$key]);?>">
                                        <?= $key ?> <i class="fas fa-xmark"></i>
                                    </a>
                                    <?php
                                    break;
                            endswitch;
                        endif;
                    endforeach; ?>
                </div>
            </div>
            <?php
        }

        ?>
        <!--END: FILTRE ACTIF -->
        <div class="card mt-4">
            <div class="card-header">
                <span class="h5">FILTRES</span>
            </div>
            <div class="card-body">
                <?php echo form_open(build_filter_url(), ['method' => 'get'], $_GET); ?>
                <?php if (!is_filter_active('alcool', ['0','1'])) : ?>
                    <div class="btn-group mb-2">
                        <input type="radio" class="btn-check" name="alcool" value="1" id="alcool-r-1" autocomplete="off">
                        <label for="alcool-r-1" class="btn btn-sm btn-outline-primary">Avec Alcool</label>
                        <input type="radio" class="btn-check" name="alcool" value="0" id="alcool-r-2" autocomplete="off">
                        <label for="alcool-r-2" class="btn btn-sm btn-outline-primary">Sans Alcool</label>
                    </div>
                    <hr>
                <?php endif; ?>
                <div class="my-2">
                    <span class="h6">Filtrer par ingrédients</span>
                </div>
                <div id="zone-ingredients">
                </div>
                <div class="mb-3">
                    <span class="btn btn-primary" id="add-ingredient">
                        <i class="fas fa-plus"></i> Ajouter un ingrédient
                    </span>
                </div>
                <div class="mb-3">
                    <div class="row">
                        <div class="col-12">
                            Note
                        </div>
                        <div class="col-md-6">
                            <input type="range" class="form-range" min="0" max="5" value="3" id="rangeOpinion">
                        </div>
                        <div class="col-md-6">
                            <output for="range4" id="rangeOpinionValue" aria-hidden="true"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></output>
                        </div>
                        <div class="col-12">
                            <div data-value="1" id="scoreOpinion">
                                <i data-value="1" class="fas fa-xl fa-star"></i>
                                <i data-value="2" class="far fa-xl fa-star"></i>
                                <i data-value="3" class="far fa-xl fa-star"></i>
                                <i data-value="4" class="far fa-xl fa-star"></i>
                                <i data-value="5" class="far fa-xl fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-grid">
                <button type="submit" class="btn btn-primary">Filtrer</button>
            </div>
            <?php echo form_close(); ?>

        </div>



    </div>
    <!--END: FILTRE -->
    <!--START: CONTENUS -->
    <div class="col p-4">
        <!--START: RECETTES -->
        <div class="row row-cols-2 row-cols-lg-4 all-recipes">
            <?php foreach ($recipes as $recipe): ?>
                <div class="col mb-4">
                    <div class="card ls-recipe h-100">
                        <div class="position-relative">
                            <div class="ribbon position-absolute text-bg-<?= (isset($recipe['alcool']) && $recipe['alcool'] == '1' ) ? "danger" : "primary";  ?> px-2 shadow">
                                <?= (isset($recipe['alcool']) && $recipe['alcool'] == '1' ) ? "Alcool" : "Sans Alcool";  ?>
                            </div>
                            <a href="<?= base_url('recette/'.$recipe['slug']); ?>">
                                <img class="card-img-top img-fluid" src="<?= base_url($recipe['mea']);?>">
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="card-title h5">
                                <?= $recipe['name']; ?>
                            </div>
                            <div class="mb-2">
                                <?php
                                for($i = 0; $i < 5; $i++) {
                                    if ($i< $recipe['score']) {
                                        echo '<i class="fas fa-star"></i>';
                                    } else {
                                        echo '<i class="far fa-star"></i>';
                                    }
                                }
                                ?>
                            </div>
                            <div class="d-grid">
                                <a href="<?= base_url('recette/'.$recipe['slug']); ?>" class="btn btn-primary"><i class="fas fa-eye"></i> Voir la recette</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <!--END: RECETTES -->
        <!--START: PAGINATION -->
        <div class="row">
            <div class="col">
                <?php if ($pager): ?>
                    <div class="d-flex justify-content-center">
                        <?= $pager->links('default', 'bootstrap_full') ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
        <!--END: PAGINATION -->
    </div>
    <!--END: CONTENUS -->
</div>
<!--END: PAGE -->
<script>
    $(document).ready(function () {
        baseUrl = "<?= base_url(); ?>";
        //Évènement sur le range picker
        $('#rangeOpinion').on('input', function () {
            var stars = '';
            for(i = 0; i < this.value; i++) {
                stars += '<i class="fas fa-star"></i>';
            }
            for(i = this.value; i < 5; i++) {
                stars += '<i class="far fa-star"></i>';
            }
            $("#rangeOpinionValue").html(stars);
        });

        $('#add-ingredient').on('click', function () {
            let row = `
                <div class="row mb-3 row-ingredient">
                    <div class="col">
                        <div class="input-group">
                            <select class="form-select flex-fill select-ingredient" name="ingredients[]">
                            </select>
                        </div>
                    </div>
                </div>
            `;
            $('#zone-ingredients').append(row);
            initAjaxSelect2('#zone-ingredients .row-ingredient:last-child .select-ingredient', {
                url: baseUrl + 'api/ingredient/all',
                placeholder: 'Rechercher un ingrédient...',
                searchFields: 'name',
                showDescription: false,
                delay: 250
            });
        });
    })
</script>