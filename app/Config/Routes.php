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

/* == Auditorías == */
$routes->match(['get', 'post'],'/auditoria/add','Auditorias::add');
$routes->match(['get', 'post'],'/auditoria/addPlanilla','Auditorias::addPlanilla');

#B
#C
#D
//DASHBOARD
$routes->match(['get', 'post'],'/dashboard','Dashboard::index');
#E
//ESTADÍSTICAS INCIDENTES
$routes->get('/estadisticas','Estadisticas::index');
$routes->get('/estadisticas/view/(:num)/(:num)','Estadisticas::view/$1/$2');
$routes->post('/estadisticas/changeState/(:num)','Estadisticas::changeState/$1');

$routes->match(['get', 'post'],'/estadisticas/addNewTipo','Estadisticas::add_planilla_tipo');
$routes->match(['get', 'post'],"/addNewFormulario",'Estadisticas::cargarTipoPlanilla');
$routes->match(['get', 'post'],"/addFormulario",'Estadisticas::addFormulario');
$routes->match(['get', 'post'],'/api/estadisticas/get/(:num)/(:num)','Estadisticas::getPaged/$1/$2');
$routes->match(['get', 'post'],'/api/estadisticas/getTotal/','Estadisticas::getPaged');
$routes->post('/Estadistica/submit/','Estadisticas::submitEstadistica');

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
$routes->match(['get', 'post'],'/api/ingresos/get/(:num)/(:num)','Perfil::getPaged/$1/$2');
$routes->match(['get', 'post'],'/api/ingresos/getTotal/','Perfil::getPaged');
$routes->get('/perfil/view/(:num)','Perfil::view/$1');
$routes->post('/perfil/editarPermisosUsuario','Perfil::editarPermisosUsuario');

//PERMISOS
$routes->get('/permisos','Permisos::index');
$routes->get('/permisos/add','Permisos::add');
$routes->match(['get', 'post'],'/permisos/getData/(:num)','Permisos::getDataPermiso/$1');
$routes->match(['get', 'post'],'/api/permisos/get/(:num)/(:num)','Permisos::getPaged/$1/$2');
$routes->match(['get', 'post'],'/api/permisos/getTotal/','Permisos::getPaged');
$routes->post('/permisos/addNewPermission','Permisos::submit');
$routes->post('/permisos/editPermission','Permisos::edit');
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
$routes->post('/getPermisos','Usuario::getAllPermisosGroup');
$routes->post('/getPermissionsGrousUsers','Usuario::getAllPermisosGroupAndUsers');
$routes->post('/getAllPermisosUser','Usuario::getAllPermisosUser');
$routes->match(['get', 'post'],"/addNewUser",'Usuario::add');
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
$routes->get('/TarjetaObs/view_obs/(:num)','TarjetaObservaciones::view/$1');

// Esta solo la hago para testear los correos como se visualizan
$routes->get('/TarjetaObs/testing','TarjetaObservaciones::testing');
// POST
$routes->post('/TarjetaObs/getModulosFilter/','TarjetaObservaciones::getModulosFilter');
$routes->post('/TarjetaObs/getEstacionesFilter/','TarjetaObservaciones::getEstacionesFilter');
$routes->post('/TarjetaObs/submit/','TarjetaObservaciones::submitTarjeta');
$routes->post('/TarjetaObs/submitDescargo/','TarjetaObservaciones::submitDescargo');
$routes->post('/TarjetaObs/submitRtaDescargo/','TarjetaObservaciones::submitRtaDescargo');
$routes->post('/TarjetaObs/submitCerrarObs/','TarjetaObservaciones::submitCerrarObs');

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
