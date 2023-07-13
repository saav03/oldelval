<?php

namespace App\Models;

use CodeIgniter\Model;

class Model_usuario extends Model
{

    public function getAll()
    {
        $builder = $this->db->table('usuario u');
        $builder->select('u.id, u.nombre,u.apellido ,u.correo,DATE_FORMAT(up.fecha_creacion, "%d/%m/%Y %H:%i") fecha_creacion, u.usuario,')
            ->join('usuario_perfil up', 'u.id = up.id_usuario', 'inner');
        return $builder->get()->getResultArray();
    }

    public function getAllPaged($offset, $tamanioPagina, $soloMax = FALSE)
    {
        $builder = $this->db->table('usuario u');
        if ($soloMax) {
            $builder->select("COUNT(*) cantidad");
        } else {
            $builder->select("u.id,u.usuario,u.nombre,u.apellido,u.correo,u.activo,CONCAT(uc.nombre,' ',uc.apellido) creador,DATE_FORMAT(up.fecha_creacion, '%d/%m/%Y %H:%i') fecha_creacion, u.activo")
                ->join("usuario uc", "u.id = uc.id", "inner")
                ->join("usuario_perfil up", "up.id_usuario = u.id", "left")
                ->limit($tamanioPagina, $offset);
        }
        $query = $builder->get();
        return $query->getResultArray();
    }
    public function add($datos)
    {
        // $db      = \Config\Database::connect();
        $this->db->transStart();
        $builder = $this->db->table('usuario u');
        $builder->insert($datos);
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $this->response->setStatusCode(400);
            $results = ['status' => false, 'message' => 'Fallo en la transaccion'];
        } else {
            $last_id = $builder->select('id')->orderBy('id', 'DESC')->get()->getRowArray();
            $results = ['status' => true, 'message' => 'OK', 'last_id' => $last_id];
        }
        return $results;
    }
    public function addDatosPerfil($datos)
    {
        $this->db->transStart();
        $builder = $this->db->table('usuario_perfil u');
        $builder->insert($datos);
        $this->db->transComplete();
    }

    public function edit($datos, $id_usuario)
    {
        $this->db->transStart();
        $builder = $this->db->table('usuario u');
        $builder->where('u.id', $id_usuario);
        $builder->update($datos);
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $this->response->setStatusCode(400);
            $results = ['status' => false, 'message' => 'Fallo en la transaccion'];
        } else {
            $last_id = $builder->select('id')->orderBy('id', 'DESC')->get()->getRowArray();
            $results = ['status' => true, 'message' => 'OK', 'last_id' => $last_id];
        }
        return $results;
    }


    public function getDatos($id)
    {
        $builder = $this->db->table('usuario u');
        $builder->select('u.id id_usuario,u.dni, u.competencia,u.usuario,u.nombre nombre_usuario ,u.telefono,u.localidad,u.apellido,u.correo,u.imagen_perfil,u.fecha_nacimiento,DATE_FORMAT(up.fecha_modificacion_perfil, "%d/%m/%Y %H:%i") fecha_modificacion, empresas.nombre as nombre_empresa, u.superadmin, DATE_FORMAT(up.fecha_first_login, "%d/%m/%Y %H:%i:%s") first_login,up.panel_emergente,up.estilo')
            ->join('usuario_perfil up', 'up.id_usuario = u.id', 'inner')
            ->join('empresas', 'empresas.id = u.empresa', 'left')
            ->where('u.id', $id);

        return $builder->get()->getResultArray();
    }
    /**
     * Actualizar la password del usuario en caso de editarse la contraseña
     */
    public function changePassword($clave, $id_usuario)
    {
        $builder = $this->db->table('usuario u');
        $builder->set('u.clave', $clave);
        $builder->where('u.id', $id_usuario);
        $builder->update();
    }

    public function getResponsables($id_empresa, $id_usuario_logueado)
    {
        $builder = $this->db->table('usuario u');
        $builder->select('u.id id_usuario, CONCAT(u.nombre, " ", u.apellido) usuario_nombre')
            ->join('gg_rel_usuario_permiso ggrup' ,'ggrup.id_usuario=u.id', 'join')
            ->where('u.empresa', $id_empresa)
            ->where('u.id !=', $id_usuario_logueado)
            ->where('ggrup.id_permiso', 46)
            ->orderBy('u.id');
        return $builder->get()->getResultArray();
    }

    /**
     * Actualiza el estado del grupo del usuario en la tabla 'usuario_rel_usuario_grupo'
     */
    public function changeState($id_grupo, $id_usuario)
    {
        $db = db_connect();
        $db->query("UPDATE usuario_rel_usuario_grupo SET estado = CASE WHEN estado = 1 THEN 0 ELSE 1 END WHERE id_grupo = " . $id_grupo . " AND id_usuario = " . $id_usuario);

        // $db->query("UPDATE usuario_rel_usuario_grupo SET estado = " . $estado . " WHERE id_grupo = " . $id_grupo . " AND id_usuario = " . $id_usuario);
    }

    /**
     * Actualizar el estado del usuario, 1: Activo | 0:Inactivo
     */
    public function changeStateUser($id)
    {
        $db = db_connect();
        $db->query("UPDATE usuario SET activo = CASE WHEN activo = 1 THEN 0 ELSE 1 END WHERE id = " . $id);
    }
}
