<?php

namespace App\Models;

use CodeIgniter\Model;

class Model_estadisticas extends Model
{
	/**
	 * Trae los datos de la estadística ya cargada
	 * Dependiendo que $id se pase por parámetro trae el tipo de estadística
	 */
	public function getEstadistica($id_estadistica, $id_tipo)
	{
		// $id_tipo = 1;
		$builder = $this->db->table('estadisticas_planilla est');
		$builder->select('est.id id_estadistica, est.tipo tipo, est.periodo, est.anio, est.atrasado, u_carga.nombre u_carga_nombre, u_carga.apellido u_carga_apellido, est.contratista, DATE_FORMAT(est.fecha_hora_carga, "%Y-%m-%d") f_fecha_hora_carga, est.proyecto, est.modulo, est.estacion estacion, est.sistema sistema')
			->join('usuario u_carga', 'u_carga.id=est.usuario_carga', 'inner')
			->where('tipo', $id_tipo)
			->where('est.id', $id_estadistica);
		$query = $builder->get()->getResultArray();
		$id_estadistica = $query[0]['id_estadistica'];

		$indicadores = $this->getIndicadoresPlanilla($id_tipo, $id_estadistica);
		$indices = $this->getIndicadoresPlanilla($id_tipo, $id_estadistica, true);

		$titulos_estadistica = $this->getDataTitulo($id_tipo);
		$query[0]['titulos'] = $titulos_estadistica;
		$query['indices'] = $indices;

		$titulos = $query[0]['titulos'];

		$query[0]['indicadores'] = [];

		if ($id_tipo == 2) {
			$query['adjuntos'] = $this->getUploadsFromEstadistica($id_estadistica);
		}

		foreach ($titulos as $key => $t) {
			$subtitulos = $this->getDataSubtitulo($id_tipo, $t['id']);

			$indicadores_titulos = $this->getDataIndicadoresTituloPlanilla($id_tipo, $t['id'], $id_estadistica);

			foreach ($indicadores_titulos as $ind_title) {
				$query[0]['titulos'][$key]['indicadores'][] = $ind_title;
			}

			foreach ($subtitulos as $key_subt => $subt) {

				foreach ($indicadores as $ind) {
					if ($ind['id_subtitulo'] == $subt['id']) {
						$subt['indicadores'][] = $ind;
					}

					if ($ind['id_subtitulo'] == NULL && $ind['id_titulo'] == NULL) {
						if (!in_array($ind, $query[0]['indicadores'])) {
							$query[0]['indicadores'][] = $ind;
						}
					}
				}

				if ($subt['id_titulo'] == $t['id']) {
					$query[0]['titulos'][$key]['subtitulos'][] = $subt;
				}
			}
		}

		return $query;
	}

	/**
	 * Retorna el arreglo con los indicadores de la estadística ya cargada
	 */
	protected function getIndicadoresPlanilla($id_tipo, $id_estadistica, $es_kpi = false)
	{
		$builder = $this->db->table('estadisticas_rel_planilla_indicadores rel_ind');
		$builder->select('rel_ind.id rel_id_indicador, estadisticas_indicadores.id id_indicador, estadisticas_indicadores.nombre nombre, rel_ind.id_estadistica estadistica, rel_ind.valor, rel_ind.nota, rel_ind.id_tipo, estadisticas_subtitulo.id id_subtitulo, estadisticas_titulo.id id_titulo')
			->where('rel_ind.id_tipo', $id_tipo)
			->join(' estadisticas_subtitulo', 'estadisticas_subtitulo.id=rel_ind.id_subtitulo', 'left')
			->join(' estadisticas_titulo', 'estadisticas_titulo.id=rel_ind.id_titulo', 'left')
			->join('estadisticas_indicadores', 'estadisticas_indicadores.id=rel_ind.id_indicador', 'inner');
		if ($es_kpi) {
			$builder->where('rel_ind.es_kpi', 1);
		} else {
			$builder->where('rel_ind.es_kpi', 0);
		}
		$builder->where('rel_ind.id_estadistica', $id_estadistica);
		$query = $builder->get()->getResultArray();
		return $query;
	}

