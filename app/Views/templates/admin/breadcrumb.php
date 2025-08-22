<header class="header header-sticky p-0 mb-4">
    <div class="container-fluid border-bottom px-4">
        <button class="header-toggler" type="button" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()" style="margin-inline-start: -14px;">
            <i class="icon icon-lg fa-solid fa-bars"></i>
        </button>
        <ul class="header-nav">
            <!-- <li class="nav-item py-1">
                 <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
             </li>
          !--<li class="nav-item dropdown">
                 <button class="btn btn-link nav-link py-2 px-2 d-flex align-items-center" type="button" aria-expanded="false" data-coreui-toggle="dropdown">
                     <i class="fa-solid fa-circle-half-stroke theme-icon-active"></i>
                 </button>
                 <ul class="dropdown-menu dropdown-menu-end" style="--cui-dropdown-min-width: 8rem;">
                     <li>
                         <button class="dropdown-item d-flex align-items-center" type="button" data-coreui-theme-value="light">
                             <i class="fa-solid fa-sun me-3"></i>Light
                         </button>
                     </li>
                     <li>
                         <button class="dropdown-item d-flex align-items-center" type="button" data-coreui-theme-value="dark">
                             <i class="fa-solid fa-moon me-3"></i>Dark
                         </button>
                     </li>
                     <li>
                         <button class="dropdown-item d-flex align-items-center active" type="button" data-coreui-theme-value="auto">
                             <i class="fa-solid fa-circle-half-stroke me-3"></i>Auto
                         </button>
                     </li>
                 </ul>
             </li>

             <li class="nav-item py-1">
                 <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
             </li>-->
            <li class="nav-item dropdown">
                <button class="btn btn-link nav-link py-2 px-2 d-flex align-items-center" type="button" aria-expanded="false" data-coreui-toggle="dropdown">
                    <i class="icon icon-lg theme-icon-active fa-solid fa-user"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?= base_url('/admin/user/'); ?>">Modifier mon compte</a></li>
                    <li><a class="dropdown-item" href="<?= base_url('/sign-out'); ?>">Se deconnecter</a></li>
                </ul>
            </li>
        </ul>
    </div>
    <?php if (count($breadcrumb) > 0)  { ?>
        <div class="container-fluid px-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb my-0">
                    <?php foreach ($breadcrumb as $bitem) { ?>
                        <li class="breadcrumb-item">
                            <?php if ($bitem['url'] !== "") { ?>
                                <a class="link-underline link-underline-opacity-0" href="<?= base_url($bitem['url']) ?>" class=""><?= $bitem['text'] ?></a>
                            <?php } else { ?>
                                <?= $bitem['text'] ?>
                            <?php } ?>
                        </li>
                    <?php } ?>
                </ol>

            </nav>
        </div>
    <?php } ?>
</header>
