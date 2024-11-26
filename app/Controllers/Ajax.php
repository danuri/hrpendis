<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use \Hermawan\DataTables\DataTable;
use App\Models\LogModel;

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

  function getLog($usulid) {
    $usulid = decrypt($usulid);
    $model = new LogModel;
    $logs = $model->where('id_usul',$usulid)->findAll();
    echo '<div class="vertical-timeline vertical-timeline--animate vertical-timeline--one-column">';

    foreach($logs as $row){
      echo '<div class="vertical-timeline-item vertical-timeline-element">
            <div>
              <span class="vertical-timeline-element-icon bounce-in">
                <input class="form-check-input" type="radio" name="formradiocolor3" id="formradioRight7" checked="">
              </span>
              <div class="vertical-timeline-element-content bounce-in">
                <h4 class="timeline-title text-success">'.$row->keterangan.'</h4>
                <p>'.$row->created_by_name.'</p>
                <span class="vertical-timeline-element-date">'.$row->created_at.'</span>
              </div>
            </div>
          </div>';
    }
    echo '</div>';
  }
}
