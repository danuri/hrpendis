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
            <h4 class="mb-sm-0">Master Pengelola</h4>

            <div class="page-title-right">
              <ol class="breadcrumb m-0">
                <li class="breadcrumb-item "><button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#addModal">Tambah Pengelola</button></li>
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
                  <th>NIP/Nama</th>
                  <th>Jabatan</th>
                  <th>Satuan Kerja</th>
                  <th>Role</th>
                  <th>Opsi</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($pengelola as $row) {?>
                  <tr>
                    <td><?= $row->nip?><br><strong><?= $row->nama?></strong></td>
                    <td><?= $row->jabatan?></td>
                    <td><?= $row->satker?></td>
                    <td><?= $row->role?></td>
                    <td></td>
                  </tr>
                <?php } ?>
              </tbody>
              <tfoot>
                <tr>
                  <th>NIP/Nama</th>
                  <th>Jabatan</th>
                  <th>Satuan Kerja</th>
                  <th>Role</th>
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
                <h5 class="modal-title" id="myModalLabel">Tambah Pengelola Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="object">
              <form action="" id="formadd" class="row g-3" method="post">
    <div class="col-md-12">
        <label for="nip" class="form-label">NIP</label>
        <div class="input-group">
          <input type="number" class="form-control" name="nip" id="nip" required>
          <button class="btn btn-outline-success" type="button" id="cari">Cari Pegawai</button>
        </div>
    </div>
    <div class="col-md-12">
        <label for="nama" class="form-label">Nama</label>
        <input type="text" name="nama" class="form-control" id="nama" readonly>
    </div>
    <div class="col-md-12">
        <label for="Jabatan" class="form-label">Jabatan</label>
        <input type="text" name="jabatan" class="form-control" id="jabatan" readonly>
    </div>
    <div class="col-md-12">
        <label for="satker" class="form-label">Satuan Kerja</label>
        <input type="text" name="satker" class="form-control" id="satker" readonly>
        <input type="hidden" name="kode_satker" id="kode_satker" value="">
    </div>
    <div class="col-md-12">
      <label for="no_hp" class="form-label">No HP</label>
      <input type="text" name="no_hp" class="form-control" id="no_hp" required>
    </div>
    <div class="col-md-12">
        <label for="role" class="form-label">Role</label>
        <select class="form-select" name="role" id="role" required>
          <option value="1">Operator</option>
          <option value="2">Penanda Tangan</option>
        </select>
    </div>
</form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary waves-effect" onclick="$('#formadd').submit()">Simpan</button>
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

  $('#cari').on('click', function(event) {
    $nip = $('#nip').val();

    if($nip == ''){
      alert('NIP tidak boleh kosong');
    }else{
      axios.get('<?= site_url()?>admin/ajax/pegawai/'+$nip)
      .then(function (response) {
        $('#nama').val(response.data.data.NAMA_LENGKAP);
        $('#jabatan').val(response.data.data.TAMPIL_JABATAN);
        $('#satker').val(response.data.data.SATKER_3);
        $('#kode_satker').val(response.data.data.KODE_SATKER_3);
      });
    }

  });
});
function preview(id) {
  $('#preview').modal('show');
}
</script>
<?= $this->endSection() ?>
