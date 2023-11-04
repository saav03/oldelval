<?php

function template($url, $data = [])
{
    if (is_null((session()->get('isLogin')))) { // Si no esta logueado reenvia a login
        return redirect()->to('login');
    } else {
        return view('partials/header') . view($url, $data) . view('partials/footer');
    }
}
