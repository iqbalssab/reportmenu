<?php $this->extend('layout/template'); ?>
<?php $this->section('content'); ?>

<h4>SO HARIAN <?= $tgl." >> ".$divisi." PLU ".$plugab; ?></h4>
<?php $arr = [$plubiasa]; ?>
<?php foreach($arr as $ar): ?>
    <?php foreach($ar as $a): ?>
        <p><?= $a; ?></p>
    <?php endforeach; ?>
<?php endforeach; ?>


<?php $this->endSection(); ?>