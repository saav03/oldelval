<?php 

namespace App\Models;
use CodeIgniter\Model;

class Model_proyectos extends Model {

    public function getModulosFilter($id_proyecto) {
        $builder = $this->db->table('modulos m');
        $builder->select('m.*')
                ->join('rel_proyecto_modulo rpm', 'rpm.id_modulo=m.id', 'inner')
                ->where('rpm.id_proyecto', $id_proyecto)
                ->where('rpm.estado', 1);
        return $builder->get()->getResultArray();
    }
}
