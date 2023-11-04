<?php

namespace App\Models;

use CodeIgniter\Model;

class Model_movimiento extends Model
{
    public function getAllPaged($offset, $tamanioPagina, $soloMax = FALSE)
    {
        $builder = $this->db->table('mov_movimientos mov');
        if ($soloMax) {
            $builder->select("COUNT(*) cantidad");
        } else {
            $builder->select("mov.id,mov_m.nombre modulo,mov_a.nombre accion,CONCAT(u.nombre,' ',u.apellido) usuario,DATE_FORMAT(mov.fecha_hora, '%d/%m/%Y %H:%i') fecha_accion,mov.id_afectado,mov.comentario")
                ->join("usuario u", "mov.id_usuario = u.id")
                ->join("mov_modulo mov_m", "mov_m.id = mov.id_modulo")
                ->join('mov_accion mov_a','mov_a.id = mov.id_accion')
                ->limit($tamanioPagina, $offset)
                ->orderBy('id','DESC');
        }
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function add($data)
    {
        $this->db->transStart();
        $builder = $this->db->table('mov_movimientos u');
        $builder->insert($data);
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $this->response->setStatusCode(400);
            echo 'Fallo en la transaccion';
        }
    }

    public function getRecentActivity() {
        $builder = $this->db->table('mov_movimientos mm');
        $builder->select("mm.id id_mov, mm.id_usuario, mm.id_afectado, CONCAT(u.nombre, ' ', u.apellido) nombre_usuario, mm_mod.id id_modulo, mm_mod.nombre modulo, mm_accion.id id_accion, mm_accion.nombre")
                ->join('usuario u', 'u.id=mm.id_usuario', 'inner')
                ->join('mov_modulo mm_mod', 'mm_mod.id=mm.id_modulo', 'inner')
                ->join('mov_accion mm_accion', 'mm_accion.id=mm.id_accion', 'inner')
                ->orderBy('mm.id', 'DESC')
                ->limit('8');
        $query = $builder->get()->getResultArray();
        return $query;
    }
}