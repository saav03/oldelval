<?php

namespace App\Models;

use CodeIgniter\Model;

class Model_permisos extends Model
{
    public function getAll()
    {
        $builder = $this->db->table('gg_permisos ggp');
        $builder->select('ggp.id, ggp.nombre, ggp.modulo, ggp.id_permiso_padre, ggp.orden')
            ->orderBy('ggp.nombre', 'ASC');
        $query = $builder->get()->getResultArray();
        // $result = $this->menuArrayPermiso($query);
        return $query;
    }

    /**
     * Retorna un permiso individual a través del ID que se le envía por parámetros
     */
    public function getDataPermiso($id_permiso)
    {
        $builder = $this->db->table('gg_permisos ggp');
        $builder->select('*')
            ->where('ggp.id', $id_permiso);
        return $builder->get()->getRowArray();
    }

    public function getRelMenuPermiso($id)
    {
        $builder = $this->db->table('gg_rel_permiso_menu ggrpm');
        $builder->select('*')
            ->where('ggrpm.id_menu', $id);
        return $builder->get()->getResultArray();
    }

    public function getAllPaged($offset, $tamanioPagina, $soloMax = FALSE)
    {
        $builder = $this->db->table('gg_permisos ggp');

        if (!$offset && !$tamanioPagina) {
            $builder->select("COUNT(*) cantidad");
        } else {
            $builder->select('ggp.id id_permiso, ggp.nombre nombre_permiso, ggp.tipo_modulo tipo_permiso, uc.nombre usuario_creador, ggp.fecha_carga fecha_creacion, ggp.estado')
                ->limit($tamanioPagina, $offset);
        }

        $builder->join('usuario uc', 'uc.id = ggp.usuario', 'INNER');
        $resultado = $builder->get();
        return $resultado->getResultArray();
    }

    public function addPermitGroup($data)
    {
        $this->db->transStart();
        $builder = $this->db->table('gg_permisos');
        $builder->insert($data);
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

    public function disablePermit($id)
    {
        $this->db->transStart();
        $builder = $this->db->table('gg_permisos');
        $builder->set('estado', 0)
            ->where('id', $id)->update();
        $this->db->transComplete();

        if ($this->db->transStatus() === FALSE) {
            $this->response->setStatusCode(400);
            $results = ['status' => false, 'message' => 'Fallo en la transaccion'];
        } else {

            $this->db->transStart();
            $builder = $this->db->table('gg_rel_usuario_permiso');
            $builder->set('estado', 0)
                ->where('id_permiso', $id)->update();
            $this->db->transComplete();

            if ($this->db->transStatus() === FALSE) {
                $this->response->setStatusCode(400);
                $results = ['status' => false, 'message' => 'Fallo en la transaccion'];
            } else {
                $results = ['status' => true, 'message' => 'OK'];
            }
        }

        return $results;
    }

    public function enablePermit($id)
    {
        $this->db->transStart();
        $builder = $this->db->table('gg_permisos');
        $builder->set('estado', 1)
            ->where('id', $id)->update();
        $this->db->transComplete();

        if ($this->db->transStatus() === FALSE) {
            $this->response->setStatusCode(400);
            $results = ['status' => false, 'message' => 'Fallo en la transaccion'];
        } else {

            $this->db->transStart();
            $builder = $this->db->table('gg_rel_usuario_permiso');
            $builder->set('estado', 1)
                ->where('id_permiso', $id)->update();
            $this->db->transComplete();

            if ($this->db->transStatus() === FALSE) {
                $this->response->setStatusCode(400);
                $results = ['status' => false, 'message' => 'Fallo en la transaccion'];
            } else {
                $results = ['status' => true, 'message' => 'OK'];
            }
        }

        return $results;
    }

    public function getAllPermisos()
    {
        $builder = $this->db->table('gg_permisos ggp');
        $builder->select('ggp.id, ggp.nombre, ggp.modulo, ggp.id_permiso_padre, ggp.orden')
            ->where('ggp.estado', 1)
            ->orderBy('ggp.orden ASC');
        $query = $builder->get()->getResultArray();
        $result = $this->menuArrayPermiso($query);
        return $result;
    }

    /**
     * Trae todos los permisos, en caso de existir un $id_grupo, filtra por ese grupo
     */
    public function getAllPermisosUser($id_usuario, $join = '', $state = false)
    {
        $db = db_connect();
        if ($state) {
            $estado = ' AND gg_rel_usuario_permiso.estado = 1';
        } else {
            $estado = ' AND true';
        }
        $query = $db->query('SELECT gg_permisos.id id, nombre, modulo, id_permiso_padre, pertenece 
        FROM gg_permisos ' . $join . ' JOIN (SELECT IF(ISNULL(gg_rel_usuario_permiso.id_permiso), 0, 1) pertenece, gg_permisos.id as id_permiso FROM gg_rel_usuario_permiso LEFT JOIN gg_permisos ON gg_permisos.id = gg_rel_usuario_permiso.id_permiso WHERE gg_rel_usuario_permiso.id_usuario = ' . $id_usuario . $estado . ') as permisos_usuario ON gg_permisos.id = permisos_usuario.id_permiso WHERE 1 ORDER BY gg_permisos.orden ASC');

        $query = $query->getResultArray();
        $result = $this->menuArrayPermiso($query);
        return $result;
    }

    /**
     * Vincula cada permiso con el usuario
     */
    public function vincularPermisoUsuario($permisos, $id_usuario)
    {
        $builder = $this->db->table('gg_rel_usuario_permiso');
        $this->db->transStart();
        foreach ($permisos as $p) {
            $builder->insert(['id_usuario' => $id_usuario, 'id_permiso' => $p, 'estado' => 1]);
        }
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $this->response->setStatusCode(400);
            $results = ['status' => false, 'message' => 'Fallo en la transaccion'];
        } else {
            $results = ['status' => true, 'message' => 'OK'];
        }
        return $results;
    }

    /**
     * Vincula un permiso con un menú
     */
    public function vincularPermisoMenu($id_permiso, $id_menu)
    {
        $builder = $this->db->table('gg_rel_permiso_menu');
        $this->db->transStart();
        $builder->insert(['id_permiso' => $id_permiso, 'id_menu' => $id_menu]);
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $this->response->setStatusCode(400);
            $results = ['status' => false, 'message' => 'Fallo en la transaccion'];
        } else {
            $results = ['status' => true, 'message' => 'OK'];
        }
        return $results;
    }
    public function editVinculoPermisoMenu($id_permiso, $id_menu)
    {
        $db = db_connect();
        $db->query("UPDATE gg_rel_permiso_menu SET id_permiso = " . $id_permiso . " WHERE id_menu = " . $id_menu);
    }


