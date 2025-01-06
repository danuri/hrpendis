<?= $this->extend('admin/template') ?>

<?= $this->section('style') ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style media="screen">

</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0">Master Download</h4>

      <div class="page-title-right">
        <ol class="breadcrumb m-0">
          <li class="breadcrumb-item "><button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#addModal">Unggah Dokumen Baru</button></li>
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
                <th>Opsi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($download as $row) { ?>
                <tr>
                  <td><?= $row->nama ?></td>
                  <td><?= $row->keterangan ?></td>
                  <td><a href="https://ropeg.kemenag.go.id:9000/layanan/dokumen/<?= $row->lampiran ?>" target="_blank"><?= $row->lampiran ?></a></td>
                  <td><?= $row->created_at ?></td>
                  <td><a href="<?= site_url('master/download/delete/' . $row->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Dokumen akan dihapus?')">Delete</a></td>
                </tr>
              <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <th>Layanan</th>
                <th>Kode</th>
                <th>Keterangan</th>
                <th>Dibuat Pada</th>
                <th>Opsi</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div><!--end col-->
  </div>
</div>

<div id="addModal" class="modal fade" data-bs-backdrop="static" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" data-bs-scroll="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Unggah Dokumen Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="object">
        <form action="<?= site_url('master/download/save')?>" method="POST" id="formadd">
          <div class="row mb-3">
            <div class="col-lg-3">
              <label for="nama" class="form-label">Nama Dokumen</label>
            </div>
            <div class="col-lg-9">
              <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Dokumen">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-lg-3">
              <label for="keterangan" class="form-label">Keterangan</label>
            </div>
            <div class="col-lg-9">
              <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Keterangan Layanan"></textarea>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-lg-3">
              <label for="keterangan" class="form-label">Lampiran</label>
            </div>
            <div class="col-lg-9">
              <input type="file" class="form-control" name="lampiran" id="lampiran">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-success waves-effect" onclick="$('#formadd').submit()">Simpan</button>
      </div>
    </div>
  </div>
</div>

<div id="preview" class="modal fade" data-bs-backdrop="static" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" data-bs-scroll="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Preview Usulan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
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
<script type="text/javascript">
  $(document).ready(function() {

    var table = $('#datatables').DataTable();
  });

  function preview(id) {
    $('#preview').modal('show');
  }
</script>
<?= $this->endSection() ?>