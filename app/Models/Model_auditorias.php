<?php

namespace App\Models;

use CodeIgniter\Model;

class Model_auditorias extends Model
{

    /* === Estos tres métodos van de la mano === */

    /**
     * Trae el bloque de preguntas completo para cargar una auditoría
     */
    public function getBloqueAud($id_auditoria)
    {
        $builder = $this->db->table('auditorias_titulos');
        $builder->select('*')
            ->where('id', $id_auditoria);
        $auditoria = $builder->get()->getResultArray();

        $bloque_preguntas = $this->getSubtitulosAuditorias($id_auditoria);

        foreach ($bloque_preguntas as $key => $value) {
            $bloque_preguntas[$key]['preguntas'] = $this->getPreguntasAuditorias($value['id'], $id_auditoria);
        }

        $auditoria['bloque_preguntas'] = $bloque_preguntas;

        return $auditoria;
    }

    /**
     * Trae los subtitulos a través del #ID de la auditoría que se solicite por parámetro
     */
    public function getSubtitulosAuditorias($id_auditoria)
    {
        $builder = $this->db->table('auditorias_subtitulos');
        $builder->select('id, nombre')
            ->where('id_titulo', $id_auditoria);
        return $builder->get()->getResultArray();
    }

    /**
     * Trae las preguntas que pertenecen a ese subtítulo de la auditoría correspondiente (Por parámetros)
     */
    public function getPreguntasAuditorias($id_subtitulo, $id_auditoria)
    {
        $builder = $this->db->table('auditorias_preguntas');
        $builder->select('id, pregunta')
            ->where('titulo', $id_auditoria)
            ->where('subtitulo', $id_subtitulo)
            ->where('estado', 1)
            ->orderBy('orden', 'ASC');
        return $builder->get()->getResultArray();
    }
    /* Finaliza la sección donde los métodos van de la mano xd */
    /* ==================================================================================================================== */

    /**
     * Obtiene las respuestas de la Inspección/Auditoría cargada
     */
    public function getRespuestasAuditoria($id_aud_planilla, $id_pregunta)
    {
        $builder = $this->db->table('auditoria_respuestas');
        $builder->select('*')
            ->where('id_auditoria', $id_aud_planilla)
            ->where('id_pregunta', $id_pregunta);
        return $builder->get()->getResultArray();
    }

    /**
     * Obtiene el ID de las preguntas de una Inspección/Auditorías
     * Su lógica está en el método getCompleteInspection() del controlador Auditorias.php
     */
    public function getIdPreguntasAuditorias($id_subtitulo, $id_auditoria)
    {
        $builder = $this->db->table('auditorias_preguntas');
        $builder->select('id as id_pregunta')
            ->where('titulo', $id_auditoria)
            ->where('subtitulo', $id_subtitulo)
            ->where('estado', 1)
            ->orderBy('orden', 'ASC');
        return $builder->get()->getResultArray();
    }

