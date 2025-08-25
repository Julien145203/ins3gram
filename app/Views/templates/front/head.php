<!DOCTYPE html>
<html lang="fr-FR">
<head>
    <base href="<?= base_url('./') ?>">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <meta name="author" content="">
    <meta name="keyword" content="">
    <title><?= $title ?></title>
    <link rel="apple-touch-icon" sizes="57x57" href="<?= base_url('/assets/favicon/apple-icon-57x57.png') ?>">
    <link rel="apple-touch-icon" sizes="60x60" href="<?= base_url('/assets/favicon/apple-icon-60x60.png') ?>">
    <link rel="apple-touch-icon" sizes="72x72" href="<?= base_url('/assets/favicon/apple-icon-72x72.png') ?>">
    <link rel="apple-touch-icon" sizes="76x76" href="<?= base_url('/assets/favicon/apple-icon-76x76.png') ?>">
    <link rel="apple-touch-icon" sizes="114x114" href="<?= base_url('/assets/favicon/apple-icon-114x114.png') ?>">
    <link rel="apple-touch-icon" sizes="120x120" href="<?= base_url('/assets/favicon/apple-icon-120x120.png') ?>">
    <link rel="apple-touch-icon" sizes="144x144" href="<?= base_url('/assets/favicon/apple-icon-144x144.png') ?>">
    <link rel="apple-touch-icon" sizes="152x152" href="<?= base_url('/assets/favicon/apple-icon-152x152.png') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('/assets/favicon/apple-icon-180x180.png') ?>">
    <link rel="icon" type="image/png" sizes="192x192" href="<?= base_url('/assets/favicon/android-icon-192x192.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('/assets/favicon/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="96x96" href="<?= base_url('/assets/favicon/favicon-96x96.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('/assets/favicon/favicon-16x16.png') ?>">
    <link rel="manifest" href="<?= base_url('/assets/favicon/manifest.json') ?>">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/assets/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <!-- Javascript -->
    <script src="<?= base_url('/js/jquery-3.7.1.min.js') ?>"></script>
    <script>
        var base_url = '<?= base_url(); ?>';
        let csrfName = '<?= csrf_token() ?>';
        let csrfHash = '<?= csrf_hash() ?>';
    </script>
    <script src="<?= base_url('/js/toastr.min.js') ?>"></script>
    <script src="<?= base_url('/js/main.js') ?>"></script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js" integrity="sha512-GWzVrcGlo0TxTRvz9ttioyYJ+Wwk9Ck0G81D+eO63BaqHaJ3YZX9wuqjwgfcV/MrB2PhaVX9DkYVhbFpStnqpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- BOOTSTRAP BUNDLE -->
    <link rel="stylesheet" href="<?= base_url('/css/bootstrap.min.css') ?>"></link>
    <script src="<?= base_url('/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('/js/bootstrap-datepicker.min.js') ?>"></script>
    <script src="<?= base_url('/js/bootstrap-datepicker.fr.js') ?>"></script>
    <link rel="stylesheet" href="<?= base_url('/css/bootstrap-datepicker.min.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.1/daterangepicker.min.css">
    <script src="https://momentjs.com/downloads/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.1/daterangepicker.min.js"></script>

    <!-- SPLIDE JS (gallery) -->
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">

    <!-- LIGHTBOX2 -->
    <link rel="stylesheet" href="<?= base_url('/css/lightbox.min.css') ?>">
    <script src="<?= base_url('/js/lightbox.min.js') ?>"></script>

    <!-- CSS-->
    <link rel="stylesheet" href="<?= base_url('/css/toastr.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/css/custom.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/css/custom-front.css') ?>">

    <!-- SWEETALERT 2  -->
    <link href="<?= base_url('/css/sweetalert2.min.css') ?>" rel="stylesheet">
    <script src="<?= base_url('/js/sweetalert2.all.min.js') ?>"></script>
</head>
<body>

<?php if (isset($menus)) {
    echo view($template_path . 'header',['menus' => $menus]);  }  ?>
<?php if (isset($mea)) { ?>
    <img src="<?= base_url($mea) ?>" class="img-mea">
<?php } else { ?>
    <div style="margin-top: 125px"></div>
<?php } ?>
<div class="container">
