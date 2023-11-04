<?php
namespace App\Models;
use CodeIgniter\Model;
class Model_grupo extends Model { 

    public function get($id)
    {
        return $this->db->table('gg_grupos ggg')->select('*')->where('id',$id)->get()->getRowArray();

    }
    public function getAllUsuario($id_usuario)
    {
        $where = "u.id = ".$id_usuario." AND ggg.activo = 1";
        $builder = $this->db->table('gg_grupos ggg');
        $builder->select('ggg.id, ggg.nombre')->join('usuario_rel_usuario_grupo rel','rel.id_grupo = ggg.id')
        ->join('usuario u','u.id = rel.id_usuario')
        ->where($where);
        return $builder->get()->getResultArray();
    }

    public function getAll()
    {
        $where = "ggg.activo = 1";
        $builder = $this->db->table('gg_grupos ggg');
        $builder->select('ggg.id, ggg.nombre')
        ->where($where);
        return $builder->get()->getResultArray();
    }

    public function getGruposUsuario($id) {
        $builder = $this->db->table('gg_grupos ggg');
        $builder->select('ggg.id id_grupo, ggg.nombre grupo, urug.estado')
                ->join('usuario_rel_usuario_grupo urug', 'ggg.id=urug.id_grupo', 'inner')
                ->where('urug.id_usuario', $id)
                ->where('urug.estado', 1);
        return $builder->get()->getResultArray();
    }

    public function getAllUsersFromGroup($id_grupo) {
        $builder = $this->db->table('usuario_rel_usuario_grupo urug');
        $builder->select('id, id_usuario, id_grupo')
                ->where('id_grupo', $id_grupo)
                ->where('estado', 1);
        return $builder->get()->getResultArray();
    }

    public function vincularUsuario($grupos,$id_usuario)
    {
        $builder = $this->db->table('usuario_rel_usuario_grupo');
        $this->db->transStart();
        foreach ($grupos as $grupo) {
            $builder->insert(['id_grupo' => $grupo, 'id_usuario' => $id_usuario]);
        }
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $this->response->setStatusCode(400);
            $results = ['status' => false ,'message' => 'Fallo en la transaccion'];
        } else {
            $results = ['status' => true ,'message' => 'OK'];
        }
        return $results;
    }

    public function getAllPaged($offset, $tamanioPagina, $soloMax = FALSE)
    {
        $builder = $this->db->table('gg_grupos ggg');
        if ($soloMax) {
            $builder->select("COUNT(*) cantidad");
        } else {
            $builder->select("ggg.id,ggg.nombre,DATE_FORMAT(ggg.fecha_hora_carga, '%d/%m/%Y %H:%i') fecha_hora,CONCAT(u.nombre,' ',u.apellido) usuario")
                ->join("usuario u", "ggg.id_usuario = u.id", "left")->orderBy('id','ASC')
                ->limit($tamanioPagina, $offset);
        }
        $query = $builder->get();
        return $query->getResultArray();
    }
    public function add($grupo)
    {
        $builder = $this->db->table('gg_grupos');
        $this->db->transStart();
        $builder->insert($grupo);
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $this->response->setStatusCode(400);
            $results = ['status' => false ,'message' => 'Fallo en la transaccion'];
        } else {
            $last_id = $builder->select('id')->orderBy('id','DESC')->get()->getRowArray();
            $results = ['status' => true ,'message' => 'OK','last_id' => $last_id];
        }
        return $results;

    }
    
}