	/**
	 * Trae los datos de la estadística de Incidentes para poder agregar una estadística desde la vista
	 */
	public function getDataPlanillaIncidente($id_tipo)
	{

		// $id_tipo = 1; // Tiene que venir desde parámetros

		$builder = $this->db->table('estadisticas_tipo t');
		$builder->select("t.id, t.nombre")
			->where("t.id", $id_tipo);
		$query = $builder->get()->getResultArray();

		$titulos_estadistica = $this->getDataTitulo($id_tipo);
		$indicadores = $this->getDataIndicadores($id_tipo);
		$indices = $this->getDataIndicadores($id_tipo, true);

		$query[0]['titulos'] = $titulos_estadistica;
		$query[0]['indicadores'] = [];
		$query['indices'] = $indices;

		$titulos = $query[0]['titulos'];
		foreach ($titulos as $key => $t) {

			$subtitulos = $this->getDataSubtitulo($id_tipo, $t['id']);
			$indicadores_titulos = $this->getDataIndicadoresTitulo($id_tipo, $t['id']);

			foreach ($indicadores_titulos as $ind_title) {
				$query[0]['titulos'][$key]['indicadores'][] = $ind_title;
			}

			foreach ($subtitulos as $key_subt => $subt) {

				foreach ($indicadores as $ind) {
					if ($ind['id_subtitulo'] == $subt['id']) {
						$subt['indicadores'][] = $ind;
					}

					if ($ind['id_subtitulo'] == NULL && $ind['id_titulo'] == NULL) {
						if (!in_array($ind, $query[0]['indicadores'])) {
							$query[0]['indicadores'][] = $ind;
						}
					}
				}

				if ($subt['id_titulo'] == $t['id']) {
					$query[0]['titulos'][$key]['subtitulos'][] = $subt;
				}
			}
		}
		
		return $query;
	}
	public function getDataTitulo($id_tipo)
	{
		$builder = $this->db->table('estadisticas_titulo titulo');
		$builder->select("*")
			->where("titulo.id_tipo_estadistica", $id_tipo);
		return $builder->get()->getResultArray();
	}
	public function getDataSubtitulo($id_tipo, $id_titulo = '')
	{
		$builder = $this->db->table('estadisticas_subtitulo subtitulo');
		$builder->select("subtitulo.id, subtitulo.nombre, subtitulo.id_titulo");
		if ($id_titulo != '') {
			$builder->where("subtitulo.id_titulo", $id_titulo);
		}
		$builder->where("subtitulo.id_tipo", $id_tipo)
			->where("subtitulo.estado", 1);
		return $builder->get()->getResultArray();
	}
	public function getDataIndicadores($id_tipo, $es_kpi = false)
	{
		$builder = $this->db->table('estadisticas_indicadores indicador');
		$builder->select("id, nombre, id_tipo, id_subtitulo, orden, id_titulo")
			->where("indicador.id_tipo", $id_tipo);
		if ($es_kpi) {
			$builder->where('indicador.es_kpi', 1);
		} else {
			$builder->where('indicador.es_kpi', 0);
		}
		$builder->where("indicador.estado", 1);
		$builder->orderBy("indicador.orden", "ASC");
		
		return $builder->get()->getResultArray();
	}
	public function getDataIndicadoresTitulo($id_tipo, $id_titulo)
	{
		$builder = $this->db->table('estadisticas_indicadores indicador');
		$builder->select("id, nombre, id_tipo, id_subtitulo, id_titulo")
			->where("indicador.id_tipo", $id_tipo)
			->where("indicador.id_titulo", $id_titulo)
			->where("indicador.id_subtitulo", NULL)
			->where("indicador.estado", 1)
			->orderBy("indicador.orden", "ASC");
		return $builder->get()->getResultArray();
	}

	protected function getUploadsFromEstadistica($id_estadistica)
	{
		$builder = $this->db->table('adj_estadisticas adj');
		$builder->select("*")
			->where("adj.id_estadistica", $id_estadistica)
			->where("adj.estado", 1);
		return $builder->get()->getResultArray();
	}

