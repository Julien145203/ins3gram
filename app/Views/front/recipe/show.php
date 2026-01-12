<?php
// Image principale avec overlay et titre
?>
<div class="row mb-4">
    <div class="col">
        <div class="position-relative recipe-hero">
            <?php if(isset($recipe['mea']) && $recipe['mea'] !== null) : ?>
                <img src="<?= $recipe['mea']->getUrl(); ?>"
                     class="img-fluid w-100 recipe-img-mea"
                     alt="<?= $recipe['mea']->alt ?? esc($recipe['name']) ?>"
                     style="max-height: 500px; object-fit: cover; border-radius: 10px;">
            <?php else: ?>
                <img src="<?= base_url('assets/img/no-img.png'); ?>"
                     class="img-fluid w-100"
                     alt="Pas d'image"
                     style="max-height: 500px; object-fit: cover; border-radius: 10px;">
            <?php endif; ?>
            <div class="position-absolute top-0 start-0 bg-dark w-100 h-100 opacity-50" style="border-radius: 10px;"></div>
            <div class="position-absolute top-50 start-50 translate-middle text-white text-center w-100">
                <h1 class="display-4 fw-bold mb-3"><?= isset($recipe['name']) ? esc($recipe['name']) : ''; ?></h1>
                <p class="lead mb-0">
                    <i class="fas fa-user-circle me-2"></i>
                    Par <?= isset($recipe['user']) ? esc($recipe['user']->username) : 'Inconnu'; ?>
                </p>
                <?php if(isset($recipe['alcool'])): ?>
                    <span class="badge <?= $recipe['alcool'] ? 'bg-danger' : 'bg-success' ?> px-3 py-2 mt-2">
                        <i class="fas <?= $recipe['alcool'] ? 'fa-wine-glass' : 'fa-leaf' ?> me-2"></i>
                        <?= $recipe['alcool'] ? 'Avec Alcool' : 'Sans Alcool' ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php // Barre d'actions : notation, favoris, partage ?>
<div class="row mb-4">
    <div class="col-md-4 text-center">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="fas fa-star text-warning me-2"></i>Noter cette recette
                </h5>
                <div data-value="<?= isset($recipe['user_score']) ? $recipe['user_score'] : 0 ?>" id="scoreOpinion" class="mb-2">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <i data-value="<?= $i ?>" class="<?= (isset($recipe['user_score']) && $i <= $recipe['user_score']) ? 'fas' : 'far' ?> fa-star fa-2xl"></i>
                    <?php endfor; ?>
                </div>
                <small class="text-muted">
                    Moyenne: <?= isset($recipe['avg_score']) ? number_format($recipe['avg_score'], 1) : '0' ?>/5
                    (<?= isset($recipe['total_scores']) ? $recipe['total_scores'] : 0 ?> avis)
                </small>
            </div>
        </div>
    </div>

    <div class="col-md-4 text-center">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="fas fa-heart text-danger me-2"></i>Favoris
                </h5>
                <div id="favorite" data-value="<?= ($session_user != null && $session_user->hasFavorite($recipe['id'])) ? '1' : '0' ?>">
                    <?php
                    if(($session_user != null) && $session_user->hasFavorite($recipe['id'])) :
                        $text_favorite = 'Supprimer de mes favoris';
                        $class_favorite = 'fas';
                    else :
                        $text_favorite = 'Ajouter à mes favoris';
                        $class_favorite = 'far';
                    endif;
                    ?>
                    <div id="heart" class="cursor-pointer" data-bs-toggle="tooltip" title="<?= $text_favorite ?>">
                        <i class="<?= $class_favorite ?> fa-heart fa-3x text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 text-center">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="fas fa-share-nodes me-2"></i>Partager
                </h5>
                <div class="d-flex justify-content-center gap-3">
                    <?= social_share_links(current_url(), $recipe['name'] . ' - Ins3gram', ['facebook', 'twitter', 'whatsapp']); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php // Tags ?>
<?php if(!empty($tags)): ?>
    <div class="row mb-4">
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-tags me-2"></i>Catégories
                    </h5>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach($tags as $tag): ?>
                            <a href="<?= base_url('recette?tag=' . urlencode($tag['name'])) ?>"
                               class="badge bg-primary text-decoration-none px-3 py-2">
                                <i class="fas fa-hashtag me-1"></i><?= esc($tag['name']) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php // Description et galerie ?>
