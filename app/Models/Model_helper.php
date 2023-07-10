<?php

namespace App\Models;

use CodeIgniter\Model;

class Model_helper extends Model
{
    public function getGrupoRelUsuario($id_usuario)
    {
        $builder = $this->db->table('usuario_rel_usuario_grupo');
        $builder->select('id_grupo')
            ->where('id_usuario', $id_usuario);
        return $builder->get()->getRowArray();
    }
    public function getPermisosFromGrupo($id_grupo)
    {
        $builder = $this->db->table('gg_rel_permiso_grupo');
        $builder->select('id_permiso')
            ->where('id_grupo', $id_grupo);
        return $builder->get()->getResultArray();
    }

    public function getGrupoRelUsuarioMayores($id_usuario)
    {
        $builder = $this->db->table('usuario');
        $builder->select('id, id_grupo')
            ->where('id >', $id_usuario);
        return $builder->get()->getResultArray();
    }
}
