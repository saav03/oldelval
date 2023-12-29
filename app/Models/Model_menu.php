<?php

namespace App\Models;

use CodeIgniter\Model;
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Php;

class model_menu extends Model
{

	protected	$table	=	'gg_menu';
	protected	$primaryKey	=	'id';

	public function getPermisosForMenu($id_usuario, $superadmin = NULL)
	{
		$result = [];
		$builder = $this->db->table('gg_menu ggm');
		$builder->select('ggm.id id, ggm.nombre, is_heading, id_menu_padre, icono, blank, ruta, js_id')
		->join('gg_permisos ggp', 'ggp.id_menu=ggm.id', 'inner')
		->join('gg_rel_usuario_permiso ggrup', 'ggrup.id_permiso=ggp.id', 'left')
		->join('usuario u', 'u.id=ggrup.id_usuario', 'left');
		if (!$superadmin) {
			$builder->where('u.id', $id_usuario)
				->where('ggrup.estado', 1);
		}
		$builder->groupBy('ggm.id')
		->orderBy('ggm.orden ASC');
		$query = $builder->get()->getResultArray();
		if (!empty($query)) {
			$result = $this->menuArray($query);
		}
		return $result;
	}

	public function getPermisosForMenuOld($id_grupos, $id_usuario, $superadmin = NULL, $lenguaje = NULL)
	{
		$result = [];
		$builder = $this->db->table('gg_menu ggm');
		$builder->select('ggm.id id, ggm.nombre, is_heading, id_menu_padre, icono, blank, ruta, js_id')
			->join('gg_rel_permiso_menu ggrpm', 'ggrpm.id_menu=ggm.id', 'inner')
			->join('gg_permisos ggp', 'ggp.id=ggrpm.id_permiso', 'inner')
			->join('gg_rel_usuario_permiso ggrup', 'ggrup.id_permiso=ggp.id', 'left')
			->join('gg_rel_permiso_grupo ggrpg', 'ggrpg.id_permiso=ggp.id', 'inner')
			->join('usuario u', 'u.id=ggrup.id_usuario', 'left')
			->where('ggp.estado', 1);
		if (!$superadmin) {
			$builder->where('ggrup.estado', 1)
				->where('ggrpg.estado', 1)
				->where('ggm.activo', 1)
				->whereIn('ggrpg.id_grupo', $id_grupos)
				->orWhere('ggrup.id_usuario', $id_usuario);
		}
		$builder->groupBy('ggm.id')
			->orderBy('ggm.orden ASC');
		/* $query = $builder->getCompiledSelect();
			echo '<pre>';
			var_dump($query);
			echo '</pre>';
			exit; */
		$query = $builder->get()->getResultArray();

		if (!empty($query)) {
			$result = $this->menuArray($query);
		}
		return $result;
	}


	private function menuArray($menu, $padre = 0)
	{
		$result = [];
		$hijos = [];
		//Obtengo los hijos directos de $padre. Seria como un Array.find((e) => {e.padre == padre}); de js
		for ($i = 0; $i < count($menu); $i++) {
			if (isset($menu[$i]['id_menu_padre']) && $menu[$i]['id_menu_padre'] == $padre) {
				$hijos[] = $menu[$i];
			}
		}
		if ($hijos) {
			for ($i = 0; $i < count($hijos); $i++) {
				$hijo = $hijos[$i];
				$hijo['children'] = [];
				$arreglo_de_hijos = $this->menuArray($menu, $hijo['id']);
				if ($arreglo_de_hijos) {
					$hijo['children'] = $arreglo_de_hijos;
				}
				$result[] = $hijo;
			}
			return $result;
		} else {
			return false;
		}
	}

