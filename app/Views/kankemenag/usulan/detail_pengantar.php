<?= $this->extend('template') ?>

<?= $this->section('style') ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0">Detail Usulan</h4>

      <div class="page-title-right">
        <ol class="breadcrumb m-0">
          <!-- <li class="breadcrumb-item "><button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#myModal">Buat Usul</button></li> -->
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-body p-4">
        <div class="row g-4">
          <div class="col-auto">
            <div class="avatar-lg">
              <img src="<?= base_url() ?>assets/images/kemenag.png" alt="user-img" class="img-thumbnail rounded-circle">
            </div>
          </div>
          <!--end col-->
          <div class="col">
            <div class="p-2">
              <h3 class="mb-1"><?= $usulan->nama ?></h3>
              <p class="text-opacity-75"><?= $usulan->jabatan ?></p>
              <div class="hstack gap-1">
                <div class="me-2"><?= $usulan->satker ?></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <form method="post" action="<?= site_url('usulan/save') ?>" class="" id="pengantar">
          <input type="hidden" name="id" id="usulid" value="<?= $usulan->id ?>">
          <div class="row mb-4">
            <label for="nomor_usul" class="col-sm-3 col-form-label">Nomor Surat Pengantar</label>
            <div class="col-sm-9">
              <input type="text" name="nomor_pengantar" class="form-control" id="nomor_usul" value="<?= $usulan->nomor_pengantar ?>" disabled>
            </div>
          </div>
          <div class="row mb-4">
            <label for="tanggal_usul" class="col-sm-3 col-form-label">Tanggal Surat Pengantar</label>
            <div class="col-sm-9">
              <input type="date" name="tanggal_pengantar" class="form-control" id="tanggal_usul" value="<?= $usulan->tanggal_pengantar ?>" disabled>
            </div>
          </div>
          <div class="row mb-4">
            <label for="perihal" class="col-sm-3 col-form-label">Perihal</label>
            <div class="col-sm-9">
              <input type="text" name="perihal" class="form-control" id="perihal" value="<?= $usulan->perihal ?>" disabled>
            </div>
          </div>
          <div class="row mb-4">
            <label for="perihal" class="col-sm-3 col-form-label">Nama Penandatangan Usul</label>
            <div class="col-sm-9">
              <input type="text" name="penandatangan" class="form-control" id="penandatangan_usul" value="<?= $usulan->penandatangan ?>" disabled>
            </div>
          </div>
          <div class="row mb-4">
            <label for="perihal" class="col-sm-3 col-form-label">Jabatan Penandatangan Usul</label>
            <div class="col-sm-9">
              <input type="text" name="jabatan_penandatangan" class="form-control" id="penandatangan_jabatan" value="<?= $usulan->jabatan_penandatangan ?>" disabled>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>DOKUMEN</th>
              <th>LAMPIRAN</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($dokumen as $row) { ?>
              <tr>
                <td><?= $row->keterangan ?></td>
                <td id="output<?= $row->id ?>"><?= ($row->lampiran) ? '<a href="javascript:;" onclick="preview(\'https://ropeg.kemenag.go.id:9000/layanan/dokumen/' . $row->lampiran . '\')">Lihat Dokumen</a>' : 'Belum Diunggah'; ?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Lampirkan Surat Pengantar dari Kankemenag</h5>
        </div>
      <div class="card-body">
      <form method="post" action="<?= site_url('usulan/save') ?>" class="" id="pengantar">
          <input type="hidden" name="id" id="usulid" value="<?= $usulan->id ?>">
          <div class="row mb-4">
            <label for="perihal" class="col-sm-3 col-form-label">Perihal</label>
            <div class="col-sm-9">
              <input type="text" name="kab_pengantar" class="form-control" id="kab_pengantar" value="<?= $usulan->kab_pengantar ?>">
            </div>
          </div>
          <div class="row mb-4">
            <label for="perihal" class="col-sm-3 col-form-label">Lampiran</label>
            <div class="col-sm-9">
            <div class="input-group">
                <input type="file" class="form-control" name="kab_pengantar_file" id="inputGroup" aria-describedby="kab_pengantar_file" aria-label="Upload">
                <button class="btn btn-outline-success" type="button" id="kab_pengantar_file">Simpan</button>
            </div>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="text-end mb-5">
      <a href="<?= site_url('usulan/submit/'.encrypt($usulan->id))?>" class="btn btn-primary" id="btnNext">Kirim Ke Kanwil</a>
    </div>
  </div>
</div>

<div id="preview" class="modal fade" data-bs-backdrop="static" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" data-bs-scroll="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-body" id="object">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://malsup.github.io/jquery.form.js" charset="utf-8"></script>
<script type="text/javascript">
  $(document).ready(function() {

  });


  function preview(berkas) {
    $('#object').html('<object data="' + berkas + '" type="application/pdf" width="100%" style="height: 80vh;" id="object">' +
      '<p>Browser tidak mendukung!</p>' +
      '</object>');
    $('#preview').modal('show');
  }

</script>
<?= $this->endSection() ?>