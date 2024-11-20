<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use \Hermawan\DataTables\DataTable;
use App\Models\LayananModel;

class Ajax extends BaseController
{
  public function pegawai($nip)
  {
    $client = service('curlrequest');

    $response = $client->request('GET', 'https://api.kemenag.go.id/v2/pegawai/nip/0/'.$nip, [
        'headers' => [
            'Accept'        => 'application/json',
            'Content-Type' => 'application/json',
            'x-key' => 'nKr1'
        ],
        'verify' => false
    ]);

    return $this->response->setJSON($response->getBody());
  }
}
