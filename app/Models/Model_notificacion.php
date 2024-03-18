<?php

namespace App\Models;

use CodeIgniter\Model;

class Model_notificacion extends Model
{
    public function get()
    {
        $builder = $this->db->table('notificaciones');
        $builder->select('*')
            ->where('usuario_notificado', session()->get('id_usuario'))
            ->orderBy('fecha_notificacion', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function set_notificacion_leida($id_notificacion, $data) {
        $builder = $this->db->table('notificaciones');
        $builder->where('usuario_notificado', session()->get('id_usuario'))
                ->where('id', $id_notificacion);
        $builder->update($data);
    }

    public function setear_notificaciones_leidas($data) {
        $builder = $this->db->table('notificaciones');
        $builder->where('usuario_notificado', session()->get('id_usuario'));
        $builder->update($data);
    }

    public function add($data)
    {
        $this->db->transStart();
        $builder = $this->db->table('notificaciones');
        $builder->insert($data);
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $this->response->setStatusCode(400);
            echo 'Fallo en la transaccion';
        }
    }
}
