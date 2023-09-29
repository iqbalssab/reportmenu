<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid mt-3 overflow-auto">
    <div class="row">
        <div class="col-md-6">
            <h3 class="fw-bold fs-4 badge rounded-pill bg-success">Rekap Aktivasi Kartu per-Bulan</h3>    
            <table class="table mb-3 table-striped table-bordered table-responsive" style="width: 500px;" style="font-size: 15px;">
                <thead class="table-group-divider table-info">
                    <tr>
                        <th class="fw-bold text-center text-nowrap">#</th>
                        <th class="fw-bold text-center text-nowrap">Bulan</th>
                        <th class="fw-bold text-center text-nowrap">Member Merah</th>
                        <th class="fw-bold text-center text-nowrap">Member Biru</th>
                        <th class="fw-bold text-center text-nowrap">Total</th>
                        <th class="fw-bold text-center text-nowrap">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php $no = $jumlahMemberMerah = $jumlahMemberBiru = 0;
                        $no++;
                        foreach($rekap as $rk) : ?>
                        <tr>
                            <td class="text-end text-nowrap"><?= $no++; ?></td>
                            <td class="text-center text-nowrap"><?= substr($rk['JH_TRANSACTIONDATE'],5,2); ?> - <?= substr($rk['JH_TRANSACTIONDATE'],0,4); ?> </td>
                            <td class="text-end text-nowrap"><?= number_format($rk['1_JH_CUS_KODEMEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($rk['0_JH_CUS_KODEMEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($rk['1_JH_CUS_KODEMEMBER'] + $rk['0_JH_CUS_KODEMEMBER'], 0, '.', ','); ?></td>
                            <td></td>
                            <?php 
                                $jumlahMemberMerah += $rk['1_JH_CUS_KODEMEMBER'];
                                $jumlahMemberBiru += $rk['0_JH_CUS_KODEMEMBER'];
                            ?>
                        </tr>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="2" class="fw-bold text-center">Total</td>
                        <td class="fw-bold text-center"><?= number_format($jumlahMemberMerah, 0, '.', ','); ?></td>
                        <td class="fw-bold text-center"><?= number_format($jumlahMemberBiru, 0, '.', ','); ?></td>
                        <td class="fw-bold text-center"><?= number_format($jumlahMemberMerah + $jumlahMemberBiru, 0, '.', ','); ?></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            <br>
            <h3 class="fw-bold fs-4 badge rounded-pill bg-danger">Aktivasi Kartu Member Merah</h3>    
            <table class="table mb-3 table-striped table-bordered table-responsive" style="width: 750px;" style="font-size: 15px;">
                <thead class="table-group-divider table-info">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center text-nowrap">#</th>
                        <th rowspan="2"  class="fw-bold text-center text-nowrap">Bulan</th>
                        <th rowspan="2"  class="fw-bold text-center text-nowrap">Total</th>
                        <th colspan="31"  class="fw-bold text-center text-nowrap">Tanggal</th>
                        <th rowspan="2"  class="fw-bold text-center text-nowrap">Keterangan</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center text-nowrap">1</th>
                        <th class="fw-bold text-center text-nowrap">2</th>
                        <th class="fw-bold text-center text-nowrap">3</th>
                        <th class="fw-bold text-center text-nowrap">4</th>
                        <th class="fw-bold text-center text-nowrap">5</th>
                        <th class="fw-bold text-center text-nowrap">6</th>
                        <th class="fw-bold text-center text-nowrap">7</th>
                        <th class="fw-bold text-center text-nowrap">8</th>
                        <th class="fw-bold text-center text-nowrap">9</th>
                        <th class="fw-bold text-center text-nowrap">10</th>
                        <th class="fw-bold text-center text-nowrap">11</th>
                        <th class="fw-bold text-center text-nowrap">12</th>
                        <th class="fw-bold text-center text-nowrap">13</th>
                        <th class="fw-bold text-center text-nowrap">14</th>
                        <th class="fw-bold text-center text-nowrap">15</th>
                        <th class="fw-bold text-center text-nowrap">16</th>
                        <th class="fw-bold text-center text-nowrap">17</th>
                        <th class="fw-bold text-center text-nowrap">18</th>
                        <th class="fw-bold text-center text-nowrap">19</th>
                        <th class="fw-bold text-center text-nowrap">20</th>
                        <th class="fw-bold text-center text-nowrap">21</th>
                        <th class="fw-bold text-center text-nowrap">22</th>
                        <th class="fw-bold text-center text-nowrap">23</th>
                        <th class="fw-bold text-center text-nowrap">24</th>
                        <th class="fw-bold text-center text-nowrap">25</th>
                        <th class="fw-bold text-center text-nowrap">26</th>
                        <th class="fw-bold text-center text-nowrap">27</th>
                        <th class="fw-bold text-center text-nowrap">28</th>
                        <th class="fw-bold text-center text-nowrap">29</th>
                        <th class="fw-bold text-center text-nowrap">30</th>
                        <th class="fw-bold text-center text-nowrap">31</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php $no = $jumlahMember = 0;
                        $no++;
                        foreach($membermm as $mm) : ?>
                        <tr>
                            <?php 
                                $jumlahMember = $mm['1_AKT_MEMBER'] +
                                $mm['2_AKT_MEMBER'] +
                                $mm['3_AKT_MEMBER'] +
                                $mm['4_AKT_MEMBER'] +
                                $mm['5_AKT_MEMBER'] +
                                $mm['6_AKT_MEMBER'] +
                                $mm['7_AKT_MEMBER'] +
                                $mm['8_AKT_MEMBER'] +
                                $mm['9_AKT_MEMBER'] +
                                $mm['10_AKT_MEMBER'] +
                                $mm['11_AKT_MEMBER'] +
                                $mm['12_AKT_MEMBER'] +
                                $mm['13_AKT_MEMBER'] +
                                $mm['14_AKT_MEMBER'] +
                                $mm['15_AKT_MEMBER'] +
                                $mm['16_AKT_MEMBER'] +
                                $mm['17_AKT_MEMBER'] +
                                $mm['18_AKT_MEMBER'] +
                                $mm['19_AKT_MEMBER'] +
                                $mm['20_AKT_MEMBER'] +
                                $mm['21_AKT_MEMBER'] +
                                $mm['22_AKT_MEMBER'] +
                                $mm['23_AKT_MEMBER'] +
                                $mm['24_AKT_MEMBER'] +
                                $mm['25_AKT_MEMBER'] +
                                $mm['26_AKT_MEMBER'] +
                                $mm['27_AKT_MEMBER'] +
                                $mm['28_AKT_MEMBER'] +
                                $mm['29_AKT_MEMBER'] +
                                $mm['30_AKT_MEMBER'] +
                                $mm['31_AKT_MEMBER'];
                            ?>
                            <td class="text-end text-nowrap"><?= $no++; ?></td>
                            <td class="text-center text-nowrap"><?= substr($mm['AKT_BULAN'],5,2); ?> - <?= substr($mm['AKT_BULAN'],0,4); ?> </td>
                            <td class="text-end text-nowrap"><?= number_format($jumlahMember, 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['1_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['2_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['3_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['4_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['5_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['6_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['7_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['8_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['9_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['10_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['11_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['12_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['13_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['14_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['15_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['16_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['17_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['18_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['19_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['20_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['21_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['22_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['23_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['24_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['25_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['26_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['27_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['28_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['29_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['30_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['31_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <br>
            <h3 class="fw-bold fs-4 badge rounded-pill bg-info">Aktivasi Kartu Member Biru</h3>    
            <table class="table mb-3 table-striped table-bordered table-responsive" style="width: 750px;" style="font-size: 15px;">
                <thead class="table-group-divider table-info">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center text-nowrap">#</th>
                        <th rowspan="2"  class="fw-bold text-center text-nowrap">Bulan</th>
                        <th colspan="31"  class="fw-bold text-center text-nowrap">Tanggal</th>
                        <th rowspan="2"  class="fw-bold text-center text-nowrap">Keterangan</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center text-nowrap">1</th>
                        <th class="fw-bold text-center text-nowrap">2</th>
                        <th class="fw-bold text-center text-nowrap">3</th>
                        <th class="fw-bold text-center text-nowrap">4</th>
                        <th class="fw-bold text-center text-nowrap">5</th>
                        <th class="fw-bold text-center text-nowrap">6</th>
                        <th class="fw-bold text-center text-nowrap">7</th>
                        <th class="fw-bold text-center text-nowrap">8</th>
                        <th class="fw-bold text-center text-nowrap">9</th>
                        <th class="fw-bold text-center text-nowrap">10</th>
                        <th class="fw-bold text-center text-nowrap">11</th>
                        <th class="fw-bold text-center text-nowrap">12</th>
                        <th class="fw-bold text-center text-nowrap">13</th>
                        <th class="fw-bold text-center text-nowrap">14</th>
                        <th class="fw-bold text-center text-nowrap">15</th>
                        <th class="fw-bold text-center text-nowrap">16</th>
                        <th class="fw-bold text-center text-nowrap">17</th>
                        <th class="fw-bold text-center text-nowrap">18</th>
                        <th class="fw-bold text-center text-nowrap">19</th>
                        <th class="fw-bold text-center text-nowrap">20</th>
                        <th class="fw-bold text-center text-nowrap">21</th>
                        <th class="fw-bold text-center text-nowrap">22</th>
                        <th class="fw-bold text-center text-nowrap">23</th>
                        <th class="fw-bold text-center text-nowrap">24</th>
                        <th class="fw-bold text-center text-nowrap">25</th>
                        <th class="fw-bold text-center text-nowrap">26</th>
                        <th class="fw-bold text-center text-nowrap">27</th>
                        <th class="fw-bold text-center text-nowrap">28</th>
                        <th class="fw-bold text-center text-nowrap">29</th>
                        <th class="fw-bold text-center text-nowrap">30</th>
                        <th class="fw-bold text-center text-nowrap">31</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php $no = 0;
                        $no++;
                        foreach($membermb as $mm) : ?>
                        <tr>
                            <td class="text-end text-nowrap"><?= $no++; ?></td>
                            <td class="text-center text-nowrap"><?= substr($mm['AKT_BULAN'],5,2); ?> - <?= substr($mm['AKT_BULAN'],0,4); ?> </td>
                            <td class="text-end text-nowrap"><?= number_format($jumlahMember, 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['1_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['2_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['3_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['4_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['5_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['6_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['7_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['8_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['9_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['10_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['11_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['12_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['13_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['14_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['15_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['16_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['17_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['18_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['19_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['20_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['21_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['22_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['23_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['24_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['25_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['26_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['27_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['28_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['29_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['30_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mm['31_AKT_MEMBER'], 0, '.', ','); ?></td>
                            <td></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>