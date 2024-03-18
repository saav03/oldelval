<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Model_reporte_tarjeta;
use App\Models\Model_reporte_inspecciones;
use App\Models\Model_movimiento;
use DateTime;

class Dashboard extends BaseController
{

    public function __construct()
    {
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $this->session = \Config\Services::session();
        $this->model_general = model('Model_general');
        $this->model_movimiento = model('Model_movimiento');
        $this->model_reporte_tarjeta = model('Model_reporte_tarjeta');
        $this->model_reporte_inspecciones = model('Model_reporte_inspecciones');
        $this->model_reporte_estadistica = model('Model_reporte_estadistica');
    }

    public function index()
    {
        if (!$this->session->get('isLogin')) {
            return redirect()->to('login');
        } else {


            $data['maintenance'] = $this->model_general->getMaintenance();
            $actividad_reciente = $this->model_movimiento->getRecentActivity();
            $data['actividad_reciente'] = $this->_modifyRecentActivity($actividad_reciente);

            # Cantidad de Observaciones Pendientes (CARD)
            // (Descargos pendientes)
            $data['obs_tarjeta_pendiente']['principal'] = $this->model_reporte_tarjeta->get_tarjeta_pendiente(session()->get('id_usuario'));
            $data['obs_tarjeta_pendiente']['hoy'] = $this->model_reporte_tarjeta->get_tarjeta_pendiente_filter(session()->get('id_usuario'), 'hoy');
            $data['obs_tarjeta_pendiente']['mes'] = $this->model_reporte_tarjeta->get_tarjeta_pendiente_filter(session()->get('id_usuario'), 'mes');
            $data['obs_tarjeta_pendiente']['year'] = $this->model_reporte_tarjeta->get_tarjeta_pendiente_filter(session()->get('id_usuario'), 'year');

            # Cantidad de Respuestas pendientes de los descargos (CARD)
            $data['rta_descargos_pendientes'] = $this->model_reporte_tarjeta->get_descargos_rta_hallazgos_pendiente(session()->get('id_usuario'));
            $data['hallazgo_totales_propios'] = $this->model_reporte_tarjeta->get_hallazgos_totales_propios(session()->get('id_usuario'));

            # Cantidad de Tarjetas M.A.S propias completadas
            $data['get_total_tarjetas_propias_cerradas'] = $this->model_reporte_tarjeta->get_total_tarjetas_propias(session()->get('id_usuario'), 0);

            # Cantidad de Tarjetas M.A.S propias en total
            $data['get_total_tarjetas_propias'] = $this->model_reporte_tarjeta->get_total_tarjetas_propias(session()->get('id_usuario'));

            # Cantidad de Inspecciones
            $data['inspecciones'] = $this->model_reporte_inspecciones->getInspecciones();
            $data['inspecciones']['hoy'] = $this->model_reporte_inspecciones->get_inspecciones_filter('hoy');
            $data['inspecciones']['mes'] = $this->model_reporte_inspecciones->get_inspecciones_filter('mes');
            $data['inspecciones']['year'] = $this->model_reporte_inspecciones->get_inspecciones_filter('year');

            # Cantidad de Estadísticas (CARD)
            $data['estadisticas']['principal'] = $this->model_reporte_estadistica->getEstadisticas();
            $data['estadisticas']['hoy'] = $this->model_reporte_estadistica->get_estadistica_pendiente_filter('hoy');
            $data['estadisticas']['mes'] = $this->model_reporte_estadistica->get_estadistica_pendiente_filter('mes');
            $data['estadisticas']['year'] = $this->model_reporte_estadistica->get_estadistica_pendiente_filter('year');

            # Gráficos de torta de las Inspecciones (Total de Inspecciones)
            $data['inspeccion_graphic_cake']['control'] = $this->model_reporte_inspecciones->getInspeccionesGraphicCake(1);
            $data['inspeccion_graphic_cake']['vehicular'] = $this->model_reporte_inspecciones->getInspeccionesGraphicCake(2);
            $data['inspeccion_graphic_cake']['obra'] = $this->model_reporte_inspecciones->getInspeccionesGraphicCake(3);
            $data['inspeccion_graphic_cake']['auditoria'] = $this->model_reporte_inspecciones->getInspeccionesGraphicCake(4);

            # Observaciones para la consecuencia en Riesgo en Seguridad y Salud (Por año)
            $data['obs_graphic_year']['aceptable'] = $this->_getObservationsGraphicPerYear(1, 1);
            $data['obs_graphic_year']['moderado'] = $this->_getObservationsGraphicPerYear(1, 2);
            $data['obs_graphic_year']['significativo'] = $this->_getObservationsGraphicPerYear(1, 3);
            $data['obs_graphic_year']['intolerable'] = $this->_getObservationsGraphicPerYear(1, 4);

            # Observaciones para la consecuencia en Riesgo en Impacto Ambiental (Por año)
            $data['obs_graphic_year']['baja'] = $this->_getObservationsGraphicPerYear(2, 5);
            $data['obs_graphic_year']['media'] = $this->_getObservationsGraphicPerYear(2, 6);
            $data['obs_graphic_year']['alta'] = $this->_getObservationsGraphicPerYear(2, 7);
            $data['obs_graphic_year']['muy_alta'] = $this->_getObservationsGraphicPerYear(2, 8);

            # Observaciones para la consecuencia en Riesgo en Seguridad y Salud (Por mes)
            $data['obs_graphic_months']['aceptable'] = $this->_getObservationsGraphic(1, 1);
            $data['obs_graphic_months']['moderado'] = $this->_getObservationsGraphic(1, 2);
            $data['obs_graphic_months']['significativo'] = $this->_getObservationsGraphic(1, 3);
            $data['obs_graphic_months']['intolerable'] = $this->_getObservationsGraphic(1, 4);

            # Observaciones para la consecuencia en Impacto Ambiental (Por mes)
            $data['obs_graphic_months']['baja'] = $this->_getObservationsGraphic(2, 5);
            $data['obs_graphic_months']['media'] = $this->_getObservationsGraphic(2, 6);
            $data['obs_graphic_months']['alta'] = $this->_getObservationsGraphic(2, 7);
            $data['obs_graphic_months']['muy_alta'] = $this->_getObservationsGraphic(2, 8);

            # Genera un arreglo del més actual, es decir => ['1 Feb', '2 Feb', ..] etc
            $nameMonth = $this->_nameMonth();
            $nameMonth = substr($nameMonth, 0, 3);
            for ($i = 1; $i <= count($data['obs_graphic_months']['aceptable']); $i++) {
                $data['month'][] = $i . ' ' . $nameMonth;
            }

            $data['all_months'] = all_months_name();
            $data['observacionesInspecciones'] = $this->model_reporte_inspecciones->getObservacionesTable();

            return template('dashboard/view', $data);
        }
    }

