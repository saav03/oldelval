<?php 

namespace App\Models;
use CodeIgniter\Model;

class Model_estacion extends Model {

    public function getEstacionesFilter($id_modulo) {
        $builder = $this->db->table('estaciones_bombeo estacion');
        $builder->select('estacion.*')
                ->join('rel_modulo_estacion rme', 'rme.id_estacion=estacion.id', 'inner')
                ->where('rme.id_modulo', $id_modulo)
                ->where('rme.estado', 1);
        return $builder->get()->getResultArray();
    }
}
