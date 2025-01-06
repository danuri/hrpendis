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

    public function draftsr($id) {
      $id = decrypt($id);

      $model = new UsulanModel();
      $usul = $model->find($id);

      $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('template/template_usul_rekom_pembina.docx');

      $predefinedMultilevel = array('listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_BULLET_EMPTY);

      $templateProcessor->setValue('namaPegawai', $usul->nama);
      $templateProcessor->setValue('nipPegawai', $usul->nip);
      $templateProcessor->setValue('levelJabatan', $usul->level_jabatan);
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

      $filename = 'draft_rekom_'.$id.'.docx';
      $templateProcessor->saveAs('draft/'.$filename);

      return $this->response->download('draft/'.$filename,null);
    }
}
