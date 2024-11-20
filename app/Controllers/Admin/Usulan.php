<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use \Hermawan\DataTables\DataTable;

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
                    ->where('tr_usulan.prov_status', 1);

      return DataTable::of($builder)
      ->add('action', function($row){
        if($row->status == 0){
          return '<a href="'.site_url('admin/usulan/detail/'.encrypt($row->id)).'" type="button" class="btn btn-primary btn-sm" target="_blank">Edit</a>';
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
}
