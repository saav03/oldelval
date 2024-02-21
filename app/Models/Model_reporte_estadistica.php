<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Table;

class Model_reporte_estadisticas extends Model
{
    /**
     * Obtiene la cantidad de estadísticas que se realizaron en el mes actual
     * También obtiene el total de las estadísticas que se encuentran almacenadas en el sistema.
     */
    public function getEstadisticas()
    {
        $builder = $this->db->table('estadisticas_planilla');
        $builder->select('COUNT(id) este_mes')
            ->where('MONTH(fecha_hora_carga)', date('m'));
        $query = $builder->get()->getRowArray();

        # Suma el total de las Tarjetas M.A.S
        $builder->select('COUNT(id) total');
        $query['total'] = $builder->get()->getRow('total');
        return $query;
    }

    /**
     * Obtiene las estadísticas dependiendo el filtro que se pase por parámetro, el cual puede ser => ['hoy', 'mes', 'year']
     */
    public function get_estadistica_pendiente_filter($filter)
    {
        # Suma el total de las Tarjetas M.A.S con hallazgos pendientes
        $builder = $this->db->table('estadisticas_planilla e_p');
        $builder->select('COUNT(DISTINCT e_p.id) cantidad');
        switch ($filter) {
            case 'hoy':
                $builder->where('DAY(e_p.fecha_hora_carga)', date('d'));
                break;
            case 'mes':
                $builder->where('MONTH(e_p.fecha_hora_carga)', date('m'));
                break;
            case 'year':
                $builder->where('YEAR(e_p.fecha_hora_carga)', date('Y'));
                break;
        }
        return $builder->get()->getRowArray();
    }
}
