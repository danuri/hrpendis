<?php

namespace App\Controllers\Admin\Master;

use App\Controllers\BaseController;
use App\Models\DokumenModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\LayananModel;
use App\Models\CrudModel;
use App\Models\DokLayananModel;

class Layanan extends BaseController
{
    public function index()
    {
        $model = new LayananModel;
        $data['layanan'] = $model->findAll();

        return view('admin/master/layanan', $data);
    }

    public function save($id=false)
    {
      $model = new LayananModel;
      $param = [
        'layanan' => $this->request->getVar('layanan'),
        'keterangan' => $this->request->getVar('keterangan'),
      ];

      if($id){
        $param['id'] = $id;
      }

      $save = $model->save($param);

      return redirect()->back()->with('message', 'Layanan telah ditambahkan.');
    }

    function dokumen($id) {

      $model = new CrudModel;
      $dok = new DokumenModel;

      $data['dokumens'] = $dok->findAll();
      $data['dokumen'] = $model->getLayananDokumen($id);
      $data['id'] = $id;
      return view('admin/master/layanan_dokumen', $data);
    }

    function adddokumen() {
      $model = new DokLayananModel;

      $layanan = $this->request->getVar('layanan');
      $dokumen = $this->request->getVar('dokumen');
      $wajib = $this->request->getVar('wajib');

      $data = [
        'layanan' => $layanan,
        'dokumen' => $dokumen,
        'wajib' => $wajib,
      ];

      $model->insert($data);

      return redirect()->back()->with('message', 'Dokumen telah ditambahkan.');
    }
    
    function deletedokumen($id) {
      $model = new DokLayananModel;
      
      $delete = $model->delete($id);
      return redirect()->back()->with('message', 'Dokumen telah dihapus.');
    }
}
