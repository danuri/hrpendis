<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0">Download Dokumen</h4>

      <div class="page-title-right">
        <ol class="breadcrumb m-0">
          
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-12">

    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">

          <table id="datatables" class="display table table-bordered dt-responsive fonttab" style="width:100%">
            <thead>
              <tr>
                <th>Dokumen</th>
                <th>Keterangan</th>
                <th>Lampiran</th>
                <th>Dibuat Pada</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($download as $row) { ?>
                <tr>
                  <td><?= $row->nama ?></td>
                  <td><?= $row->keterangan ?></td>
                  <td><a href="https://ropeg.kemenag.go.id:9000/layanan/dokumen/<?= $row->lampiran ?>" target="_blank"><?= $row->lampiran ?></a></td>
                  <td><?= $row->created_at ?></td>
                </tr>
              <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <th>Layanan</th>
                <th>Kode</th>
                <th>Keterangan</th>
                <th>Dibuat Pada</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div><!--end col-->
  </div>
</div>
<?= $this->endSection() ?>