	/**
	 * Similar a getDataIndicadoresTitulo() Solamente que trae ahora desde la tabla de relación entre estadísticas e indicadores
	 */
	public function getDataIndicadoresTituloPlanilla($id_tipo, $id_titulo, $id_estadistica)
	{
		$builder = $this->db->table('estadisticas_rel_planilla_indicadores indicador');
		$builder->select("estadisticas_indicadores.id id_indicador, estadisticas_indicadores.nombre, indicador.id_estadistica estadistica, indicador.valor, indicador.nota, indicador.id_subtitulo, indicador.id_titulo")
			->join("estadisticas_indicadores", 'estadisticas_indicadores.id=indicador.id_indicador', 'inner')
			->where("indicador.id_tipo", $id_tipo)
			->where("indicador.id_estadistica", $id_estadistica)
			->where("indicador.id_titulo", $id_titulo)
			->where("indicador.id_subtitulo", NULL)
			->where("indicador.es_kpi", 0)
			->where("indicador.estado", 1);
		return $builder->get()->getResultArray();
	}

	public function addPlanillaFormulario($datos)
	{
		$this->db->transStart();
		$builder = $this->db->table('estadisticas_planilla');
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

		$this->db->transStart();
		$builder = $this->db->table('estadisticas_planilla');
		$builder->insert($datos);
		$this->db->transComplete();
	}

	public function addIndicadoresPlanilla($datos)
	{
		$this->db->transStart();
		$builder = $this->db->table('estadisticas_indicadores');
		$builder->insert($datos);
		$this->db->transComplete();
	}

	public function getTipos()
	{
		$builder = $this->db->table('estadisticas_tipo t');
		$builder->select("t.id, t.nombre, p.id id_pregunta, p.indicador");
		$builder->join("estadisticas_indicadores as p", "p.id_tipo=t.id", "INNER");
		$builder->where("t.activo", 1);
		$builder->where("p.activo", 1);
		$builder->orderBy("t.nombre", "ASC");
		$builder->orderBy("p.id", "ASC");
		$result = $builder->get();
		$query = $result->getResultArray();

		$tipos = array();
		foreach ($query as $v) {
			$id = $v['id'];
			if (!isset($tipos[$id])) {
				$tipo = array(
					'id' => $id,
					'nombre' => $v['nombre'],
					'preguntas' => []
				);
				$tipos[$id] = $tipo;
			}

			$pregunta = array(
				'id' => $v['id_pregunta'],
				'pregunta' => $v['indicador']
			);
			$tipos[$id]['preguntas'][] = $pregunta;
		}

		$tipos_final = []; //para json
		foreach ($tipos as $t) {
			$tipos_final[] = $t;
		}
		return $tipos_final;
	}

	public function getPeriodos()
	{
		$builder = $this->db->table('periodos p');
		$builder->select('*');
		return $builder->get()->getResultArray();
	}

	public function getAllPaged($offset, $tamanioPagina, $soloMax = FALSE)
	{
		$builder = $this->db->table('estadisticas_planilla e');

		if ($soloMax) {
			$builder->select("COUNT(*) cantidad");
		} else {
			$builder->select("e.id id_estadistica, empresas.nombre contratista, proyectos.nombre proyecto, e.tipo, e.periodo, e.anio, e.fecha_hora_carga, u_carga.nombre nombre_u_carga, u_carga.apellido apellido_u_carga, e.estado")
				->join('empresas', 'empresas.id=e.contratista', 'inner')
				->join('proyectos', 'proyectos.id=e.proyecto', 'inner')
				->join('usuario u_carga', 'u_carga.id=e.usuario_carga', 'inner')
				->orderBy('e.id', 'DESC')
				->limit($tamanioPagina, $offset);
		}
		$query = $builder->get();
		return $query->getResultArray();
	}

	public function changeState($id_estadistica)
	{
		$this->db->query("UPDATE `estadisticas_planilla` SET `estado` = CASE WHEN estado = 1 THEN 0 ELSE 1 END WHERE `estadisticas_planilla`.`id` = " . $id_estadistica);
	}
}
