<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Table;


class Model_reporte_tarjeta extends Model
{
    /**
     * Suma todas las Tarjetas M.A.S (Ojo, no los hallazgos, las tarjetas) en donde el usuario
     * logueado está como responsable y la Tarjeta aún tiene hallazgos pendientes
     * También Suma el total de todas las Tarjetas M.A.S
     */
    public function get_tarjeta_pendiente($id_usuario)
    {
        # Suma el total de las Tarjetas M.A.S con hallazgos pendientes
        $builder = $this->db->table('tarjeta_observaciones tar_obs');
        $builder->select('COUNT(DISTINCT tar_obs.id) cantidad')
            ->join('tarjeta_hallazgos t_h', 't_h.id_tarjeta=tar_obs.id', 'inner')
            ->where('t_h.responsable', $id_usuario)
            ->where('t_h.resuelto', null);
        $query = $builder->get()->getRowArray();

        # Suma el total de las Tarjetas M.A.S
        $builder->select('COUNT(tar_obs.id) total');
        $query['total'] = $builder->get()->getRow('total');

        $porcentaje = ($query['cantidad'] / $query['total']) * 100;
        $query['porcentaje'] = number_format($porcentaje, 2, '.', '');

        return $query;
    }

    /**
     * Suma todas las Tarjetas M.A.S (Ojo, no los hallazgos, las tarjetas) en donde el usuario
     * logueado está como responsable y la Tarjeta aún tiene hallazgos pendientes
     * También Suma el total de todas las Tarjetas M.A.S
     */
    public function get_tarjeta_pendiente_filter($id_usuario, $filter)
    {
        # Suma el total de las Tarjetas M.A.S con hallazgos pendientes
        $builder = $this->db->table('tarjeta_observaciones tar_obs');
        $builder->select('COUNT(DISTINCT tar_obs.id) cantidad')
            ->join('tarjeta_hallazgos t_h', 't_h.id_tarjeta=tar_obs.id', 'inner')
            ->where('t_h.responsable', $id_usuario)
            ->where('t_h.resuelto', null);
        switch ($filter) {
            case 'hoy':
                $builder->where('DAY(tar_obs.fecha_hora_carga)', date('d'));
                break;
            case 'mes':
                $builder->where('MONTH(tar_obs.fecha_hora_carga)', date('m'));
                break;
            case 'year':
                $builder->where('YEAR(tar_obs.fecha_hora_carga)', date('Y'));
                break;
        }
        $query = $builder->get()->getRowArray();

        # Suma el total de las Tarjetas M.A.S
        $builder->select('COUNT(tar_obs.id) total');
        $query['total'] = $builder->get()->getRow('total');

        $porcentaje = ($query['cantidad'] / $query['total']) * 100;
        $query['porcentaje'] = number_format($porcentaje, 2, '.', '');

        return $query;
    }

    /**
     * 
     */
    public function get_descargos_rta_hallazgos_pendiente($id_usuario)
    {
        $builder = $this->db->table('tarjeta_hallazgos t_h');
        $builder->select('COUNT(t_h_d.id) cantidad')
            ->join('tarjeta_hallazgo_descargos t_h_d', 't_h_d.id_hallazgo=t_h.id', 'inner')
            ->where('usuario_carga', $id_usuario)
            ->where('t_h_d.respuesta IS NULL')
            ->where('resuelto IS NULL');
        return $builder->get()->getRowArray();
    }

    /**
     * 
     */
    public function get_hallazgos_totales_propios($id_usuario)
    {
        $builder = $this->db->table('tarjeta_hallazgos t_h');
        $builder->select('COUNT(t_h.id) cantidad')
            ->where('usuario_carga', $id_usuario);
        return $builder->get()->getRowArray();
    }

    /**
     * 
     */
    public function get_total_tarjetas_propias($id_usuario, $situacion = '')
    {
        $builder = $this->db->table('tarjeta_observaciones t_a');
        $builder->select('COUNT(t_a.id) cantidad');
        if ($situacion != '')
            $builder->where('t_a.situacion', $situacion);
        $builder->where('usuario_carga', $id_usuario);
        return $builder->get()->getRowArray();
    }

    /**
     * Obtiene la cantidad de Hallazgos dependiendo el aspecto y la significancia que se pase por parámetro
     * Por el momento trae todas las tarjetas, no solamente del usuario
     */
    public function get_observaciones_graphic($aspecto = 1, $significancia = 1)
    {
        $builder = $this->db->table('tarjeta_hallazgos t_h');
        $builder->select('COUNT(DISTINCT t_h.id) cantidad, DATE_FORMAT(fecha_hora_carga, "%d") dia')
            ->where('MONTH(fecha_hora_carga)', date('m'))
            ->where('YEAR(fecha_hora_carga)', date('Y'))
            ->where('aspecto', $aspecto)
            ->where('significancia', $significancia)
            ->groupBy('DAY(fecha_hora_carga)');
        return $builder->get()->getResultArray();
    }

    /**
     * Similar al método de get_observaciones_graphic() solamente que realiza el filtro por el año actual
     */
    public function get_observaciones_graphic_per_year($aspecto = 1, $significancia = 1)
    {
        $builder = $this->db->table('tarjeta_hallazgos');
        $builder->select('EXTRACT(MONTH FROM fecha_hora_carga) AS mes, COUNT(id) as cantidad')
            ->where('YEAR(fecha_hora_carga) = YEAR(CURRENT_DATE())')
            ->where('aspecto', $aspecto)
            ->where('significancia', $significancia)
            ->groupBy('mes');
        return $builder->get()->getResultArray();
    }
}
