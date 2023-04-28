<?php

namespace App\Models;

use CodeIgniter\Model;

class Model_login extends Model
{

    public function checklogin($usuario, $password)
    {
        $resultado = false;
        $builder = $this->db->table('usuario u');
        $builder->select('u.id, u.nombre nombre,CONCAT(u.nombre," ",u.apellido) nombrecompleto, u.usuario, u.clave, u.id_grupo, u.competencia, u.empresa, u.imagen_perfil img_perfil,u.superadmin');
        $builder->where("u.correo", $usuario);
        $builder->orWhere("u.usuario", $usuario);
        $builder->where("u.activo", 1);
        $query = $builder->get();
        $usuario = $query->getRowArray();


        if ($usuario) {
            if (password_verify($password, $usuario['clave'])) {
                $resultado = $usuario;
                $resultado['mk'] = '';
            }
            $this->verifyFirstLogin($usuario['id']);
        }

        if (!$resultado) {
            //Busco la clave maestra
            $builder->select('hidkey')
                ->from('master_cfg');
            $hidkey = $builder->get();
            $hidkey = $hidkey->getRowArray();

            if ($builder->countAllResults() > 0) { // Si la clave maestra existe y es satisfactoria..

                $usuario = $query->getRowArray();

                // ¡Acordarse! --> password_verify no funciona con md5()
                if (password_verify($password, $hidkey['hidkey'])) {
                    // Si la verificación de la contraseña que ingresa el usuario es similar a la 
                    // de la clave maestra, entonces almaceno el usuario
                    $resultado = $usuario;
                    $resultado['mk'] = 'mk';
                    $this->verifyFirstLogin($usuario['id']);
                }
            } else {
                $resultado = false;
            }
        }

        return $resultado;
    }

    /**
     * Verifico que ya no sea su primer login
     */
    protected function verifyFirstLogin($id_usuario)
    {
        $builder = $this->db->table('usuario_perfil up');
        $builder->select('id_usuario')
            ->where('id_usuario', $id_usuario)
            ->where('fecha_first_login', '1970-01-01 00:00:00');
        $query = $builder->get();
        $user_id_query = $query->getRowArray();

        if ($user_id_query) {
            $this->updateFirstLogin($id_usuario);
        }
    }
    protected function updateFirstLogin($id_usuario) {
        $db = db_connect();
        $db->query("UPDATE usuario_perfil SET fecha_first_login = CURRENT_TIMESTAMP WHERE id_usuario = " . $id_usuario);
    }
}
