<?php

namespace App\Controllers\Admin\Master;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\LayananModel;

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
        'kode' => $this->request->getVar('kode'),
        'keterangan' => $this->request->getVar('keterangan'),
      ];

      if($id){
        $param['id'] = $id;
      }

      $save = $model->save($param);

      return $this->response->setJSON($save);
    }
}
