<?php

namespace App\Models;

use CodeIgniter\Model;

class Model_responsable_empresas extends Model
{
    /**
     * 
     */
    public function getAllPaged($offset, $tamanioPagina, $soloMax = TRUE)
    {
        $builder = $this->db->table('responsable_empresas r_e');
        if ($soloMax) {
            $builder->select("COUNT(DISTINCT r_e.id) cantidad");
        } else {
            $builder->select('r_e.id, r_e.id_usuario id_usuario_responsable, CONCAT(u_r.nombre," ", u_r.apellido) usuario_responsable, e.id id_empresa, e.nombre empresa, r_e.tarjeta_mas, r_e.inspecciones, DATE_FORMAT(r_e.fecha_carga, "%d/%m/%Y") fecha_carga_f, CONCAT(u_c.nombre," ", u_c.apellido) usuario_carga, r_e.activo')
                ->orderBy('r_e.id', 'DESC')
                ->limit($tamanioPagina, $offset);
        }
        $builder->join('usuario u_r', 'u_r.id=r_e.id_usuario')
            ->join('empresas e', 'e.id=r_e.id_empresa')
            ->join('usuario u_c', 'u_c.id=r_e.usuario_carga');
        return $builder->get()->getResultArray();
    }

    /**
     * Traer todos aquellos responsables excepto quien está logueado en el sistema (No podés hacer una Tarjeta y elegirte como Responsable)
     */
    public function getAllResponsables($tarjeta_mas = false, $inspecciones = false)
    {
        $builder = $this->db->table('responsable_empresas r_e');
        $builder->select('r_e.id_usuario id, CONCAT(u_r.nombre," ", u_r.apellido) responsable_name, r_e.id_empresa');

        if ($tarjeta_mas) {
            $builder->where('tarjeta_mas', 1);
        } else if ($inspecciones) {
            $builder->where('inspecciones', 1);
        }
        $builder->join('usuario u_r', 'u_r.id=r_e.id_usuario');
        $builder->where('r_e.id_usuario !=', session()->get('id_usuario'));
        return $builder->get()->getResultArray();
    }

    /**
     * Inserta la relación entre el usuario responsable de esa empresa asignada.
     */
    public function insertData($data)
    {
        $builder = $this->db->table('responsable_empresas');
        $builder->insert($data);
        return $this->db->insertId();
    }

    /**
     * 
     */
    public function delete($id_rel = null, bool $purge = false)
    {
        $builder = $this->db->table('responsable_empresas')->where('id', $id_rel)->delete();
    }
}
