<header class="header header-sticky p-0 border-0 mb-4">
    <nav class="navbar fixed-top navbar-expand-lg">
        <div class="container-fluid px-4">
            <!-- Bouton du menu hamburger -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Logo -->
            <a class="navbar-brand text-white order-0" href="<?= site_url(($lang == 'fr' ? '' : 'en/home')) ?>">
                Boinot-Geay Location
            </a>

            <!-- Bouton de changement de langue -->
            <a href="<?= site_url(($lang == 'fr' ? 'en/home' : '')) ?>" class="btn btn-language">
                <?= $lang == 'fr' ? '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><rect x="1" y="4" width="30" height="24" rx="4" ry="4" fill="#071b65"></rect><path d="M5.101,4h-.101c-1.981,0-3.615,1.444-3.933,3.334L26.899,28h.101c1.981,0,3.615-1.444,3.933-3.334L5.101,4Z" fill="#fff"></path><path d="M22.25,19h-2.5l9.934,7.947c.387-.353,.704-.777,.929-1.257l-8.363-6.691Z" fill="#b92932"></path><path d="M1.387,6.309l8.363,6.691h2.5L2.316,5.053c-.387,.353-.704,.777-.929,1.257Z" fill="#b92932"></path><path d="M5,28h.101L30.933,7.334c-.318-1.891-1.952-3.334-3.933-3.334h-.101L1.067,24.666c.318,1.891,1.952,3.334,3.933,3.334Z" fill="#fff"></path><rect x="13" y="4" width="6" height="24" fill="#fff"></rect><rect x="1" y="13" width="30" height="6" fill="#fff"></rect><rect x="14" y="4" width="4" height="24" fill="#b92932"></rect><rect x="14" y="1" width="4" height="30" transform="translate(32) rotate(90)" fill="#b92932"></rect><path d="M28.222,4.21l-9.222,7.376v1.414h.75l9.943-7.94c-.419-.384-.918-.671-1.471-.85Z" fill="#b92932"></path><path d="M2.328,26.957c.414,.374,.904,.656,1.447,.832l9.225-7.38v-1.408h-.75L2.328,26.957Z" fill="#b92932"></path><path d="M27,4H5c-2.209,0-4,1.791-4,4V24c0,2.209,1.791,4,4,4H27c2.209,0,4-1.791,4-4V8c0-2.209-1.791-4-4-4Zm3,20c0,1.654-1.346,3-3,3H5c-1.654,0-3-1.346-3-3V8c0-1.654,1.346-3,3-3H27c1.654,0,3,1.346,3,3V24Z" opacity=".15"></path><path d="M27,5H5c-1.657,0-3,1.343-3,3v1c0-1.657,1.343-3,3-3H27c1.657,0,3,1.343,3,3v-1c0-1.657-1.343-3-3-3Z" fill="#fff" opacity=".2"></path></svg>' : '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><path fill="#fff" d="M10 4H22V28H10z"></path><path d="M5,4h6V28H5c-2.208,0-4-1.792-4-4V8c0-2.208,1.792-4,4-4Z" fill="#092050"></path><path d="M25,4h6V28h-6c-2.208,0-4-1.792-4-4V8c0-2.208,1.792-4,4-4Z" transform="rotate(180 26 16)" fill="#be2a2c"></path><path d="M27,4H5c-2.209,0-4,1.791-4,4V24c0,2.209,1.791,4,4,4H27c2.209,0,4-1.791,4-4V8c0-2.209-1.791-4-4-4Zm3,20c0,1.654-1.346,3-3,3H5c-1.654,0-3-1.346-3-3V8c0-1.654,1.346-3,3-3H27c1.654,0,3,1.346,3,3V24Z" opacity=".15"></path><path d="M27,5H5c-1.657,0-3,1.343-3,3v1c0-1.657,1.343-3,3-3H27c1.657,0,3,1.343,3,3v-1c0-1.657-1.343-3-3-3Z" fill="#fff" opacity=".2"></path></svg>' ?>
            </a>

            <!-- Menu utilisateur (positionné après le logo en mobile, mais à droite en desktop) -->
            <div class="navbar-nav d-flex order-1 order-md-3">
                <?php if (isset($user)) { ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="icon icon-lg theme-icon-active fa-solid fa-user"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if ($user->isAdmin()){ ?>
                                <li><a class="dropdown-item" href="<?= base_url( '/admin'); ?>"><i class="fa-solid fa-house-laptop me-2"></i>Accéder à l'admin</a></li>
                            <?php } ?>
                            <li><a class="dropdown-item" href="<?= base_url($lang == 'fr' ? '/mon-compte/mes-reservations' : '/en/mon-compte/mes-reservations'); ?>"><i class="fa-solid fa-pencil me-2"></i><?= lang('Global.my_account') ?></a></li>
                            <li><a class="dropdown-item" href="<?= base_url($lang == 'fr' ? '/sign-out' : '/en/sign-out'); ?>"><i class="fa-solid fa-right-from-bracket me-2"></i><?= lang('Global.sign_out') ?></a></li>
                        </ul>
                    </li>
                <?php } else { ?>
                    <a href="<?= base_url($lang == 'fr' ? '/sign-in' : '/en/sign-in') ?>" class="nav-link text-white"><i class="fa-solid fa-user me-3 ms-3"></i></a>
                    <a href="<?= base_url($lang == 'fr' ? '/sign-up' : '/en/sign-up') ?>" class="nav-link text-white"><?= lang('Global.sign_up') ?></a>
                <?php } ?>
            </div>

            <!-- Menu principal (poussé à gauche en desktop grâce à ms-auto) -->
            <div class="collapse navbar-collapse order-2 order-md-1 ms-auto" id="navbarNav">
                <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll border-0">
                    <?php foreach ($menus as $km => $menu) {
                        if (isset($menu['require']) && ! $user->check($menu['require'])) { continue; }
                        if (!isset($menu['subs'])) { ?>
                            <li class="nav-item <?= ($localmenu === $km ? 'active' : '') ?>" id="menu_<?= $km ?>">
                                <a class="nav-link text-white" href="<?= base_url(($lang == 'fr' ? '' : 'en/') . $menu['url']); ?>">
                                    <?php if (isset($menu['icon'])) { echo $menu['icon']; }
                                    else { ?><svg class="nav-icon"><span class="bullet bullet-dot"></span></svg><?php } ?>
                                    <?= $menu['title'] ?>
                                </a>
                            </li>
                        <?php } else { ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?= (isset($menu['icon'])) ? $menu['icon'] : ""; ?>
                                    <?= $menu['title'] ?>
                                </a>
                                <ul class="dropdown-menu">
                                    <?php foreach ($menu['subs'] as $ksm => $smenu) {
                                        if (isset($smenu['require']) && ! $user->check($smenu['require'])) { continue; } ?>
                                        <li id="menu_<?= $ksm ?>">
                                            <a class="dropdown-item" href="<?= base_url(($lang == 'fr' ? '' : 'en') . $smenu['url']); ?>">
                                                <?php if (isset($smenu['icon'])) echo $smenu['icon']; ?>
                                                <?= $smenu['title'] ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php }
                    } ?>
                </ul>
            </div>
        </div>
    </nav>
</header>