<div class="row mb-4">
    <?php if(!empty($recipe['images'])): ?>
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-images me-2"></i>Galerie Photos</h5>
                </div>
                <div class="card-body">
                    <div id="main-slider" class="splide mb-3">
                        <div class="splide__track">
                            <ul class="splide__list">
                                <?php foreach($recipe['images'] as $image): ?>
                                    <li class="splide__slide">
                                        <a href="<?= $image->getUrl(); ?>" data-lightbox="recipe-gallery">
                                            <img class="img-fluid rounded"
                                                 src="<?= $image->getUrl(); ?>"
                                                 alt="<?= $image->alt ?? esc($recipe['name']) ?>"
                                                 style="max-height: 400px; width: 100%; object-fit: cover;">
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>

                    <div id="thumbnail-slider" class="splide">
                        <div class="splide__track">
                            <ul class="splide__list">
                                <?php foreach($recipe['images'] as $image): ?>
                                    <li class="splide__slide">
                                        <img class="img-thumbnail rounded cursor-pointer"
                                             src="<?= $image->getUrl(); ?>"
                                             alt="Miniature"
                                             style="height: 80px; object-fit: cover;">
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="<?= !empty($recipe['images']) ? 'col-lg-6' : 'col-12' ?>">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Description</h5>
            </div>
            <div class="card-body">
                <div class="recipe-description">
                    <?= $recipe['description'] ?? '<p class="text-muted">Aucune description disponible.</p>'; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php // Ingrédients ?>
<?php if(!empty($ingredients)): ?>
    <div class="row mb-4">
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-list-ul me-2"></i>Ingrédients</h5>
                </div>
                <div class="card-body">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                        <?php foreach($ingredients as $ingredient): ?>
                            <div class="col">
                                <div class="d-flex align-items-center p-3 border rounded bg-light h-100">
                                    <?php if(!empty($ingredient['image'])): ?>
                                        <img src="<?= base_url($ingredient['image']) ?>"
                                             alt="<?= esc($ingredient['ingredient']) ?>"
                                             class="rounded me-3"
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center"
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-utensils text-white"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="flex-grow-1">
                                        <strong class="d-block"><?= esc($ingredient['ingredient']) ?></strong>
                                        <small class="text-muted">
                                            <?= esc($ingredient['quantity']) ?> <?= esc($ingredient['unit']) ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php // Étapes ?>
