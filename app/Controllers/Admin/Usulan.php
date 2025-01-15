<?php

namespace App\Controllers\Admin;

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
        return view('admin/usulan/index');
    }

    public function getdata()
    {
      $db = \Config\Database::connect('default', false);
      $builder = $db->table('tr_usulan')
                    ->select('tr_usulan.id, tm_layanan.layanan, tr_usulan.created_at, nip, nama, jabatan, nomor_pengantar, perihal, status')
                    ->join('tm_layanan', 'tm_layanan.id = tr_usulan.layanan')
                    ->where('tr_usulan.status >', 10);

      return DataTable::of($builder)
      ->add('action', function($row){
        if($row->status == 11){
          return '<a href="'.site_url('usulan/accept/'.encrypt($row->id)).'" type="button" class="btn btn-primary btn-sm" onClick="return confirm(\'Terima usulan?\')">Cek</a>';
        }else{
          return '<a href="'.site_url('usulan/detail/'.encrypt($row->id)).'" type="button" class="btn btn-primary btn-sm">View</a> <a href="javascript:;" type="button" class="btn btn-warning btn-sm" onClick="log(\''.$row->id.'\')">Log</a>';
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
      $model->update($id,['status'=>12]);

      $logm = new LogModel();
      $logm->insert(['id_usul'=>$id,'status_usulan'=>12,'keterangan'=>'Diterima Admin Ditjen Pendis','created_by'=>session('nip'),'created_by_name'=>session('nama')]);

      session()->setFlashdata('message', 'Usulan telah diterima. Silahkan melakukan verifikasi.');
      return redirect()->to('usulan/detail/'.encrypt($id));
    }

    public function decline($id)
    {
      $model = new UsulanModel;
      
      $id = decrypt($id);
      $keterangan = $this->request->getVar('keterangan');
      $model->update($id,['status'=>121,'keterangan'=>$keterangan]);

      $logm = new LogModel();
      $logm->insert(['id_usul'=>$id,'status_usulan'=>121,'keterangan'=>'Dikembalikan Ke Kanwil. '.$keterangan,'created_by'=>session('nip'),'created_by_name'=>session('nama')]);

      session()->setFlashdata('message', 'Usulan telah diterima. Silahkan melakukan verifikasi.');
      return $this->response->setJSON(['status'=>'success']);
    }

    public function proses($id)
    {
      $model = new UsulanModel;
      $id = decrypt($id);
      $model->update($id,['status'=>13]);

      $logm = new LogModel();
      $logm->insert(['id_usul'=>$id,'status_usulan'=>13,'keterangan'=>'Disetujui & Proses Surat Rekomendasi','created_by'=>session('nip'),'created_by_name'=>session('nama')]);

      session()->setFlashdata('message', 'Usulan telah disetujui. Silahkan melakukan proses penerbitan Surat Rekomendasi.');
      return redirect()->to('usulan/detail/'.encrypt($id));
    }

    public function detail($id)
    {
        $lmodel = new LayananModel;
        $model = new UsulanModel;
        $docm = new DokLayananModel;
        $id = decrypt($id);

        $data['usulan'] = $model->find($id);
        $data['dokumen'] = $docm->getDokumen($data['usulan']->layanan,$id);
        
        if($data['usulan']->status > 13){
          return view('admin/usulan/detail_view', $data);
        }else if($data['usulan']->status == 13){
          return view('admin/usulan/detail_proses', $data);
        }else{
          return view('admin/usulan/detail', $data);
        }
    }

    public function draftpengantar($id) {
      $id = decrypt($id);

      $model = new UsulanModel();
      $usul = $model->find($id);

      $model = new LayananModel();
      $layanan = $model->find($usul->layanan);

      $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('template/master_rekom.docx');

      $predefinedMultilevel = array('listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_BULLET_EMPTY);

      $templateProcessor->setValue('nama', $usul->nama);
      $templateProcessor->setValue('nip', $usul->nip);
      $templateProcessor->setValue('pangkat', $usul->pangkat.', '.$usul->golongan);
      $templateProcessor->setValue('jabatanlengkap', $usul->keterangan_jabatan);
      $templateProcessor->setValue('jabatanbaru', $usul->jabatan_baru.' pada '.$usul->unit_baru);
      $templateProcessor->setValue('setuju', ($usul->rekomendasi_setuju == 1)?'disetujui':'tidak disetujui');
      $templateProcessor->setValue('kanwilpengusul', $usul->prov_pengantar_jabatan);
      $templateProcessor->setValue('kanwilnomor', $usul->prov_pengantar_nomor);
      $templateProcessor->setValue('kanwiltanggal', $usul->prov_pengantar_tanggal);
      $templateProcessor->setValue('perihal', $usul->perihal);
      $templateProcessor->setValue('layanan', $layanan->layanan);

      $templateProcessor->setValue('surat_nomor', $usul->rekomendasi_nomor);
      $templateProcessor->setValue('surat_tanggal', $usul->rekomendasi_tanggal);

      $filename = 'draft_super_rekom_'.$id.'.docx';
      $templateProcessor->saveAs('draft/'.$filename);

      return $this->response->download('draft/'.$filename,null);
    }

    function rekomendasi()
    {

      if (! $this->validate([
        'rekomendasi_pengantar_file' => [
                  'rules' => 'uploaded[rekomendasi_pengantar_file]|ext_in[rekomendasi_pengantar_file,pdf,PDF]|max_size[rekomendasi_pengantar_file,2048]',
                  'errors' => [
                    'uploaded' => 'Harus Ada File yang diupload',
                    'mime_in' => 'File Extention Harus Berupa pdf',
                    'max_size' => 'Ukuran File Maksimal 2 MB'
                  ]
              ]
      ])) {
          return redirect()->back()->with('message', 'Data tidak lengkap');
      }

      $file_name = $_FILES['rekomendasi_pengantar_file']['name'];
      $ext = pathinfo($file_name, PATHINFO_EXTENSION);
      
      $usulid = $this->request->getVar('id');
      $file_name = 'rekompengantar.'.$usulid.'.'.$ext;
      $temp_file_location = $_FILES['rekomendasi_pengantar_file']['tmp_name'];

      $file_name2 = $_FILES['rekomendasi_file']['name'];
      $ext = pathinfo($file_name2, PATHINFO_EXTENSION);
      
      $file_name2 = 'rekom.'.$usulid.'.'.$ext;
      $temp_file_location2 = $_FILES['rekomendasi_file']['tmp_name'];

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

      $result2 = $s3->putObject([
        'Bucket' => 'layanan',
        'Key'    => 'dokumen/'.$file_name2,
        'SourceFile' => $temp_file_location2,
        'ContentType' => 'application/pdf'
      ]);

      $url2 = $result->get('ObjectURL');

      if($url){
        $model = new UsulanModel;
  
        $data = [
          'rekomendasi_tanggal' => $this->request->getVar('rekomendasi_tanggal'),
          'rekomendasi_nomor' => $this->request->getVar('rekomendasi_nomor'),
          'rekomendasi_nama' => $this->request->getVar('rekomendasi_nama'),
          'rekomendasi_jabatan' => $this->request->getVar('rekomendasi_jabatan'),
          'rekomendasi_setuju' => $this->request->getVar('rekomendasi_setuju'),
          'rekomendasi_pengantar_file' => $file_name,
          'rekomendasi_file' => $file_name2,
          'status' => 14
        ];
  
        $update = $model->update($usulid,$data);

        $logm = new LogModel();
        $logm->insert(['id_usul'=>$usulid,'status_usulan'=>4,'keterangan'=>'Dibuatkan Surat Pengantar Ke Kanwil','created_by'=>session('nip'),'created_by_name'=>session('nama')]);

      }

      return redirect()->back()->with('message','Dokumen telah diunggah');
    }
}
