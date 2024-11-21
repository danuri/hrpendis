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
            <li class="breadcrumb-item"><a href="<?- site_url('usulan')?>">Usulan</a></li>
            <li class="breadcrumb-item active">Detail Usulan</li>
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
              <img src="<?= base_url()?>assets/images/kemenag.jpg" alt="user-img" class="img-thumbnail rounded-circle">
            </div>
          </div>
          <!--end col-->
          <div class="col">
            <div class="p-2">
              <h3 class="mb-1"><?= $usulan->nama?></h3>
              <p class="text-opacity-75"><?= $usulan->jabatan?></p>
              <div class="hstack gap-1">
                <div class="me-2"><?= $usulan->satker?></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card border card-border-success">
      <div class="card-body">
        <div class="vertical-navs-step form-steps">
          <div class="row gy-5">
            <div class="col-lg-4">
              <div class="nav flex-column custom-nav nav-pills" role="tablist" aria-orientation="vertical">
                <button class="nav-link active" id="v-pills-1-tab" data-bs-toggle="pill" data-bs-target="#v-pills-1" type="button" role="tab" aria-controls="v-pills-1" aria-selected="true">
                  <span class="step-title me-2">
                    <i class="ri-close-circle-fill step-icon me-2"></i> Step 1
                  </span>
                  Pengantar
                </button>
                <button class="nav-link" id="v-pills-2-tab" data-bs-toggle="pill" data-bs-target="#v-pills-2" type="button" role="tab" aria-controls="v-pills-bill-2" aria-selected="false">
                  <span class="step-title me-2">
                    <i class="ri-close-circle-fill step-icon me-2"></i> Step 2
                  </span>
                  Dokumen Persyaratan
                </button>
                <button class="nav-link" id="v-pills-3-tab" data-bs-toggle="pill" data-bs-target="#v-pills-3" type="button" role="tab" aria-controls="v-pills-3" aria-selected="false" disabled>
                  <span class="step-title me-2">
                    <i class="ri-close-circle-fill step-icon me-2"></i> Step 3
                  </span>
                  Review
                </button>
              </div>
              <!-- end nav -->
            </div> <!-- end col-->
            <div class="col-lg-8">
              <div class="px-lg-4">
                <div class="tab-content">
                  <div class="tab-pane fade show active" id="v-pills-1" role="tabpanel" aria-labelledby="v-pills-1-tab">
                    <div>
                      <h5>Lengkapi Data</h5>
                      <p class="text-muted">Isi sesuai Surat Pengantar</p>
                      <hr>
                    </div>
                    <form method="post" action="<?= site_url('usulan/save')?>" class="" id="pengantar">
                      <input type="hidden" name="id" id="usulid" value="<?= $usulan->id?>">
                    <div class="row mb-4">
                      <label for="nomor_usul" class="col-sm-3 col-form-label">Nomor Surat Pengantar</label>
                      <div class="col-sm-9">
                        <input type="text" name="nomor_pengantar" class="form-control" id="nomor_usul" value="<?= $usulan->nomor_pengantar?>" required>
                      </div>
                    </div>
                    <div class="row mb-4">
                      <label for="tanggal_usul" class="col-sm-3 col-form-label">Tanggal Surat Pengantar</label>
                      <div class="col-sm-9">
                        <input type="date" name="tanggal_pengantar" class="form-control" id="tanggal_usul" value="<?= $usulan->tanggal_pengantar?>" required>
                      </div>
                    </div>
                    <div class="row mb-4">
                      <label for="perihal" class="col-sm-3 col-form-label">Perihal</label>
                      <div class="col-sm-9">
                        <input type="text" name="perihal" class="form-control" id="perihal" value="<?= $usulan->perihal?>" required>
                      </div>
                    </div>
                    <div class="row mb-4">
                      <label for="perihal" class="col-sm-3 col-form-label">Nama Penandatangan Usul</label>
                      <div class="col-sm-9">
                        <input type="text" name="penandatangan" class="form-control" id="penandatangan_usul" value="<?= $usulan->penandatangan?>" required>
                      </div>
                    </div>
                    <div class="row mb-4">
                      <label for="perihal" class="col-sm-3 col-form-label">Jabatan Penandatangan Usul</label>
                      <div class="col-sm-9">
                        <input type="text" name="jabatan_penandatangan" class="form-control" id="penandatangan_jabatan" value="<?= $usulan->jabatan_penandatangan?>" required>
                      </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mt-4">
                      <button type="button" class="btn btn-warning btn-label right ms-auto nexttab" data-nexttab="v-pills-2-tab"><i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Berikutnya</button>
                    </div>
                    </form>
                  </div>
                  <!-- end tab pane -->
                  <div class="tab-pane fade" id="v-pills-2" role="tabpanel" aria-labelledby="v-pills-2-tab">
                    <div>
                      <h5>Lengkapi Dokumen</h5>
                      <p class="text-muted">Fill all information below</p>
                      <hr>
                    </div>
                    <table class="table table-bordered table-striped">
                      <thead>
                        <tr>
                          <th>DOKUMEN</th>
                          <th>STATUS</th>
                          <th>UNGGAH</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($dokumen as $row) { ?>
                          <tr>
                            <td><?= $row->keterangan?></td>
                            <td id="output<?= $row->id?>"><?= ($row->lampiran)?'<a href="javascript:;" onclick="preview(\'https://ropeg.kemenag.go.id:9000/layanan/dokumen/'.$row->lampiran.'\')">Lihat Dokumen</a>':'Belum Diunggah';?></td>
                            <td>
                              <button type="button" class="btn btn-soft-danger waves-effect waves-light btn-sm" onclick="$('#file<?= $row->id?>').click()"><i class="bx bx-upload align-middle"></i></button>
                              <form method="POST" action="<?= site_url('dokumen/upload') ?>" style="display: none;" id="form<?= $row->id ?>" enctype="multipart/form-data">
                                <input type="hidden" name="nip" value="<?= $usulan->nip ?>">
                                <input type="hidden" name="usul" value="<?= encrypt($usulan->id) ?>">
                                <input type="hidden" name="iddok" value="<?= $row->dokumen ?>">
                                <input type="hidden" name="layanan" value="<?= $row->layanan ?>">
                                <input type="file" name="dokumen" id="file<?=$row->id ?>" onchange="uploadfile('<?= $row->id ?>')">
                              </form>
                            </td>
                          </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                    <div class="d-flex align-items-start gap-3 mt-4">
                      <button type="button" class="btn btn-light btn-label previestab" data-previous="v-pills-1-tab"><i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i> Kembali</button>
                      <button type="button" class="btn btn-warning btn-label right ms-auto nexttab" data-nexttab="v-pills-3-tab" id="reviewbutton"  disabled><i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Review Usulan</button>
                    </div>
                  </div>
                  <!-- end tab pane -->

                  <!-- end tab pane -->
                  <div class="tab-pane fade" id="v-pills-3" role="tabpanel" aria-labelledby="v-pills-3-tab">
                    <div class="text-center pt-4 pb-2">

                      <div class="mb-4">
                        <lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop" colors="primary:#0ab39c,secondary:#405189" style="width:120px;height:120px"></lord-icon>
                      </div>
                      <h5>Your Order is Completed !</h5>
                      <p class="text-muted">You Will receive an order confirmation email with details of your order.</p>
                      <div class="mb-3">
                        <input class="form-check-input" type="checkbox" id="formCheck6">
                        <label class="form-check-label" for="formCheck6">
                          Saya telah memeriksa dokumen dengan benar
                        </label>
                      </div>
                      <div class="d-flex align-items-start gap-3 mt-4">
                        <button type="button" class="btn btn-light btn-label previestab" data-previous="v-pills-2-tab"><i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i> Kembali</button>
                        <a type="button" class="btn btn-warning btn-label right ms-auto nexttab" href="<?= site_url('usulan/submit/'.encrypt($usulan->id))?>" onclick="return confirm('Anda yakin akan mengirimkan usulan?')" id="submitbutton" disabled><i class="ri-upload-line label-icon align-middle fs-16 ms-2"></i>Kirim Usulan</a>
                      </div>
                    </div>
                  </div>
                  <!-- end tab pane -->
                </div>
                <!-- end tab content -->
              </div>
            </div>
            <!-- end col -->
          </div>
          <!-- end row -->

</div>
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

  if ($( "table:contains('Belum Diunggah')" ).length == 0) {
    $('#reviewbutton').removeAttr('disabled');
    $('#v-pills-3-tab').removeAttr('disabled');
  }

});

