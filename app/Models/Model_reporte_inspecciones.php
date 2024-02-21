<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Table;


class Model_reporte_inspecciones extends Model
{
    /**
     * Obtiene la cantidad de inspecciones que se realizaron en el mes actual
     * También obtiene el total de las inspecciones que se encuentran almacenadas en el sistema.
     */
    public function getInspecciones()
    {
        $builder = $this->db->table('auditoria');
        $builder->select('COUNT(id) este_mes')
            ->where('MONTH(fecha_hora_carga)', date('m'));
        $query = $builder->get()->getRowArray();

        # Suma el total de las Tarjetas M.A.S
        $builder->select('COUNT(id) total');
        $query['total'] = $builder->get()->getRow('total');

        return $query;
    }

    /**
     * Obtiene las inspecciones dependiendo el filtro que se pase por parámetro, el cual puede ser => ['hoy', 'mes', 'year']
     */
    public function get_inspecciones_filter($filter)
    {
        # Suma el total de las Tarjetas M.A.S con hallazgos pendientes
        $builder = $this->db->table('auditoria a');
        $builder->select('COUNT(DISTINCT a.id) cantidad');
        switch ($filter) {
            case 'hoy':
                $builder->where('DAY(a.fecha_hora_carga)', date('d'));
                $builder->where('MONTH(a.fecha_hora_carga)', date('m'));
                $builder->where('YEAR(a.fecha_hora_carga)', date('Y'));
                break;
            case 'mes':
                $builder->where('MONTH(a.fecha_hora_carga)', date('m'));
                $builder->where('YEAR(a.fecha_hora_carga)', date('Y'));
                break;
            case 'year':
                $builder->where('YEAR(a.fecha_hora_carga)', date('Y'));
                break;
        }
        return $builder->get()->getRowArray();
    }

    /**
     * Obtiene los datos de cada Inspección (De Control, Vehicular, etc)
     * Luego, los retorna para poder visualizarlos en el gráfico de tortas
     * Se utiliza en el Controller del Dashboard.php
     */
    public function getInspeccionesGraphicCake($type_inspection)
    {
        $builder = $this->db->table('auditoria');
        $builder->select('COUNT(id) cantidad')
            ->where('auditoria.auditoria', $type_inspection);
        $query = $builder->get()->getRow('cantidad');
        return $query;
    }

    /**
     * Este método obtiene las respuestas de las observaciones que se realizaron en las Inspecciones/Auditorías
     * Se utiliza en la tabla que está en el dashboard del Controller Dashboard.php "Respuestas de Observaciones"
     */
    public function getObservacionesTable()
    {
        $builder = $this->db->table('auditoria');
        $builder->select('auditoria.id id_auditoria, CONCAT(u.nombre, " ", u.apellido) responsable, auditoria.auditoria, a_h_d.estado, a_h_d.respuesta')
            ->join('auditoria_hallazgos a_h', 'auditoria.id=a_h.id_auditoria')
            ->join('auditoria_hallazgo_descargos a_h_d', 'a_h.id=a_h_d.id_hallazgo')
            ->join('usuario u', 'a_h.responsable=u.id');
        return $builder->get()->getResultArray();
    }
}