    /**
     * Dependiendo el movimiento registrado, va a mostrarse en las Actividades Recientes (Solamente va a mostrar 8 actividades)
     * Dependiendo la acción de esa actividad, se va a mostrar de otro color y otro tipo de contenido
     * Pueden ser actividades de alta de usuario, modificación de permisos, etc.
     */
    protected function _modifyRecentActivity($actividad)
    {
        $actividades = [];
        $comentario = '';
        foreach ($actividad as $key => $act) {

            switch ($act['id_modulo']) {
                case '1': // Usuario
                    $usuario = $this->model_general->get('usuario', $act['id_afectado']);
                    $actividad[$key]['estado'] = 'primary';
                    switch ($act['id_accion']) {
                        case '1':
                            $comentario = $act['nombre_usuario'] . ' generó un nuevo usuario <b style="color: gray; text-decoration: none;">#' . $act['id_afectado'] . '</b> ' . $usuario['nombre'] . ' ' . $usuario['apellido'];
                            break;
                        case '2':
                            break;
                        case '3':
                            $comentario = $act['nombre_usuario'] . ' editó al usuario <b style="color: gray; text-decoration: none;">#' . $act['id_afectado'] . '</b> ' . $usuario['nombre'] . ' ' . $usuario['apellido'];
                            break;
                    }
                    break;
                case '2': // Perfil
                    break;
                case '3': // Contraseña
                    break;
                case '4': // Grupos
                    break;
                case '5': // Permisos
                    $actividad[$key]['estado'] = 'primary';
                    switch ($act['id_accion']) {
                        case '1':
                            $comentario = $act['nombre_usuario'] . ' generó un nuevo permiso';
                            break;
                        case '2':
                            break;
                        case '3':
                            break;
                    }
                    break;
                case '6': // Tarjeta
                    $actividad[$key]['estado'] = 'primary';
                    switch ($act['id_accion']) {
                        case '1':
                            $comentario = 'Nueva  <a href="" style="color: gray; text-decoration: none;"><b>Tarjeta M.A.S #' . $act['id_afectado'] . '</b></a> generada por ' . $act['nombre_usuario'];
                            break;
                    }
                    break;
                case '7': // Estadística
                    $actividad[$key]['estado'] = 'primary';
                    switch ($act['id_accion']) {
                        case '1':
                            $comentario = $act['nombre_usuario'] . ' ha creado una <b style="color: gray;"><b>Estadística #' . $act['id_afectado'] . '</b></b>';
                            break;
                    }
                    break;
                case '8': // Credencial Usuario
                    $actividad[$key]['estado'] = 'primary';
                    switch ($act['id_accion']) {
                        case '1':
                            $comentario = $act['nombre_usuario'] . ' realizó un envío de credenciales';
                            break;
                    }
                    break;
                case '9': // Auditoría / Inspección
                    $actividad[$key]['estado'] = 'primary';
                    switch ($act['id_accion']) {
                        case '1':
                            $comentario = 'Nueva  <b style="color: gray; text-decoration: none;"><b>Inspección #' . $act['id_afectado'] . '</b></b> generada por ' . $act['nombre_usuario'];
                            break;
                    }
                    break;
                case '10': // Descargo
                    $actividad[$key]['estado'] = 'primary';
                    switch ($act['id_accion']) {
                        case '1':
                            $comentario = $act['nombre_usuario'] . ' realizó un <b style="color: gray;"><b>Descargo #' . $act['id_afectado'] . '</b></b> para la Tarjeta M.A.S';
                            break;
                    }
                    break;
                case '11': // Rechazar el Descargo (Inspección)
                    $actividad[$key]['estado'] = 'danger';
                    $comentario = $act['nombre_usuario'] . ' rechazó el <b href="" style="color: gray;"><b>Descargo #' . $act['id_afectado'] . '</b></b> de la Inspección';
                    break;
                case '12': // Rechazar el Descargo (Tarjeta M.A.S)
                    $actividad[$key]['estado'] = 'danger';
                    $comentario = $act['nombre_usuario'] . ' rechazó el <b style="color: gray;"><b>Descargo #' . $act['id_afectado'] . '</b></b> de la Tarjeta M.A.S';
                    break;
                case '13':  // Aceptar el Descargo (Tarjeta M.A.S)
                    $actividad[$key]['estado'] = 'success';
                    $comentario = $act['nombre_usuario'] . ' aprobó el <b href="" style="color: gray;"><b>Descargo #' . $act['id_afectado'] . '</b></b> de la Tarjeta M.A.S';
                    break;
                case '14':  // Aceptar el Descargo (Inspección)
                    $actividad[$key]['estado'] = 'success';
                    $comentario = $act['nombre_usuario'] . ' aprobó el <b href="" style="color: gray;"><b>Descargo #' . $act['id_afectado'] . '</b></b> de la Inspección';
                    break;
            }

            $fecha = new DateTime($act['fecha_hora']);
            $fecha_actual = new DateTime();

            $diferencia = $fecha_actual->diff($fecha);

            if ($diferencia->y > 0) {
                $actividad[$key]['tiempo'] = $diferencia->y . " años";
            } elseif ($diferencia->m > 0) {
                $actividad[$key]['tiempo'] = $diferencia->m != 1 ? $diferencia->m . ' meses' : $diferencia->m . ' mes';
            } elseif ($diferencia->d >= 7) {
                $semanas = floor($diferencia->d / 7);
                $actividad[$key]['tiempo'] = $semanas != 1 ? $semanas . ' sem' : $semanas . ' sem';
            } elseif ($diferencia->d > 0) {
                $actividad[$key]['tiempo'] = $diferencia->d . " días";
            } elseif ($diferencia->h > 0) {
                $actividad[$key]['tiempo'] = $diferencia->h . " hs";
            } elseif ($diferencia->i > 0) {
                $actividad[$key]['tiempo'] = $diferencia->i . " min";
            } else {
                $actividad[$key]['tiempo'] = $diferencia->s . " seg";
            }

            $actividad[$key]['comentario'] = $comentario;
        }
        return $actividad;
    }

