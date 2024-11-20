<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CrudModel;
use App\Models\UsulDokumenModel;
use App\Models\TubelModel;
use Aws\S3\S3Client;

class Dokumen extends BaseController
{
    public function index()
    {
      return view('admin/dokumen');
    }

    public function embed($layanan,$nip)
    {
      $crud = new CrudModel();
      $dokumen = $crud->query_array("SELECT a.*,b.LAMPIRAN,c.DOKUMEN AS KODE,c.KETERANGAN FROM TM_LAYANAN_DOKUMEN a
                                  LEFT JOIN (SELECT LAMPIRAN,DOKUMEN FROM TR_DOKUMEN WHERE NIP='$nip') b
                                  ON b.DOKUMEN=a.DOKUMEN
                                  LEFT JOIN TM_DOKUMEN c
                                  ON c.ID=a.DOKUMEN
                                  WHERE a.LAYANAN='$layanan'");
      ?>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Nama Dokumen</th>
            <th>Dokumen</th>
            <th>Upload</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($dokumen as $row) {
            $download = ($row->LAMPIRAN)?'<a href="https://docu.kemenag.go.id:9000/layanan/dokumen/'.$row->LAMPIRAN.'" target="_blank"><i class="bx bx-download align-middle"></i></a>':'Belum Upload';
            echo '
            <tr>
            <td>'.$row->KETERANGAN.'</td>
            <td id="output'.$row->ID.'">'.$download.'</td>
            <td>
            <button type="button" class="btn btn-soft-danger waves-effect waves-light btn-sm" onclick="$(\'#file'.$row->ID.'\').click()"><i class="bx bx-upload align-middle"></i></button>
            <form method="POST" action="'.site_url('dokumen/upload').'" style="display: none;" id="form'.$row->ID.'" enctype="multipart/form-data">
            <input type="hidden" name="nip" value="'.$nip.'">
            <input type="hidden" name="kode" value="'.$row->DOKUMEN.'">
            <input type="file" name="dokumen" id="file'.$row->ID.'" onchange="uploadfile(\''.$row->ID.'\')">
            </form>
            </td>
            </tr>
            ';
          }
          ?>
        </tbody>
      </table>
      <?php
    }

    public function view($layanan,$nip)
    {
      $crud = new CrudModel();
      $dokumen = $crud->query_array("SELECT a.*,b.LAMPIRAN,c.DOKUMEN AS KODE,c.KETERANGAN FROM TM_LAYANAN_DOKUMEN a
                                  LEFT JOIN (SELECT LAMPIRAN,DOKUMEN FROM TR_DOKUMEN WHERE NIP='$nip') b
                                  ON b.DOKUMEN=a.DOKUMEN
                                  LEFT JOIN TM_DOKUMEN c
                                  ON c.ID=a.DOKUMEN
                                  WHERE a.LAYANAN='$layanan'");
      ?>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Nama Dokumen</th>
            <th>Dokumen</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($dokumen as $row) {

            $download = '<a href="https://ropeg.kemenag.go.id:9000/layanan/dokumen/'.$row->LAMPIRAN.'" target="_blank"><i class="bx bx-download align-middle"></i></a>';
            // if(substr($row->LAMPIRAN,0,4) == 'docu'){
            // }else{
            //   $download = ($row->LAMPIRAN)?'<a href="'.site_url('uploads/dokumen/'.$row->LAMPIRAN).'" target="_blank"><i class="bx bx-download align-middle"></i></a>':'Belum Upload';
            // }

            echo '
            <tr>
            <td>'.$row->KETERANGAN.'</td>
            <td id="output'.$row->ID.'">'.$download.'</td>
            </tr>
            ';
          }
          ?>
        </tbody>
      </table>
      <?php
    }

    public function upload()
    {
      if (!$this->validate([
			'dokumen' => [
				'rules' => 'uploaded[dokumen]|ext_in[dokumen,pdf,PDF]|max_size[dokumen,2048]',
				'errors' => [
					'uploaded' => 'Harus Ada File yang diupload',
					'mime_in' => 'File Extention Harus Berupa pdf',
					'max_size' => 'Ukuran File Maksimal 2 MB'
				]
			]
  		])) {
  			// session()->setFlashdata('error', $this->validator->listErrors());
  			// return redirect()->back()->withInput();
        return $this->response->setJSON(['status'=>'error','message'=>$this->validator->getErrors()['dokumen']]);
  		}

      $model = new UsulDokumenModel();

      $file_name = $_FILES['dokumen']['name'];
      $ext = pathinfo($file_name, PATHINFO_EXTENSION);

      $nip = $this->request->getVar('nip');
      $usul = decrypt($this->request->getVar('usul'));
      $iddok = $this->request->getVar('iddok');
      $layanan = $this->request->getVar('layanan');

      $file_name = 'docu.'.$nip.'.'.$iddok.'.'.$layanan.'.'.$ext;
      $temp_file_location = $_FILES['dokumen']['tmp_name'];

      $s3 = new S3Client([
        'region'  => 'us-east-1',
        'endpoint' => 'https://ropeg.kemenag.go.id:9000/',
        'use_path_style_endpoint' => true,
        'version' => 'latest',
        'credentials' => [
          'key'    => "PkzyP2GIEBe8z29xmahI",
          'secret' => "voNVqTilX2iux6u7pWnaqJUFG1414v0KTaFYA0Uz",
        ],
        'http'    => [
            'verify' => false
        ]
      ]);

      $result = $s3->putObject([
        'Bucket' => 'layanan',
        'Key'    => 'dokumen/'.$file_name,
        'SourceFile' => $temp_file_location,
        'ContentType' => 'application/pdf'
      ]);

      $url = $result->get('ObjectURL');

      // $file = $this->request->getFile('dokumen');
      // $fname = $file->getRandomName();

      if($url){

        $crud = new CrudModel();
        $cek = $crud->getRow('tr_usul_dokumen',['id_dokumen' => $iddok,'id_usul' => $usul,'id_layanan' => $layanan]);

        if($cek){
          $model
            ->where(['id_usul' => $usul,'id_dokumen' => $iddok,'id_layanan' => $layanan])
            ->set(['lampiran' => $file_name])
            ->update();
        }else{
          $data = [
            'id_usul' => $usul,
            'id_layanan' => $layanan,
            'id_dokumen' => $iddok,
            'lampiran' => $file_name,
            'created_by' => session('nip'),
          ];

          $model->insert($data);
        }
        return $this->response->setJSON(['status'=>'success','message'=>'<a href="https://ropeg.kemenag.go.id:9000/layanan/dokumen/'.$file_name.'" target="_blank">Lihat Dokumen</a>']);
      }else{
        return $this->response->setJSON(['status'=>'error','message'=>$file->getError()]);
      }
    }
}
