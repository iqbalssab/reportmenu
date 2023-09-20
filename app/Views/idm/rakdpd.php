<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<?php
    $nomorShelving = $nomorSubRak = $nomorUrut = '99';
?>
<div class="container-fluid mt-3 overflow-auto">
    <div class="row">
        <div class="col-md-3" style="width: 300px;">
            <div class="card w-60 mb-3 mx-auto">
                <div class="card-header text-light" style="background-color: #0040ff;">
                    <h5 class="text-start fw-bold">Visualisasi Rak DPD</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="rakdpd" class="form" role="form" class="form-inline">
                        <?= csrf_field(); ?>
                        <div class="fw-bold mb-2" style="font-size: 15px;">Kode Rak</div>
                        <select class="form-select form-select-sm mx-auto mb-3" name="kodeRak" id="kodeRak" style="font-size: 14px; width: 250px;">
                            <?php 
                                foreach($kdrak as $rak) :
                                    echo '<option value ="' . $rak['LKS_KODERAK'] . '">' . $rak['LKS_KODERAK'] . "</option>";                           
                                endforeach
                            ?>
                        </select>
                        <hr>
                        <button type="submit" name="tombol" value="btnview" class="text-light btn d-block fw-bold mx-auto" style=" background-color: #6528F7; width: 250px;">Tampil</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-10 mx-auto">
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">    
                <?php foreach($rakdpd as $dpd) : ?>
                    <?php 
                        // Shelving
                        if ($nomorShelving != $dpd['LKS_SHELVINGRAK']) {
                            if ($nomorShelving != '99') {
                                echo '</tr></table>';
                                echo '</td>';
                                echo '</tr>';
                            }
                            $nomorSubRak = '99';
                            $nomorShelving = $dpd['LKS_SHELVINGRAK'];
                            echo '<tr>';
                            echo '<td>Shelving ' . $nomorShelving . '</td>';
                        }

                        // SubRak
                        if ($nomorSubRak != $dpd['LKS_KODESUBRAK']) {
                            if ($nomorSubRak != '99') {
                                echo '</tr></table>';
                                echo '</td>';
                            }
                            $nomorSubRak = $dpd['LKS_KODESUBRAK'];
                            echo '<td>';
                            //echo 'Sub Rak ' . $nomorSubRak ;
                            echo '<table>';
                            //echo '<tr>' . '<button type="button" class="btn btn-xs btn-danger">' . $nomorSubRak . '</button>' . '</tr>';
                            echo '<tr>' ;
                            echo '<h5>' ;
                            echo '<small>Rak: </small>' . $dpd['LKS_KODERAK'] ;
                            echo '<small> Sub Rak: </small>' . $dpd['LKS_KODESUBRAK'] ;
                            echo '<small> Shelving: </small>' . $dpd['LKS_SHELVINGRAK'] ;
                            echo '</h5>';
                            //echo '<button type="button" class="btn btn-xs btn-danger">' . $nomorSubRak . '</button>' ;
                            echo '</tr>';
                            echo '<tr>';
                        }
                    ?>
                    <td>
                        <div class="rak-dpd">
                            <span class="badge" >' .  $dpd['LKS_NOURUT'] . '</span>
                            <?php 
                                if ($dpd['LKS_NOID'] != '000XX') {
                                    echo '<span class="badge">' .  substr($dpd['LKS_NOID'],0,3) . ' ' ;
                                }
                                switch (substr($dpd['LKS_NOID'],3,1)) {
                                    case "A":
                                        echo '<span class="glyphicon glyphicon-certificate kuning bg-warning"></span>' ;  
                                        break;
                                    case "T":
                                        echo '<span class="glyphicon glyphicon-certificate merah bg-danger"></span>' ;
                                        break;
                                    case "B":
                                        echo '<span class="glyphicon glyphicon-certificate hijaucerah bg-success"></span>' ;
                                        break;
                                    
                                    default:
                                        //echo '<span class="glyphicon glyphicon-certificate"></span>' ;
                                }
                                echo '</span>';
                                //echo $dpd['LKS_NOID'] ;
                                //echo $dpd['LKS_PRDCD'] ;
                                echo '<h5 align=center>' ;
                                //echo '<br>';
                                echo $dpd['LKS_NAMABARANG'] .'<br>';
                                //echo '<span class="badge">' .  $dpd['LKS_PRDCD'] .'</span>';
                                echo '-<small>' . $dpd['LKS_PRDCD'] . '</small>' ;
                                echo '</h5>';
                            ?>
                        </div>
                    </td>
                <?php endforeach ?>
                <?php 
                    echo '</tr></table>';
                    echo '</td>';
                    echo '</tr>';
                    echo "</table>";
                ?>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>