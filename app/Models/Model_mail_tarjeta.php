<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Table;
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Php;

class Model_mail_tarjeta extends Model
{
    public function getInfoTarjetaCreada($id_obs, $id_obs_accion = '', $otro_responsable = false)
    {
        $builder = $this->db->table('tarjeta_observaciones tar_obs');
        $builder->select('tar_obs.id id_obs, tar_obs.fecha_deteccion, tar_obs.tipo_observacion, tar_obs.observador, p.nombre proyecto, u.nombre nombre_u_carga, u.apellido apellido_u_carga, u.correo correo_carga')
            ->join('proyectos p', 'p.id=tar_obs.proyecto', 'inner')
            ->join('usuario u', 'u.id=tar_obs.usuario_carga', 'inner')
            ->where('tar_obs.id', $id_obs);
        $query = $builder->get()->getResultArray();

        if (!empty($id_obs_accion)) {
            $query['responsable'] = $this->getPlanAccionTarjeta($id_obs_accion);
            if ($otro_responsable)
                $query['relevo_responsable'] = $this->getPlanAccionTarjeta($id_obs_accion, true);
        }

        return $query;
    }

    protected function getPlanAccionTarjeta($id_obs_accion, $otro_responsable = false)
    {
        if ($otro_responsable) {
            $responsable = 'relevo_responsable';
        } else {
            $responsable = 'responsable';
        }

        $builder = $this->db->table('tarjeta_hallazgos tar_hallazgo');
        $builder->select('u_responsable.nombre nombre_u_responsable, u_responsable.apellido apellido_u_responsable, u_responsable.correo correo_responsable')
            ->join("usuario u_responsable", "u_responsable.id=tar_hallazgo." . $responsable . "", "inner")
            ->where('tar_hallazgo.id', $id_obs_accion);
        return $builder->get()->getResultArray()[0];
    }

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

    public function getRespuestaDescargo($id_descargo)
    {
        $builder = $this->db->table('tarjeta_hallazgo_descargos descargo');
        $builder->select('obs.id id_obs, hallazgo.id id_hallazgo, descargo.id_usuario_rta usuario_rta, descargo.estado estado_rta, descargo.respuesta, u.nombre u_nombre_carga, u.apellido u_apellido_carga, u.correo correo_carga, u_responde.nombre u_nombre_responde, u_responde.apellido u_apellido_responde, u_responde.correo correo_responsable, DATE_FORMAT(descargo.fecha_hora_respuesta, "%d/%m/%Y") fecha_rta')
            ->join('tarjeta_hallazgos hallazgo', 'hallazgo.id=descargo.id_hallazgo', 'inner')
            ->join('tarjeta_observaciones obs', 'obs.id=hallazgo.id_tarjeta', 'inner')
            ->join('usuario u', 'u.id=obs.usuario_carga', 'inner')
            ->join('usuario u_responde', 'u_responde.id=descargo.id_usuario', 'inner')
            ->where('descargo.id', $id_descargo);
        return $builder->get()->getRowArray();
    }

    public function getDataTarjetaReconocimiento($id_obs, $id_hallazgo)
    {
        $builder = $this->db->table('tarjeta_observaciones tar_obs');
        $builder->select('tar_obs.id id_obs, tar_obs.fecha_deteccion, tar_obs.tipo_observacion, tar_obs.observador, p.nombre proyecto, th.hallazgo observacion, CONCAT(u.nombre, " ", u.apellido) usuario_carga, u.correo correo_carga, CONCAT(responsable.nombre, " ", responsable.apellido) responsable, responsable.correo responsable_correo')
            ->join('tarjeta_hallazgos th', 'th.id_tarjeta=tar_obs.id', 'inner')
            ->join('proyectos p', 'p.id=tar_obs.proyecto', 'inner')
            ->join('usuario u', 'u.id=tar_obs.usuario_carga', 'inner')
            ->join('usuario responsable', 'responsable.id=th.responsable', 'inner')
            ->where('tar_obs.id', $id_obs)
            ->where('th.id', $id_hallazgo);
        return $builder->get()->getRowArray();
    }
}
