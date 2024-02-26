<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Table;
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Php;

class Model_mail_tarjeta extends Model
{
    /**
     * Cuando se crea una nueva Inspección, obtiene los datos para poder enviar los correos
     */
    public function getDataNewInspection($id_inspection, $auditoria)
    {
        $builder = $this->db->table('auditoria a')
            ->select('a.id id, auditoria, a_titulos.nombre modelo_tipo, CONCAT(u_carga.nombre, " ", u_carga.apellido) usuario_carga, u_carga.correo correo_usuario_carga, DATE_FORMAT(a.fecha_hora_carga, "%d/%m/%Y") fecha_carga_format, a.supervisor_responsable,  p.nombre proyecto')
            ->join('auditorias_titulos a_titulos', 'a_titulos.id=a.modelo_tipo', 'inner')
            ->join('usuario u_carga', 'u_carga.id=a.usuario_carga', 'inner')
            ->join('proyectos p', 'p.id=a.proyecto', 'inner')
            ->where('a.id', $id_inspection);
        $query = $builder->get()->getRowArray();

        switch ($auditoria) {
            case '1':
                $nombre_inspeccion = 'Inspección de Control';
                break;
            case '2':
                $nombre_inspeccion = 'Inspección Vehicular';
                break;
            case '3':
                $nombre_inspeccion = 'Inspección de Obra';
                break;
            case '4':
                $nombre_inspeccion = 'Inspección de Auditoría';
                break;
        }
        $query['auditoria'] = $nombre_inspeccion;
        return $query;
    }

    /**
     * Obtiene los datos del hallazgo creado para poder enviarlo a través del correo
     * Si $segundo_responsable es 'false' entonces es el responsable, caso contrario es el relevo
     */
    public function getDataNewObservacion($auditoria, $id_hallazgo, $segundo_responsable) {
        $builder = $this->db->table('auditoria_hallazgos a_h');
        $builder->select('a.id id_inspeccion, a_h.id id, CONCAT(u_carga.nombre, " ", u_carga.apellido) usuario_carga, u_carga.correo correo_usuario_carga, CONCAT(u_responsable.nombre, " ", u_responsable.apellido) responsable, u_responsable.correo correo_usuario_responsable, DATE_FORMAT(a_h.fecha_cierre, "%d/%m/%Y") fecha_cierre, tipo.nombre tipo, p.nombre proyecto')
            ->join('auditoria a', 'a.id=a_h.id_auditoria', 'inner')
            ->join('usuario u_carga', 'u_carga.id=a_h.usuario_carga', 'inner');
            if (!$segundo_responsable) {
                $builder->join('usuario u_responsable', 'u_responsable.id=a_h.responsable', 'inner');
            } else {
                $builder->join('usuario u_responsable', 'u_responsable.id=a_h.relevo_responsable', 'left');
            }
            $builder->join('tipo_hallazgo tipo', 'tipo.id=a_h.tipo', 'inner')
            ->join('proyectos p', 'p.id=a.proyecto', 'inner')
            ->where('a_h.id', $id_hallazgo);
        $query = $builder->get()->getRowArray();

        switch ($auditoria) {
            case '1':
                $nombre_inspeccion = 'Inspección de Control';
                break;
            case '2':
                $nombre_inspeccion = 'Inspección Vehicular';
                break;
            case '3':
                $nombre_inspeccion = 'Inspección de Obra';
                break;
            case '4':
                $nombre_inspeccion = 'Inspección de Auditoría';
                break;
        }
        $query['auditoria'] = $nombre_inspeccion;

        return $query;
    }

    /**
     * Obtiene los datos de un nuevo descargo para enviarlo por correo
     */
    public function getDataNewDescargo($id_descargo) {
        $builder = $this->db->table('auditoria_hallazgo_descargos aud_h_d')
        ->select('aud_h_d.id id_descargo, aud_h.id id_hallazgo, aud.id id_inspeccion, aud.auditoria, CONCAT(u_carga.nombre, " ", u_carga.apellido) usuario_carga, u_carga.correo correo_usuario_carga, aud_h_d.motivo, CONCAT(u_rta.nombre, " ", u_rta.apellido) usuario_rta, DATE_FORMAT(aud_h_d.fecha_hora_motivo, "%d/%m/%Y") fecha_motivo, DATE_FORMAT(aud_h.fecha_cierre, "%d/%m/%Y") fecha_cierre')
        ->join('auditoria_hallazgos aud_h', 'aud_h.id=aud_h_d.id_hallazgo', 'inner')
        ->join('auditoria aud', 'aud.id=aud_h.id_auditoria', 'inner')
        ->join('usuario u_carga', 'u_carga.id=aud_h.usuario_carga', 'inner')
        ->join('usuario u_rta', 'u_rta.id=aud_h_d.id_usuario', 'inner')
        ->where('aud_h_d.id', $id_descargo);
        $query = $builder->get()->getRowArray();
        return $query;
    }

    /**
     * Obtiene los datos del descargo que se cargó previamente
     */
    public function getDataRtaDescargo($id_descargo) {
        $builder = $this->db->table('auditoria_hallazgo_descargos aud_h_d')
        ->select('aud_h_d.id id_descargo, aud_h.id id_hallazgo, aud.id id_inspeccion, aud.auditoria, CONCAT(u_carga_hallazgo.nombre, " ", u_carga_hallazgo.apellido) usuario_carga_hallazgo, u_carga_hallazgo.correo correo_usuario_carga_hallazgo, aud_h_d.respuesta, CONCAT(u_carga_descargo.nombre, " ", u_carga_descargo.apellido) usuario_carga_descargo, u_carga_descargo.correo correo_usuario_carga_descargo, DATE_FORMAT(aud_h_d.fecha_hora_motivo, "%d/%m/%Y") fecha_motivo, DATE_FORMAT(aud_h.fecha_cierre, "%d/%m/%Y") fecha_cierre, aud_h_d.estado')
        ->join('auditoria_hallazgos aud_h', 'aud_h.id=aud_h_d.id_hallazgo', 'inner')
        ->join('auditoria aud', 'aud.id=aud_h.id_auditoria', 'inner')
        ->join('usuario u_carga_hallazgo', 'u_carga_hallazgo.id=aud_h.usuario_carga', 'inner')
        ->join('usuario u_carga_descargo', 'u_carga_descargo.id=aud_h_d.usuario_carga', 'inner')
        ->where('aud_h_d.id', $id_descargo);
        $query = $builder->get()->getRowArray();
        return $query;
    }
}