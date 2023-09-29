<!DOCTYPE html>
<html lang="en">

<?php $ip = "http://".$_SERVER['SERVER_NAME']."/reportmenu/"; ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="<?= $ip; ?>public/fontawesome/css/fontawesome.css">
    <link rel="stylesheet" href="<?= $ip; ?>public/fontawesome/css/brands.css">
    <link rel="stylesheet" href="<?= $ip; ?>public/fontawesome/css/solid.css">
    <link rel="stylesheet" href="<?= $ip; ?>public/fontawesome/css/regular.css">
    <!-- Laragon -->
    <link rel="stylesheet" href="<?= $ip; ?>public/bootstrap/dist/css/bootstrap.min.css">


    <!-- Style CSS -->
    <link rel="stylesheet" href="<?= $ip; ?>public/css/style.css">
    <!-- Kalo gapake Laragon/XAMPP -->
    <!-- <link rel="stylesheet" href="/bootstrap/dist/css/bootstrap.min.css"> -->

        <!-- Favicons -->
    <link href="<?= $ip; ?>public/assets/img/igr2.png" rel="icon">

</head>

<body class="bg-dark-subtle">
    <div class="wrapper">

    <?= $this->include('layout/sidebar')?>

    <?= $this->renderSection('content'); ?>

    </div>
    <script src="<?= $ip; ?>public/jquery-3.7.0.min.js"></script>
    <!-- <script src="jquery-3.7.0.min.js"></script> -->
    <script src="<?=$ip?>public/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="bootstrap/dist/js/bootstrap.bundle.min.js"></script> -->

   

    <script>
    
        $(function() {
           $('.dropdown').hover(function(){
            $(this).addClass('open');
           },
           function(){
            $(this).removeClass('open');
           }); 
        });
    </script>
</body>
</html>