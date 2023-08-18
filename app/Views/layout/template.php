<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="<?= base_url('fontawesome/css/fontawesome.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('fontawesome/css/brands.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('fontawesome/css/solid.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('fontawesome/css/regular.css'); ?>">
    <!-- Laragon -->
    <link rel="stylesheet" href="<?= base_url('bootstrap/dist/css/bootstrap.min.css'); ?>">

    <!-- Style CSS -->
    <link rel="stylesheet" href="/css/style.css">
    <!-- Kalo gapake Laragon/XAMPP -->
    <!-- <link rel="stylesheet" href="/bootstrap/dist/css/bootstrap.min.css"> -->
</head>

<body>
    
    <?= $this->include('layout/navbar')?>

    <?= $this->renderSection('content'); ?>

    <script src="<?=base_url('jquery-3.7.0.min.js');?>"></script>
    <!-- <script src="jquery-3.7.0.min.js"></script> -->
    <script src="<?=base_url('bootstrap/dist/js/bootstrap.bundle.min.js')?>"></script>
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