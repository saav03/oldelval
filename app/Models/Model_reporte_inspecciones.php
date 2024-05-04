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

    /**
     * Obtiene de aquellas Inspecciones, las observaciones que se hayan realizado y donde el responsable es el usuario que está
     * iniciado sesión
     * [IMPORTANTE => Se encarga de sumar todos los pendientes en relación a las observaciones de las Inspecciones]
     */
    public function get_inspecciones_pendiente($id_usuario) {
        $builder = $this->db->table('auditoria_hallazgos aud_h');
        $builder->select('COUNT(DISTINCT aud_h.id) cantidad')
            ->where('aud_h.responsable', $id_usuario)
            ->where('aud_h.resuelto', null);
        $query = $builder->get()->getRowArray();

        # Obtenemos la consulta y sumamos todas las cantidades. Es decir, sumamos aquellos hallazgos donde el usuario es responsable
        # Y también donde tiene que responder de los descargos
        $cantidad_rta_pendientes = $this->_get_descargos_rta_hallazgos_pendiente($id_usuario);
        $query['cantidad'] = $query['cantidad'] + $cantidad_rta_pendientes['cantidad'];

        return $query;
    }

    /**
     * Obtiene la cantidad de descargos pendiente de una respuesta del usuario quien está logueado
     */
    protected function _get_descargos_rta_hallazgos_pendiente($id_usuario)
    {
        $builder = $this->db->table('auditoria_hallazgos aud_h');
        $builder->select('COUNT(a_h_d.id) cantidad')
            ->join('auditoria_hallazgo_descargos a_h_d', 'a_h_d.id_hallazgo=aud_h.id', 'inner')
            ->where('usuario_carga', $id_usuario)
            ->where('a_h_d.respuesta IS NULL')
            ->where('resuelto IS NULL');
        return $builder->get()->getRowArray();
    }
}