	public function drawMenu($menu)
	{
		/* Tom: ADAPTADO A LA PLANTILLA DE BOOTSTRAP 5 QUE SE DIBUJA DIFERENTE*/
		$txt = '';
		foreach ($menu as $menu_item) {
			$icon = $menu_item['icono'] != '' ? 'fas fa-fw fa-' . $menu_item['icono'] : '';
			$id_padre = $menu_item['id_menu_padre'];
			$es_heading = $menu_item['is_heading'] == 1;
			$tiene_padre = !is_null($id_padre) && $id_padre != 0;
			$nombre = $menu_item['nombre'];
			$blank = $menu_item['blank'] == 1 ? 'target="_blank"' : "";
			if ($tiene_padre) {
				if ($es_heading) {
					$txt .= "<li><h6 class='nav-heading'>$nombre</h6>";
				} else {
					$txt .= "<li><a id='menu-" . $menu_item['id'] . "' class='catchable-menu' ' $blank href='" . base_url($menu_item['ruta']) . "'>$nombre</a>";
				}
			} else {
				if ($es_heading) {
					$txt .= "<li class='nav-heading' style='padding:10 0px'>$nombre</li>";
				} else {
					$txt .= "<li class='nav-item'>";
					/*SI TIENE HIJOS*/
					if ($menu_item['children']) {
						$collapse = "icons-nav-" . $menu_item['id'];
						$txt .= "<a class='nav-link collapsed' href='#' data-bs-toggle='collapse' data-bs-target='#$collapse' aria-expanded='true' aria-controls='$collapse'>";
						$txt .= "<i class='$icon' style='padding-right:10px;'></i> <span>$nombre</span>";
						$txt .= "<i class='bi bi-chevron-down ms-auto'></i>";
						$txt .= "</a>";
						$txt .= "<ul id='$collapse' class='nav-content collapse' data-bs-parent='#accordionSidebar'>"; //class='py-2 collapse-inner rounded'
						$txt .= $this->drawMenu($menu_item['children']);
						$txt .= "</ul>";
					} else {
						/*	NO TIENE HIJOS */
						$txt .= "<a class='nav-link collapsed catchable-menu-solo' id='menu-" . $menu_item['id'] . "' $blank href='" . base_url($menu_item['ruta']) . "'><i class='$icon' style='padding-right:10px;'></i> <span>$nombre</span></a>";
					}
				}
			}
		}
		return $txt;
		/*REFERENCIA LEGIBLE POR HUMANOS
				SI TIENE PADRE

					ES HEADING

						h6.collapse header

					SINO
						
						a.collapse-item href=url | 			
	
				SINO

					ES HEADING

						hr sidebar-divider
						div sidebar #nombre#

					SINO

						SI TIENE HIJOS

							a.nav-link href=#

						SINO

							a.nav-link.collapsed data-target ...
	     */
	}

	public function getPosibleParents()
	{
		// Lara 9/2/22
		//Como la plantilla no permite submenues anidados, no cualquier item de menu puede tener hijos. Solo aquellos que NO tengan padre pueden tener hijos
		$builder = $this->db->table("gg_menu");
		$builder->select("id, nombre, orden");

		$builder->where("id_menu_padre", 0);
		$builder->where("is_heading", 0);
		$builder->where("activo", 1);
		$builder->orderBy("nombre", "ASC");
		$query = $builder->get();
		return $query->getResultArray();
	}


	public function insertAndReorder($data)
	{
		//Lara 9/2/22
		$this->reorder($data['id_menu_padre'], $data['orden']);
		return $this->insertMethod($data);
	}

	public function updateAndReorder($data, $recordId)
	{
		//Lara 12/2/22
		$this->reorder($data['id_menu_padre'], $data['orden']);
		return $this->updateMethod($data, $recordId);
	}

	public function insertMethod($data)
	{ //Se llamaba insert pero coincide con un metodo de codeigniter, ahora se llama insertMethod
		//Lara 9/2/22
		$builder = $this->db->table('gg_menu');
		$builder->insert($data);
		return $this->db->insertID();
	}

	public function updateMethod($data, $recordId)
	{ //Se llamaba update pero coincide con un metodo de codeigniter, ahora se llama updateMethod
		$builder = $this->db->table('gg_menu');
		$builder->where('id', $recordId);
		$builder->update($data);
	}

	public function deactivateAndReorder($data, $recordId)
	{
		$original_data = $this->get($recordId);
		$this->reorder($original_data['id_menu_padre'], $original_data['orden'], false);
		$data['orden'] = -1;
		$this->updateMethod($data, $recordId);
	}

