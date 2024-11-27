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
          return '<a href="'.site_url('usulan/accept/'.encrypt($row->id)).'" type="button" class="btn btn-primary btn-sm" onClick="return confirm(\'Terima usulan?\')">Terima</a>';
        }else{
          return '<a href="javascript:;" type="button" class="btn btn-primary btn-sm" onClick="preview(\''.$row->id.'\')">View</a> <a href="javascript:;" type="button" class="btn btn-warning btn-sm" onClick="log(\''.$row->id.'\')">Log</a>';
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
        $data['dokumen'] = $docm->getDokumen(1,$id);
        
        if($data['usulan']->status > 13){
          return view('admin/usulan/detail_view', $data);
        }else if($data['usulan']->status == 13){
          return view('admin/usulan/detail_proses', $data);
        }else{
          return view('admin/usulan/detail', $data);
        }
    }
}
