<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\DownloadModel;

class Download extends BaseController
{
    public function index()
    {
        $model = new DownloadModel;
        $data['download'] = $model->findAll();
        return view('download', $data);
    }
}