    ###################### NUEVA AUDITORIAS (NUEVO CODIGO ACTUALIZADO)
    public function getAllPaged($offset, $tamanioPagina, $params, $soloMax = FALSE)
    {
        $auditoria = $params['auditoria'];
        $modelo_tipo = isset($params['modelo_tipo']) && $params['modelo_tipo'] ? $params['modelo_tipo'] : false;
        $contratista = isset($params['contratista']) && $params['contratista'] ? $params['contratista'] : false;
        $supervisor = isset($params['supervisor']) && $params['supervisor'] ? $params['supervisor'] : false;
        $proyecto = isset($params['proyecto']) && $params['proyecto'] ? $params['proyecto'] : false;
        $usuario_carga = isset($params['usuario_carga']) && $params['usuario_carga'] ? $params['usuario_carga'] : false;
        $desde = isset($params['fecha_desde']) && $params['fecha_desde'] ? $params['fecha_desde'] : false;
        $hasta = isset($params['fecha_hasta']) && $params['fecha_hasta'] ? $params['fecha_hasta'] : false;
        $equipo = isset($params['equipo']) && $params['equipo'] ? $params['equipo'] : false;
        $conductor = isset($params['conductor']) && $params['conductor'] ? $params['conductor'] : false;
        // $num_interno = isset($params['num_interno']) && $params['num_interno'] ? $params['num_interno'] : false;
        $resultado = isset($params['resultado']) && $params['resultado'] ? $params['resultado'] : false;
        $builder = $this->db->table('auditoria a');

        if ($modelo_tipo)
            $builder->where('a.modelo_tipo', $modelo_tipo);
        if ($contratista)
            $builder->where('a.contratista', $contratista);
        // TODO | Solo filtra por el nombre, pero no por el apellido (Arreglar)
        if ($supervisor)
            $builder->like('a.supervisor_responsable', $supervisor, 'both');
        if ($proyecto)
            $builder->where('a.proyecto', $proyecto);
        if ($usuario_carga)
            $builder->like('u_carga.nombre', $usuario_carga, 'both');

        if ($auditoria == 2) {
            if ($equipo)
                $builder->like('a.equipo', $equipo, 'both');
            if ($conductor)
                $builder->like('a.conductor', $conductor, 'both');

            if ($resultado) {
                if ($resultado == 1) {
                    $builder->where('a.resultado_inspeccion', 1);
                } else {
                    $builder->where('a.resultado_inspeccion', 0);
                }
            }
        }

        if ($desde)
            $builder->where("a.fecha_hora_carga >=", $desde);
        if ($hasta)
            $builder->where("a.fecha_hora_carga <=", $hasta);


        if ($soloMax) {
            $builder->select("COUNT(*) cantidad")
                ->where('a.auditoria', $auditoria);
        } else {
            $vehicular_query = $auditoria == 2 ? ', a.conductor, a.equipo, a.resultado_inspeccion' : '';
            $builder->select("a.id id_auditoria, a_title.nombre modelo_tipo, emp.nombre contratista, a.supervisor_responsable supervisor, p.nombre as proyecto, CONCAT(u_carga.nombre,' ',u_carga.apellido) usuario_carga, DATE_FORMAT(a.fecha_hora_carga, '%d/%m/%Y') as fecha_carga_format" . $vehicular_query)
                ->join('empresas emp', 'emp.id=a.contratista', 'inner')
                ->join('auditorias_titulos a_title', 'a_title.id=a.modelo_tipo', 'inner')
                ->join('proyectos p', 'p.id=a.proyecto', 'inner')
                ->join('usuario u_carga', 'u_carga.id=a.usuario_carga', 'inner')
                ->where('a.auditoria', $auditoria)
                ->orderBy('a.id', 'DESC')
                ->limit($tamanioPagina, $offset);
        }
        $query = $builder->get();
        return $query->getResultArray();
    }

    /**
     * Tabla de Edición de las Auditorías de Control
     */
    public function getAllPagedControlEdicion($offset, $tamanioPagina, $soloMax = FALSE)
    {
        $builder = $this->db->table('auditorias_titulos aud_title');

        if ($soloMax) {
            $builder->select("COUNT(*) cantidad");
        } else {
            $builder->select('id, nombre')
                ->limit($tamanioPagina, $offset);
        }
        $query = $builder->get();
        return $query->getResultArray();
    }

    /**
     * Obtiene cada título de las Inspecciones/Auditorías cargadas
     * Esto se hace principalmente para cargar una Inspección y saber de que tipo se está cargando
     */
    public function getAllTitlesAuditoria($tipo = 1, $obsoleto = false)
    {
        $builder = $this->db->table('auditorias_titulos');
        $builder->select('*');

        $builder->where('tipo_aud', $tipo);
        if (!$obsoleto) {
            $builder->where('obsoleto', NULL);
        }
        $builder->where('estado', 1);


        return $builder->get()->getResultArray();
    }

    /**
     * Trae el bloque completo de la auditoría con sus preguntas y sus respuestas
     */
    public function getBloqueInspectionComplete($id_auditoria)
    {
        $builder = $this->db->table('auditoria a');
        $builder->select('a.id as id_auditoria, a.auditoria, a.modelo_tipo, empresas.nombre as contratista, a.equipo, a.conductor, a.num_interno, a.supervisor_responsable, DATE_FORMAT(a.fecha_hora_carga, "%Y-%m-%d") fecha_carga, DATE_FORMAT(a.fecha_hora_carga, "%d/%m/%Y") as fecha_carga_format, a.cant_personal, a.marca, a.modelo, a.patente, DATE_FORMAT(a.hora, "%H:%i") hora, proyectos.nombre proyecto, modulos.nombre modulo, estacion.nombre estacion, sistema.nombre sistema, a.tarea_que_realiza, a.resultado_inspeccion, a.medidas_implementar, CONCAT(u_carga.nombre," ", u_carga.apellido) usuario_carga')
            ->join('empresas', 'empresas.id=a.contratista', 'inner')
            ->join('proyectos', 'proyectos.id=a.proyecto', 'inner')
            ->join('modulos', 'modulos.id=a.modulo', 'left')
            ->join('estaciones_bombeo estacion', 'estacion.id=a.estacion', 'left')
            ->join('sistemas_oleoductos sistema', 'sistema.id=a.sistema', 'left')
            ->join('usuario u_carga', 'u_carga.id=a.usuario_carga', 'left')
            ->where('a.id', $id_auditoria);
        $auditoria = $builder->get()->getRowArray();
        return $auditoria;
    }

