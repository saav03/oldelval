<?php

namespace App\Models;

use CodeIgniter\Model;

class Model_logs extends Model
{
    public function getAllPagedPerfil($offset, $tamanioPagina, $soloMax = FALSE)
    {
        $builder = $this->db->table('logs l');
        if ($soloMax) {
            $builder->select("COUNT(*) cantidad")
                ->where('l.id_usuario', session()->get('id_usuario'))
                ->where('l.mk', '');
        } else {
            $builder->select("l.id,u.nombre usuario,DATE_FORMAT(l.fecha_hora, '%d/%m/%Y %H:%i') fecha_hora")
                ->join('usuario u', 'l.id_usuario = u.id', 'inner')
                ->where('l.id_usuario', session()->get('id_usuario'))
                ->where('l.mk', '')
                ->orderBy('id', 'DESC')
                ->limit($tamanioPagina, $offset);
        }
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getAllPagedUsuario($offset, $tamanioPagina, $id_user, $soloMax = FALSE)
    {
        $builder = $this->db->table('logs l');
        if ($soloMax) {
            $builder->select("COUNT(*) cantidad")
                ->where('l.id_usuario', $id_user);
        } else {
            $builder->select("l.id,u.nombre usuario, l.mk,DATE_FORMAT(l.fecha_hora, '%d/%m/%Y %H:%i') fecha_hora")
                ->join('usuario u', 'l.id_usuario = u.id', 'inner')
                ->where('l.id_usuario', $id_user)
                ->orderBy('id', 'DESC')
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
            $builder->select("l.id,CONCAT(u.nombre,' ',u.apellido) usuario, l.mk,DATE_FORMAT(l.fecha_hora, '%d/%m/%Y %H:%i') fecha_hora")
                ->join('usuario u', 'l.id_usuario = u.id', 'inner')
                ->orderBy('id', 'DESC')
                ->limit($tamanioPagina, $offset);
        }
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function addLog($mk = '')
    {
        $builder = $this->db->table('logs');
        $data = [
            'id_usuario' => session('id_usuario')
        ];
        if (!empty($mk)) {
            $data['mk'] = $mk;
        }

        $builder->insert($data);
    }
}
