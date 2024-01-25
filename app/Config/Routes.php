<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Login');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * ------------------------------------------------------------------a--
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
//$routes->get('/', 'Home::index');


    ###                         ###
### -> Respetar orden alfabetico <- ###
    ###                         ###

#0-9
#A    
//ACCESOS
$routes->get('accessList','Usuario::ingresos');
$routes->match(['get', 'post'],'/api/access/get/(:num)/(:num)','Usuario::getPagedAccess/$1/$2');
$routes->match(['get', 'post'],'/api/access/getTotal/','Usuario::getPagedAccess');
$routes->match(['get', 'post'],'/api/auditorias/getBloqueAud/(:num)','Auditorias::getBloqueAud/$1');

/* == Auditorías == */
$routes->get('/auditorias','Auditorias::index');
$routes->post('/auditorias/submitDescargo','Auditorias::submitDescargo');
$routes->post('/auditorias/submitRtaDescargo','Auditorias::submitRtaDescargo');
$routes->post('/auditorias/submitCloseObs','Aud_Control::submitCerrarHallazgo');

$routes->post('/auditorias/changeState/(:num)/(:num)','Auditorias::changeState/$1/$2');

$routes->get('/auditorias/testing','Auditorias::testing');

$routes->match(['get', 'post'],'/auditoria/add','Auditorias::add');
$routes->match(['get', 'post'],'/auditoria/addPlanilla','Auditorias::addPlanilla');
$routes->match(['get', 'post'],'/Auditorias/submit','Auditorias::submitCrearNewAuditoria');
$routes->match(['get', 'post'],'/Auditorias/autocomplete','Auditorias::autocomplete_responsable');
$routes->post('/Auditorias/submitUpload/','Auditorias::submitUpload');

/* == Auditorías Control == */
$routes->post('/audcontrol/submitPlanilla','Aud_Control::submitPlanilla');
$routes->match(['get', 'post'],'/api/auditorias/getControl/(:num)/(:num)','Aud_Control::getPagedControl/$1/$2');
$routes->match(['get', 'post'],'/api/auditorias/getTotalControl/','Aud_Control::getPagedControl');
$routes->get('/auditorias/view_aud_control/(:num)','Aud_Control::view_aud_control/$1');
$routes->get('/pdf/auditoria_control/(:num)','Aud_Control::view_aud_control_pdf/$1');
/* == Auditorías Vehicular == */
$routes->post('/audvehicular/submitPlanilla','Aud_Vehicular::submitPlanilla');
$routes->match(['get', 'post'],'/api/auditorias/getVehicular/(:num)/(:num)','Aud_Vehicular::getPagedVehicular/$1/$2');
$routes->match(['get', 'post'],'/api/auditorias/getTotalVehicular/','Aud_Vehicular::getPagedVehicular');
$routes->get('/auditorias/view_aud_vehicular/(:num)','Aud_Vehicular::view_aud_vehicular/$1');
$routes->get('/pdf/auditoria_vehicular/(:num)','Aud_Vehicular::view_aud_vehicular_pdf/$1');


/* == Auditorías Tarea de Campo == */
$routes->post('/audtarea_de_campo/submitPlanilla', 'Aud_tarea_de_campo::submitPlanilla');
$routes->match(['get', 'post'], '/api/auditorias/get_tarea_campo/(:num)/(:num)', 'Aud_tarea_de_campo::getPaged_tarea_de_campo/$1/$2');
$routes->match(['get', 'post'], '/api/auditorias/getTotal_tarea_campo/', 'Aud_tarea_de_campo::getPaged_tarea_de_campo');
$routes->get('/auditorias/view_aud_tarea_campo/(:num)', 'Aud_tarea_de_campo::view_aud_tarea_campo/$1');
$routes->get('/pdf/auditoria_tarea_de_campo/(:num)', 'Aud_tarea_de_campo::view_aud_tarea_de_campo_pdf/$1');

/* == Auditorías Auditorias== */
$routes->post('/aud_auditoria/submitPlanilla', 'Aud_Auditoria::submitPlanilla');
$routes->match(['get', 'post'], '/api/auditorias/get_auditoria/(:num)/(:num)', 'Aud_Auditoria::getPaged_auditoria/$1/$2');
$routes->match(['get', 'post'], '/api/auditorias/getTotal_auditoria/', 'Aud_Auditoria::getPaged_auditoria');
$routes->get('/auditorias/view_aud_auditoria/(:num)', 'Aud_Auditoria::view_aud_auditoria/$1');
$routes->get('/pdf/auditoria_auditoria/(:num)', 'Aud_Auditoria::view_aud_auditoria_pdf/$1');

