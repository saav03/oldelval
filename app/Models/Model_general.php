<?php

namespace App\Models;

use CodeIgniter\Model;

class Model_general extends Model
{

    //Aclaracion: varios metodos llevan la G al final para poder diferenciarlos de los metodos de codeigniter
    //Ejemplo: "insert" es una funcion por defecto de CI4 pero si se desea usar la version de model_general entonces se debe
    //llamar diferente, en este caso lleva la G al final "insertG".

    public function insertG($tabla, $datos)
    {
        $builder = $this->db->table($tabla);
        $builder->insert($datos);
        return $this->db->insertId();
    }

    public function insertMultiple($tabla, $datos)
    {
        $builder = $this->db->table($tabla);
        return $builder->insertBatch($datos);
    }

    public function updateG($tabla, $id, $datos)
    {
        //FUNCIONA SIEMPRE QUE LA CLAVE DE LA TABLA SEA ID
        $builder = $this->db->table($tabla);
        $builder->where('id', $id);
        $builder->update($datos);
    }

    public function getAll($tabla, $order = NULL)
    {
        $builder = $this->db->table($tabla);
        if ($order) {
            $builder->orderBy($order[0], $order[1]);
        }
        $result = $builder->get();
        return $result->getResultArray();
    }

    public function getAllRiesgos($tabla)
    {
        $builder = $this->db->table($tabla);
        $builder->select("*, nombre as nombre");
        $result = $builder->get($tabla);
        return $result->getResultArray();
    }

    public function getAllActivo($tabla)
    {
        //Igual a getAll pero trae solo los elementos activos de la tabla. Requiere que la tabla tenga un campo de 'activo' y que 1 sea su valor true.
        $builder = $this->db->table($tabla);
        $builder->where('activo', 1);
        $result = $builder->get();
        return $result->getResultArray();
    }

    public function getAllEstadoActivo($tabla, $order = null)
    {
        //Igual a getAllActivo pero trae solo los elementos activos de la tabla. Requiere que la tabla tenga un campo de 'estado' y que 1 sea su valor true.
        $builder = $this->db->table($tabla);
        $builder->where('estado', 1);
        if ($order) {
            $builder->orderBy($order[0], $order[1]);
        }
        $result = $builder->get();
        return $result->getResultArray();
    }

    public function getLastId($tabla)
    {
        $builder = $this->db->table($tabla);
        $builder->select('id');
        $builder->orderBy('id', 'DESC');
        $builder->limit(1);
        $result = $builder->get();
        return $result->getRowArray();
    }

    public function get($tabla, $id)
    {
        //FUNCIONA SIEMPRE QUE LA CLAVE DE LA TABLA SEA ID
        $builder = $this->db->table($tabla);
        $builder->where('id', $id);
        $result = $builder->get();
        return $result->getRowArray();
    }

    public function getModified($tabla, $id, $where = 'id')
    {
        $builder = $this->db->table($tabla);
        $builder->where($where, $id);
        $result = $builder->get();
        return $result->getResultArray();
    }

    public function activar($tabla, $id)
    {
        //Funciona siempre que la clave de la tabla sea ID y exista un campo 'activo'
        $this->db->query("UPDATE $tabla SET activo = 1 WHERE id=$id");
    }

    public function desactivar($tabla, $id)
    {
        //Funciona siempre que la clave de la tabla sea ID y exista un campo 'activo'
        $this->db->query("UPDATE $tabla SET activo = 0 WHERE id=$id");
    }

    public function deleteG($tabla, $id)
    {
        //FUNCIONA SIEMPRE QUE LA CLAVE DE LA TABLA SEA ID
        $builder = $this->db->table($tabla);
        $builder->where('id', $id);
        $builder->delete();
    }
    public function deleteModified($tabla, $id, $where = 'id')
    {
        $builder = $this->db->table($tabla);
        $builder->where($where, $id);
        $builder->delete();
    }
    public function get_mov($id_afectado)
    {
        $builder = $this->db->table('mov_movimientos');
        $builder->select('mov_movimientos.id, id_usuario, id_modulo, id_accion, id_afectado, DATE_FORMAT(fecha_hora, "%d/%m/%Y %h:%i:%s") fecha_hora_format, comentario, u.nombre nombre_editor, u.apellido apellido_editor');
        $builder->join('usuario u', 'u.id=mov_movimientos.id_usuario', 'inner');
        $builder->where('id_afectado', $id_afectado);
        $builder->orderBy('mov_movimientos.fecha_hora', 'DESC');
        $result = $builder->get();
        return $result->getRowArray();
    }

    /**
     * Obtiene información para saber si el sistema está en mantenimiento en tal fecha o no
     */
    public function getMaintenance()
    {
        $builder = $this->db->table('maintenance');
        $builder->select('status, DATE_FORMAT(hora, "%H:%i") hora, DATE_FORMAT(fecha, "%d/%m/%Y") fecha');
        return $builder->get()->getRowArray();
    }
}
