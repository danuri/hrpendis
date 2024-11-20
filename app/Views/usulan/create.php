<?= $this->extend('template') ?>

<?= $this->section('style') ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0">Buat Draft Usulan</h4>

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

    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-4">
              <img src="<?= base_url()?>assets/images/verification-img.png" width="100%" alt="">
            </div>
            <div class="col-8">
              <form class="" action="<?= site_url('usulan/create')?>" method="post">
                <div class="row mb-4">
                  <label for="nomor_usul" class="col-sm-3 col-form-label">Cari NIP Pegawai</label>
                  <div class="col-sm-9">
                    <div class="input-group">
                      <input type="number" class="form-control" name="nip" id="nip" required>
                      <button class="btn btn-outline-success" type="button" id="cari">Cari Pegawai</button>
                    </div>
                  </div>
                </div>
                <div class="row mb-4">
                  <label for="nomor_usul" class="col-sm-3 col-form-label">Nama</label>
                  <div class="col-sm-9">
                    <input type="text" name="nama" class="form-control" id="nama" readonly>
                  </div>
                </div>
                <div class="row mb-4">
                  <label for="nomor_usul" class="col-sm-3 col-form-label">Jabatan</label>
                  <div class="col-sm-9">
                    <input type="text" name="jabatan" class="form-control" id="jabatan" readonly>
                  </div>
                </div>
                <div class="row mb-4">
                  <label for="nomor_usul" class="col-sm-3 col-form-label">Satuan Kerja</label>
                  <div class="col-sm-9">
                    <input type="text" name="satker" class="form-control" id="satker" readonly>
                    <input type="hidden" name="kode_satker" id="kode_satker" value="">
                  </div>
                </div>
                <div class="row mb-4">
                  <label for="nomor_usul" class="col-sm-3 col-form-label">Jenis Usul</label>
                  <div class="col-sm-9">
                    <select class="form-select" name="layanan" id="layanan" required>
                      <option value="">Pilih Layanan</option>
                      <?php foreach ($layanan as $row) {
                        echo '<option value="'.$row->id.'">'.$row->layanan.'</option>';
                      } ?>
                    </select>
                  </div>
                </div>
                <div class="d-flex align-items-start gap-3 mt-4">
                    <button type="submit" class="btn btn-success btn-label right ms-auto"><i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Berikutnya</button>
                </div>
              </form>
            </div>
          </div>

          </div>
        </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://d2mj1s7x3czrue.cloudfront.net/hrms/assets/js/pages/form-wizard.init.js"></script>
<script type="text/javascript">
$(document).ready(function() {

  var table = $('#datatables').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: '<?= site_url('manajemen/pegawai/getdata')?>',
      data: function (d) {
        d.jabatan = $('#jabatan').val(),
        d.jenis = $('#jenis').val();
        d.unit = $('#unit').val();
      }
    },
    columns: [
      {data: 'NIP_BARU'},
      {data: 'NAMA_LENGKAP'},
      {data: 'TAMPIL_JABATAN'},
      {data: 'SATKER_2'},
      {data: 'SATKER_4'},
      {data: 'STATUS_PEGAWAI'},
      {data: 'action', orderable: false}
    ]
  });

  $(".select2").select2()
  $('#satker1').on('change', function(event) {
    getsatker($('#satker1').val());
    $('#selectsatker2').css('display','');
  });

  $('#satker2').on('change', function(event) {
    getsatker($('#satker2').val());
    $('#selectsatker3').css('display','');
  });

  $('#jabatan').change(function(event) {
    table.ajax.reload();
  });

  $('#jenis').change(function(event) {
    table.ajax.reload();
  });

  $('#unit').change(function(event) {
    table.ajax.reload();
  });

  $('#cari').on('click', function(event) {
    $nip = $('#nip').val();

    if($nip == ''){
      alert('NIP tidak boleh kosong');
    }else{
      axios.get('<?= site_url()?>ajax/pegawai/'+$nip)
      .then(function (response) {
        $('#nama').val(response.data.data.NAMA_LENGKAP);
        $('#jabatan').val(response.data.data.TAMPIL_JABATAN);
        $('#satker').val(response.data.data.SATKER_3);
        $('#kode_satker').val(response.data.data.KODE_SATKER_3);
      });
    }

  });
});

function cari() {
  $nip = $('#nip').val();

  axios.get('<?= site_url()?>ajax/pegawai/'+$nip)
  .then(function (response) {
    console.log(response.data.data);
  });
}
</script>
<?= $this->endSection() ?>