/* == Auditorías Edición == */
$routes->get('auditorias/planillas', 'Auditorias::getAllAuds');
$routes->get('auditorias/planillas/(:num)', 'Auditorias::getAudEdition/$1');

# Edición Auditoría 
$routes->match(['get', 'post'],'/api/auditorias/getControlEdicion/(:num)/(:num)','Aud_Control::getPagedEdicionControl/$1/$2');
$routes->match(['get', 'post'],'/api/auditorias/getTotalControlEdicion/','Aud_Control::getPagedEdicionControl');
$routes->post('/auditoria/submitEdicionPlanilla', 'Auditorias::submitEdicionPlanilla');



#B
#C
#D
//DASHBOARD
$routes->match(['get', 'post'],'/dashboard','Dashboard::index');
#E

// ESTADISTICA GENERAL
$routes->post('/estadisticas/getModulosFilter/','Estadisticas::getModulosFilter');

/* ¿Por qué no puedo filtrar las estaciones con el controlador de Estadísticas? */
$routes->post('/TarjetaObs/getEstacionesFilter/','TarjetaObservaciones::getEstacionesFilter');

//ESTADÍSTICAS INCIDENTES
$routes->get('/estadisticas','Estadisticas::index');
$routes->get('/api/estadisticas/getYearsPeriod/(:num)','Estadisticas::getYearsPeriod/$1');

$routes->get('/estadisticas/view/(:num)/(:num)','Estadisticas::view/$1/$2');
$routes->post('/estadisticas/changeState/(:num)','Estadisticas::changeState/$1');

$routes->match(['get', 'post'],'/estadisticas/addNewTipo','Estadisticas::add_planilla_tipo');
$routes->match(['get', 'post'],"/addNewFormulario",'Estadisticas::cargarTipoPlanilla');
$routes->match(['get', 'post'],"/addFormulario",'Estadisticas::addFormulario');
$routes->match(['get', 'post'],'/api/estadisticas/get/(:num)/(:num)','Estadisticas::getPaged/$1/$2');
$routes->match(['get', 'post'],'/api/estadisticas/getTotal/','Estadisticas::getPaged');

$routes->post('/Estadistica/submit/','Estadisticas::submitEstadistica');
$routes->post('/Estadistica/submitIndiceIFAAP/','Estadisticas::submitIndiceIFAAP');

// ESTADÍSTICAS GESTION VEHICULAR
$routes->match(['get', 'post'],'/addGestionVehicular','Estadisticas::addGestionVehicular');

// ESTADÍSTICAS CAPACITACIONES
$routes->match(['get', 'post'],'/addCapacitaciones','Estadisticas::addCapacitaciones');



#F
#G
//GRUPOS
$routes->get('grupo','Grupo::index');
$routes->match(['get', 'post'],'/api/groups/get/(:num)/(:num)','Grupo::getPaged/$1/$2');
$routes->match(['get', 'post'],'/api/groups/getTotal/','Grupo::getPaged');
$routes->match(['get', 'post'],"/addNewGroup",'Grupo::add');
$routes->get('/Grupo/view/(:num)','Grupo::view/$1');
#H
#I
//INDEX
//$routes->get('/oldelval', 'Dashboard::index');
$routes->get('/', 'Dashboard::index');
#J
#K
#L
//LOGIN
$routes->get('login', 'Login::index');
$routes->match(['get', 'post'],'/Login/checklogin','Login::checklogin');
$routes->post('/cambio_contrasena','Login::index');
$routes->get('/reset_password','Login::index');
$routes->get('logout','Login::logout');
#M
//MOVIMIENTOS
$routes->get('movimientos','Movimiento::index');
$routes->match(['get', 'post'],'/api/movimientos/get/(:num)/(:num)','Movimiento::getPaged/$1/$2');
$routes->match(['get', 'post'],'/api/movimientos/getTotal/','Movimiento::getPaged');

//MENU
$routes->get('/menu','Menu::index');
$routes->get('/Menu/view/(:num)','Menu::view/$1');
$routes->get('/Menu/viewAdd','Menu::viewAdd');
$routes->post('/Menu/add','Menu::add');
$routes->post('/Menu/activation','Menu::activation');
$routes->match(['get', 'post'],'/api/menu/get/(:num)/(:num)','Menu::getPaged/$1/$2');
$routes->match(['get', 'post'],'/api/menu/getTotal/','Menu::getPaged');
$routes->match(['get', 'post'],'/api/menu/edit/','Menu::edit');
#N
#O
#P
//PERFIL
$routes->get('/perfil','Perfil::index');
$routes->match(['get', 'post'],'/api/ingresos/get/(:num)/(:num)','Perfil::getPagedPerfil/$1/$2');
$routes->match(['get', 'post'],'/api/ingresos/getTotal/','Perfil::getPagedPerfil');
$routes->get('/perfil/view/(:num)','Perfil::view/$1');
$routes->post('/perfil/editarPermisosUsuario','Perfil::editarPermisosUsuario');
$routes->post('/perfil/editUser','Perfil::edit');

