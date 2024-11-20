<?= $this->extend('template') ?>

<?= $this->section('style') ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style media="screen">

</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Data Usulan</h4>

            <div class="page-title-right">
            </div>
        </div>
    </div>
</div>

<div class="row">
  <div class="col-lg-12">

      <div class="col-lg-12">
        <div class="card border card-border-success">
          <div class="card-body">
            <form action="javascript:void(0);" class="row g-3">
                <div class="col-md-4">
                    <label for="layanan" class="form-label">Jenis Layanan</label>
                    <select id="layanan" name="layanan" class="form-select">
                      <option value="">Semua Layanan</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="statuslayanan" class="form-label">Status</label>
                    <select id="statuslayanan" name="statuslayanan" class="form-select">
                        <option value="">Semua</option>
                    </select>
                </div>
            </form>
          </div>
        </div>

        <div class="card border card-border-success">
          <div class="card-body">

            <table id="datatables" class="display table table-bordered dt-responsive fonttab" style="width:100%">
              <thead>
                <tr>
                  <th>Nama/NIP</th>
                  <th>Jenis</th>
                  <th>Tanggal</th>
                  <th>Nomor Surat</th>
                  <th>Perihal</th>
                  <th>Status</th>
                  <th>Opsi</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
              <tfoot>
                <tr>
                  <th>Nama/NIP</th>
                  <th>Jenis</th>
                  <th>Tanggal</th>
                  <th>Nomor Surat</th>
                  <th>Perihal</th>
                  <th>Status</th>
                  <th>Opsi</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div><!--end col-->
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

<div id="log" class="modal fade" data-bs-backdrop="static" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" data-bs-scroll="true">
    <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Progres Usulan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                                                <div class="vertical-timeline-item vertical-timeline-element">
                                                    <div>
                                                        <span class="vertical-timeline-element-icon bounce-in">
                                                            <input class="form-check-input" type="radio" name="formradiocolor3" id="formradioRight7" checked="">
                                                        </span>
                                                        <div class="vertical-timeline-element-content bounce-in">
                                                            <h4 class="timeline-title text-success">Usulan Selesai</h4>
                                                            <p>Yet another one, at <span class="text-success">5:00 PM</span></p>
                                                            <span class="vertical-timeline-element-date">10 Okt 2024, 10:19 WIB</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="vertical-timeline-item vertical-timeline-element">
                                                    <div>
                                                        <span class="vertical-timeline-element-icon bounce-in">
                                                            <input class="form-check-input" type="radio" name="formradiocolor7" id="formradioRight11" disabled>
                                                        </span>
                                                        <div class="vertical-timeline-element-content bounce-in">
                                                            <h4 class="timeline-title">Usulan diterbitkan Surat Rekomendasi</h4>
                                                            <p>meeting with team mates about the launch of new product. and tell them about new features</p>
                                                            <span class="vertical-timeline-element-date">10 Okt 2024, 10:19 WIB</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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

  var table = $('#datatables').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '<?= site_url('usulan/getdata')?>',
            data: function (d) {
                d.layanan = $('#layanan').val(),
                d.status = $('#statuslayanan').val();
            }
        },
        columns: [
            {data: 'nama'},
            {data: 'layanan'},
            {data: 'created_at'},
            {data: 'nomor_pengantar'},
            {data: 'perihal'},
            {data: 'status'},
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
});

function getsatker($id) {
  axios.get('manajemen/pegawai/getcountsatker/'+$id)
  .then(function (response) {
    $('#jumlahpegawai').html(response.data);
  });
}

function preview(id) {
  $('#preview').modal('show');
}

function log(id) {
  $('#log').modal('show');
}
</script>
<?= $this->endSection() ?>