    public function insertAndReorder($data)
    {
        $this->reorder($data['id_permiso_padre'], $data['orden']);
        return $this->insertMethod($data);
    }

    public function insertMethod($data)
    {
        $builder = $this->db->table('gg_permisos');
        $builder->insert($data);
        return $this->db->insertID();
    }

    public function updateAndReorder($data, $recordId)
    {
        //Lara 12/2/22
        $this->reorder($data['id_permiso_padre'], $data['orden']);
        return $this->updateMethod($data, $recordId);
    }

    public function updateMethod($data, $recordId)
    { //Se llamaba update pero coincide con un metodo de codeigniter, ahora se llama updateMethod
        $builder = $this->db->table('gg_permisos');
        $builder->where('id', $recordId);
        $builder->update($data);
    }

    public function getAllPermisosGroupAndUsers($grupos, $id_usuario)
    {

        $id_grupos = join(",", $grupos);

        $db = db_connect();
        $query = $db->query('SELECT gg_permisos.id id, nombre, modulo, id_permiso_padre, pertenece_grupo, pertenece_usuario
        FROM gg_permisos
        
        LEFT JOIN (SELECT IF(ISNULL(gg_rel_usuario_permiso.id_permiso), 0, 1) pertenece_usuario, gg_permisos.id as id_permiso FROM gg_rel_usuario_permiso 
    	   LEFT JOIN gg_permisos ON gg_permisos.id = gg_rel_usuario_permiso.id_permiso WHERE gg_rel_usuario_permiso.id_usuario = '. $id_usuario . ' AND gg_rel_usuario_permiso.estado = 1) as permisos_usuario ON gg_permisos.id = permisos_usuario.id_permiso 
           
           LEFT JOIN (SELECT IF(ISNULL(gg_rel_permiso_grupo.id_permiso), 0, 1) pertenece_grupo, gg_permisos.id as id_permiso FROM gg_rel_permiso_grupo 
           LEFT JOIN gg_permisos ON gg_permisos.id = gg_rel_permiso_grupo.id_permiso WHERE gg_rel_permiso_grupo.id_grupo IN (' . $id_grupos . ') AND gg_rel_permiso_grupo.estado = 1) as permisos_grupo ON gg_permisos.id = permisos_grupo.id_permiso 
           WHERE 1 GROUP BY gg_permisos.id ORDER BY gg_permisos.orden ASC
           ');

        $query = $query->getResultArray();
        $result = $this->menuArrayPermiso($query);
        return $result;
    }

    /**
     * Trae todos los permisos, en caso de existir un $id_grupo, filtra por ese grupo
     */
    public function getAllPermisosGroup($grupos, $join, $estado = false)
    {
        $where = '';
        if ($estado) {
            $where = 'AND gg_rel_permiso_grupo.estado = 1';
        }

        $id_grupos = join(",", $grupos);

        $groupby = '';
        if (count($grupos) >= 2) {
            $groupby = 'GROUP BY gg_permisos.id';
        }

        $db = db_connect();
        $query = $db->query('SELECT gg_permisos.id id, nombre, id_permiso_padre, pertenece 
		FROM gg_permisos ' . $join . ' JOIN (SELECT IF(ISNULL(gg_rel_permiso_grupo.id_permiso), 0, 1) pertenece, gg_permisos.id as id_permiso FROM gg_rel_permiso_grupo LEFT JOIN gg_permisos ON gg_permisos.id = gg_rel_permiso_grupo.id_permiso WHERE gg_rel_permiso_grupo.id_grupo IN (' . $id_grupos . ') ' . $where . ' ) as permisos_grupo ON gg_permisos.id = permisos_grupo.id_permiso WHERE 1 ' . $groupby . ' ORDER BY gg_permisos.orden ASC');
        $query = $query->getResultArray();
        $result = $this->menuArrayPermiso($query);
        return $result;
    }

    /**
     * Elimina aquellos permisos que son pasados a través de los parámetros de un grupo especifico
     * Elimina en la tabla 'gg_rel_usuario_permiso'
     */
    public function deletePermissionUser($permisos, $id_grupo)
    {
        $id_permisos = join(",", $permisos);
        $db = db_connect();
        $query = $db->query('DELETE * FROM gg_rel_usuario_permiso ggrup 
        INNER JOIN usuario u ON ggrup.id_usuario = u.id
        INNER JOIN usuario_rel_usuario_grupo ON usuario_rel_usuario_grupo.id_usuario = u.id 
        WHERE usuario_rel_usuario_grupo.id_grupo = ' . $id_grupo . ' AND ggrup.id_permiso IN (' . $id_permisos . ')');
    }

    /**
     * Agrega los permisos a todos aquellos usuarios que pertenecen a ese grupo
     */
    public function addGroupPermissionToUser($permisos, $id_grupo)
    {
        /* TERMINAR ESTA CONSULTAAAA */
    }

    private function menuArrayPermiso($menu, $padre = 0)
    {
        $result = [];
        $hijos = [];
        //Obtengo los hijos directos de $padre. Seria como un Array.find((e) => {e.padre == padre}); de js
        for ($i = 0; $i < count($menu); $i++) {
            if ($menu[$i]['id_permiso_padre'] == $padre) {
                $hijos[] = $menu[$i];
            }
        }
        if ($hijos) {
            for ($i = 0; $i < count($hijos); $i++) {
                $hijo = $hijos[$i];
                $hijo['children'] = [];
                $arreglo_de_hijos = $this->menuArrayPermiso($menu, $hijo['id']);
                if ($arreglo_de_hijos) {
                    $hijo['children'] = $arreglo_de_hijos;
                }
                $result[] = $hijo;
            }
            return $result;
        } else {
            return false;
        }
    }

    public function getPosibleParents()
    {
        // Lara 9/2/22
        //Como la plantilla no permite submenues anidados, no cualquier item de menu puede tener hijos. Solo aquellos que NO tengan padre pueden tener hijos
        $builder = $this->db->table("gg_permisos");
        $builder->select("id, nombre, orden");

        $builder->where("id_permiso_padre", 0);
        $builder->orderBy("nombre", "ASC");
        $query = $builder->get();
        return $query->getResultArray();
    }

    protected function reorder($parentID, $startFrom, $increment = TRUE)
    {
        //Lara 9/2/22
        /*
			Esta funcion reordena los items de los permisos. Dado un id padre, se ordenaran todos los hijos de ese id (Solo los hijos, no los nietos ni otras descendencias)
			El reordenamiento es orden+1 a partir de orden $startFrom
			Si no existe permiso con orden $startFrom nada ocurrira
		*/
        if ($increment)
            $this->db->query("UPDATE gg_permisos SET orden=orden+1 WHERE id_permiso_padre =$parentID AND orden >=$startFrom AND orden != -1");
        else
            $this->db->query("UPDATE gg_permisos SET orden=orden-1 WHERE id_permiso_padre =$parentID AND orden >=$startFrom AND orden != -1");
    }

    /**
     * Actualiza el estado del permiso en la tabla 'gg_rel_permiso_grupo'
     */
    public function changeState($id_grupo, $permiso, $estado)
    {
        $db = db_connect();
        $db->query("UPDATE gg_rel_permiso_grupo SET estado = " . $estado . " WHERE id_grupo = " . $id_grupo . " AND id_permiso = " . $permiso);
    }

    /**
     * Actualiza el estado del permiso en la tabla 'gg_rel_permiso_grupo'
     */
    public function changeStatePermisoUser($id_usuario, $permiso, $estado)
    {
        $db = db_connect();
        $db->query("UPDATE gg_rel_usuario_permiso SET estado = " . $estado . " WHERE id_usuario = " . $id_usuario . " AND id_permiso = " . $permiso);
    }

    /**
     *
     */
    public function deleteOnePermissionUser($id_usuario, $id_permiso)
    {
        $db = db_connect();
        $db->query("DELETE FROM gg_rel_usuario_permiso WHERE id_usuario = $id_usuario AND id_permiso = $id_permiso");
    }
}
