<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use \Hermawan\DataTables\DataTable;
use App\Models\LayananModel;
use App\Models\UsulanModel;
use App\Models\DokLayananModel;
use App\Models\LogModel;

class Usulan extends BaseController
{
    public function index()
    {
        return view('usulan/index');
    }

    public function create()
    {
        $lmodel = new LayananModel;
        $data['layanan'] = $lmodel->findAll();
        return view('usulan/create', $data);
    }

    public function createsave()
    {
      $model = new UsulanModel;

      $data = [
        'layanan' => $this->request->getVar('layanan'),
        'kode_satker' => session('kelola'),
        'satker' => $this->request->getVar('satker'),
        'nip' => $this->request->getVar('nip'),
        'nama' => $this->request->getVar('nama'),
        'jabatan' => $this->request->getVar('jabatan'),
        'status' => 0,
        'created_by' => session('nip'),
      ];
      $insert = $model->insert($data);

      $logm = new LogModel();
      $logm->insert(['id_usul'=>$insert,'status_usulan'=>0,'keterangan'=>'Membuat Draft Usulan','created_by'=>session('nip'),'created_by_name'=>session('nama')]);

      return redirect()->to('usulan/detail/'.encrypt($insert));
    }

    public function detail($id)
    {
        $lmodel = new LayananModel;
        $model = new UsulanModel;
        $docm = new DokLayananModel;
        $id = decrypt($id);

        $data['usulan'] = $model->find($id);
        $data['dokumen'] = $docm->getDokumen(1,$id);

        if($data['usulan']->status == 0){
          return view('usulan/create_detail', $data);
        }else{
          return view('usulan/detail_view', $data);
        }
    }

    public function save()
    {
      $model = new UsulanModel;

      $data = [
        'id' => $this->request->getVar('id'),
        'nomor_pengantar' => $this->request->getVar('nomor_pengantar'),
        'tanggal_pengantar' => $this->request->getVar('tanggal_pengantar'),
        'perihal' => $this->request->getVar('perihal'),
        'penandatangan' => $this->request->getVar('penandatangan'),
        'jabatan_penandatangan' => $this->request->getVar('jabatan_penandatangan'),
        'alasan' => $this->request->getVar('alasan')
      ];
      $insert = $model->save($data);

      $logm = new LogModel();
      $logm->insert(['id_usul'=>$this->request->getVar('id'),'status_usulan'=>0,'keterangan'=>'Update Draft Usulan','created_by'=>session('nip'),'created_by_name'=>session('nama')]);

      return $this->response->setJSON(['success']);
    }

    public function submit($id)
    {
      $model = new UsulanModel;

      $id = decrypt($id);

      $data = [
        'id' => $id,
        'status' => 1
      ];
      $insert = $model->save($data);

      $logm = new LogModel();
      $logm->insert(['id_usul'=>$id,'status_usulan'=>1,'keterangan'=>'Dikirim ke Kankemenag','created_by'=>session('nip'),'created_by_name'=>session('nama')]);

      return redirect()->back()->with('message', 'Usulan telah dikirimkan Ke Kantor Kementerian Agama Kabupaten/Kota.');
    }

    public function getdata()
    {
      $db = \Config\Database::connect('default', false);
      $builder = $db->table('tr_usulan')
                    ->select('tr_usulan.id, tm_layanan.layanan, tr_usulan.created_at, nip, nama, jabatan, nomor_pengantar, perihal, status')
                    ->join('tm_layanan', 'tm_layanan.id = tr_usulan.layanan')
                    ->where('tr_usulan.kode_satker', session('kelola'));

      return DataTable::of($builder)
      ->add('action', function($row){
        if($row->status == 0){
          return '<a href="'.site_url('usulan/detail/'.encrypt($row->id)).'" type="button" class="btn btn-primary btn-sm" target="_blank">Edit</a> <a href="'.site_url('usulan/delete/'.encrypt($row->id)).'" type="button" class="btn btn-danger btn-sm">Delete</a>';
        }else{
          return '<a href="'.site_url('usulan/detail/'.encrypt($row->id)).'" type="button" class="btn btn-primary btn-sm">View</a> <a href="javascript:;" type="button" class="btn btn-warning btn-sm" onClick="log(\''.encrypt($row->id).'\')">Logs</a>';
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
}
