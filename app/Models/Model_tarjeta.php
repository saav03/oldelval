<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Table;


class Model_tarjeta extends Model
{

	public function getMatrizRiesgo()
	{
		//Arreglo ordenado convenientemente
		// $this->load->library('table');
		$table = new \CodeIgniter\View\Table();
		$template = array(
			'table_open' => '<table class="table table-responsive">',
		);
		$table->setTemplate($template);

		$db = db_connect();
		$query = $db->query("SELECT * FROM matriz_riesgo_letras ORDER BY codigo ASC");
		$query = $query->getResultArray();

		$cell1 = array('data' => '', 'class' => "header1", 'colspan' => 8);
		$cell2 = array('data' => 'Probabilidad de Ocurrencia', 'class' => "header1", 'colspan' => 4);
		$celdas = [];
		array_push($celdas, $cell1, $cell2);
		$table->addRow($celdas);
		$celdas = [];

		$cell0 = array('data' => "", 'class' => 'header1', 'colspan' => 2);
		$cell1 = array('data' => "Causas Potenciales", 'class' => 'header1', 'rowspan' => 1, 'colspan' => 6);
		$celdas = [];
		array_push($celdas, $cell0, $cell1);
		foreach ($query as $value) {
			$cell = [];
			$cell = array('data' => $value['codigo'], 'class' => 'header1');
			array_push($celdas, $cell);
		}
		$table->addRow($celdas);
		$celdas = [];

		$cell0 = array('data' => "Nivel", 'class' => 'headerBordeIzquierdo', 'rowspan' => 1, 'colspan' => 2);
		$cell1 = array('data' => "Lesión Enfermedad", 'class' => 'header2', 'rowspan' => 1);
		$cell2 = array('data' => "Vehicular", 'class' => 'header2', 'rowspan' => 1);
		$cell3 = array('data' => "Medio Ambiente", 'class' => 'header2', 'rowspan' => 1);
		$cell4 = array('data' => "Propiedad/Procesos", 'class' => 'header2', 'rowspan' => 1);
		$cell5 = array('data' => "Reputación", 'class' => 'header2', 'rowspan' => 1);
		$cell6 = array('data' => "Seguridad Física Delitos contra personas y bienes", 'class' => 'header2', 'rowspan' => 1);
		$celdas = [];
		array_push($celdas, $cell0, $cell1, $cell2, $cell3, $cell4, $cell5, $cell6);

		foreach ($query as $value) {
			$cell = [];
			if ($value['codigo'] == 4) {
				$cell = array('data' => $value['texto'], 'class' => 'headerBordeDerecho');
			} else {
				$cell = array('data' => $value['texto'], 'class' => 'header3');
			}
			array_push($celdas, $cell);
		}
		$table->addRow($celdas);
		$celdas = [];

		$levels = array(
			4 => array(
				'data' => ['<b>Catastrófico</b>', 'Fatalidad o Incapacidad permanente', 'Incidente vehicular con daños totales > U$S 100K, Fatalidad de CWS o de terceros', 'Reportable externamente, Costo remediación o multas > U$S 50K, 
				Ingreso de quimicos peligrossos en vias acuíferas', 'Costo total de reparación o reemplazo > U$S 250K, Daños a la propiedad de terceros (no vehiculos) causada por CWS > U$S 100K', 'Cobertura de medios de prensa nacionales. 
				Multas o violaciones > U$S 100K', 'Agresión física que resulte en una fatalidad o incapacidad permanante, Secuestro/ extorsión,
				Destrucción/ robo/ fraude de activos de CWS> U$S 100K'],
				'class' => 'striped1'
			),
			3 => array(
				'data' => ['<b>Grave</b>', 'Herida con días caídos', 'Incidente vehicular con daños totales > U$S 15K pero < U$S 100K, Vuelco o MVI con heridas > que primeros auxilios de personal CWS o de terceros', 'Reportable externamente, Costo remediación o multas > U$S 10K pero < U$S 50K, Derrame > 8.000 lts (excepto agua limpia)', 'Costo total de reparación o reemplazo > U$S 50K pero < U$S 250K,  Daños a la propiedad de terceros (no vehiculos) causada por CWS > U$S 25K pero < U$S 100K', 'Cobertura de medios de prensa regionales. 
				Multas o violaciones > U$S 10K pero < U$S 100K', 'Asalto que resulta en LTI, Amenaza confirmada con arma o de daño corporal, Comportamiento amenazante (coerción, acecho)/ infracción criminal, Destrucción/ robo/ fraude de activos de CWS > U$S 25K pero < U$S 100K'],
				'class' => 'striped2'
			),
			2 => array(
				'data' => ['<b>Serio</b>', 'Tratamiento médico,  Tareas restringidas, Transferencia de puesto', 'MVI con daños totales > U$S 5K pero < U$S 15K, Heridas con primeros auxilios, Vehículo o conductor de CWS demorado por autoridades', 'Reportable externamente, Costo remediación o multas > U$S 2.5K pero < U$S 10K, Derrame > 1.600 lts pero < 8.000 lts (excepto agua limpia)', 'Costo total de reparación o reemplazo > U$S 10K pero < U$S 50K, Daños a la propiedad de terceros (no vehiculos) causada por CWS < U$S 100K', 'Cobertura medios de prensa locales. Quejas por escrito del cliente,
				Multas o violaciones < U$S 10K', 'Asalto que resulta en tratamiento médico, Amenaza implícita con arma o daño corporal, Destrucción/ robo/ fraude de los activos de CWS > U$S 5K pero < U$S 25K, Otros delitos menores'],
				'class' => 'striped1'
			),
			1 => array(
				'data' => ['<b>Menor</b>', 'Primeros Auxilios', 'MVI con daños totales < U$S 5K, Multa a vehículo de CWS', 'No reportable, Costo remediación  < U$S 2.5K, Derrame < 1.600 lts', 'Costo total de reparación, reemplazo < U$S 10K', 'Nota: Las multas o violaciones establecidas más arriba excluyen las multas ambientales', 'Asalto que resulta en primeros auxilios, comportamiento molesto, Destruccion/ robo/ fraude de bienes de CWS < U$S 5K'],
				'class' => 'striped2'
			)
		);

		foreach ($levels as $levnum => $data) {
			$cell1 = array('data' => "<b>$levnum</b>", 'class' => $data['class']);
			array_push($celdas, $cell1);
			foreach ($data['data'] as $data_name) {
				$temp_cell = array('data' => $data_name, 'class' => $data['class']);
				array_push($celdas, $temp_cell);
			}
			foreach ($query as $value) {
				$cell = [];
				$bajo_desde = $value['bajo_desde'];
				$medio_desde = $value['medio_desde'];
				$alto_desde = $value['alto_desde'];
				$extremo_desde = $value['extremo_desde'];
				$temp_class = "color_blank";
				if ($bajo_desde != -1 && $levnum >= $bajo_desde) {
					$temp_class = "celdas_colores color_apagado_bajo";
					$numero = "1";
				}
				if ($medio_desde != -1 && $levnum >= $medio_desde) {
					$numero = "2";
					$temp_class = "celdas_colores color_apagado_medio";
				}
				if ($alto_desde != -1 && $levnum >= $alto_desde) {
					$numero = "3";
					$temp_class = "celdas_colores color_apagado_alto";
				}
				if ($extremo_desde != -1 && $levnum >= $extremo_desde) {
					$numero = "4";
					$temp_class = "celdas_colores color_apagado_extremo";
				}
				$cell = array('data' => "<input name='riesgo_tab' class='inputCeldas' data-num=$levnum value='" . $value['codigo'] . "' style='color:black;visibility: hidden;' type=radio><label style='width: -webkit-fill-available;'><b>" . $numero . "</b></label><div class='checkAnimado'></div>", 'class' => $temp_class);
				array_push($celdas, $cell);
			}
			$table->addRow($celdas);
			$celdas = [];
		}
		return $table->generate();
	}

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

	public function getAllPaged($offset, $tamanioPagina, $soloMax = FALSE)
	{
		$builder = $this->db->table('tarjeta_observaciones tar_obs');
		if ($soloMax) {
			$builder->select("COUNT(*) cantidad");
			$builder->join('tarjeta_hallazgos', 'tarjeta_hallazgos.id_tarjeta=tar_obs.id', 'left')
					->join('usuario user_responsable', 'user_responsable.id=tarjeta_hallazgos.responsable', 'left');
			if (!vista_access('add_obs')) {
				$builder->where("user_responsable.id", session()->get('id_usuario'));
			}
		} else {
			$builder->select("tar_obs.id id_tarjeta, proyectos.nombre proyecto,modulos.nombre modulo, estaciones_bombeo.nombre estacion, sistemas_oleoductos.nombre sistema, tar_obs.fecha_deteccion, tar_obs.tipo_obs observacion, tar_obs.situacion, tar_obs.estado tar_estado")
				->join('tarjeta_hallazgos', 'tarjeta_hallazgos.id_tarjeta=tar_obs.id', 'left')
				->join('usuario user_responsable', 'user_responsable.id=tarjeta_hallazgos.responsable', 'left')
				->join('proyectos', 'proyectos.id=tar_obs.proyecto', 'inner')
				->join('modulos', 'modulos.id=tar_obs.modulo', 'left')
				->join('estaciones_bombeo', 'estaciones_bombeo.id=tar_obs.estacion_bombeo', 'left')
				->join('sistemas_oleoductos', 'sistemas_oleoductos.id=tar_obs.sistema_oleoducto', 'left')
				->where('tar_obs.estado', 1)
				->orderBy('tar_obs.id', 'DESC')
				->limit($tamanioPagina, $offset);

			/* == Si no tiene el permiso para agregar una observación entonces para el histórico filtra por el responsable == */
			if (!vista_access('add_obs')) {
				$builder->where("user_responsable.id", session()->get('id_usuario'));
			}
		}
		$query = $builder->get();
		return $query->getResultArray();
	}

	/**
	 * Trae los datos de la tarjeta de observación según el ID del parámetro
	 */
	public function getDataTarjeta($id_obs)
	{
		$builder = $this->db->table('tarjeta_observaciones tar_obs');
		$builder->select('tar_obs.id id_tarjeta, tar_obs.observador, proyectos.nombre proyecto,modulos.nombre modulo, estaciones_bombeo.nombre estacion, sistemas_oleoductos.nombre sistema, DATE_FORMAT(tar_obs.fecha_deteccion, "%d/%m/%Y") fecha_deteccion, tar_obs.tipo_obs observacion, tar_obs.descripcion tar_descripcion, tar_obs.situacion, tar_obs.estado tar_estado, tar_obs.usuario_carga')
			->join('proyectos', 'proyectos.id=tar_obs.proyecto', 'inner')
			->join('modulos', 'modulos.id=tar_obs.modulo', 'left')
			->join('estaciones_bombeo', 'estaciones_bombeo.id=tar_obs.estacion_bombeo', 'left')
			->join('sistemas_oleoductos', 'sistemas_oleoductos.id=tar_obs.sistema_oleoducto', 'left')
			->where('tar_obs.id', $id_obs)
			->where('tar_obs.estado', 1);
		$query = $builder->get();
		$tarjeta = $query->getRowArray();

		/* == Cargo el hallazgo == */
		$query_hallazgo = $this->getDataHallazgoTarjeta($id_obs);
		$id_hallazgo = $query_hallazgo['id'];
		$tarjeta['hallazgo'] = $query_hallazgo;

		/* == Cierre de la tarjeta (Si es que está cerrada) == */
		$query_cierre_obs = $this->getCierreMotivoTarjeta($id_obs);
		$tarjeta['cierre'] = $query_cierre_obs;

		/* == Cargo los adjuntos del hallazgo == */
		$query_hallazgo_adj = $this->getAdjHallazgoTarjeta($id_hallazgo);
		$tarjeta['hallazgo']['adjuntos'] = $query_hallazgo_adj;

		/* == Cargo los descargos del hallazgo == */
		$query_hallazgo_descargo = $this->getDescargoHallazgoTarjeta($id_hallazgo);

		foreach ($query_hallazgo_descargo as $key => $d) {
			/* == Cargo los adjuntos de todos los hallazgos == */
			$adjuntos_descargo = $this->getAdjDescargosHallazgo($d['id']);
			$query_hallazgo_descargo[$key]['descargos_adj'] = $adjuntos_descargo;
		}

		$tarjeta['hallazgo']['descargos'] = $query_hallazgo_descargo;

		return $tarjeta;
	}

	/**
	 * Trae el hallazgo correspondiente a la tarjeta que se solicite por parámetro
	 */
	protected function getDataHallazgoTarjeta($id_obs)
	{
		$builder = $this->db->table('tarjeta_hallazgos tar_hallazgo');
		$builder->select('tar_hallazgo.id, tar_hallazgo.hallazgo hallazgo, tar_hallazgo.accion_recomendacion, tar_clasf.nombre clasificacion, tar_tipo_hallazgo.nombre tipo, tar_hallazgo.riesgo, tar_hallazgo.riesgo_fila, tar_hallazgo.responsable, empresas.nombre contratista, tar_hallazgo.fecha_cierre, u.nombre responsable_nombre, u.apellido responsable_apellido')
			->join('tarjeta_clasificaciones tar_clasf', 'tar_clasf.id=tar_hallazgo.clasificacion', 'inner')
			->join('tarjeta_tipo_hallazgo tar_tipo_hallazgo', 'tar_tipo_hallazgo.id=tar_hallazgo.tipo', 'left')
			->join('empresas', 'empresas.id=tar_hallazgo.contratista', 'inner')
			->join('usuario u', 'u.id=tar_hallazgo.responsable', 'left')
			->where('tar_hallazgo.id_tarjeta', $id_obs);
		return $builder->get()->getRowArray();
	}

	/**
	 * Trae el motivo de cierre de la observación solicita por parámetro
	 */
	protected function getCierreMotivoTarjeta($id_obs)
	{
		$builder = $this->db->table('tarjeta_obs_cierre tar_motivo_cierre');
		$builder->select('tar_motivo_cierre.*, u.nombre responsable_nombre, u.apellido responsable_apellido')
			->join('usuario u', 'u.id=tar_motivo_cierre.id_usuario_cierre', 'inner')
			->where('tar_motivo_cierre.id_tarjeta_obs', $id_obs);
		return $builder->get()->getRowArray();
	}

	/**
	 * Trae todos aquellos adjuntos que pertenezcan al id del hallazgo solicitado por parámetro
	 */
	protected function getAdjHallazgoTarjeta($id_hallazgo)
	{
		$builder = $this->db->table('tarjeta_hallazgos_adjuntos tar_hallazgo_adj');
		$builder->select('*')
			->where('tar_hallazgo_adj.id_hallazgo', $id_hallazgo);
		return $builder->get()->getResultArray();
	}

	/**
	 * Trae todos aquellos adjuntos que pertenezcan al id del hallazgo solicitado por parámetro
	 */
	protected function getDescargoHallazgoTarjeta($id_hallazgo)
	{
		$builder = $this->db->table('tarjeta_hallazgo_descargos tar_hallazgo_descargo');
		$builder->select('tar_hallazgo_descargo.id, tar_hallazgo_descargo.estado, tar_hallazgo_descargo.motivo, DATE_FORMAT(tar_hallazgo_descargo.fecha_hora_motivo, "%Y/%m/%d %H:%i:%s") fecha_hora_motivo, tar_hallazgo_descargo.respuesta, DATE_FORMAT(tar_hallazgo_descargo.fecha_hora_respuesta, "%d/%m/%Y %H:%i:%s") fecha_hora_respuesta, u.nombre, u.apellido, u_rta.nombre nombre_user_rta, u_rta.apellido apellido_user_rta')
			->join('usuario u', 'u.id=tar_hallazgo_descargo.id_usuario', 'inner')
			->join('usuario u_rta', 'u_rta.id=tar_hallazgo_descargo.id_usuario_rta', 'left')
			->where('tar_hallazgo_descargo.id_hallazgo', $id_hallazgo);
		return $builder->get()->getResultArray();
	}

	/**
	 * Trae todos aquellos adjuntos que pertenezcan al descargo solicitado por parámetro
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
		$builder->select('tarjeta_indicadores.*')
			->join('tarjeta_rel_indicadores', 'tarjeta_rel_indicadores.id_indicador=tarjeta_indicadores.id', 'inner')
			->where('tarjeta_rel_indicadores.id_tarjeta', $id_obs);
		return $builder->get()->getResultArray();
	}

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
}
