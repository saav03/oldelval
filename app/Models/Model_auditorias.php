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
    /* ==================================================================================================================== */

    public function getRtasFromPreguntaAudControl($id_aud_planilla, $id_pregunta)
    {
        $builder = $this->db->table('aud_rtas_control');
        $builder->select('*')
            ->where('id_auditoria', $id_aud_planilla)
            ->where('id_pregunta', $id_pregunta);
        return $builder->get()->getResultArray();
    }

    public function getRtasFromPreguntaAudVehicular($id_aud_planilla, $id_pregunta)
    {
        $builder = $this->db->table('aud_rtas_vehicular');
        $builder->select('*')
            ->where('id_auditoria', $id_aud_planilla)
            ->where('id_pregunta', $id_pregunta);
        return $builder->get()->getResultArray();
    }

    public function getRtasFromPreguntaAudTarea_campo($id_aud_planilla, $id_pregunta)
    {
        $builder = $this->db->table('aud_rtas_tarea_de_campo');
        $builder->select('*')
            ->where('id_auditoria', $id_aud_planilla)
            ->where('id_pregunta', $id_pregunta);
        return $builder->get()->getResultArray();
    }

    public function getRtasFromPreguntaAud_auditoria($id_aud_planilla, $id_pregunta)
    {
        $builder = $this->db->table('aud_rtas_auditorias');
        $builder->select('*')
            ->where('id_auditoria', $id_aud_planilla)
            ->where('id_pregunta', $id_pregunta);
        return $builder->get()->getResultArray();
    }


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

    public function getAllPagedControl($offset, $tamanioPagina, $params, $soloMax = FALSE)
    {
        $modelo_tipo_control = isset($params['modelo_tipo_control']) && $params['modelo_tipo_control'] ? $params['modelo_tipo_control'] : false;
        $contratista = isset($params['contratista']) && $params['contratista'] ? $params['contratista'] : false;
        $supervisor = isset($params['supervisor']) && $params['supervisor'] ? $params['supervisor'] : false;
        $proyecto_aud_control = isset($params['proyecto_aud_control']) && $params['proyecto_aud_control'] ? $params['proyecto_aud_control'] : false;
        $usuario_carga_control = isset($params['usuario_carga_control']) && $params['usuario_carga_control'] ? $params['usuario_carga_control'] : false;
        $desde = isset($params['fecha_desde_control']) && $params['fecha_desde_control'] ? $params['fecha_desde_control'] : false;
        $hasta = isset($params['fecha_hasta_control']) && $params['fecha_hasta_control'] ? $params['fecha_hasta_control'] : false;


        $builder = $this->db->table('auditoria_control aud_control');

        if ($modelo_tipo_control) {
            $builder->where('aud_control.modelo_tipo', $modelo_tipo_control);
        }
        if ($contratista) {
            $builder->where('aud_control.contratista', $contratista);
        }
        if ($supervisor) {
            $builder->like('u_supervisor.nombre', $supervisor, 'both');
        }
        if ($proyecto_aud_control) {
            $builder->where('aud_control.proyecto', $proyecto_aud_control);
        }
        if ($usuario_carga_control) {
            $builder->like('u_carga.nombre', $usuario_carga_control, 'both');
        }
        if ($desde) {
            $builder->where("aud_control.fecha_carga >=", $desde);
        }
        if ($hasta) {
            $builder->where("aud_control.fecha_carga <=", $hasta);
        }

        if ($soloMax) {
            $builder->select("COUNT(*) cantidad");
        } else {
            $builder->select("aud_control.id id_auditoria, aud_title.nombre modelo_tipo, emp.nombre contratista, CONCAT(u_supervisor.nombre,' ',u_supervisor.apellido) as supervisor, proyectos.nombre as proyecto, CONCAT(u_carga.nombre,' ',u_carga.apellido) usuario_carga, DATE_FORMAT(aud_control.fecha_carga, '%d/%m/%Y') as fecha_carga_format, aud_control.estado")
                ->join('empresas emp', 'emp.id=aud_control.contratista', 'inner')
                ->join('auditorias_titulos as aud_title', 'aud_title.id=aud_control.modelo_tipo', 'inner')
                ->join('proyectos', 'proyectos.id=aud_control.proyecto', 'inner')
                ->join('usuario u_supervisor', 'u_supervisor.id=aud_control.supervisor_responsable', 'inner')
                ->join('usuario u_carga', 'u_carga.id=aud_control.usuario_carga', 'inner')
                ->orderBy('aud_control.id', 'DESC')
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

    public function getAllPagedVehicular($offset, $tamanioPagina, $params, $soloMax = FALSE)
    {

        $id_aud_vehicular = isset($params['id_aud_vehicular']) && $params['id_aud_vehicular'] ? $params['id_aud_vehicular'] : false;
        $modelo_tipo_vehicular = isset($params['modelo_tipo_vehicular']) && $params['modelo_tipo_vehicular'] ? $params['modelo_tipo_vehicular'] : false;
        $equipo = isset($params['equipo']) && $params['equipo'] ? $params['equipo'] : false;
        $conductor = isset($params['conductor']) && $params['conductor'] ? $params['conductor'] : false;
        $num_interno = isset($params['num_interno_vehicular']) && $params['num_interno_vehicular'] ? $params['num_interno_vehicular'] : false;
        $titular = isset($params['titular']) && $params['titular'] ? $params['titular'] : false;
        $proyecto = isset($params['proyecto_aud_vehicular']) && $params['proyecto_aud_vehicular'] ? $params['proyecto_aud_vehicular'] : false;
        $resultado = isset($params['resultado_inspeccion']) && $params['resultado_inspeccion'] ? $params['resultado_inspeccion'] : false;
        $usuario_carga = isset($params['usuario_carga_vehicular']) && $params['usuario_carga_vehicular'] ? $params['usuario_carga_vehicular'] : false;
        $desde = isset($params['fecha_desde_vehicular']) && $params['fecha_desde_vehicular'] ? $params['fecha_desde_vehicular'] : false;
        $hasta = isset($params['fecha_hasta_vehicular']) && $params['fecha_hasta_vehicular'] ? $params['fecha_hasta_vehicular'] : false;

        $builder = $this->db->table('auditoria_vehicular aud_vehicular');

        if ($id_aud_vehicular) {
            $builder->where('aud_vehicular.id', $id_aud_vehicular);
        }
        if ($modelo_tipo_vehicular) {
            $builder->where('aud_vehicular.modelo_tipo', $modelo_tipo_vehicular);
        }
        if ($equipo) {
            $builder->like('aud_vehicular.equipo', $equipo, 'both');
        }
        if ($conductor) {
            $builder->like('u_conductor.nombre', $conductor, 'both');
        }
        if ($num_interno) {
            $builder->like('aud_vehicular.num_interno', $num_interno, 'both');
        }
        if ($titular) {
            $builder->like('u_titular.nombre', $titular, 'both');
        }
        if ($proyecto) {
            $builder->where('aud_vehicular.proyecto', $proyecto);
        }
        if ($resultado == 1) {
            $builder->where('aud_vehicular.resultado_inspeccion', '1');
        } else if ($resultado == 2) {
            $builder->where('aud_vehicular.resultado_inspeccion', '0');
        }
        if ($usuario_carga) {
            $builder->like('u_carga.nombre', $usuario_carga, 'both');
        }
        if ($desde) {
            $builder->where("aud_vehicular.fecha_hora_carga >=", $desde);
        }
        if ($hasta) {
            $builder->where("aud_vehicular.fecha_hora_carga <=", $hasta);
        }

        if ($soloMax) {
            $builder->select("COUNT(*) cantidad");
        } else {
            $builder->select("aud_vehicular.id id_auditoria, aud_title.nombre modelo_tipo, aud_vehicular.equipo, CONCAT(u_conductor.nombre,' ',u_conductor.apellido) as conductor, aud_vehicular.num_interno, CONCAT(u_titular.nombre,' ',u_titular.apellido) as titular, proyectos.nombre proyecto, IF (aud_vehicular.resultado_inspeccion = 1, 'Satisfactoria', 'No Satisfactoria') as resultado_inspeccion, CONCAT(u_carga.nombre,' ',u_carga.apellido) as usuario_carga, DATE_FORMAT(aud_vehicular.fecha_hora_carga, '%d/%m/%Y') as fecha_carga_format, aud_vehicular.estado")
                ->orderBy('aud_vehicular.id', 'DESC')
                ->limit($tamanioPagina, $offset);
        }
        $builder->join('auditorias_titulos as aud_title', 'aud_title.id=aud_vehicular.modelo_tipo', 'inner')
            ->join('proyectos', 'proyectos.id=aud_vehicular.proyecto', 'inner')
            ->join('usuario u_titular', 'u_titular.id=aud_vehicular.titular', 'inner')
            ->join('usuario u_conductor', 'u_conductor.id=aud_vehicular.conductor', 'inner')
            ->join('usuario u_carga', 'u_carga.id=aud_vehicular.usuario_carga', 'inner');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getAllPaged_auditoria($offset, $tamanioPagina, $params, $soloMax = FALSE)
    {
        $modelo_tipo_auditoria_a = isset($params['modelo_tipo_auditoria_a']) && $params['modelo_tipo_auditoria_a'] ? $params['modelo_tipo_auditoria_a'] : false;
        $contratista = isset($params['contratista_auditoria_a']) && $params['contratista_auditoria_a'] ? $params['contratista_auditoria_a'] : false;
        $supervisor = isset($params['supervisor_auditoria_a']) && $params['supervisor_auditoria_a'] ? $params['supervisor_auditoria_a'] : false;
        $proyecto_aud_auditoria_a = isset($params['proyecto_aud_auditoria_a']) && $params['proyecto_aud_auditoria_a'] ? $params['proyecto_aud_auditoria_a'] : false;
        $usuario_carga_auditoria_a = isset($params['usuario_carga_auditoria_a']) && $params['usuario_carga_auditoria_a'] ? $params['usuario_carga_auditoria_a'] : false;
        $desde = isset($params['fecha_desde_auditoria_a']) && $params['fecha_desde_auditoria_a'] ? $params['fecha_desde_auditoria_a'] : false;
        $hasta = isset($params['fecha_hasta_auditoria_a']) && $params['fecha_hasta_auditoria_a'] ? $params['fecha_hasta_auditoria_a'] : false;


        $builder = $this->db->table('auditoria_auditoria aud_auditoria_auditoria');

        if ($modelo_tipo_auditoria_a) {
            $builder->where('aud_auditoria_auditoria.modelo_tipo', $modelo_tipo_auditoria_a);
        }
        if ($contratista) {
            $builder->where('aud_auditoria_auditoria.contratista', $contratista);
        }
        if ($supervisor) {
            $builder->like('u_supervisor.nombre', $supervisor, 'both');
        }
        if ($proyecto_aud_auditoria_a) {
            $builder->where('aud_auditoria_auditoria.proyecto', $proyecto_aud_auditoria_a);
        }
        if ($usuario_carga_auditoria_a) {
            $builder->like('u_carga.nombre', $usuario_carga_auditoria_a, 'both');
        }
        if ($desde) {
            $builder->where("aud_auditoria_auditoria.fecha_carga >=", $desde);
        }
        if ($hasta) {
            $builder->where("aud_auditoria_auditoria.fecha_carga <=", $hasta);
        }

        if ($soloMax) {
            $builder->select("COUNT(*) cantidad");
        } else {
            $builder->select("aud_auditoria_auditoria.id id_auditoria, aud_title.nombre modelo_tipo, emp.nombre contratista, CONCAT(u_supervisor.nombre,' ',u_supervisor.apellido) as supervisor, proyectos.nombre as proyecto, CONCAT(u_carga.nombre,' ',u_carga.apellido) usuario_carga, DATE_FORMAT(aud_auditoria_auditoria.fecha_carga, '%d/%m/%Y') as fecha_carga_format, aud_auditoria_auditoria.estado")
                ->join('empresas emp', 'emp.id=aud_auditoria_auditoria.contratista', 'inner')
                ->join('auditorias_titulos as aud_title', 'aud_title.id=aud_auditoria_auditoria.modelo_tipo', 'inner')
                ->join('proyectos', 'proyectos.id=aud_auditoria_auditoria.proyecto', 'inner')
                ->join('usuario u_supervisor', 'u_supervisor.id=aud_auditoria_auditoria.supervisor_responsable', 'inner')
                ->join('usuario u_carga', 'u_carga.id=aud_auditoria_auditoria.usuario_carga', 'inner')
                ->orderBy('aud_auditoria_auditoria.id', 'DESC')
                ->limit($tamanioPagina, $offset);
        }
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getAllPagedTarea_de_campo($offset, $tamanioPagina, $params, $soloMax = FALSE)
    {
        $modelo_tipo_auditoria_tarea_de_campo = isset($params['modelo_tipo_tarea_de_campo']) && $params['modelo_tipo_tarea_de_campo'] ? $params['modelo_tipo_tarea_de_campo'] : false;
        $contratista = isset($params['contratista_tarea_de_campo']) && $params['contratista_tarea_de_campo'] ? $params['contratista_tarea_de_campo'] : false;
        $supervisor = isset($params['supervisor_tarea_de_campo']) && $params['supervisor_tarea_de_campo'] ? $params['supervisor_tarea_de_campo'] : false;
        $proyecto_aud_auditoria_tarea_de_campo = isset($params['proyecto_aud_tarea_de_campo']) && $params['proyecto_aud_tarea_de_campo'] ? $params['proyecto_aud_tarea_de_campo'] : false;
        $usuario_carga_auditoria_tarea_de_campo = isset($params['usuario_carga_tarea_de_campo']) && $params['usuario_carga_tarea_de_campo'] ? $params['usuario_carga_tarea_de_campo'] : false;
        $desde = isset($params['fecha_desde_tarea_de_campo']) && $params['fecha_desde_tarea_de_campo'] ? $params['fecha_desde_tarea_de_campo'] : false;
        $hasta = isset($params['fecha_hasta_tarea_de_campo']) && $params['fecha_hasta_tarea_de_campo'] ? $params['fecha_hasta_tarea_de_campo'] : false;


        $builder = $this->db->table('auditoria_tarea_de_campo aud_auditoria_tarea_de_campo');

        if ($modelo_tipo_auditoria_tarea_de_campo) {
            $builder->where('aud_auditoria_tarea_de_campo.modelo_tipo', $modelo_tipo_auditoria_tarea_de_campo);
        }
        if ($contratista) {
            $builder->where('aud_auditoria_tarea_de_campo.contratista', $contratista);
        }
        if ($supervisor) {
            $builder->like('u_supervisor.nombre', $supervisor, 'both');
        }
        if ($proyecto_aud_auditoria_tarea_de_campo) {
            $builder->where('aud_auditoria_tarea_de_campo.proyecto', $proyecto_aud_auditoria_tarea_de_campo);
        }
        if ($usuario_carga_auditoria_tarea_de_campo) {
            $builder->like('u_carga.nombre', $usuario_carga_auditoria_tarea_de_campo, 'both');
        }
        if ($desde) {
            $builder->where("aud_auditoria_tarea_de_campo.fecha_carga >=", $desde);
        }
        if ($hasta) {
            $builder->where("aud_auditoria_tarea_de_campo.fecha_carga <=", $hasta);
        }

        if ($soloMax) {
            $builder->select("COUNT(*) cantidad");
        } else {
            $builder->select("aud_auditoria_tarea_de_campo.id id_auditoria, aud_title.nombre modelo_tipo, emp.nombre contratista, CONCAT(u_supervisor.nombre,' ',u_supervisor.apellido) as supervisor, proyectos.nombre as proyecto, CONCAT(u_carga.nombre,' ',u_carga.apellido) usuario_carga, DATE_FORMAT(aud_auditoria_tarea_de_campo.fecha_carga, '%d/%m/%Y') as fecha_carga_format, aud_auditoria_tarea_de_campo.estado")
                ->join('empresas emp', 'emp.id=aud_auditoria_tarea_de_campo.contratista', 'inner')
                ->join('auditorias_titulos as aud_title', 'aud_title.id=aud_auditoria_tarea_de_campo.modelo_tipo', 'inner')
                ->join('proyectos', 'proyectos.id=aud_auditoria_tarea_de_campo.proyecto', 'inner')
                ->join('usuario u_supervisor', 'u_supervisor.id=aud_auditoria_tarea_de_campo.supervisor_responsable', 'inner')
                ->join('usuario u_carga', 'u_carga.id=aud_auditoria_tarea_de_campo.usuario_carga', 'inner')
                ->orderBy('aud_auditoria_tarea_de_campo.id', 'DESC')
                ->limit($tamanioPagina, $offset);
        }
        $query = $builder->get();
        return $query->getResultArray();
    }

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
    public function getBloqueCompletoAudControl($id_auditoria)
    {
        $builder = $this->db->table('auditoria_control aud_control');
        $builder->select('aud_control.id as id_auditoria, aud_control.modelo_tipo, empresas.nombre as contratista, CONCAT(u_supervisor.nombre," ", u_supervisor.apellido) as supervisor_responsable, DATE_FORMAT(aud_control.fecha_carga, "%d/%m/%Y") as fecha_carga_format, aud_control.cant_personal, aud_control.num_informe, proyectos.nombre proyecto, modulos.nombre modulo, estacion.nombre estacion, sistema.nombre sistema, CONCAT(u_carga.nombre," ", u_carga.apellido) usuario_carga')
            ->join('empresas', 'empresas.id=aud_control.contratista', 'inner')
            ->join('usuario u_supervisor', 'u_supervisor.id=aud_control.supervisor_responsable', 'inner')
            ->join('proyectos', 'proyectos.id=aud_control.proyecto', 'inner')
            ->join('modulos', 'modulos.id=aud_control.modulo', 'left')
            ->join('estaciones_bombeo estacion', 'estacion.id=aud_control.estacion', 'left')
            ->join('sistemas_oleoductos sistema', 'sistema.id=aud_control.sistema', 'left')
            ->join('usuario u_carga', 'u_carga.id=aud_control.usuario_carga', 'left')
            ->where('aud_control.id', $id_auditoria);
        $auditoria = $builder->get()->getRowArray();
        return $auditoria;
    }

    public function getBloqueCompletoAudTareaCampo($id_auditoria)
    {
        $builder = $this->db->table('auditoria_tarea_de_campo aud_auditoria_tarea_de_campo');
        $builder->select('aud_auditoria_tarea_de_campo.id as id_auditoria, aud_auditoria_tarea_de_campo.modelo_tipo, empresas.nombre as contratista, CONCAT(u_supervisor.nombre," ", u_supervisor.apellido) as supervisor_responsable, DATE_FORMAT(aud_auditoria_tarea_de_campo.fecha_carga, "%d/%m/%Y") as fecha_carga_format, aud_auditoria_tarea_de_campo.cant_personal, aud_auditoria_tarea_de_campo.num_informe, proyectos.nombre proyecto, modulos.nombre modulo, estacion.nombre estacion, sistema.nombre sistema, CONCAT(u_carga.nombre," ", u_carga.apellido) usuario_carga')
            ->join('empresas', 'empresas.id=aud_auditoria_tarea_de_campo.contratista', 'inner')
            ->join('usuario u_supervisor', 'u_supervisor.id=aud_auditoria_tarea_de_campo.supervisor_responsable', 'inner')
            ->join('proyectos', 'proyectos.id=aud_auditoria_tarea_de_campo.proyecto', 'inner')
            ->join('modulos', 'modulos.id=aud_auditoria_tarea_de_campo.modulo', 'left')
            ->join('estaciones_bombeo estacion', 'estacion.id=aud_auditoria_tarea_de_campo.estacion', 'left')
            ->join('sistemas_oleoductos sistema', 'sistema.id=aud_auditoria_tarea_de_campo.sistema', 'left')
            ->join('usuario u_carga', 'u_carga.id=aud_auditoria_tarea_de_campo.usuario_carga', 'left')
            ->where('aud_auditoria_tarea_de_campo.id', $id_auditoria);
        $auditoria = $builder->get()->getRowArray();
        return $auditoria;
    }

    public function getBloqueCompletoAud_auditoria($id_auditoria)
    {
        $builder = $this->db->table('auditoria_auditoria aud_auditoria_auditoria');
        $builder->select('aud_auditoria_auditoria.id as id_auditoria, aud_auditoria_auditoria.modelo_tipo, empresas.nombre as contratista, CONCAT(u_supervisor.nombre," ", u_supervisor.apellido) as supervisor_responsable, DATE_FORMAT(aud_auditoria_auditoria.fecha_carga, "%d/%m/%Y") as fecha_carga_format, aud_auditoria_auditoria.cant_personal, aud_auditoria_auditoria.num_informe, proyectos.nombre proyecto, modulos.nombre modulo, estacion.nombre estacion, sistema.nombre sistema, CONCAT(u_carga.nombre," ", u_carga.apellido) usuario_carga')
            ->join('empresas', 'empresas.id=aud_auditoria_auditoria.contratista', 'inner')
            ->join('usuario u_supervisor', 'u_supervisor.id=aud_auditoria_auditoria.supervisor_responsable', 'inner')
            ->join('proyectos', 'proyectos.id=aud_auditoria_auditoria.proyecto', 'inner')
            ->join('modulos', 'modulos.id=aud_auditoria_auditoria.modulo', 'left')
            ->join('estaciones_bombeo estacion', 'estacion.id=aud_auditoria_auditoria.estacion', 'left')
            ->join('sistemas_oleoductos sistema', 'sistema.id=aud_auditoria_auditoria.sistema', 'left')
            ->join('usuario u_carga', 'u_carga.id=aud_auditoria_auditoria.usuario_carga', 'left')
            ->where('aud_auditoria_auditoria.id', $id_auditoria);
        $auditoria = $builder->get()->getRowArray();
        return $auditoria;
    }

    public function getHallazgoAud($id_auditoria, $tipo)
    {
        $builder = $this->db->table('obs_hallazgos h');
        $builder->select('h.id id_hallazgo, h.id_auditoria, h.tipo_auditoria, h.hallazgo, h.plan_accion, h.efecto_impacto, empresas.nombre contratista, CONCAT(responsable.nombre," ", responsable.apellido) responsable, h.responsable id_responsable, h.relevo_responsable id_relevo, h.significancia, CONCAT(relevo.nombre," ", relevo.apellido) relevo, h.significancia, h.fecha_cierre fecha_cierre, DATE_FORMAT(h.fecha_cierre, "%d/%m/%Y") fecha_cierre_format, DATE_FORMAT(h.fecha_hora_carga, "%d/%m/%Y") fecha_hora_carga, h.usuario_carga id_usuario_carga, CONCAT(u_carga.nombre," ", u_carga.apellido) usuario_carga')
            ->join('empresas', 'empresas.id=h.contratista', 'inner')
            ->join('usuario responsable', 'responsable.id=h.responsable', 'inner')
            ->join('usuario relevo', 'relevo.id=h.relevo_responsable', 'left')
            ->join('usuario u_carga', 'u_carga.id=h.usuario_carga', 'inner')
            ->where('h.id_auditoria', $id_auditoria)
            ->where('h.tipo_auditoria', $tipo);
        $hallazgo = $builder->get()->getRowArray();
        if (!empty($hallazgo)) {
            $id_hallazgo = $hallazgo['id_hallazgo'];
            /* == Efectos relacionados al hallazgo == */
            $hallazgo['efectos'] = $this->getEfectosRelHallazgo($id_hallazgo);

            /* == Cargo los descargos pertenecientes al Hallazgo == */
            $descargos = $this->getDescargosHallazgo($hallazgo['id_hallazgo']);

            if (!is_null($descargos)) {
                /* == Cargo los Adjuntos del Hallazgo == */
                $adj_hallazgo = $this->getAdjHallazgo($id_hallazgo);
                $hallazgo['adjuntos'] = $adj_hallazgo;

                foreach ($descargos as $key => $d) {
                    /* == Cargo los adjuntos de todos los hallazgos == */
                    $adj_descargos = $this->getAdjDescargosHallazgo($d['id_descargo']);
                    $descargos[$key]['descargos_adj'] = $adj_descargos;
                }

                $hallazgo['descargos'] = $descargos;
            }

            /* == Cierre de la tarjeta (Si es que está cerrada) == */
            $cierre = $this->getCierreMotivoHallazgo($id_hallazgo);
            $hallazgo['cierre'] = $cierre;
        }



        return $hallazgo;
    }


    public function getSignificanciaRelHallazgo($id_hallazgo)
    {
        $builder = $this->db->table('obs_rel_hallazgo_significancia orhs');
        $builder->select('orhs.id id_rel_significancia, orhs.id_significancia, s.nombre nombre_significancia')
            ->join('significancia s', 's.id=orhs.id_significancia', 'inner')
            ->where('id_hallazgo', $id_hallazgo);
        return $builder->get()->getResultArray();
    }
    public function getEfectosRelHallazgo($id_hallazgo)
    {
        $builder = $this->db->table('obs_rel_hallazgo_efecto orhe');
        $builder->select('orhe.id id_rel_efecto, orhe.id_efecto, e.nombre nombre_efecto')
            ->join('efectos_impactos e', 'e.id=orhe.id_efecto', 'inner')
            ->where('id_hallazgo', $id_hallazgo);
        return $builder->get()->getResultArray();
    }
    public function getDescargosHallazgo($id_hallazgo)
    {
        $builder = $this->db->table('obs_hallazgos_descargos ohd');
        $builder->select('ohd.id id_descargo, ohd.id_hallazgo, ohd.estado estado_descargo, ohd.motivo, DATE_FORMAT(ohd.fecha_hora_motivo, "%d/%m/%Y") fecha_hora_motivo, ohd.respuesta, DATE_FORMAT(ohd.fecha_hora_respuesta, "%d/%m/%Y") fecha_hora_respuesta, CONCAT(u_descargo.nombre," ", u_descargo.apellido) usuario_descargo, CONCAT(u_rta.nombre," ", u_rta.apellido) usuario_respuesta')
            ->join('usuario u_descargo', 'u_descargo.id=ohd.id_usuario', 'inner')
            ->join('usuario u_rta', 'u_rta.id=ohd.id_usuario_rta', 'left')
            ->where('ohd.id_hallazgo', $id_hallazgo);
        return $builder->get()->getResultArray();
    }
    public function getAdjHallazgo($id_hallazgo)
    {
        $builder = $this->db->table('obs_hallazgos_adj oha');
        $builder->select('*')
            ->where('oha.id_hallazgo', $id_hallazgo);
        return $builder->get()->getResultArray();
    }
    public function getAdjDescargosHallazgo($id_descargo)
    {
        $builder = $this->db->table('obs_descargos_adjuntos oda');
        $builder->select('*')
            ->where('oda.id_descargo', $id_descargo);
        return $builder->get()->getResultArray();
    }
    protected function getCierreMotivoHallazgo($id_hallazgo)
    {
        $builder = $this->db->table('obs_hallazgo_cierre ohc');
        $builder->select('ohc.*, CONCAT(u.nombre," ", u.apellido) usuario_cierre')
            ->join('usuario u', 'u.id=ohc.id_usuario_cierre', 'inner')
            ->where('ohc.id_hallazgo_obs', $id_hallazgo);
        return $builder->get()->getRowArray();
    }

    /**
     * Trae el bloque completo de la auditoría con sus preguntas y sus respuestas
     */
    public function getBloqueCompletoAudVehicular($id_auditoria)
    {
        $builder = $this->db->table('auditoria_vehicular aud_vehicular');
        $builder->select('aud_vehicular.id id_auditoria, aud_vehicular.modelo_tipo, cont.nombre as contratista, aud_vehicular.equipo, CONCAT(conductor.nombre, " ", conductor.apellido) conductor, aud_vehicular.num_interno, aud_vehicular.marca, aud_vehicular.modelo, aud_vehicular.patente, CONCAT(titular.nombre, " ", titular.apellido) titular, aud_vehicular.fecha, aud_vehicular.hora, proyectos.nombre proyecto, tarea_que_realiza, resultado_inspeccion, medidas_implementar, DATE_FORMAT(aud_vehicular.fecha_hora_carga, "%d/%m/%Y") fecha_carga_format, CONCAT(u_carga.nombre," ", u_carga.apellido) usuario_carga')
            ->join('usuario conductor', 'conductor.id=aud_vehicular.conductor', 'inner')
            ->join('usuario titular', 'titular.id=aud_vehicular.titular', 'inner')
            ->join('empresas cont', 'cont.id = aud_vehicular.contratista', 'left')
            ->join('proyectos', 'proyectos.id=aud_vehicular.proyecto', 'inner')
            ->join('usuario u_carga', 'u_carga.id=aud_vehicular.usuario_carga', 'left')
            ->where('aud_vehicular.id', $id_auditoria);
        $auditoria = $builder->get()->getRowArray();
        return $auditoria;
    }

    /**
     * Actualiza los comentarios de las respuestas después de que se carga una auditoría
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
        $builder = $this->db->table('aud_rtas_vehicular');
        $builder->set('tipo_obs', $tipo_obs);
        $builder->where('id_auditoria', $id_auditoria);
        $builder->where('id_pregunta', $id_pregunta);
        $builder->update();
    }

    public function addDescargo($datos)
    {
        $this->db->transStart();
        $builder = $this->db->table('obs_hallazgos_descargos');
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

    /**
     * Actualiza el estado de la Auditoría dependiendo que se pase por parámetros
     */
    public function changeState($id_auditoria, $tabla)
    {
        $this->db->query("UPDATE `$tabla` SET `estado` = CASE WHEN estado = 1 THEN 0 ELSE 1 END WHERE `$tabla`.`id` = " . $id_auditoria);
    }

    public function getAudEdition($id)
    {
        $builder = $this->db->table('auditorias_preguntas aud_preguntas');
        $builder->select('id, pregunta, subtitulo, titulo')->where('aud_preguntas.titulo', $id)->orderBy('aud_preguntas.orden', 'ASC');
        $preguntas = $builder->get()->getResultArray();
    }
}
