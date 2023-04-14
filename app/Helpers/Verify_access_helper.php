<?php

/* ACCESO POR GRUPOS */
/* function acceso($grupo)
{
    if (is_null((session()->get('isLogin')))) {// Si no esta logueado falla
        $acceso = false;
    } else {
        $acceso = false;
        if (session()->get('superadmin')) { //Si es admin se saltea las condiciones y puede entrar
            $acceso = true;
        } else {
            if (is_array($grupo)) { //si es array consulta elemento por elemento hasta encontrar
                foreach ($grupo as $g) {
                    if (in_array($g, session()->get('grupos'))) { // <------------------------------ACA hay que traer la columna de id de los grupos
                        $acceso = true;
                        break;
                    } else {
                        return view('partials/header') . view('dashboard/index') . view('partials/footer');
                    }
                }
            } else { //Si es un elemento consulta si pertenece al grupo
                if (in_array($grupo, session()->get('grupos'))) {
                    $acceso = true;
                } else {
                    return view('partials/header') . view('dashboard/index') . view('partials/footer');
                }
            }
        }
    }
    return $acceso;
} */

/**
 * Busca por los permisos que tiene asignado el usuario
 * Más que nada se aplica para poder visualizar algo o no
 */
function vista_access($permiso)
{
    if (is_null((session()->get('isLogin')))) { // Si no esta logueado falla
        $acceso = false;
    } else {
        $acceso = false;
        if (session()->get('superadmin')) { //Si es admin se saltea las condiciones y puede entrar
            $acceso = true;
        } else {

            if (is_array($permiso)) { //si es array consulta elemento por elemento hasta encontrar

                foreach ($permiso as $p) {
                    if (in_array($p, session()->get('permisos_plus'))) { // <------------------------------ACA hay que traer la columna de los permisos
                        $acceso = true;
                        break;
                    } else {
                        $acceso = false;
                        // return view('partials/header') . view('dashboard/index') . view('partials/footer');
                    }
                }
            } else { //Si es un elemento consulta si pertenece al permiso
                if (in_array($permiso, session()->get('permisos_plus'))) {
                    $acceso = true;
                } else {
                    $acceso = false;
                    // return view('partials/header') . view('dashboard/index') . view('partials/footer');
                }
            }
        }
    }
    return $acceso;
}

/**
 * Busca por los permisos que tiene asignado el usuario
 */
function acceso($permiso)
{
    if (is_null((session()->get('isLogin')))) { // Si no esta logueado falla
        $acceso = false;
    } else {
        $acceso = false;
        if (session()->get('superadmin')) { //Si es admin se saltea las condiciones y puede entrar
            $acceso = true;
        } else {

            if (is_array($permiso)) { //si es array consulta elemento por elemento hasta encontrar

                foreach ($permiso as $p) {
                    if (in_array($p, session()->get('permisos_usuario'))) { // <------------------------------ACA hay que traer la columna de los permisos
                        $acceso = true; // NO FUNCIONAAA
                        break;
                    } else {
                        return redirect()->to('/');
                        $acceso = false;
                    }
                }
            } else { //Si es un elemento consulta si pertenece al permiso
                if (in_array($permiso, session()->get('permisos_usuario'))) { 
                    $acceso = true;
                } else {
                    $acceso = false;
                }
            }
        }
    }
    return $acceso;
}
