<?= $this->extend('admin/template') ?>

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
    <div class="card border card-border-success">
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
    <div class="card border card-border-success">
      <div class="card-body">
        <form method="post" action="<?= site_url('usulan/save') ?>" class="" id="pengantar">
          <input type="hidden" name="id" id="usulid" value="<?= $usulan->id ?>">
          <div class="row mb-4">
            <label for="nomor_usul" class="col-sm-3 col-form-label">Nomor Surat Pengantar</label>
            <div class="col-sm-9">
              <input type="text" name="nomor_pengantar" class="form-control" id="nomor_usul" value="<?= $usulan->prov_pengantar_nomor ?>" disabled>
            </div>
          </div>
          <div class="row mb-4">
            <label for="tanggal_usul" class="col-sm-3 col-form-label">Tanggal Surat Pengantar</label>
            <div class="col-sm-9">
              <input type="date" name="tanggal_pengantar" class="form-control" id="tanggal_usul" value="<?= $usulan->prov_pengantar_tanggal ?>" disabled>
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
              <input type="text" name="penandatangan" class="form-control" id="penandatangan_usul" value="<?= $usulan->prov_pengantar_nama ?>" disabled>
            </div>
          </div>
          <div class="row mb-4">
            <label for="perihal" class="col-sm-3 col-form-label">Jabatan Penandatangan Usul</label>
            <div class="col-sm-9">
              <input type="text" name="jabatan_penandatangan" class="form-control" id="penandatangan_jabatan" value="<?= $usulan->prov_pengantar_jabatan ?>" disabled>
            </div>
          </div>
          <div class="row mb-4">
            <label for="perihal" class="col-sm-3 col-form-label">Alasan</label>
            <div class="col-sm-9">
              <?= alasanFormat($usulan->alasan);?>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="card border card-border-success">
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
            <tr>
                <td>Surat Pengantar Kankemenag</td>
                <td><a href="javascript:;" onclick="preview('https://ropeg.kemenag.go.id:9000/layanan/dokumen/<?= $usulan->kab_pengantar_file?>')">Lihat Dokumen</a></td>
              </tr>
              <tr>
                <td>Surat Pengantar Kanwil</td>
                <td><a href="javascript:;" onclick="preview('https://ropeg.kemenag.go.id:9000/layanan/dokumen/<?= $usulan->prov_pengantar_file?>')">Lihat Dokumen</a></td>
              </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="card border card-border-success">
        <div class="card-header">
            <a href="<?= site_url('usulan/draftpengantar/'.encrypt($usulan->id))?>" type="button" class="btn btn-success float-end fs-11">Generate Draft</a>
            <h6 class="card-title mb-0">Usul Tanda Tangan Surat Rekomendasi</h6>
        </div>
      <div class="card-body">
      <form method="post" action="<?= site_url('usulan/rekomendasi') ?>" class="" id="pengantar2" enctype="multipart/form-data">
          <input type="hidden" name="id" id="usulid" value="<?= $usulan->id ?>">
          <div class="row mb-3">
        <div class="col-lg-3">
            <label for="rekomendasi_nomor" class="form-label">Nomor Surat Rekomendasi</label>
        </div>
        <div class="col-lg-9">
            <input type="text" class="form-control" id="rekomendasi_nomor" name="rekomendasi_nomor" value="<?= $usulan->rekomendasi_nomor?>">
        </div>
    </div>
          <div class="row mb-3">
        <div class="col-lg-3">
            <label for="rekomendasi_tanggal" class="form-label">Tanggal Surat Rekomendasi</label>
        </div>
        <div class="col-lg-9">
            <input type="date" class="form-control" id="rekomendasi_tanggal" name="rekomendasi_tanggal" value="<?= $usulan->rekomendasi_tanggal?>">
        </div>
    </div>
          <div class="row mb-3">
        <div class="col-lg-3">
            <label for="rekomendasi_tanggal" class="form-label">Rekomendasi Persetujuan</label>
        </div>
        <div class="col-lg-9">
            <select name="rekomendasi_setuju" id="rekomendasi_setuju" class="form-select">
              <option value="1">Disetujui</option>
              <option value="2">Tidak Disetujui</option>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-lg-3">
            <label for="rekomendasi_pengantar_file" class="form-label">Lampiran Surat Pengantar</label>
        </div>
        <div class="col-lg-9">
        <input type="file" class="form-control" name="rekomendasi_pengantar_file" id="rekomendasi_pengantar_file" aria-describedby="rekomendasi_pengantar_file" aria-label="Upload">
        </div>
      </div>
    <div class="row mb-3">
        <div class="col-lg-3">
            <label for="rekomendasi_file" class="form-label">Lampiran Surat Rekomendasi</label>
        </div>
        <div class="col-lg-9">
        <input type="file" class="form-control" name="rekomendasi_file" id="rekomendasi_file" aria-describedby="rekomendasi_file" aria-label="Upload">
        </div>
      </div>
    <div class="row mb-3">
        <div class="col-lg-3">
        </div>
        <div class="col-lg-9">
        <input type="submit" name="submit" class="btn btn-outline-success" value="Simpan">
        </div>
      </div>
        </form>
      </div>
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