	protected function reorder($parentID, $startFrom, $increment = TRUE)
	{
		//Lara 9/2/22
		/*
			Esta funcion reordena los items del menu. Dado un id padre, se ordenaran todos los hijos de ese id (Solo los hijos, no los nietos ni otras descendencias)
			El reordenamiento es orden+1 a partir de orden $startFrom
			Si no existe menu con orden $startFrom nada ocurrira
		*/
		if ($increment)
			$this->db->query("UPDATE gg_menu SET orden=orden+1 WHERE id_menu_padre=$parentID AND orden >=$startFrom AND orden != -1");
		else
			$this->db->query("UPDATE gg_menu SET orden=orden-1 WHERE id_menu_padre=$parentID AND orden >=$startFrom AND orden != -1");
	}

	public function getAllPaged($offset, $tamanioPagina, $params = [])
	{
		//Lara 10/2/22
		$builder = $this->db->table('gg_menu');
		if (!$offset && !$tamanioPagina) {
			$builder->select("COUNT(*) cantidad");
		} else {
			$builder->select("gg_menu.*, submenu.nombre as nombre_submenu, CONCAT(uc.nombre,' ',uc.apellido) creador, DATE_FORMAT(gg_menu.fecha_hora, '%d/%m/%Y %H:%i') fecha_hora_format");
			$builder->limit($tamanioPagina, $offset);
		}
		$builder->join('usuario uc', 'uc.id = gg_menu.usuario', 'INNER');
		$builder->join('gg_menu submenu', 'submenu.id=gg_menu.id_menu_padre', 'LEFT');
		/*	PARAMETROS DE BUSQUEDA */

		if ($params) {
			extract($params);
			if (!is_null($name) && $name != 'gg_menu') {
				$builder->like('gg_menu.nombre', $name);
			}
			if (!is_null($submenu) && $submenu != '') {
				$builder->where('gg_menu.id_menu_padre', $submenu);
			}
			if (!is_null($separator) && $separator != '') {
				$builder->where('gg_menu.is_heading', $separator);
			}
			if (!is_null($creator) && $creator != '') {
				$builder->like('nombre_usuario', $creator);
			}
			if (!is_null($creation_date_from) && $creation_date_from != '') {
				$builder->where("gg_menu.fecha_hora >= '$creation_date_from'");
			}
			if (!is_null($creation_date_upto) && $creation_date_upto != '') {
				$builder->where("gg_menu.fecha_hora <= '$creation_date_upto'");
			}
			if (!is_null($status) && $status != '') {
				$builder->where('gg_menu.activo', $status);
			}
			if (!is_null($order_by) && $order_by != '') {
				if (is_null($order_type) || $order_type == '') {
					$order_type = "ASC";
				}
				if (in_array($order_by, ["nombre_submenu"])) {
					$builder->orderBy($order_by, $order_type);
				} else {
					$builder->orderBy("gg_menu.$order_by", $order_type);
				}
			}
		} else {
			$builder->orderBy("gg_menu.nombre", "ASC");
		}
		$resultado = $builder->get();
		return $resultado->getResultArray();
	}

	public function get($id)
	{
		//Lara 11/2/22
		$builder = $this->db->table('gg_menu');
		$builder->select("gg_menu.*, usuario.nombre as nombre_usuario, usuario.usuario, DATE_FORMAT(gg_menu.fecha_hora, '%d/%m/%Y %H:%i') fecha_hora_format");
		$builder->join("usuario", "usuario.id=gg_menu.usuario", "INNER");
		$builder->where("gg_menu.id", $id);
		$query = $builder->get();
		return $query->getRowArray();
	}

	public function getAll($active =  TRUE)
	{
		$builder = $this->db->table('gg_menu');
		$builder->select("gg_menu.*,IF(gg_menu.is_heading = 1, CONCAT(gg_menu.nombre,' (Cabecera)' ),gg_menu.nombre) nombre");
		if ($active) {
			$builder->where("gg_menu.activo", 1);
		}
		$query = $builder->get();
		return $query->getResultArray();
	}
}