<?php if(!empty($steps)): ?>
    <div class="row mb-4">
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Préparation</h5>
                </div>
                <div class="card-body">
                    <?php foreach($steps as $index => $step): ?>
                        <div class="step-item mb-4 pb-4 <?= $index < count($steps) - 1 ? 'border-bottom' : '' ?>">
                            <div class="d-flex align-items-start">
                                <div class="step-number bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center me-3"
                                     style="width: 50px; height: 50px; min-width: 50px;">
                                    <strong class="fs-5"><?= $step['order'] ?></strong>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-2">Étape <?= $step['order'] ?></h6>
                                    <p class="mb-0"><?= nl2br(esc($step['description'])) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
    $(document).ready(function () {
        // Initialisation des tooltips Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Initialisation du slider principal et des miniatures (si images présentes)
        <?php if(!empty($recipe['images']) && count($recipe['images']) > 1): ?>
        var main = new Splide('#main-slider', {
            type: 'fade',
            pagination: false,
            arrows: true,
            heightRatio: 0.5,
        });

        var thumbnails = new Splide('#thumbnail-slider', {
            rewind: true,
            fixedWidth: 80,
            fixedHeight: 80,
            isNavigation: true,
            gap: 10,
            focus: 'center',
            pagination: false,
            cover: true,
            breakpoints: {
                640: {
                    fixedWidth: 60,
                    fixedHeight: 60,
                },
            },
        });

        main.sync(thumbnails);
        main.mount();
        thumbnails.mount();
        <?php endif; ?>

        // Gestion du système de notation
        $('#scoreOpinion').on('mouseenter', '.fa-star', function(){
            var opinion_score = $(this).data('value');
            $('.fa-star').each(function() {
                if ($(this).data('value') <= opinion_score) {
                    $(this).removeClass('far').addClass('fas');
                } else {
                    $(this).removeClass('fas').addClass('far');
                }
            });
        });

        $('#scoreOpinion').on('mouseleave', function(){
            var current_score = $(this).data('value');
            $('.fa-star').each(function() {
                if ($(this).data('value') <= current_score) {
                    $(this).removeClass('far').addClass('fas');
                } else {
                    $(this).removeClass('fas').addClass('far');
                }
            });
        });

        $('#scoreOpinion').on('click', function(){
            <?php if ($session_user != null) : ?>
            var score = $('#scoreOpinion .fa-star:hover').data('value');
            if (!score) {
                // Si aucune étoile n'est survolée, prendre la dernière cliquée
                score = $(this).data('value');
            }
            var name = $('h1').first().text();

            Swal.fire({
                title: "Validation",
                text: "Êtes-vous sûr de vouloir mettre " + score + " étoile(s) à " + name + " ?",
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Oui !",
                cancelButtonText: "Non !"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "<?= base_url('api/recipe/score'); ?>",
                        type: "POST",
                        data: {
                            'score': score,
                            'id_recipe': '<?= $recipe['id']; ?>',
                            'id_user': '<?= $session_user->id ?? ""; ?>',
                        },
                        success: function(response) {
                            $('#scoreOpinion').data('value', score);
                            Swal.fire({
                                title: 'Merci !',
                                text: 'Votre note a été enregistrée',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Erreur',
                                text: 'Une erreur est survenue',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
            <?php else : ?>
            swalConnexion();
            <?php endif; ?>
        });

        // Gestion des favoris
        $('#favorite').on('click', '#heart', function(){
            <?php if ($session_user != null) : ?>
            $.ajax({
                url: '<?= base_url('api/recipe/favorite'); ?>',
                type: 'POST',
                data: {
                    id_user: '<?= $session_user->id ?? ""; ?>',
                    id_recipe: '<?= $recipe['id']; ?>',
                },
                success: function(response) {
                    const tooltip = bootstrap.Tooltip.getInstance('#heart');
                    if(response.type == 'delete') {
                        if(tooltip) tooltip.setContent({ '.tooltip-inner': 'Ajouter à mes favoris' });
                        $('#favorite .fa-heart').removeClass('fas').addClass('far');
                        $('#favorite').data('value', '0');
                    } else {
                        if(tooltip) tooltip.setContent({ '.tooltip-inner': 'Supprimer de mes favoris' });
                        $('#favorite .fa-heart').removeClass('far').addClass('fas');
                        $('#favorite').data('value', '1');
                    }
                }
            });
            <?php else : ?>
            swalConnexion();
            <?php endif; ?>
        });

        // Fonction pour demander la connexion
        function swalConnexion() {
            Swal.fire({
                title: "Vous n'êtes pas connecté(e) !",
                text: "Veuillez vous connecter ou vous inscrire.",
                icon: "warning",
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: "S'inscrire",
                denyButtonText: 'Se connecter',
                cancelButtonText: "Revenir à la recette",
                confirmButtonColor: "var(--bs-primary)",
                denyButtonColor: "var(--bs-success)",
                cancelButtonColor: "var(--bs-secondary)",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?= base_url('register'); ?>";
                } else if (result.isDenied) {
                    window.location.href = "<?= base_url('sign-in'); ?>";
                }
            });
        }
    });
</script>

<style>
    .recipe-hero {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .fa-star {
        color: var(--bs-warning);
        cursor: pointer;
        transition: transform 0.2s;
    }

    .fa-star:hover {
        transform: scale(1.2);
    }

    .fa-heart {
        transition: transform 0.2s;
    }

    .fa-heart:hover {
        transform: scale(1.1);
    }

    #heart {
        cursor: pointer;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .step-number {
        font-weight: bold;
    }

    .recipe-description {
        font-size: 1.1rem;
        line-height: 1.8;
    }

    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.15) !important;
    }

    .social-share-links a {
        transition: transform 0.2s;
        display: inline-block;
    }

    .social-share-links a:hover {
        transform: scale(1.2);
    }
</style>