<?php

namespace App\Controllers\Admin\Master;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\DokumenModel;

class Dokumen extends BaseController
{
    public function index()
    {
        $model = new DokumenModel;
        $data['dokumen'] = $model->findAll();

        return view('admin/master/dokumen', $data);
    }

    public function save($id=false)
    {
      $model = new DokumenModel;
      $param = [
        'dokumen' => $this->request->getVar('dokumen'),
        'keterangan' => $this->request->getVar('keterangan'),
      ];

      if($id){
        $param['id'] = $id;
      }

      $save = $model->save($param);

      return redirect()->back()->with('message', 'Dokumen telah ditambahkan.');
    }

    function delete($id) {
      $model = new DokumenModel;
      
      $delete = $model->delete($id);
      return redirect()->back()->with('message', 'Dokumen telah dihapus.');
    }
}