function cari() {
  $nip = $('#nip').val();

  axios.get('<?= site_url()?>ajax/pegawai/'+$nip)
  .then(function (response) {
    console.log(response.data.data);
  });
}

function uploadfile(id) {
  $('#form'+id).ajaxSubmit({
    // target: '#output'+id,
    beforeSubmit: function(a,f,o) {
      alert('Mengunggah');
    },
    warning: function(data) {
      if(data.status == 'error'){
        Swal.fire({title:"Ooppss...",text:data.message,icon:"error",confirmButtonColor:"#5b73e8"});
      }else{
        Swal.fire({html:"Dokumen telah diunggah",confirmButtonColor:"#5b73e8"});
        $('#output'+id).html(data.message);

        if ($( "table:contains('Belum Diunggah')" ).length == 0) {
          $('#reviewbutton').removeAttr('disabled');
        }
      }
    }
  });
}

function preview(berkas) {
  $('#object').html('<object data="'+berkas+'" type="application/pdf" width="100%" style="height: 80vh;" id="object">'+
                      '<p>Browser tidak mendukung!</p>'+
                    '</object>');
  $('#preview').modal('show');
}

//
document.querySelectorAll(".form-steps").forEach(function (form) {

    // next tab
    form.querySelectorAll(".nexttab").forEach(function (nextButton) {
        console.log("nextButton");
        var tabEl = form.querySelectorAll('button[data-bs-toggle="pill"]');
        tabEl.forEach(function (item) {
            item.addEventListener('show.bs.tab', function (event) {
                event.target.classList.add('done');
            });
        });
        nextButton.addEventListener("click", function () {
            var nextTab = nextButton.getAttribute('data-nexttab');

            if(nextTab == 'v-pills-2-tab'){
              console.log(nextTab);
              $('#pengantar').ajaxSubmit( { beforeSubmit: validate, success: function(){ document.getElementById(nextTab).click(); } } );
            }else{
              document.getElementById(nextTab).click();
            }
        });
    });

    //Pervies tab
    form.querySelectorAll(".previestab").forEach(function (prevButton) {

        prevButton.addEventListener("click", function () {
            console.log("prevButton", prevButton);
            var prevTab = prevButton.getAttribute('data-previous');
            var totalDone = prevButton.closest("div").querySelectorAll(".custom-nav .done").length;
            for (var i = totalDone - 1; i < totalDone; i++) {
                (prevButton.closest("div").querySelectorAll(".custom-nav .done")[i]) ? prevButton.closest("div")
                .querySelectorAll(".custom-nav .done")[i].classList.remove('done') : '';
            }
            document.getElementById(prevTab).click();
        });
    });

    // Step number click
    var tabButtons = form.querySelectorAll('button[data-bs-toggle="pill"]');
    tabButtons.forEach(function (button, i) {
        button.setAttribute("data-position", i);
        button.addEventListener("click", function () {
            var getProgreebar = button.getAttribute("data-progressbar");
            if (getProgreebar) {
                var totallength = document.getElementById("custom-progress-bar").querySelectorAll("li").length - 1;
                var current = i;
                var percent = (current / totallength) * 100;
                document.getElementById("custom-progress-bar").querySelector('.progress-bar').style.width = percent + "%";
            }
            (form.querySelectorAll(".custom-nav .done").length > 0) ?
                form.querySelectorAll(".custom-nav .done").forEach(function (doneTab) {
                    doneTab.classList.remove('done');
                })
                : '';
            for (var j = 0; j <= i; j++) {
                tabButtons[j].classList.contains('active') ?  tabButtons[j].classList.remove('done') :
                tabButtons[j].classList.add('done');
            }
        });
    });
});

function validate(formData, jqForm, options) {
    for (var i=0; i < formData.length; i++) {
        if (!formData[i].value) {
            alert('Harap isi dengan lengkap!');
            return false;
        }
    }

    return true;
}
</script>
<?= $this->endSection() ?>