    /**
     * Actualiza los comentarios de las respuestas después de que se carga una auditoría/inspección
     */
    public function updateComentarioRta($datos, $tabla)
    {
        extract($datos);
        $builder = $this->db->table($tabla);
        $builder->set('comentario', $comentario);
        $builder->where('id_auditoria', $id_auditoria);
        $builder->where('id_pregunta', $id_pregunta);
        $builder->update();
    }

    /**
     * Actualiza los tipo de observaciones de las respuestas después de que se carga una auditoría checklist vehicular
     */
    public function updateTipoObsRta($datos)
    {
        extract($datos);
        $builder = $this->db->table('auditoria_respuestas');
        $builder->set('tipo_obs', $tipo_obs);
        $builder->where('id_auditoria', $id_auditoria);
        $builder->where('id_pregunta', $id_pregunta);
        $builder->update();
    }

    /**
     * Como su nombre indica, carga un nuevo descargo para el hallazgo creado previamente
     */
    public function addDescargo($datos)
    {
        $this->db->transStart();
        $builder = $this->db->table('auditoria_hallazgo_descargos');
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
     * El nombre tal vez esté mal, no edita un descargo, sino, que actualiza las columnas que estén
     * referenciadas a una respuesta, si el descargo obtiene una respuesta, entonces se genera un update de esos datos
     */
    public function editDescargo($datos, $id_descargo)
    {
        $this->db->transStart();
        $builder = $this->db->table('auditoria_hallazgo_descargos insp_descargo');
        $builder->where('insp_descargo.id', $id_descargo);
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
     * Actualiza el estado de la Auditoría dependiendo que se pase por parámetros
     */
    public function changeState($id_auditoria, $tabla)
    {
        $this->db->query("UPDATE `$tabla` SET `estado` = CASE WHEN estado = 1 THEN 0 ELSE 1 END WHERE `$tabla`.`id` = " . $id_auditoria);
    }

    /**
     * Obtiene las preguntas para poder editar una Inspección/Auditoría
     */
    public function getAudEdition($id)
    {
        $builder = $this->db->table('auditorias_preguntas aud_preguntas');
        $builder->select('id, pregunta, subtitulo, titulo')->where('aud_preguntas.titulo', $id)->orderBy('aud_preguntas.orden', 'ASC');
        $preguntas = $builder->get()->getResultArray();
    }

    /* === Auditorias (Nueva forma realizada con JS) ===  */
    /**
     * Carga una nueva inspección en la base de datos
     * La tabla es la misma para las cuatro Inspecciones que hay cargadas por el momento
     */
    public function createInspection($datos)
    {
        $this->db->transStart();
        $builder = $this->db->table('auditoria');
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
     * Crea los hallazgos para la Auditoría creada 
     * (Una Auditoría puede tener muchos hallazgos asignados (positivos y con oportunidad de mejora)
     */
    public function createHallazgoAuditoria($data)
    {
        $this->db->transStart();
        $builder = $this->db->table('auditoria_hallazgos');
        $builder->insert($data);
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
     * Trae todos los hallazgos pertenecientes al ID pasado por parámetro de la Inspección
     */
    public function getHallazgosInspeccion($id_inspeccion)
    {
        $builder = $this->db->table('auditoria_hallazgos h');
        $builder->select('h.id id_hallazgo, h.id_auditoria, h.hallazgo, h.plan_accion, c.id tipo_aspecto, c.nombre aspecto, h.significancia, h.resuelto, t_p.id id_tipo, t_p.nombre tipo_obs, u_r.id id_responsable, CONCAT(u_r.nombre," ", u_r.apellido) usuario_responsable, u_r_r.id id_relevo_responsable, CONCAT(u_r_r.nombre," ", u_r_r.apellido) relevo_responsable, h.fecha_cierre, DATE_FORMAT(h.fecha_cierre, "%d/%m/%Y") fecha_cierre_f, u_c.id id_usuario_carga, CONCAT(u_c.nombre," ", u_c.apellido) usuario_carga')
            ->join('consecuencias c', 'c.id=h.aspecto', 'join')
            ->join('tipo_hallazgo t_p', 't_p.id=h.tipo', 'join')
            ->join('usuario u_r', 'u_r.id=h.responsable', 'join')
            ->join('usuario u_r_r', 'u_r_r.id=h.relevo_responsable', 'left')
            ->join('usuario u_c', 'u_c.id=h.usuario_carga', 'join')
            ->where('h.estado', 1)
            ->where('h.id_auditoria', $id_inspeccion);
        $query = $builder->get();
        return $query->getResultArray();
    }
    /**
     * Obtiene los adjuntos del hallazgo cargado previamente
     */
    public function getAdjHallazgoInspection($id_hallazgo)
    {
        $builder = $this->db->table('auditoria_hallazgos_adjuntos insp_hallazgo_adj');
        $builder->select('*')
            ->where('insp_hallazgo_adj.id_hallazgo', $id_hallazgo);
        return $builder->get()->getResultArray();
    }
    /**
     * Trae los efectos de la tabla de relación 'auditoria_rel_efecto' de cada hallazgo correspondiente
     */
    public function getEfectosRelHallazgo($id_hallazgo)
    {
        $builder = $this->db->table('auditoria_rel_efecto are');
        $builder->select('are.id id_rel_efecto, are.efecto_id, e.nombre nombre_efecto')
            ->join('efectos_impactos e', 'e.id=are.efecto_id', 'inner')
            ->where('hallazgo_id', $id_hallazgo);
        return $builder->get()->getResultArray();
    }

    /**
     * Obtiene los descargos del hallazgo
     */
    public function getDescargoHallazgoInspection($id_hallazgo)
    {
        $builder = $this->db->table('auditoria_hallazgo_descargos insp_hallazgo_descargo');
        $builder->select('insp_hallazgo_descargo.id, insp_hallazgo_descargo.estado, insp_hallazgo_descargo.motivo, DATE_FORMAT(insp_hallazgo_descargo.fecha_hora_motivo, "%d/%m/%Y %H:%i:%s") fecha_hora_motivo, insp_hallazgo_descargo.respuesta, DATE_FORMAT(insp_hallazgo_descargo.fecha_hora_respuesta, "%d/%m/%Y %H:%i:%s") fecha_hora_respuesta, u.nombre, u.apellido, u_rta.nombre nombre_user_rta, u_rta.apellido apellido_user_rta')
            ->join('usuario u', 'u.id=insp_hallazgo_descargo.id_usuario', 'inner')
            ->join('usuario u_rta', 'u_rta.id=insp_hallazgo_descargo.id_usuario_rta', 'left')
            ->where('insp_hallazgo_descargo.id_hallazgo', $id_hallazgo);
        return $builder->get()->getResultArray();
    }
    /**
     * Trae todos aquellos adjuntos que pertenezcan al descargo solicitado por parámetro
     */
    public function getAdjDescargosHallazgo($id_descargo)
    {
        $builder = $this->db->table('auditoria_descargos_adj insp_adj_descargo');
        $builder->select('*')
            ->where('insp_adj_descargo.id_descargo', $id_descargo);
        return $builder->get()->getResultArray();
    }

    public function delete($id_inspeccion = null, bool $purge = false)
    {
        $builder = $this->db->table('auditoria')
            ->where('id', $id_inspeccion)
            ->delete();
    }

    public function getIdHallazgos($id_inspeccion)
    {
        $builder = $this->db->table('auditoria_hallazgos');
        $builder->select('id')
            ->where('id_auditoria', $id_inspeccion);
        return $builder->get()->getResultArray();
    }

    public function getNameAdjuntos($id_hallazgo) {
        $builder = $this->db->table('auditoria_hallazgos_adjuntos');
        $builder->select('adjunto')
            ->where('id_hallazgo', $id_hallazgo);
        return $builder->get()->getResultArray();
    }

    public function getIdDescargos($id_hallazgos)
    {
        $builder = $this->db->table('auditoria_hallazgo_descargos');
        $builder->select('id')
            ->where('id_hallazgo', $id_hallazgos);
        return $builder->get()->getRowArray();
    }
    public function getNameAdjuntosDescargos($id_descargo) {
        $builder = $this->db->table('auditoria_descargos_adj');
        $builder->select('adjunto')
            ->where('id_descargo', $id_descargo);
        return $builder->get()->getResultArray();
    }

    /* TODO LO QUE SE HAGA ACA ES AUXILIAR, SE VA A EJECUTAR UNA VEZ Y NO SE EJECUTA MÁS */
    public function getRespuestasInspeccionAux($id_inspeccion, $tabla) {
        $builder = $this->db->table($tabla);
        $builder->select('*')
        ->where('id_auditoria', $id_inspeccion);
        return $builder->get()->getResultArray();
    }
    public function getHallazgoInspeccionAux($id_inspeccion, $tipo_auditoria) {
        $builder = $this->db->table('obs_hallazgos');
        $builder->select('*')
        ->where('id_auditoria', $id_inspeccion)
        ->where('tipo_auditoria', $tipo_auditoria);
        return $builder->get()->getRowArray();
    }
    public function getHallazgosAdjuntosAux($id_hallazgo) {
        $builder = $this->db->table('obs_hallazgos_adj');
        $builder->select('*')
        ->where('id_hallazgo', $id_hallazgo);
        return $builder->get()->getResultArray();
    }
}
