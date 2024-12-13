<?php

namespace App\Models;

use CodeIgniter\Model;

class DokLayananModel extends Model
{
    protected $table            = 'tm_layanan_dokumen';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = false;
    protected $allowedFields    = [];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getDokumen($idlayanan,$idusul)
    {
      $query = $this->db->query("SELECT a.*,b.lampiran,b.id as iddoc,b.status,c.keterangan FROM tm_layanan_dokumen a
                                LEFT JOIN (SELECT id,id_dokumen,lampiran,status FROM tr_usul_dokumen WHERE id_usul='$idusul' AND id_layanan='$idlayanan') b
                                ON b.id_dokumen=a.dokumen
                                LEFT JOIN tm_dokumen c
                                ON c.id=a.dokumen
                                WHERE a.layanan='$idlayanan'")->getResult();
      return $query;
    }
}
