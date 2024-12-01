<?php

namespace App\Controllers\Kanwil;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use \Hermawan\DataTables\DataTable;
use Aws\S3\S3Client;
use App\Models\LayananModel;
use App\Models\UsulanModel;
use App\Models\DokLayananModel;
use App\Models\LogModel;

class Usulan extends BaseController
{
    public function index()
    {
        return view('kanwil/usulan/index');
    }

    public function detail($id)
    {
        $lmodel = new LayananModel;
        $model = new UsulanModel;
        $docm = new DokLayananModel;
        $id = decrypt($id);

        $data['usulan'] = $model->find($id);
        $data['dokumen'] = $docm->getDokumen(1,$id);
        
        if($data['usulan']->status > 10){
          return view('kanwil/usulan/detail_view', $data);
        }else{
          return view('kanwil/usulan/detail', $data);
        }
    }

    public function detailpengantar($id)
    {
        $lmodel = new LayananModel;
        $model = new UsulanModel;
        $docm = new DokLayananModel;
        $id = decrypt($id);

        $data['usulan'] = $model->find($id);
        $data['dokumen'] = $docm->getDokumen(1,$id);
        return view('kanwil/usulan/detail_pengantar', $data);
    }

    function pengantar()
    {

      if (! $this->validate([
        'dokumen' => [
                  'rules' => 'uploaded[dokumen]|ext_in[dokumen,pdf,PDF]|max_size[dokumen,2048]',
                  'errors' => [
                    'uploaded' => 'Harus Ada File yang diupload',
                    'mime_in' => 'File Extention Harus Berupa pdf',
                    'max_size' => 'Ukuran File Maksimal 2 MB'
                  ]
              ]
      ])) {
          return redirect()->back()->with('message', 'Data tidak lengkap');
      }

      $file_name = $_FILES['dokumen']['name'];
      $ext = pathinfo($file_name, PATHINFO_EXTENSION);
      
      $usulid = $this->request->getVar('id');
      $file_name = 'kabpengantar.'.$usulid.'.'.$ext;
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

      if($url){
        $model = new UsulanModel;
  
        $data = [
          'prov_pengantar_file' => $file_name,
          'status' => 10
        ];
  
        $update = $model->update($usulid,$data);

        $logm = new LogModel();
        $logm->insert(['id_usul'=>$usulid,'status_usulan'=>10,'keterangan'=>'Dibuatkan Surat Pengantar Ke Ditjen Pendis','created_by'=>session('nip'),'created_by_name'=>session('nama')]);

        return redirect()->back()->with('message','Dokumen telah diunggah');
      }else{
        return redirect()->back()->with('message','Dokumen gagal diunggah');

      }

    }

    public function getdata()
    {
      $db = \Config\Database::connect('default', false);
      $builder = $db->table('tr_usulan')
                    ->select('tr_usulan.id, tm_layanan.layanan, tr_usulan.created_at, nip, nama, jabatan, nomor_pengantar, perihal, status')
                    ->join('tm_layanan', 'tm_layanan.id = tr_usulan.layanan')
                    ->where('tr_usulan.status >', 4)
                    ->like('tr_usulan.kode_satker', kodekepala(session('kelola')), 'after');

      return DataTable::of($builder)
      ->add('action', function($row){
        if($row->status == 1){
          return '<a href="'.site_url('usulan/accept/'.encrypt($row->id)).'" type="button" class="btn btn-primary btn-sm" onClick="return confirm(\'Terima usulan?\')">Terima</a>';
        }else{
          return '<a href="'.site_url('usulan/detail/'.encrypt($row->id)).'" type="button" class="btn btn-primary btn-sm">View</a> <a href="javascript:;" type="button" class="btn btn-warning btn-sm" onClick="log(\''.encrypt($row->id).'\')">Log</a>';
        }
      })->format('status', function($value, $meta){
        return usul_status($value);
      })->filter(function ($builder, $request) {

        if ($request->layanan)
            $builder->where('tr_usulan.layanan', $request->layanan);

        if ($request->status)
            $builder->where('tr_usulan.status', $request->status);

      })
      ->toJson(true);
    }

    public function accept($id)
    {
      $model = new UsulanModel;
      $id = decrypt($id);
      $model->update($id,['status'=>2]);

      $logm = new LogModel();
      $logm->insert(['id_usul'=>$id,'status_usulan'=>2,'keterangan'=>'Diterima Admin kanwil','created_by'=>session('nip'),'created_by_name'=>session('nama')]);

      session()->setFlashdata('message', 'Usulan telah diterima. Silahkan melakukan verifikasi.');
      return redirect()->to('usulan/detail/'.encrypt($id));
    }

    public function decline($id)
    {
      $model = new UsulanModel;
      
      $id = decrypt($id);
      $keterangan = $this->request->getVar('keterangan');
      $model->update($id,['status'=>81,'keterangan'=>$keterangan]);

      $logm = new LogModel();
      $logm->insert(['id_usul'=>$id,'status_usulan'=>81,'keterangan'=>'Dikembalikan Ke Kankemenag. '.$keterangan,'created_by'=>session('nip'),'created_by_name'=>session('nama')]);

      session()->setFlashdata('message', 'Usulan telah diterima. Silahkan melakukan verifikasi.');
      return $this->response->setJSON(['status'=>'success']);
    }

    public function submit($id)
    {
      $model = new UsulanModel;
      $id = decrypt($id);
      $model->update($id,['status'=>11]);

      $logm = new LogModel();
      $logm->insert(['id_usul'=>$id,'status_usulan'=>11,'keterangan'=>'Dikirim Ke Ditjen Pendis','created_by'=>session('nip'),'created_by_name'=>session('nama')]);

      session()->setFlashdata('message', 'Usulan telah diterima. Silahkan melakukan verifikasi.');
      return redirect()->to('usulan/detail/'.encrypt($id));
    }

    public function draftpengantar($id) {
      $id = decrypt($id);

      $model = new UsulanModel();
      $usul = $model->find($id);

      $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('template/template_usul_rekom_pembina.docx');

      $predefinedMultilevel = array('listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_BULLET_EMPTY);

      $templateProcessor->setValue('namaPegawai', $usul->nama);
      $templateProcessor->setValue('nipPegawai', $usul->nip);
      $templateProcessor->setValue('levelJabatan', $usul->level_jabatan);
      $templateProcessor->setValue('tujuan', $pembina->pembina_jabatan);
      $templateProcessor->setValue('suratDari', $usul->pengantar_dari);
      $templateProcessor->setValue('nomorSuratDari', $usul->pengantar_nomor);
      $templateProcessor->setValue('tglSuratDari', local_date($usul->pengantar_tanggal));
      $templateProcessor->setValue('pangkatPegawai', $usul->pangkat);
      $templateProcessor->setValue('golonganPegawai', $usul->golongan);
      $templateProcessor->setValue('jabatanLama', $usul->jabatan_lama);
      $templateProcessor->setValue('jabatanBaru', $usul->jabatan_baru);
      $templateProcessor->setValue('satuanKerja', $usul->unit_kerja);

      $templateProcessor->setValue('nomorSurat', 'nomorsurat');
      $templateProcessor->setValue('tglSurat', 'tglsurat');

      $filename = 'draft_nodin_pembina_jafung_'.$id.'.docx';
      $templateProcessor->saveAs('draft/'.$filename);

      return $this->response->download('draft/'.$filename,null);
    }
}
