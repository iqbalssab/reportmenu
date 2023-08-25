<?php $this->extend('layout/template'); ?>
<?php $this->section('content'); ?>

 <!-- ======= Hero Section ======= -->
 <section id="hero">
    <div class="hero">
      <h1 class="text-light ms-3">Welcome to INDOGROSIR</h1>
      <h2 class="text-light ms-5">MITRA USAHA TERPERCAYA</h2>
    <!-- card atas -->
      <div class="card ms-5 me-5 bg-dark-subtle ">
        <div class="card-body">
          <!-- <h1>
            QUICK LAUNCH
          </h1> -->

        </div>
        <div class="d-grid gap-2 mb-4">
          <a class="btn btn-primary btn-lg" href="http://192.168.240.190:81/login" role="button">Login IAS</a>
          <a class="btn btn-secondary btn-lg" href="https://ess-online.hrindomaret.com/" role="button">Login ICM</a>
        </div>
      </div>
    </div>

    <!-- end -->

    <!-- card bawah -->

      <div class="card ms-5 me-5 mt-2 bg-dark-subtle">
        <div class="card-body-">

        </div>
        <div class="container">

          <div class="row">
            <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
              <div class="card" style="width: 18rem;">
                <div class="card-header bg-warning">
                  ESS
                </div>
                <ul class="list-group list-group-flush mb-2">
                  <div class="d-grid gap-2 col-xxl-10 ">
                    <a class="btn btn-primary" href="https://ess-online.hrindomaret.com/ " type="button">Login ESS</a>
                    <a class="btn btn-primary" href="https://ess-online.hrindomaret.com/ " type="button">Login TSM</a>
                  </div>
                </ul>
              </div>
            </div>
  
            <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
              <div class="card" style="width: 18rem;">
                <div class="card-header">
                  Featured
                </div>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item">An item</li>
                  <li class="list-group-item">A second item</li>
                  <li class="list-group-item">A third item</li>
                </ul>
              </div>
            </div>
  
            <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
              <div class="card" style="width: 18rem;">
                <div class="card-header">
                  Featured
                </div>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item">An item</li>
                  <li class="list-group-item">A second item</li>
                  <li class="list-group-item">A third item</li>
                </ul>
              </div>
            </div>
  
          </div>
      </div>
    </div>

    <!-- end -->
  </section>

<?php $this->endSection(); ?>