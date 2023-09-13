<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<?php $now = date('Y-m-d'); ?>
<div class="container mt-3">
    <div class="row">
        <div class="col-md-3">
        <div class="card">
                <div class="card-header bg-primary text-light">
                    <p class="text-light fw-bold">PB OMI</p>
                </div>
                <div class="card-body">
                    <form action="progress" method="get">
                    <label for="tgl" class="fw-bold d-block">TGL PROSES :</label>
                    <input type="date" name="tgl" id="tgl" class="w-100 mb-3" value="<?= $now; ?>">

                    <button type="submit" name="view" value="view" class="w-100 btn btn-success text-light text-center mb-1"><i class="fa-solid fa-magnifying-glass"></i> Lihat Progress</button>
                    </form>
                    <a href="cekprosespbomi" class="btn btn-dark text-light text-center"><i class="fa-solid fa-arrow-left"></i> Back</a>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <?php if(!empty($progress)): ?>
                <div class="card-header bg-primary-subtle">
                    <p class="text-primary fw-bold">Progress Realisasi PBOMI</p>
                </div>
                <div class="card-body">
                       <?php 
                        $no=0;
                        $totalitem = 0;
                        $belumpicking = 0;
                        $totalpicking = 0;
                        $belumscanning = 0;
                        $totalscanning = 0;
                        $belumdsp = 0;
                        $totaldsp = 0;
                        $belumsph = 0;
                        $totalsph = 0;
                        
                        foreach($progress as $prg):
                        $totalitem += $prg['JUMLAHITEM'];
                        if ($prg['TAHAP']==1) {
                            $belumpicking += $prg['JUMLAHITEM'];
                        }if($prg['TAHAP']==2){
                            $belumscanning += $prg['JUMLAHITEM'];
                        }if($prg['TAHAP']==3){
                            $belumdsp += $prg['JUMLAHITEM'];
                        }if($prg['TAHAP']==4){
                            $belumsph += $prg['JUMLAHITEM'];
                        }if($prg['TAHAP'] >= 4){
                            $totaldsp += $prg['JUMLAHITEM'];
                        }if($prg['TAHAP']==5){
                            $totalsph += $prg['JUMLAHITEM'];
                        } 
                        endforeach; 
                        ?>

                        <?php if($totalitem>0): 
                            $totalpicking = $totalitem - $belumpicking;
                            $tahappicking = round($totalpicking/$totalitem*100,2);    
                        ?>
                        <div>Sudah Picking : <?= $totalpicking." dari ".$totalitem; ?> item upload</div>
                        <div class="progress w-100 mb-3" role="progressbar" aria-label="Warning example" aria-valuenow="<?= $tahappicking; ?>" aria-valuemin="0" aria-valuemax="100" style="height: 30px;">
                            <div class="progress-bar bg-warning text-light" style="width: <?= $tahappicking; ?>%;">
                                <span><?= $tahappicking; ?>% Complete</span>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if($totalitem>0): 
                            $totalscanning = $totalpicking - $belumscanning;
				            $tahapscanning = round($totalscanning/$totalitem*100,2);
                        ?>
                        <div>Sudah Scanning : <?= $totalscanning." dari ".$totalitem; ?> item upload</div>
                        <div class="progress w-100 mb-3" role="progressbar" aria-label="Warning example" aria-valuenow="<?= $tahapscanning; ?>" aria-valuemin="0" aria-valuemax="100" style="height: 30px;">
                            <div class="progress-bar bg-primary text-light" style="width: <?= $tahapscanning; ?>%;">
                                <span><?= $tahapscanning; ?>% Complete</span>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if($totaldsp>0): 
                            $totaldsp = $totalscanning - $belumdsp;
                            $tahapdsp = round($totaldsp/$totalscanning*100,2);
                        ?>
                        <div>Sudah Dicetak DSP : <?= $totaldsp." dari ".$totalscanning; ?> item yang sudah di scanning</div>
                        <div class="progress w-100 mb-3" role="progressbar" aria-label="Warning example" aria-valuenow="<?= $tahapdsp; ?>" aria-valuemin="0" aria-valuemax="100" style="height: 30px;">
                            <div class="progress-bar bg-success text-light" style="width: <?= $tahapdsp; ?>%;">
                                <span><?= $tahapdsp; ?>% Complete</span>
                            </div>
                        </div>    
                        <?php endif; ?>
                        <?php if($totalsph>0): 
                            $tahapsph = round($totalsph/$totaldsp*100,2);
                        ?>
                        <div>Realisasi Sales : <?= $totalsph." dari ".$totaldsp; ?> item DSP</div>
                        <div class="progress w-100 mb-3" role="progressbar" aria-label="Warning example" aria-valuenow="<?= $tahapsph; ?>" aria-valuemin="0" aria-valuemax="100" style="height: 30px;">
                            <div class="progress-bar bg-success text-light" style="width: <?= $tahapsph; ?>%;">
                                <span><?= $tahapsph; ?>% Complete</span>
                            </div>
                        </div> 
                            
                        <?php endif; ?>

                    </div>
                    <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>