//PERMISOS
$routes->get('/permisos','Permisos::index');
$routes->get('/permisos/add','Permisos::add');
$routes->get('/permisos/edit/(:num)','Permisos::edit/$1');
$routes->match(['get', 'post'],'/permisos/getData/(:num)','Permisos::getDataPermiso/$1');
$routes->match(['get', 'post'],'/api/permisos/get/(:num)/(:num)','Permisos::getPaged/$1/$2');
$routes->match(['get', 'post'],'/api/permisos/getTotal/','Permisos::getPaged');
$routes->post('/permisos/addNewPermission','Permisos::submit');
$routes->post('/permisos/editPermission','Permisos::editPermission');
$routes->post('/addNewPermissionGroup','Permisos::addGroupPermission');
$routes->post('/Permisos/disablePermission','Permisos::disable');
$routes->post('/Permisos/enablePermission','Permisos::enable');

#Q
#R
#S
#T
#U
//USUARIOS
$routes->get('Usuario','Usuario::index');
$routes->get('/new_user','Usuario::addView');
$routes->get('/Usuario/view/(:num)','Usuario::view/$1');
$routes->match(['get', 'post'],'/api/ingresos_user/get/(:num)/(:num)/(:num)','Usuario::getPagedUsuario/$1/$2/$3');
$routes->match(['get', 'post'],'/api/ingresos_user/getTotal/(:num)','Usuario::getPagedUsuario/$1');
$routes->post('/getPermisos','Usuario::getAllPermisosGroup');
$routes->post('/getPermissionsGrousUsers','Usuario::getAllPermisosGroupAndUsers');
$routes->post('/getAllPermisosUser','Usuario::getAllPermisosUser');
$routes->match(['get', 'post'],"/addNewUser",'Usuario::add');
$routes->match(['get', 'post'],"/sendCredentials",'Usuario::sendCredentials');
$routes->match(['get', 'post'],"/usuario/getResponsables/(:num)",'Usuario::getResponsables/$1');
$routes->match(['get', 'post'],"Usuario/editUser",'Usuario::edit');
// $routes->match(['get', 'post'],"/getPermisos",'Usuario::getAllPermisosGroup');
$routes->get('/all_users','Usuario::index');
$routes->match(['get', 'post'],'/api/usuarios/get/(:num)/(:num)','Usuario::getPaged/$1/$2');
$routes->match(['get', 'post'],'/api/usuarios/getTotal/','Usuario::getPaged');
$routes->post('/Usuario/changeStateUser/(:num)','Usuario::changeStateUser/$1');
$routes->post('/usuario/editarPermisosUsuario','Usuario::editarPermisosUsuario');


// TARJETAS DE OBSERVACIONES
$routes->get('/TarjetaObs','TarjetaObservaciones::index');
$routes->get('/TarjetaObs/add_obs','TarjetaObservaciones::view_add_obs');
$routes->match(['get', 'post'],'/api/TarjetaObs/get/(:num)/(:num)','TarjetaObservaciones::getPaged/$1/$2');
$routes->match(['get', 'post'],'/api/TarjetaObs/getTotal/','TarjetaObservaciones::getPaged');

// Esta solo la hago para testear los correos como se visualizan
$routes->get('/TarjetaObs/testing/(:num)/(:alphanum)','TarjetaObservaciones::testing/$1/$2');
// POST
$routes->post('/TarjetaObs/getModulosFilter/','TarjetaObservaciones::getModulosFilter');
$routes->post('/TarjetaObs/getEstacionesFilter/','TarjetaObservaciones::getEstacionesFilter');
$routes->post('/TarjetaObs/submit/','TarjetaObservaciones::submitTarjeta');
$routes->post('/TarjetaObs/submitDescargo/','TarjetaObservaciones::submitDescargo');
$routes->post('/TarjetaObs/submitRtaDescargo/','TarjetaObservaciones::submitRtaDescargo');
$routes->post('/TarjetaObs/submitCerrarObs/','TarjetaObservaciones::submitCerrarObs');
$routes->get('/TarjetaObs/view_obs/(:num)','TarjetaObservaciones::view/$1');

#V
#X
#Y
#Z

//CUALQUIER OTRO
$routes->match(['get','post'],'(:any)', 'Dashboard::index'); //Siempre al final



/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
