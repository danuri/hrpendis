<?php

namespace App\Models;

use CodeIgniter\Model;

class CrudModel extends Model
{
  protected $db;

  public function __construct()
  {
    $this->db = \Config\Database::connect('default', false);

  }

  public function getRow($table,$where)
  {
    $builder = $this->db->table($table);
    $query = $builder->getWhere($where);

    return $query->getRow();
  }

  public function getArray($table,$where=false)
  {
    $builder = $this->db->table($table);

    if($where){
      $query = $builder->getWhere($where);
    }else{
      $query = $builder->get();
    }

    return $query->getResult();
  }

  public function getCount($table,$where=false)
  {
    $builder = $this->db->table($table);

    if($where){
      $query = $builder->getWhere($where);
    }else{
      $query = $builder->get();
    }

    return $query->countAllResults();
  }

  public function query_row($query)
  {
    $query = $this->db->query($query)->getRow();
    return $query;
  }

  public function query_array($query)
  {
    $query = $this->db->query($query)->getResult();
    return $query;
  }

  public function getActivities($id)
  {
    $query = $this->db->query("exec sp_get_aktifitas @usul='".$id."'")->getResult();
    return $query;
  }

  public function getRequest($kodesatker)
  {
    $query = $this->db->query("SELECT
                              	TR_USULAN.*,
                              	TM_LAYANAN.KELOLA,
                              	TM_LAYANAN.LAYANAN AS NAMALAYANAN,
                              	sk.SATUAN_KERJA,
                              	(SELECT COUNT(ID) FROM TR_USUL_BERKAS b WHERE b.USULAN=TR_USULAN.ID) AS jumlah
                              FROM
                              	dbo.TR_USULAN
                              	INNER JOIN dbo.TM_LAYANAN	ON TR_USULAN.LAYANAN = TM_LAYANAN.ID
                              	LEFT JOIN simpeg41.dbo.TM_SATUAN_KERJA sk ON TR_USULAN.KODE_SATKER= sk.KODE_SATUAN_KERJA
                              WHERE TR_USULAN.KODE_SATKER='$kodesatker'")->getResult();
    return $query;
  }

  public function getDetailRequest($id)
  {
    $query = $this->db->query("SELECT
                              	TR_USULAN.*,
                              	TM_LAYANAN.KELOLA,
                              	TM_LAYANAN.LAYANAN AS NAMALAYANAN,
                              	TM_LAYANAN.ROUTE,
                              	sk.SATUAN_KERJA,
                              	(SELECT COUNT(ID) FROM TR_USUL_BERKAS b WHERE b.USULAN=TR_USULAN.ID) AS jumlah
                              FROM
                              	dbo.TR_USULAN
                              	INNER JOIN dbo.TM_LAYANAN	ON TR_USULAN.LAYANAN = TM_LAYANAN.ID
                              	LEFT JOIN simpeg41.dbo.TM_SATUAN_KERJA sk ON TR_USULAN.KODE_SATKER= sk.KODE_SATUAN_KERJA
                              WHERE TR_USULAN.ID='$id'")->getRow();
    return $query;
  }

  public function getDetailUsulan($id)
  {
    $query = $this->db->query("SELECT
                              	TR_USULAN.*,
                              	TM_LAYANAN.LAYANAN AS NAMALAYANAN,
                              	TM_LAYANAN.ROUTE,
                              	TM_LAYANAN.KELOLA,
                              	p.NAMA_LENGKAP AS NAMA_ADMIN,
                              	sk.SATUAN_KERJA,
                              	(SELECT COUNT(ID) FROM TR_USUL_BERKAS b WHERE b.USULAN=TR_USULAN.ID) AS JUMLAH
                              FROM
                              	dbo.TR_USULAN
                              	LEFT JOIN
                              	dbo.TM_LAYANAN
                              	ON
                              		TR_USULAN.LAYANAN = TM_LAYANAN.ID
                              	LEFT JOIN
                              	simpeg41.dbo.TEMP_PEGAWAI AS p
                              	ON
                              		TR_USULAN.ADMIN = p.NIP_BARU
                              	LEFT JOIN
                              	simpeg41.dbo.TM_SATUAN_KERJA AS sk
                              	ON
                              		TR_USULAN.KODE_SATKER = sk.KODE_SATUAN_KERJA
                              WHERE
                              	TR_USULAN.ID = '$id'")->getRow();
    return $query;
  }

  public function getDokumen($idlayanan,$nip)
  {
    $query = $this->db->query("SELECT a.*,b.LAMPIRAN,c.DOKUMEN AS KODE,c.KETERANGAN FROM TM_LAYANAN_DOKUMEN a
                              LEFT JOIN (SELECT LAMPIRAN,DOKUMEN FROM TR_DOKUMEN WHERE NIP='$nip') b
                              ON b.DOKUMEN=a.DOKUMEN
                              LEFT JOIN TM_DOKUMEN c
                              ON c.ID=a.DOKUMEN
                              WHERE a.LAYANAN='$idlayanan'")->getResult();
    return $query;
  }
}
