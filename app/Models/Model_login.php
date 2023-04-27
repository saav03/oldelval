<?php
namespace App\Models;

use CodeIgniter\Model;
class Model_login extends Model {
   
    public function checklogin($usuario, $password){ 
        $resultado = false;
        $builder = $this->db->table('usuario u');
        $builder->select('u.id, u.nombre nombre,CONCAT(u.nombre," ",u.apellido) nombrecompleto, u.usuario, u.clave, u.id_grupo, u.competencia, u.empresa, u.imagen_perfil img_perfil,u.superadmin');
		$builder->where("u.correo", $usuario);
		$builder->orWhere("u.usuario", $usuario);
		$builder->where("u.activo", 1);
        $query = $builder->get();
        $usuario = $query->getRowArray();
        if($usuario){
            if(password_verify($password, $usuario['clave'])){
                $resultado = $usuario;
            }
        }
        return $resultado;
    }
   }