<?php

namespace App\Models;

class Model_aud_control extends Model_auditorias
{

    public function getDescargosHallazgo($id_hallazgo)
    {
        $builder = $this->db->table('obs_hallazgos_descargos ohd');
        $builder->select('ohd.id id_descargo, ohd.id_hallazgo, ohd.estado estado_descargo, ohd.motivo, DATE_FORMAT(ohd.fecha_hora_motivo, "%d/%m/%Y") fecha_hora_motivo, ohd.respuesta, DATE_FORMAT(ohd.fecha_hora_respuesta, "%d/%m/%Y") fecha_hora_respuesta, CONCAT(u_descargo.nombre," ", u_descargo.apellido) usuario_descargo, CONCAT(u_rta.nombre," ", u_rta.apellido) usuario_respuesta')
            ->join('usuario u_descargo', 'u_descargo.id=ohd.id_usuario', 'inner')
            ->join('usuario u_rta', 'u_rta.id=ohd.id_usuario_rta', 'left')
            ->where('ohd.id_hallazgo', $id_hallazgo);
        return $builder->get()->getResultArray();
    }

    public function cerrarDescargo($datos, $id_descargo)
	{
		$this->db->transStart();
		$builder = $this->db->table('obs_hallazgos_descargos');
		$builder->where('obs_hallazgos_descargos.id', $id_descargo);
		$builder->update($datos);
		$this->db->transComplete();
		if ($this->db->transStatus() === FALSE) {
			$this->response->setStatusCode(400);
			$results = ['status' => false, 'message' => 'Fallo en la transaccion'];
		} else {
			$last_id = $builder->select('id')->orderBy('id', 'DESC')->get()->getRowArray();
			$results = ['status' => true, 'message' => 'OK', 'last_id' => $last_id];
		}
		return $results;
	}

    public function addMotivoCierre($datos)
	{
		$this->db->transStart();
		$builder = $this->db->table('obs_hallazgo_cierre');
		$builder->insert($datos);
		$this->db->transComplete();
		if ($this->db->transStatus() === FALSE) {
			$this->response->setStatusCode(400);
			$results = ['status' => false, 'message' => 'Fallo en la transaccion'];
		} else {
			$last_id = $builder->select('id')->orderBy('id', 'DESC')->get()->getRowArray();
			$results = ['status' => true, 'message' => 'OK', 'last_id' => $last_id];
		}
		return $results;
	}
}
