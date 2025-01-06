<?php

namespace App\Controllers\Admin\Master;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\DownloadModel;
use Aws\S3\S3Client;

class Download extends BaseController
{
    public function index()
    {
        $model = new DownloadModel;
        $data['download'] = $model->findAll();

        return view('admin/master/download', $data);
    }

    function delete($id) {
        $model = new DownloadModel;
        
        $delete = $model->delete($id);

        return redirect()->back()->with('message', 'Dokumen telah dihapus.');
    }

    public function save()
    {
      if (!$this->validate([
			'lampiran' => [
				'rules' => 'uploaded[lampiran]|ext_in[lampiran,pdf,PDF]',
				'errors' => [
					'uploaded' => 'Harus Ada File yang diupload',
					'mime_in' => 'File Extention Harus Berupa pdf'
				]
			]
  		])) {
            
        return $this->response->setJSON(['status'=>'error','message'=>$this->validator->getErrors()['dokumen']]);
  		}

      $model = new DownloadModel;

      $file = $this->request->getFile('lampiran');

      $fname = $file->getRandomName();
      $temp_file_location = $_FILES['lampiran']['tmp_name'];

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
        'Key'    => 'dokumen/'.$fname,
        'SourceFile' => $temp_file_location,
        'ContentType' => 'application/pdf'
      ]);

      $url = $result->get('ObjectURL');

    //   $file = $this->request->getFile('lampiran');
    //   $fname = $file->getRandomName();

      if($url){

        $data = [
            'nama' => $this->request->getVar('nama'),
            'keterangan' => $this->request->getVar('keterangan'),
            'lampiran' => $fname,
          ];

        $model->insert($data);

        return redirect()->back()->with('message', 'Dokumen telah ditambahkan.');
      }else{
        return redirect()->back()->with('message', 'Dokumen gagal ditambahkan.');
      }
    }
}
