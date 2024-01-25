<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Table;


class Model_tarjeta extends Model
{
	/**
	 * Inserta la tarjeta M.A.S en la tabla 'tarjeta_observaciones'
	 */
	public function addSubmit($datos)
	{
		$this->db->transStart();
		$builder = $this->db->table('tarjeta_observaciones');
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

	/**
	 * Inserta los observadores que pertenecen a la Tarjeta M.A.S
	 */
	public function addObservadorTarjeta($datos)
	{
		$this->db->transStart();
		$builder = $this->db->table('tarjeta_rel_observador');
		$builder->insert($datos);
		$this->db->transComplete();
	}

	/**
	 * Inserta el submit de los hallazgos encontrados tanto positivos como posibilidad de mejora en la tabla 'tarjeta_hallazgos'
	 */
	public function addSubmitHallazgo($datos)
	{
		$this->db->transStart();
		$builder = $this->db->table('tarjeta_hallazgos');
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

	/**
	 * Típico histórico del DynamicTable
	 */
	public function getAllPaged($offset, $tamanioPagina, $soloMax = FALSE)
	{
		$builder = $this->db->table('tarjeta_observaciones tar_obs');
		if ($soloMax) {
			$builder->select("COUNT(DISTINCT tar_obs.id) cantidad");
			$builder->join('tarjeta_hallazgos', 'tarjeta_hallazgos.id_tarjeta=tar_obs.id', 'left')
				->join('usuario user_responsable', 'user_responsable.id=tarjeta_hallazgos.responsable', 'left');
			if (!vista_access('nueva_observacion')) {
				$builder->where("user_responsable.id", session()->get('id_usuario'));
			}
		} else {
			$builder->select("tar_obs.id id_tarjeta, proyectos.nombre proyecto,modulos.nombre modulo, estaciones_bombeo.nombre estacion, sistemas_oleoductos.nombre sistema, COUNT(tarjeta_hallazgos.resuelto) resueltos, COUNT(tarjeta_hallazgos.id) hallazgos, DATE_FORMAT(tar_obs.fecha_deteccion, '%d/%m/%Y') fecha_deteccion, tar_obs.observador, tar_obs.tipo_observacion observacion, tar_obs.situacion, tar_obs.estado tar_estado")
				->join('tarjeta_hallazgos', 'tarjeta_hallazgos.id_tarjeta=tar_obs.id', 'left')
				->join('usuario user_responsable', 'user_responsable.id=tarjeta_hallazgos.responsable', 'left')
				->join('proyectos', 'proyectos.id=tar_obs.proyecto', 'inner')
				->join('modulos', 'modulos.id=tar_obs.modulo', 'left')
				->join('estaciones_bombeo', 'estaciones_bombeo.id=tar_obs.estacion_bombeo', 'left')
				->join('sistemas_oleoductos', 'sistemas_oleoductos.id=tar_obs.sistema_oleoducto', 'left')
				->where('tar_obs.estado', 1)
				->groupBy('tar_obs.id')
				->orderBy('tar_obs.id', 'DESC')
				->limit($tamanioPagina, $offset);

			/* == Si no tiene el permiso para agregar una observación entonces para el histórico filtra por el responsable == */
			if (!vista_access('nueva_observacion')) {
				$builder->where("user_responsable.id", session()->get('id_usuario'));
			}
		}
		// echo '<pre>';
		// var_dump($builder->getCompiledSelect());
		// echo '</pre>';
		// exit;
		$query = $builder->get();
		return $query->getResultArray();
	}

	/**
	 * Trae los datos de la tarjeta de observación según el ID del parámetro
	 */
	public function getDataTarjeta($id_obs)
	{
		$builder = $this->db->table('tarjeta_observaciones tar_obs');
		$builder->select('tar_obs.id id_tarjeta, tar_obs.tipo_observacion, tar_obs.observador, e.nombre contratista, proyectos.nombre proyecto,modulos.nombre modulo, estaciones_bombeo.nombre estacion, sistemas_oleoductos.nombre sistema, DATE_FORMAT(tar_obs.fecha_deteccion, "%d/%m/%Y") fecha_deteccion, tar_obs.tipo_observacion observacion, tar_obs.descripcion tar_descripcion, tar_obs.situacion, tar_obs.estado tar_estado, tar_obs.usuario_carga')
			->join('proyectos', 'proyectos.id=tar_obs.proyecto', 'inner')
			->join('modulos', 'modulos.id=tar_obs.modulo', 'left')
			->join('estaciones_bombeo', 'estaciones_bombeo.id=tar_obs.estacion_bombeo', 'left')
			->join('sistemas_oleoductos', 'sistemas_oleoductos.id=tar_obs.sistema_oleoducto', 'left')
			->join('empresas e', 'e.id=tar_obs.contratista', 'join')
			->where('tar_obs.id', $id_obs)
			->where('tar_obs.estado', 1);
		$query = $builder->get();
		$tarjeta = $query->getRowArray();

		// * Obtiene los hallazgos pertenecientes a la tarjeta
		$hallazgos = $this->getAllHallazgosTarjeta($tarjeta['id_tarjeta']);

		// * Obtiene los adjuntos que pertenecen a los hallazgos (Itera por cada hallazgo)
		// * Obtiene los efectos que pertenecen a los hallazgos (=)
		foreach ($hallazgos as $key => $h) {
			$adjuntos = $this->getAdjHallazgoTarjeta($h['id_hallazgo']);
			$efectos = $this->getEfectosRelHallazgo($h['id_hallazgo']);
			$descargos = $this->getDescargoHallazgoTarjeta($h['id_hallazgo']);
			$hallazgos[$key]['adjuntos'] = $adjuntos;
			$hallazgos[$key]['efectos'] = $efectos;

			foreach ($descargos as $key_d => $d) {
				$adjuntos_descargo = $this->getAdjDescargosHallazgo($d['id']);
				$descargos[$key_d]['descargos_adj'] = $adjuntos_descargo;
			}

			$hallazgos[$key]['descargos'] = $descargos;
		}

		$tarjeta['hallazgos'] = $hallazgos;

		$tarjeta['cierre'] = $this->getCierreMotivoTarjeta($tarjeta['id_tarjeta']);
		$tarjeta['hallazgos_pendientes'] = $this->getObsResult($tarjeta['id_tarjeta']);

		/* == Cargo los observadores a la tarjeta == */
		$tarjeta['observadores'] = $this->getObservadoresTarjeta($id_obs);

		return $tarjeta;
	}
	/**
	 * Trae todos los hallazgos pertenecientes al ID pasado por parámetro de la Tarjeta M.A.S
	 * (Se utiliza en el método $this->getDataTarjeta())
	 */
	public function getAllHallazgosTarjeta($id_tarjeta)
	{
		$builder = $this->db->table('tarjeta_hallazgos h');
		$builder->select('h.id id_hallazgo, h.id_tarjeta, h.hallazgo, h.plan_accion, c.id tipo_aspecto, c.nombre aspecto, h.significancia, h.resuelto, t_p.id id_tipo, t_p.nombre tipo_obs, u_r.id id_responsable, CONCAT(u_r.nombre," ", u_r.apellido) usuario_responsable, u_r_r.id id_relevo_responsable, CONCAT(u_r_r.nombre," ", u_r_r.apellido) relevo_responsable, h.fecha_cierre, DATE_FORMAT(h.fecha_cierre, "%d/%m/%Y") fecha_cierre_f, u_c.id id_usuario_carga, CONCAT(u_c.nombre," ", u_c.apellido) usuario_carga')
			->join('consecuencias c', 'c.id=h.aspecto', 'join')
			->join('tipo_hallazgo t_p', 't_p.id=h.tipo', 'join')
			->join('usuario u_r', 'u_r.id=h.responsable', 'join')
			->join('usuario u_r_r', 'u_r_r.id=h.relevo_responsable', 'left')
			->join('usuario u_c', 'u_c.id=h.usuario_carga', 'join')
			->where('h.estado', 1)
			->where('h.id_tarjeta', $id_tarjeta);
		$query = $builder->get();
		return $query->getResultArray();
	}
	/**
	 * Trae los efectos de la tabla de relación 'tarjeta_rel_efecto' de cada hallazgo correspondiente
	 * (Se utiliza en el método $this->getDataTarjeta())
	 */
	public function getEfectosRelHallazgo($id_hallazgo)
	{
		$builder = $this->db->table('tarjeta_rel_efecto tre');
		$builder->select('tre.id id_rel_efecto, tre.efecto_id, e.nombre nombre_efecto')
			->join('efectos_impactos e', 'e.id=tre.efecto_id', 'inner')
			->where('hallazgo_id', $id_hallazgo);
		return $builder->get()->getResultArray();
	}

	/**
	 * Trae el motivo de cierre de la observación solicita por parámetro
	 * (Se utiliza en el método $this->getDataTarjeta())
	 */
	protected function getCierreMotivoTarjeta($id_obs)
	{
		$builder = $this->db->table('tarjeta_obs_cierre tar_motivo_cierre');
		$builder->select('tar_motivo_cierre.id, tar_motivo_cierre.motivo, DATE_FORMAT(tar_motivo_cierre.fecha_hora_cierre, "%d/%m/%Y %H:%i:%s") fecha_cierre_format, CONCAT(u.nombre," ", u.apellido) responsable_cierre')
			->join('usuario u', 'u.id=tar_motivo_cierre.id_usuario_cierre', 'inner')
			->where('tar_motivo_cierre.id_tarjeta_obs', $id_obs);
		return $builder->get()->getRowArray();
	}

	/**
	 * Trae todos aquellos adjuntos que pertenezcan al id del hallazgo solicitado por parámetro
	 * (Se utiliza en el método $this->getDataTarjeta())
	 */
	protected function getAdjHallazgoTarjeta($id_hallazgo)
	{
		$builder = $this->db->table('tarjeta_hallazgos_adjuntos tar_hallazgo_adj');
		$builder->select('*')
			->where('tar_hallazgo_adj.id_hallazgo', $id_hallazgo);
		return $builder->get()->getResultArray();
	}

	/**
	 * Trae aquellos observadores que pertenecen al id de la tarjeta solicitada por parámetro
	 * (Se utiliza en el método $this->getDataTarjeta())
	 */
	protected function getObservadoresTarjeta($id_tarjeta)
	{
		$builder = $this->db->table('tarjeta_rel_observador tro');
		$builder->select('*')
			->where('tro.id_tarjeta', $id_tarjeta);
		return $builder->get()->getResultArray();
	}

	/**
	 * Trae todos aquellos adjuntos que pertenezcan al id del hallazgo solicitado por parámetro
	 * (Se utiliza en el método $this->getDataTarjeta())
	 */
	public function getDescargoHallazgoTarjeta($id_hallazgo)
	{
		$builder = $this->db->table('tarjeta_hallazgo_descargos tar_hallazgo_descargo');
		$builder->select('tar_hallazgo_descargo.id, tar_hallazgo_descargo.estado, tar_hallazgo_descargo.motivo, DATE_FORMAT(tar_hallazgo_descargo.fecha_hora_motivo, "%d/%m/%Y %H:%i:%s") fecha_hora_motivo, tar_hallazgo_descargo.respuesta, DATE_FORMAT(tar_hallazgo_descargo.fecha_hora_respuesta, "%d/%m/%Y %H:%i:%s") fecha_hora_respuesta, u.nombre, u.apellido, u_rta.nombre nombre_user_rta, u_rta.apellido apellido_user_rta')
			->join('usuario u', 'u.id=tar_hallazgo_descargo.id_usuario', 'inner')
			->join('usuario u_rta', 'u_rta.id=tar_hallazgo_descargo.id_usuario_rta', 'left')
			->where('tar_hallazgo_descargo.id_hallazgo', $id_hallazgo);
		return $builder->get()->getResultArray();
	}

	/**
	 * Trae todos aquellos adjuntos que pertenezcan al descargo solicitado por parámetro
	 * (Se utiliza en el método $this->getDataTarjeta())
	 */
	protected function getAdjDescargosHallazgo($id_descargo)
	{
		$builder = $this->db->table('tarjeta_descargos_adj tar_adj_descargo');
		$builder->select('*')
			->where('tar_adj_descargo.id_descargo', $id_descargo);
		return $builder->get()->getResultArray();
	}

	/**
	 * Trae todos los indicadores perteneciente a la tarjeta pasada por parámetro
	 */
	public function getDataIndicadoresTarjeta($id_obs)
	{
		$builder = $this->db->table('tarjeta_indicadores');
		$builder->select('tarjeta_rel_indicadores.*, tarjeta_indicadores.nombre, tarjeta_indicadores.descripcion')
			->join('tarjeta_rel_indicadores', 'tarjeta_rel_indicadores.id_indicador=tarjeta_indicadores.id', 'inner')
			->where('tarjeta_rel_indicadores.id_tarjeta', $id_obs);
		return $builder->get()->getResultArray();
	}

	/**
	 * Submit del descargo de cada hallazgo
	 */
	public function addDescargo($datos)
	{
		$this->db->transStart();
		$builder = $this->db->table('tarjeta_hallazgo_descargos');
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
	/**
	 * Se utiliza en el controlador 'TarjetaObservaciones.php' para cambiar el estado del descargo
	 */
	public function editDescargo($datos, $id_descargo)
	{
		$this->db->transStart();
		$builder = $this->db->table('tarjeta_hallazgo_descargos');
		$builder->where('tarjeta_hallazgo_descargos.id', $id_descargo);
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

	/**
	 * Inserta el motivo del cierre de la Tarjeta M.A.S en la tabla 'tarjeta_obs_cierre'
	 */
	public function addMotivoCierre($datos)
	{
		$this->db->transStart();
		$builder = $this->db->table('tarjeta_obs_cierre');
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

	/**
	 * De la tabla 'tarjeta_hallazgos' busca aquellos hallazgos de los cuales aún no están resueltos
	 * Se utiliza más que nada para saber si la Tarjeta M.A.S es posible cerrarla si todos los hallazgos están completos
	 */
	public function getObsResult($id_tarjeta)
	{
		$builder = $this->db->table('tarjeta_hallazgos');
		$builder->select('COUNT(*) cantidad_sin_resolver')
			->where('id_tarjeta', $id_tarjeta)
			->where('resuelto IS NULL');
		$query = $builder->get();
		return $query->getRowArray();
	}

	/**
	 * ! NO ESTA EN FUNCIONAMIENTO (09/12/2023)
	 */
	public function closeDescargoForced($datos, $id_descargo)
	{
		$this->db->transStart();
		$builder = $this->db->table('tarjeta_hallazgo_descargos');
		$builder->where('tarjeta_hallazgo_descargos.id', $id_descargo);
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
}
