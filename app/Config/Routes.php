<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
 $routes->get('auth', 'Auth::index');
 $routes->get('auth/login', 'Auth::login');
 $routes->get('auth/logout', 'Auth::logout');
 $routes->get('auth/callback', 'Auth::callback');

 if(session('level') == 1){
    $routes->get('/', 'Admin\Home::index',['filter' => 'auth']);
 }else{
  $routes->get('/', 'Home::index',['filter' => 'auth']);
}

$routes->group("usulan", ["filter" => "auth"], function ($routes) {

    if(session('level') == 4){
      $routes->get('', 'Usulan::index');
      $routes->get('create', 'Usulan::create');
      $routes->post('create', 'Usulan::createsave');
      $routes->get('detail/(:any)', 'Usulan::detail/$1');
      $routes->get('getdata', 'Usulan::getdata');
      $routes->post('save', 'Usulan::save');
      $routes->get('submit/(:any)', 'Usulan::submit/$1');
      $routes->get('delete/(:any)', 'Usulan::delete/$1');
    }else if(session('level') == 3){
      $routes->get('', 'Kankemenag\Usulan::index');
      $routes->get('detail/pengantar/(:any)', 'Kankemenag\Usulan::detailpengantar/$1');
      $routes->get('detail/(:any)', 'Kankemenag\Usulan::detail/$1');
      $routes->get('accept/(:any)', 'Kankemenag\Usulan::accept/$1');
      $routes->get('verifikasi/(:any)', 'Kankemenag\Usulan::verifikasi/$1');
      $routes->get('getdata', 'Kankemenag\Usulan::getdata');
      $routes->post('decline/(:any)', 'Kankemenag\Usulan::decline/$1');
      $routes->post('save', 'Kankemenag\Usulan::save');
      $routes->post('pengantar', 'Kankemenag\Usulan::pengantar');
      $routes->get('draftpengantar/(:any)', 'Kankemenag\Usulan::draftpengantar/$1');
      $routes->get('submit/(:any)', 'Kankemenag\Usulan::submit/$1');
    }else if(session('level') == 2){
      $routes->get('', 'Kanwil\Usulan::index');
      $routes->get('detail/pengantar/(:any)', 'Kanwil\Usulan::detailpengantar/$1');
      $routes->get('detail/(:any)', 'Kanwil\Usulan::detail/$1');
      $routes->get('accept/(:any)', 'Kanwil\Usulan::accept/$1');
      $routes->get('getdata', 'Kanwil\Usulan::getdata');
      $routes->post('save', 'Kanwil\Usulan::save');
      $routes->post('decline/(:any)', 'Kanwil\Usulan::decline/$1');
      $routes->post('pengantar', 'Kanwil\Usulan::pengantar');
      $routes->get('draftpengantar/(:any)', 'Kanwil\Usulan::draftpengantar/$1');
      $routes->get('submit/(:any)', 'Kanwil\Usulan::submit/$1');
    }else if(session('level') == 1){
      $routes->get('', 'Admin\Usulan::index');
      $routes->get('detail/pengantar/(:any)', 'Admin\Usulan::detailpengantar/$1');
      $routes->get('detail/(:any)', 'Admin\Usulan::detail/$1');
      $routes->get('accept/(:any)', 'Admin\Usulan::accept/$1');
      $routes->post('decline/(:any)', 'Admin\Usulan::decline/$1');
      $routes->get('proses/(:any)', 'Admin\Usulan::proses/$1');
      $routes->get('getdata', 'Admin\Usulan::getdata');
      $routes->post('save', 'Admin\Usulan::save');
      $routes->post('pengantar', 'Admin\Usulan::pengantar');
      $routes->post('rekomendasi', 'Admin\Usulan::rekomendasi');
      $routes->get('draftpengantar/(:any)', 'Admin\Usulan::draftpengantar/$1');
      $routes->get('draftsr/(:any)', 'Admin\Usulan::draftsr/$1');
      $routes->get('submit/(:any)', 'Admin\Usulan::submit/$1');
    }
 });

 $routes->get('download', 'Download::index',['filter' => 'auth']);

 $routes->group("dokumen", ["filter" => "auth"], function ($routes) {
   $routes->get('view/(:num)/(:num)', 'Dokumen::view/$1/$2');
   $routes->get('embed/(:num)/(:num)', 'Dokumen::embed/$1/$2');
   $routes->post('upload', 'Dokumen::upload');
   $routes->get('validasi/(:num)/(:num)', 'Dokumen::validasi/$1/$2');
 });

 $routes->group("ajax", ["filter" => "auth"], function ($routes) {
     $routes->get('pegawai/(:any)', 'Ajax::pegawai/$1');
     $routes->get('log/(:any)', 'Ajax::getLog/$1');
 });

 if(session('level') == 1){

   $routes->group("master", ["filter" => "auth"], function ($routes) {

       $routes->group("layanan", function ($routes) {
           $routes->get('', 'Admin\Master\Layanan::index');
           $routes->get('dokumen/delete/(:num)', 'Admin\Master\Layanan::deletedokumen/$1');
           $routes->get('dokumen/(:any)', 'Admin\Master\Layanan::dokumen/$1');
           $routes->get('detail/(:any)', 'Admin\Master\Layanan::detail/$1');
           $routes->get('delete/(:any)', 'Admin\Master\Layanan::delete/$1');
           $routes->get('getdata', 'Admin\Master\Layanan::getdata');
           $routes->post('save', 'Admin\Master\Layanan::save');
           $routes->post('dokumen/save', 'Admin\Master\Layanan::adddokumen');
       });
  
       $routes->group("dokumen", function ($routes) {
           $routes->get('', 'Admin\Master\Dokumen::index');
           $routes->get('detail/(:any)', 'Admin\Master\Dokumen::detail/$1');
           $routes->get('delete/(:any)', 'Admin\Master\Dokumen::delete/$1');
           $routes->get('getdata', 'Admin\Master\Dokumen::getdata');
           $routes->post('save', 'Admin\Master\Dokumen::save');
       });
  
       $routes->group("pengelola", function ($routes) {
           $routes->get('', 'Admin\Master\Pengelola::index');
           $routes->post('', 'Admin\Master\Pengelola::save');
           $routes->get('detail/(:any)', 'Admin\Master\Pengelola::detail/$1');
           $routes->get('getdata', 'Admin\Master\Pengelola::getdata');
           $routes->post('save', 'Admin\Master\Pengelola::save');
       });
  
       $routes->group("download", function ($routes) {
           $routes->get('', 'Admin\Master\Download::index');
           $routes->post('', 'Admin\Master\Download::save');
           $routes->post('save', 'Admin\Master\Download::save');
           $routes->get('delete/(:any)', 'Admin\Master\Download::delete/$1');
       });

        $routes->group("ajax", function ($routes) {
          $routes->get('pegawai/(:any)', 'Admin\Ajax::pegawai/$1');
          $routes->get('log/(:any)', 'Admin\Ajax::getLog/$1');
        });

   });
 }
