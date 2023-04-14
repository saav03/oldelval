<?php
namespace App\Models;

use CodeIgniter\Model;
class Model_logs extends Model {
    public function getAllPaged($offset, $tamanioPagina, $soloMax = FALSE)
    {
        $builder = $this->db->table('logs l');
        if ($soloMax) {
            $builder->select("COUNT(*) cantidad");
        } else {
            $builder->select("l.id,u.nombre usuario,DATE_FORMAT(l.fecha_hora, '%d/%m/%Y %H:%i') fecha_hora")->join('usuario u', 'l.id_usuario = u.id', 'inner')->where('l.id_usuario',session()->get('id_usuario'))->orderBy('id','DESC')
            ->limit($tamanioPagina, $offset);
        }
        $query = $builder->get();
        return $query->getResultArray();
    }
    public function getAllPagedAccess($offset, $tamanioPagina, $soloMax = FALSE)
    {
        $builder = $this->db->table('logs l');
        if ($soloMax) {
            $builder->select("COUNT(*) cantidad");
        } else {
            $builder->select("l.id,CONCAT(u.nombre,' ',u.apellido) usuario,DATE_FORMAT(l.fecha_hora, '%d/%m/%Y %H:%i') fecha_hora")
            ->join('usuario u', 'l.id_usuario = u.id', 'inner')
            ->orderBy('id','DESC')
            ->limit($tamanioPagina, $offset);
        }
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function addLog()
    {
        $builder = $this->db->table('logs');
        $builder->insert(['id_usuario'  => session('id_usuario')]);
    }
}
   