    /**
     * Obtiene las observaciones, la cantidad que se hicieron en los días del mes actual
     * Luego, llamamos al función de ayuda _days_of_month() que me trae todos los días del mes actual
     * Y vamos iterando y comparando si la cantidad de observaciones que traje de la BD el día es igual al día del mes actual
     * entonces agrego la cantidad de observaciones en un nuevo arreglo, caso contrario, agregamos solo 0.
     */
    protected function _getObservationsGraphic($aspecto, $significancia)
    {
        $observaciones_graphic = [];
        $all_hallazgos_months =  $this->model_reporte_tarjeta->get_observaciones_graphic($aspecto, $significancia);
        $days_month = $this->_days_of_month();

        $hallazgos_cantidad = [];
        for ($i = 0; $i < count($days_month); $i++) {
            $cantidad = 0;
            for ($j = 0; $j < count($all_hallazgos_months); $j++) {
                if ($days_month[$i] == $all_hallazgos_months[$j]['dia']) {
                    $cantidad = $all_hallazgos_months[$j]['cantidad'];
                    break;
                }
            }
            $hallazgos_cantidad[] = $cantidad;
        }

        return $hallazgos_cantidad;
    }

    /**
     * Obtiene las observaciones que se realizaron en el año actual, muestra todos los meses.
     */
    protected function _getObservationsGraphicPerYear($aspecto, $significancia)
    {
        $total = $this->model_reporte_tarjeta->get_observaciones_graphic_per_year($aspecto, $significancia);
        $meses = all_months_indices();

        $hallazgos = [];
        for ($i = 1; $i <= count($meses); $i++) {
            $cantidad = 0;
            for ($j = 0; $j < count($total); $j++) {
                if ($i == $total[$j]['mes']) {
                    $cantidad = $total[$j]['cantidad'];
                    break;
                }
            }
            $hallazgos[] = $cantidad;
        }
        return $hallazgos;
    }

