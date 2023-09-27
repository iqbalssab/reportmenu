<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<?php $no = 0; ?>
<div class="container-fluid mt-3 overflow-auto">
    <div class="row">
        <div class="col-md-6">
            <?php if(!empty($kunjmm)) { ?>
                <h3 class="fw-bold fs-4 badge rounded-pill bg-danger">Kunjungan Member Merah</h3>    
                <table class="table mb-3 table-striped table-bordered table-responsive" style="font-size: 15px;">
                    <thead class="table-group-divider">
                        <tr>
                            <th rowspan="2" class="fw-bold text-center text-nowrap bg-info">#</th>
                            <th rowspan="2" class="fw-bold text-center text-nowrap bg-info">Bulan</th>
                            <th rowspan="2" class="fw-bold text-center text-nowrap bg-info">Hari Penjualan</th>
                            <th rowspan="2" class="fw-bold text-center text-nowrap bg-info">Avg Kunjungan</th>
                            <th colspan="31" class="fw-bold text-center text-nowrap bg-info">Tanggal</th>
                            <th rowspan="2" class="fw-bold text-center text-nowrap bg-info">Keterangan</th>
                        </tr>
                        <tr>
                            <th class="fw-bold text-center text-nowrap bg-info">1</th>
                            <th class="fw-bold text-center text-nowrap bg-info">2</th>
                            <th class="fw-bold text-center text-nowrap bg-info">3</th>
                            <th class="fw-bold text-center text-nowrap bg-info">4</th>
                            <th class="fw-bold text-center text-nowrap bg-info">5</th>
                            <th class="fw-bold text-center text-nowrap bg-info">6</th>
                            <th class="fw-bold text-center text-nowrap bg-info">7</th>
                            <th class="fw-bold text-center text-nowrap bg-info">8</th>
                            <th class="fw-bold text-center text-nowrap bg-info">9</th>
                            <th class="fw-bold text-center text-nowrap bg-info">10</th>
                            <th class="fw-bold text-center text-nowrap bg-info">11</th>
                            <th class="fw-bold text-center text-nowrap bg-info">12</th>
                            <th class="fw-bold text-center text-nowrap bg-info">13</th>
                            <th class="fw-bold text-center text-nowrap bg-info">14</th>
                            <th class="fw-bold text-center text-nowrap bg-info">15</th>
                            <th class="fw-bold text-center text-nowrap bg-info">16</th>
                            <th class="fw-bold text-center text-nowrap bg-info">17</th>
                            <th class="fw-bold text-center text-nowrap bg-info">18</th>
                            <th class="fw-bold text-center text-nowrap bg-info">19</th>
                            <th class="fw-bold text-center text-nowrap bg-info">20</th>
                            <th class="fw-bold text-center text-nowrap bg-info">21</th>
                            <th class="fw-bold text-center text-nowrap bg-info">22</th>
                            <th class="fw-bold text-center text-nowrap bg-info">23</th>
                            <th class="fw-bold text-center text-nowrap bg-info">24</th>
                            <th class="fw-bold text-center text-nowrap bg-info">25</th>
                            <th class="fw-bold text-center text-nowrap bg-info">26</th>
                            <th class="fw-bold text-center text-nowrap bg-info">27</th>
                            <th class="fw-bold text-center text-nowrap bg-info">28</th>
                            <th class="fw-bold text-center text-nowrap bg-info">29</th>
                            <th class="fw-bold text-center text-nowrap bg-info">30</th>
                            <th class="fw-bold text-center text-nowrap bg-info">31</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <?php 
                            $no++;
                            foreach($kunjmm as $mm) : ?>
                            <tr>
                                <td class="text-end text-nowrap"><?= $no++; ?></td>
                                <td class="text-center text-nowrap"><?= substr($mm['KUN_BULAN'],4,2) . '-' . substr($mm['KUN_BULAN'],0,4); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['KUN_HARI'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['KUN_AVG'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['1_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['2_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['3_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['4_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['5_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['6_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['7_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['8_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['9_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['10_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['11_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['12_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['13_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['14_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['15_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['16_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['17_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['18_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['19_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['20_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['21_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['22_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['23_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['24_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['25_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['26_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['27_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['28_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['29_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['30_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mm['31_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            <?php } ?>
            <?php if(!empty($kunjmb)) { ?>
                <h3 class="fw-bold fs-4 badge rounded-pill bg-info">Kunjungan Member Biru</h3>    
                <table class="table mb-3 table-striped table-bordered table-responsive" style="font-size: 15px;">
                    <thead class="table-group-divider">
                        <tr>
                            <th rowspan="2" class="fw-bold text-center text-nowrap bg-info">#</th>
                            <th rowspan="2" class="fw-bold text-center text-nowrap bg-info">Bulan</th>
                            <th rowspan="2" class="fw-bold text-center text-nowrap bg-info">Hari Penjualan</th>
                            <th rowspan="2" class="fw-bold text-center text-nowrap bg-info">Avg Kunjungan</th>
                            <th colspan="31" class="fw-bold text-center text-nowrap bg-info">Tanggal</th>
                            <th rowspan="2" class="fw-bold text-center text-nowrap bg-info">Keterangan</th>
                        </tr>
                        <tr>
                            <th class="fw-bold text-center text-nowrap bg-info">1</th>
                            <th class="fw-bold text-center text-nowrap bg-info">2</th>
                            <th class="fw-bold text-center text-nowrap bg-info">3</th>
                            <th class="fw-bold text-center text-nowrap bg-info">4</th>
                            <th class="fw-bold text-center text-nowrap bg-info">5</th>
                            <th class="fw-bold text-center text-nowrap bg-info">6</th>
                            <th class="fw-bold text-center text-nowrap bg-info">7</th>
                            <th class="fw-bold text-center text-nowrap bg-info">8</th>
                            <th class="fw-bold text-center text-nowrap bg-info">9</th>
                            <th class="fw-bold text-center text-nowrap bg-info">10</th>
                            <th class="fw-bold text-center text-nowrap bg-info">11</th>
                            <th class="fw-bold text-center text-nowrap bg-info">12</th>
                            <th class="fw-bold text-center text-nowrap bg-info">13</th>
                            <th class="fw-bold text-center text-nowrap bg-info">14</th>
                            <th class="fw-bold text-center text-nowrap bg-info">15</th>
                            <th class="fw-bold text-center text-nowrap bg-info">16</th>
                            <th class="fw-bold text-center text-nowrap bg-info">17</th>
                            <th class="fw-bold text-center text-nowrap bg-info">18</th>
                            <th class="fw-bold text-center text-nowrap bg-info">19</th>
                            <th class="fw-bold text-center text-nowrap bg-info">20</th>
                            <th class="fw-bold text-center text-nowrap bg-info">21</th>
                             <th class="fw-bold text-center text-nowrap bg-info">22</th>
                            <th class="fw-bold text-center text-nowrap bg-info">23</th>
                            <th class="fw-bold text-center text-nowrap bg-info">24</th>
                            <th class="fw-bold text-center text-nowrap bg-info">25</th>
                            <th class="fw-bold text-center text-nowrap bg-info">26</th>
                            <th class="fw-bold text-center text-nowrap bg-info">27</th>
                            <th class="fw-bold text-center text-nowrap bg-info">28</th>
                            <th class="fw-bold text-center text-nowrap bg-info">29</th>
                            <th class="fw-bold text-center text-nowrap bg-info">30</th>
                            <th class="fw-bold text-center text-nowrap bg-info">31</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <?php 
                            $no = 0;
                            $no++;
                            foreach($kunjmb as $mb) : ?>
                            <tr>
                                <td class="text-end text-nowrap"><?= $no++; ?></td>
                                <td class="text-center text-nowrap"><?= substr($mb['KUN_BULAN'],4,2) . '-' . substr($mb['KUN_BULAN'],0,4); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['KUN_HARI'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['KUN_AVG'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['1_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['2_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['3_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['4_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['5_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['6_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['7_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['8_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['9_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['10_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['11_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['12_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['13_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['14_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['15_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['16_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['17_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['18_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['19_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['20_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['21_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['22_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['23_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['24_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['25_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['26_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['27_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['28_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['29_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['30_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td class="text-end text-nowrap"><?= number_format($mb['31_KUN_MEMBER'], 0, '.', ','); ?></td>
                                <td></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>