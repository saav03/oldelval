<?php 

namespace App\Models;
use CodeIgniter\Model;

class Model_empresas extends Model {

    public function getEmpresas($id_empresa = 0) {
        $builder = $this->db->table('empresas');
        $builder->select('*');
        if ($id_empresa != 0) {
            $builder->where('empresas.id', $id_empresa);
        }
        $builder->where('empresas.estado', 1);
        return $builder->get()->getResultArray();
    }
}
