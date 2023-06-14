<?php

namespace App\Models;

class Model_aud_vehicular extends Model_auditorias
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


	/* == Envío de E-Mails == */

	/**
	 * Trae los datos de una Auditoría de CheckList Vehicular (Cuando se genera un plan de acción)
	 */
	public function getDataHallazgoEmail($id_aud, $id_hallazgo, $tipo)
	{
		$builder = $this->db->table('obs_hallazgos oh');
		$builder->select('oh.id id_hallazgo, oh.id_auditoria id, oh.tipo_auditoria, at.nombre titulo_auditoria, CONCAT(u_carga.nombre, " ", u_carga.apellido) usuario_carga, u_carga.correo correo_usuario_carga, CONCAT(u_responsable.nombre, " ", u_responsable.apellido) responsable, u_responsable.correo correo_responsable, CONCAT(relevo.nombre, " ", relevo.apellido) relevo, relevo.correo correo_relevo, CONCAT(conductor.nombre, " ", conductor.apellido) conductor, CONCAT(titular.nombre, " ", titular.apellido) titular, DATE_FORMAT(oh.fecha_hora_carga, "%d/%m/%Y") fecha_deteccion')
			->join('auditoria_vehicular av', 'av.id=oh.id_auditoria', 'inner')
			->join('auditorias_titulos at', 'at.id=av.modelo_tipo', 'inner')
			->join('usuario u_carga', 'u_carga.id=oh.usuario_carga', 'inner')
			->join('usuario u_responsable', 'u_responsable.id=oh.responsable', 'inner')
			->join('usuario relevo', 'relevo.id=oh.relevo_responsable', 'left')
			->join('usuario conductor', 'conductor.id=av.conductor', 'left')
			->join('usuario titular', 'titular.id=av.titular', 'left')
			->where('av.id', $id_aud)
			->where('oh.id', $id_hallazgo)
			->where('oh.tipo_auditoria', $tipo);
		return $builder->get()->getRowArray();
	}
}