    /**
     * Método sencillo para traerme todos los días del mes
     */
    protected function _days_of_month()
    {
        # Cuenta el total de los días del mes
        $y = date('Y');
        $m = date('m');
        $total_days = cal_days_in_month(CAL_GREGORIAN, $m, $y);
        $days_of_month = [];
        for ($day = 1; $day <= $total_days; $day++) {
            if ($day < 10) {
                $day = 0 . $day;
            }
            $days_of_month[] = $day;
        }
        return $days_of_month;
    }

    /**
     * Método sencillo que me trae los nombres del mes (en español) dependiendo que tipo de mes sea.
     * (Es raro que solamente PHP me los traiga en inglés y no español).
     * TODO | Tal vez sea bueno ponerlo en un helper
     */
    protected function _nameMonth()
    {
        switch (date('m')) {
            case '1':
                $month = 'Enero';
                break;
            case '2':
                $month = 'Febrero';
                break;
            case '3':
                $month = 'Marzo';
                break;
            case '4':
                $month = 'Abril';
                break;
            case '5':
                $month = 'Mayo';
                break;
            case '6':
                $month = 'Junio';
                break;
            case '7':
                $month = 'Julio';
                break;
            case '8':
                $month = 'Agosto';
                break;
            case '9':
                $month = 'Septiembre';
                break;
            case '10':
                $month = 'Octubre';
                break;
            case '11':
                $month = 'Noviembre';
                break;
            case '12':
                $month = 'Diciembre';
                break;
        }
        return $month;
    }
}
