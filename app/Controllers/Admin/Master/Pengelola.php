<?php

namespace App\Controllers\Admin\Master;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PengelolaModel;

class Pengelola extends BaseController
{
  public function index()
  {
      $model = new PengelolaModel;
      $data['pengelola'] = $model->findAll();

      return view('admin/master/pengelola', $data);
  }

  public function save($id=false)
  {
    $model = new PengelolaModel;
    $param = [
      'nip' => $this->request->getVar('nip'),
      'nama' => $this->request->getVar('nama'),
      'jabatan' => $this->request->getVar('jabatan'),
      'no_hp' => $this->request->getVar('no_hp'),
      'kode_satker' => $this->request->getVar('kode_satker'),
      'satker' => $this->request->getVar('satker'),
      'level' => (session('level')+1),
      'role' => $this->request->getVar('role'),
    ];

    if($id){
      $param['id'] = $id;
    }

    $save = $model->save($param);

    return redirect()->back()->with('message', 'Pengelola telah ditambahkan');

  }
}
