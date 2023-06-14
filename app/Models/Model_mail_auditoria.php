<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Table;
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Php;

class Model_mail_tarjeta extends Model
{
    public function getInfoNewDescargo($id_hallazgo, $id_descargo)
    {
        $builder = $this->db->table('tarjeta_hallazgo_descargos descargo');
        $builder->select('obs.id id_obs, u.nombre u_nombre_carga, u.apellido u_apellido_carga, u.correo correo_carga, u_responde.nombre u_nombre_responde, u_responde.apellido u_apellido_responde, descargo.id_hallazgo id_hallazgo, descargo.id id_descargo, descargo.motivo, descargo.estado estado_descargo, DATE_FORMAT(fecha_hora_motivo, "%d/%m/%Y") fecha_motivo, DATE_FORMAT(hallazgo.fecha_cierre, "%d/%m/%Y") fecha_vencimiento')
            ->join('tarjeta_hallazgos hallazgo', 'hallazgo.id=descargo.id_hallazgo', 'inner')
            ->join('tarjeta_observaciones obs', 'obs.id=hallazgo.id_tarjeta', 'inner')
            ->join('usuario u', 'u.id=obs.usuario_carga', 'inner')
            ->join('usuario u_responde', 'u_responde.id=descargo.id_usuario', 'inner')
            ->where('descargo.id_hallazgo', $id_hallazgo)
            ->where('descargo.id', $id_descargo);
        $query = $builder->get()->getRowArray();
        return $query;
    }

    /**
     * Respuesta Descargo Auditoría Control
     */
    public function getRespuestaDescargoAudControl($id_descargo)
    {
        $builder = $this->db->table('obs_hallazgos_descargos descargo');
        $builder->select('ac.id, at.nombre titulo_auditoria, hallazgo.id id_hallazgo, descargo.id_usuario_rta id_usuario_rta, descargo.estado estado_rta, descargo.respuesta, CONCAT(u.nombre, " ", u.apellido) usuario_carga, u.correo correo_carga, CONCAT(u_responde.nombre, " ", u_responde.apellido) usuario_responde, u_responde.correo correo_responsable, DATE_FORMAT(descargo.fecha_hora_respuesta, "%d/%m/%Y") fecha_rta')
            ->join('obs_hallazgos hallazgo', 'hallazgo.id=descargo.id_hallazgo', 'inner')
            ->join('auditoria_control ac', 'ac.id=hallazgo.id_auditoria', 'inner')
			->join('auditorias_titulos at', 'at.id=ac.modelo_tipo', 'inner')
            ->join('usuario u', 'u.id=hallazgo.usuario_carga', 'inner')
            ->join('usuario u_responde', 'u_responde.id=descargo.id_usuario', 'inner')
            ->where('descargo.id', $id_descargo);
        return $builder->get()->getRowArray();
    }

    /**
     * Respuesta Descargo Auditoría Vehicular
     */
    public function getRespuestaDescargoAudVehicular($id_descargo)
    {
        $builder = $this->db->table('obs_hallazgos_descargos descargo');
        $builder->select('av.id, at.nombre titulo_auditoria, hallazgo.id id_hallazgo, descargo.id_usuario_rta id_usuario_rta, descargo.estado estado_rta, descargo.respuesta, CONCAT(u.nombre, " ", u.apellido) usuario_carga, u.correo correo_carga, CONCAT(u_responde.nombre, " ", u_responde.apellido) usuario_responde, u_responde.correo correo_responsable, DATE_FORMAT(descargo.fecha_hora_respuesta, "%d/%m/%Y") fecha_rta')
            ->join('obs_hallazgos hallazgo', 'hallazgo.id=descargo.id_hallazgo', 'inner')
            ->join('auditoria_vehicular av', 'av.id=hallazgo.id_auditoria', 'inner')
			->join('auditorias_titulos at', 'at.id=av.modelo_tipo', 'inner')
            ->join('usuario u', 'u.id=hallazgo.usuario_carga', 'inner')
            ->join('usuario u_responde', 'u_responde.id=descargo.id_usuario', 'inner')
            ->where('descargo.id', $id_descargo);
        return $builder->get()->getRowArray();
    }

    /**
     * Descargo para la Auditoría CheckList Vehicular
     */
    public function getInfoNewDescargoAudVehicular($id_hallazgo, $id_descargo)
    {
        $builder = $this->db->table('obs_hallazgos_descargos descargo');
        $builder->select('av.id, at.nombre titulo_auditoria, CONCAT(u.nombre, " ", u.apellido) usuario_carga, u.correo correo_carga, CONCAT(u_responde.nombre, " ", u_responde.apellido) usuario_responde, descargo.id_hallazgo id_hallazgo, descargo.id id_descargo, descargo.motivo, descargo.estado estado_descargo, DATE_FORMAT(fecha_hora_motivo, "%d/%m/%Y") fecha_motivo, DATE_FORMAT(hallazgo.fecha_cierre, "%d/%m/%Y") fecha_vencimiento')
            ->join('obs_hallazgos hallazgo', 'hallazgo.id=descargo.id_hallazgo', 'inner')
            ->join('auditoria_vehicular av', 'av.id=hallazgo.id_auditoria', 'inner')
			->join('auditorias_titulos at', 'at.id=av.modelo_tipo', 'inner')
            ->join('usuario u', 'u.id=hallazgo.usuario_carga', 'inner')
            ->join('usuario u_responde', 'u_responde.id=descargo.id_usuario', 'inner')
            ->where('descargo.id_hallazgo', $id_hallazgo)
            ->where('descargo.id', $id_descargo);
        $query = $builder->get()->getRowArray();
        return $query;
    }

    /**
     * Descargo para la Auditoría Control
     */
    public function getInfoNewDescargoAudControl($id_hallazgo, $id_descargo)
    {
        $builder = $this->db->table('obs_hallazgos_descargos descargo');
        $builder->select('ac.id, at.nombre titulo_auditoria, CONCAT(u.nombre, " ", u.apellido) usuario_carga, u.correo correo_carga, CONCAT(u_responde.nombre, " ", u_responde.apellido) usuario_responde, descargo.id_hallazgo id_hallazgo, descargo.id id_descargo, descargo.motivo, descargo.estado estado_descargo, DATE_FORMAT(fecha_hora_motivo, "%d/%m/%Y") fecha_motivo, DATE_FORMAT(hallazgo.fecha_cierre, "%d/%m/%Y") fecha_vencimiento')
            ->join('obs_hallazgos hallazgo', 'hallazgo.id=descargo.id_hallazgo', 'inner')
            ->join('auditoria_control ac', 'ac.id=hallazgo.id_auditoria', 'inner')
			->join('auditorias_titulos at', 'at.id=ac.modelo_tipo', 'inner')
            ->join('usuario u', 'u.id=hallazgo.usuario_carga', 'inner')
            ->join('usuario u_responde', 'u_responde.id=descargo.id_usuario', 'inner')
            ->where('descargo.id_hallazgo', $id_hallazgo)
            ->where('descargo.id', $id_descargo);
        $query = $builder->get()->getRowArray();
        return $query;
    }
}
