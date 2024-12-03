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
            <h4 class="mb-sm-0">Dokumen Layanan</h4>

            <div class="page-title-right">
              <ol class="breadcrumb m-0">
                <li class=" "><a href="<?= site_url('master/layanan')?>" class="btn btn-success">Kembali</a></li>
                <li class=" "><button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#addModal">Tambah Dokumen Persyaratan</button></li>
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
                  <th>Wajib</th>
                  <th>Opsi</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($dokumen as $row) {?>
                  <tr>
                    <td><?= $row->nama_dokumen?></td>
                    <td><?= $row->keterangan?></td>
                    <td><?= $row->wajib?></td>
                    <td><a href="<?= site_url('master/layanan/deletedokumen/'.$row->id)?>" class="btn btn-sm btn-danger" onclick="return confirm('Dokumen akan dihapus dari layanan?')">Delete</a></td>
                  </tr>
                <?php } ?>
              </tbody>
              <tfoot>
                <tr>
                    <th>Dokumen</th>
                  <th>Keterangan</th>
                  <th>Wajib</th>
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
                <h5 class="modal-title" id="myModalLabel">Buat Layanan Baru</h5>
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
