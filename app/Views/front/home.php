<div class="container py-5">

    <!-- Section de bienvenue -->
    <div class="row text-center mb-5">
        <div class="col">
            <img src="<?= base_url('assets/img/logo-full.png') ?>" alt="Logo" class="mb-4" style="max-width: 220px;">
            <h1 class="fw-bold">Bienvenue sur <span class="text-primary">Ins3gram</span> 👋</h1>
            <p class="lead text-muted mt-3">
                Découvrez, créez et partagez vos recettes préférées.<br>
                Une plateforme conçue pour les passionnés de cuisine !
            </p>
            <a href="<?= base_url('recette') ?>" class="btn btn-primary btn-lg mt-3">
                <i class="bi bi-book"></i> Voir les recettes
            </a>
        </div>
    </div>

    <!-- Section fonctionnalités principales -->
    <div class="row text-center g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <i class="bi bi-journal-text display-4 text-primary mb-3"></i>
                    <h4 class="fw-semibold">Explorez</h4>
                    <p class="text-muted">Parcourez des centaines de recettes triées par catégorie, ingrédient ou popularité.</p>
                    <a href="<?= base_url('recettes') ?>" class="stretched-link text-decoration-none">Découvrir</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <i class="bi bi-pencil-square display-4 text-success mb-3"></i>
                    <h4 class="fw-semibold">Créez</h4>
                    <p class="text-muted">Ajoutez vos propres recettes avec photos, étapes et ingrédients détaillés.</p>
                    <a href="<?= base_url('recette/ajouter') ?>" class="stretched-link text-decoration-none">Ajouter une recette</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <i class="bi bi-people display-4 text-warning mb-3"></i>
                    <h4 class="fw-semibold">Partagez</h4>
                    <p class="text-muted">Échangez avec la communauté et inspirez les autres cuisiniers amateurs.</p>
                    <a href="<?= base_url('communaute') ?>" class="stretched-link text-decoration-none">Voir la communauté</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Section vide ou message alternatif -->
    <div class="row text-center mt-5">
        <div class="col">
            <img src="<?= base_url('assets/img/logo-full-tristoune.png') ?>" alt="Aucune recette" style="max-width: 200px;" class="mb-3">
            <h5 class="text-muted">Il n’y a encore rien à afficher…</h5>
            <p>Ajoutez votre première recette pour démarrer l’aventure ! 🍳</p>
        </div>
    </div>

</div>
