<div class="container py-4">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-1">
                        <i class="fas fa-heart text-danger me-2"></i>
                        Mes Favoris
                    </h1>
                    <p class="text-muted">
                        <?php if ($totalFavorites > 0): ?>
                            Vous avez <strong><?= $totalFavorites ?></strong> recette<?= $totalFavorites > 1 ? 's' : '' ?> en favoris
                        <?php else: ?>
                            Vous n'avez pas encore de recettes favorites
                        <?php endif; ?>
                    </p>
                </div>
                <a href="<?= base_url('recette') ?>" class="btn btn-outline-primary">
                    <i class="fas fa-search me-1"></i>
                    Découvrir des recettes
                </a>
            </div>
        </div>
    </div>

    <?php if (empty($favorites)): ?>
        <!-- Message quand aucun favori -->
        <div class="row">
            <div class="col text-center py-5">
                <div class="mb-4">
                    <i class="far fa-heart fa-5x text-muted"></i>
                </div>
                <h3 class="text-muted mb-3">Aucun favori pour le moment</h3>
                <p class="text-muted mb-4">
                    Explorez nos recettes et ajoutez vos préférées à vos favoris !
                </p>
                <a href="<?= base_url('recette') ?>" class="btn btn-primary btn-lg">
                    <i class="fas fa-compass me-2"></i>
                    Explorer les recettes
                </a>
            </div>
        </div>
    <?php else: ?>
        <!-- Liste des favoris -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
            <?php foreach ($favorites as $recipe): ?>
                <div class="col" data-recipe-id="<?= $recipe['id'] ?>">
                    <div class="card h-100 shadow-sm recipe-card">
                        <!-- Image -->
                        <div class="position-relative">
                            <?php if (!empty($recipe['mea'])): ?>
                                <img src="<?= base_url($recipe['mea']) ?>"
                                     class="card-img-top recipe-img"
                                     alt="<?= esc($recipe['name']) ?>"
                                     style="height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-secondary d-flex align-items-center justify-content-center"
                                     style="height: 200px;">
                                    <i class="fas fa-image fa-3x text-white-50"></i>
                                </div>
                            <?php endif; ?>

                            <!-- Badge alcool -->
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge <?= $recipe['alcool'] ? 'bg-danger' : 'bg-success' ?>">
                                    <?= $recipe['alcool'] ? 'Avec alcool' : 'Sans alcool' ?>
                                </span>
                            </div>

                            <!-- Bouton favori -->
                            <div class="position-absolute top-0 end-0 m-2">
                                <button class="btn btn-light btn-sm rounded-circle toggle-favorite"
                                        data-recipe-id="<?= $recipe['id'] ?>"
                                        title="Retirer des favoris">
                                    <i class="fas fa-heart text-danger"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Corps de la carte -->
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-2">
                                <a href="<?= base_url('recette/' . $recipe['slug']) ?>"
                                   class="text-decoration-none text-dark">
                                    <?= esc($recipe['name']) ?>
                                </a>
                            </h5>

                            <!-- Note -->
                            <div class="mb-2">
                                <?php
                                $score = round($recipe['score']);
                                for ($i = 1; $i <= 5; $i++):
                                    ?>
                                    <i class="<?= $i <= $score ? 'fas' : 'far' ?> fa-star text-warning"></i>
                                <?php endfor; ?>
                                <small class="text-muted ms-1">
                                    (<?= number_format($recipe['score'], 1) ?>)
                                </small>
                            </div>

                            <!-- Description -->
                            <?php if (!empty($recipe['description'])): ?>
                                <p class="card-text text-muted small mb-3">
                                    <?= esc(mb_substr(strip_tags($recipe['description']), 0, 100)) ?>...
                                </p>
                            <?php endif; ?>

                            <!-- Date d'ajout aux favoris -->
                            <div class="mt-auto">
                                <small class="text-muted">
                                    <i class="far fa-clock me-1"></i>
                                    Ajouté le <?= date('d/m/Y', strtotime($recipe['created_at'])) ?>
                                </small>
                            </div>
                        </div>

                        <!-- Pied de carte -->
                        <div class="card-footer bg-transparent border-top-0">
                            <a href="<?= base_url('recette/' . $recipe['slug']) ?>"
                               class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-eye me-1"></i>
                                Voir la recette
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    $(document).ready(function() {
        const baseUrl = "<?= base_url(); ?>";

        // Gestion du toggle favori
        $(document).on('click', '.toggle-favorite', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const $btn = $(this);
            const recipeId = $btn.data('recipe-id');
            const $card = $btn.closest('.col');

            // Désactiver le bouton pendant la requête
            $btn.prop('disabled', true);

            $.ajax({
                url: baseUrl + 'favoris/toggle',
                type: 'POST',
                data: { id_recipe: recipeId },
                success: function(response) {
                    if (response.success) {
                        if (response.type === 'delete') {
                            // Animation de suppression
                            $card.fadeOut(400, function() {
                                $(this).remove();

                                // Vérifier s'il reste des favoris
                                if ($('.recipe-card').length === 0) {
                                    location.reload();
                                }
                            });

                            // Toast de confirmation
                            showToast('success', response.message);
                        }
                    } else {
                        if (response.redirect) {
                            window.location.href = baseUrl + response.redirect;
                        } else {
                            showToast('error', response.message);
                            $btn.prop('disabled', false);
                        }
                    }
                },
                error: function() {
                    showToast('error', 'Une erreur est survenue');
                    $btn.prop('disabled', false);
                }
            });
        });

        // Fonction pour afficher les toasts
        function showToast(type, message) {
            const iconMap = {
                'success': 'fas fa-check-circle',
                'error': 'fas fa-exclamation-circle',
                'info': 'fas fa-info-circle'
            };

            const toast = `
            <div class="toast align-items-center text-white bg-${type === 'error' ? 'danger' : type} border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="${iconMap[type]} me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;

            let $container = $('#toast-container');
            if ($container.length === 0) {
                $container = $('<div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3"></div>');
                $('body').append($container);
            }

            const $toast = $(toast);
            $container.append($toast);

            const bsToast = new bootstrap.Toast($toast[0]);
            bsToast.show();

            $toast.on('hidden.bs.toast', function() {
                $(this).remove();
            });
        }
    });
</script>

<style>
    .recipe-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }


    .recipe-img {
        transition: transform 0.3s;
    }


    .toggle-favorite {
        transition: all 0.2s;
        z-index: 10;
    }

    .toggle-favorite:hover {
        transform: scale(1.1);
    }
</style>