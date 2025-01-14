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
              <th>STATUS</th>
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
        <div class="card-body">
            <h6 class="card-title mb-0">Surat Rekomendasi</h6>
            <table class="table">
              <tr>
                <td>Surat Pengantar Rekomendasi</td>
                <td><a href="javascript:;" type="button" class="btn btn-success float-end fs-11" onclick="preview('https://ropeg.kemenag.go.id:9000/layanan/dokumen/<?= $usulan->rekomendasi_pengantar_file?>')">Lihat</a></td>
                <td>Belum di TTE</td>
              </tr>
              <tr>
                <td>Surat Rekomendasi</td>
                <td><a href="javascript:;" type="button" class="btn btn-success float-end fs-11" onclick="preview('https://ropeg.kemenag.go.id:9000/layanan/dokumen/<?= $usulan->rekomendasi_file?>')">Lihat</a></td>
                <td>Belum di TTE</td>
              </tr>
            </table>
        </div>
    </div>

    <div class="text-end mb-5">
      <?php if($usulan->status < 13){ ?>
        <button type="submit" class="btn btn-danger" onclick="declined()">Kembalikan</button>
        <a href="<?= site_url('usulan/detail/pengantar/'.encrypt($usulan->id))?>" class="btn btn-primary d-none" id="btnNext">Selanjutnya</a>
        <?php } ?>
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
    cekVerifikasi();
    
    $('.formcheck').change(function(event) {
    if(this.checked) {
      $.get('<?= site_url('dokumen/validasi');?>/'+this.id+'/1', function() {
        alert('Berkas divalidasi');
      });
    }else{
      $.get('<?= site_url('dokumen/validasi');?>/'+this.id+'/0', function() {
        alert('Berkas belum divalidasi');
      });
    }
    cekVerifikasi();
  });

  });

  function cekVerifikasi() {
    jdoc = <?= count($dokumen)?>;
    jver = $('.formcheck:checked').length;

    if(jdoc == jver){
      console.log('ok');
      $('#btnNext').removeClass('d-none');
    }else{
      console.log('no');
      $('#btnNext').addClass('d-none');
    }
  }

  function cari() {
    $nip = $('#nip').val();

    axios.get('<?= site_url() ?>ajax/pegawai/' + $nip)
      .then(function(response) {
        console.log(response.data.data);
      });
  }

  function uploadfile(id) {
    $('#form' + id).ajaxSubmit({
      // target: '#output'+id,
      beforeSubmit: function(a, f, o) {
        alert('Mengunggah');
      },
      success: function(data) {
        if (data.status == 'error') {
          Swal.fire({
            title: "Ooppss...",
            text: data.message,
            icon: "error",
            confirmButtonColor: "#5b73e8"
          });
        } else {
          Swal.fire({
            html: "Dokumen telah diunggah",
            confirmButtonColor: "#5b73e8"
          });
          $('#output' + id).html(data.message);
        }
      }
    });
  }

  function preview(berkas) {
    $('#object').html('<object data="' + berkas + '" type="application/pdf" width="100%" style="height: 80vh;" id="object">' +
      '<p>Browser tidak mendukung!</p>' +
      '</object>');
    $('#preview').modal('show');
  }

  function declined() {
  Swal.fire({
    text: 'Masukan informasi pengembalian!',
    input: 'text',
    inputAttributes: {
      autocapitalize: 'off'
    },
    showCancelButton: true,
    confirmButtonText: 'Kembalikan Berkas',
    showLoaderOnConfirm: true,
    preConfirm: (data) => {
      return fetch('<?= site_url('usulan/decline/'.encrypt($usulan->id)) ?>', {
        method: "POST",
        body: JSON.stringify({ keterangan: data }),
        headers: {"Content-type": "application/json; charset=UTF-8"}})
        .then(response => {
          if (!response.ok) {
            throw new Error(response.statusText)
          }
          return response.json()
        })
        .catch(error => {
          Swal.showValidationMessage(
            `Request failed: ${error}`
          )
        })
      },
      allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
      if (result.isConfirmed) {
        // redirect
      }
    });
  }

  function validate(formData, jqForm, options) {
    for (var i = 0; i < formData.length; i++) {
      if (!formData[i].value) {
        alert('Harap isi dengan lengkap!');
        return false;
      }
    }

    return true;
  }
</script>
<?= $this->endSection() ?>