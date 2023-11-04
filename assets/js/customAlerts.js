//Requiere sweet alert
function showConfirmationButton() { //con el customConfirmationButton este queda obsoleto, revisar si remplazar por customConfirmationButton
	return Swal.fire({
		title: "Confirmación de carga",
		text: "¿Desea enviar este registro?",
		icon: "question",
		showCancelButton: true,
		reverseButtons: true,
		allowOutsideClick: false,
		cancelButtonColor: "#d33",
		confirmButtonColor: "#3085d6",
		confirmButtonText: "Enviar",
		cancelButtonText: "Cancelar",
	});
}

function showConfirmationButtonGV() { //con el customConfirmationButton este queda obsoleto, revisar si remplazar por customConfirmationButton
	return Swal.fire({
		title: "Confirmación de carga",
		text: "¿Desea enviar este Gerenciamiento?",
		icon: "question",
		showCancelButton: true,
		reverseButtons: true,
		allowOutsideClick: false,
		cancelButtonColor: "#d33",
		confirmButtonColor: "#3085d6",
		confirmButtonText: "Enviar",
		cancelButtonText: "Cancelar",
	});
}

function successAlert(successText = "Se completó el registro.") {
	return Swal.fire({
		title: "Registro exitoso",
		text: successText,
		icon: "success",
		allowOutsideClick: false,
	});
}

function showErrorAlert(title = "Error", text = "Ocurrió un error") {
	return Swal.fire({
		icon: "error",
		title: title,
		text: text,
	});
}

function canceledActionAlert() {
	return Swal.fire(
		"Operación Cancelada",
		"No se han realizado cambios",
		"error"
	);
}

function loadingAlert() {
	return Swal.fire({
		title: "Carga en proceso",
		text: "Por favor espere.",
		//imageUrl: ,
		imageWidth: 100,
		imageHeight: 100,
		allowOutsideClick: false,
		allowEscapeKey: false,
		allowEnterKey: false,
		showConfirmButton: false,
	});
}
//Edit tom 2021
function showDeleteConfirmationButton() {
	return Swal.fire({
		title: "Confirmar borrado",
		text: "¿Desea borrar este registro?",
		icon: "question",
		showCancelButton: true,
		reverseButtons: true,
		allowOutsideClick: false,
		cancelButtonColor: "#d33",
		confirmButtonColor: "#3085d6",
		confirmButtonText: "Borrar",
		cancelButtonText: "Cancelar",
	});
}

function showChangeConfirmationButton() {
	return Swal.fire({
		title: "Ya existe un Documento Vigente de este tipo.",
		text: "¿Seguro que desea reemplazarlo?",
		icon: "question",
		showCancelButton: true,
		reverseButtons: true,
		allowOutsideClick: false,
		cancelButtonColor: "#d33",
		confirmButtonColor: "#3085d6",
		confirmButtonText: "Reemplazar",
		cancelButtonText: "Cancelar",
	});
}

function successDeleteAlert(successText = "Se completó el borrado.") {
	return Swal.fire({
		title: "Borrado exitoso",
		text: successText,
		icon: "success",
		allowOutsideClick: false,
	});
}

function showEditConfirmationButton() {
	return Swal.fire({
		title: "Confirmar edición",
		text: "¿Desea editar este registro?",
		icon: "question",
		showCancelButton: true,
		reverseButtons: true,
		allowOutsideClick: false,
		cancelButtonColor: "#d33",
		confirmButtonColor: "#3085d6",
		confirmButtonText: "Editar",
		cancelButtonText: "Cancelar",
	});
}

function successDeleteAlert(successText = "Se completó el borrado.") {
	return Swal.fire({
		title: "Edición exitosa",
		text: successText,
		icon: "success",
		allowOutsideClick: false,
	});
}

function noDataAlert() {
	return Swal.fire({
		title: "No se registraron Adjuntos",
		text: "Esta Credencial fué registrada sin archivos adjuntos que la acompañen.",
		icon: "warning",
		allowOutsideClick: true,
	});
}
function showConfirmationButtonSubproceso() {
	return Swal.fire({
		title: "Cargar sin Subproceso",
		text: "¿Desea cargar el proceso sin Subproceso vinculado?",
		icon: "question",
		showCancelButton: true,
		reverseButtons: true,
		allowOutsideClick: false,
	})
}

function showConfirmationButtonActividad() {
	return Swal.fire({
		title: "Cargar sin Actividad",
		text: "¿Desea cargar el/los Subprocesos sin Actividad/es vinculadas?",
		icon: "question",
		showCancelButton: true,
		reverseButtons: true,
		allowOutsideClick: false,
	})
}

function showConfirmationButtonEliminarDocumento() {
	return Swal.fire({
		title: "Confirmación de Eliminación",
		text: "¿Seguro que desea eliminar este Documento?",
		icon: "warning",
		showCancelButton: true,
		reverseButtons: true,
		allowOutsideClick: false,
	})
}

function showConfirmationButtonDesactivarConductor(nombre) {
	return Swal.fire({
		title: "Confirmación de Desactivación",
		text: "¿Seguro que desea desactivar a " + nombre + "?",
		icon: "warning",
		showCancelButton: true,
		reverseButtons: true,
		allowOutsideClick: false,
	})
}

function showConfirmationButtonDesactivarUsuario(nombre) {
	return Swal.fire({
		title: "Confirmación de Desactivación",
		text: "¿Seguro que desea desactivar a " + nombre + "?",
		icon: "warning",
		showCancelButton: true,
		reverseButtons: true,
		allowOutsideClick: false,
	})
}

function showConfirmationButtonActivarUsuario(nombre) {
	return Swal.fire({
		title: "Confirmación de Reactivación",
		text: "¿Seguro que desea reactivar a " + nombre + "?",
		icon: "warning",
		showCancelButton: true,
		reverseButtons: true,
		allowOutsideClick: false,
	})
}

function showConfirmationButtonDesactivarVehiculo(nro_interno) {
	return Swal.fire({
		title: "Confirmación de Desactivación",
		text: "¿Seguro que desea desactivar el Vehiculo #" + nro_interno + "?",
		icon: "warning",
		showCancelButton: true,
		reverseButtons: true,
		allowOutsideClick: false,
	})
}
//Edit tom 21-01-22
function showCustomConfirmationButton(title = "Confirmación de carga", text = "¿Desea enviar este registro?", confirmButtonText = "Enviar") {
	return Swal.fire({
		title: title,
		text: text,
		icon: "question",
		showCancelButton: true,
		reverseButtons: true,
		allowOutsideClick: false,
		cancelButtonColor: "#d33",
		confirmButtonColor: "#3085d6",
		confirmButtonText: confirmButtonText,
		cancelButtonText: "Cancelar",
	});
}

function showConfirmationButtonAprob() { //con el customConfirmationButton este queda obsoleto, revisar si remplazar por customConfirmationButton
	return Swal.fire({
		title: "¿Seguro desea elimiar este Aprobador?",
		text: "Se eliminara solo de esta Línea de Servicios.",
		icon: "question",
		showCancelButton: true,
		reverseButtons: true,
		allowOutsideClick: false,
		cancelButtonColor: "#3085d6",
		confirmButtonColor: "#d33",
		confirmButtonText: "Eliminar",
		cancelButtonText: "Cerrar",
	});
}
function successAlertAprob(successText) {
	return Swal.fire({
		title: "Borrado Exitoso",
		text: successText,
		icon: "success",
		allowOutsideClick: false,
	});
}
function showConfirmationButtonPassword() { //con el customConfirmationButton este queda obsoleto, revisar si remplazar por customConfirmationButton
	return Swal.fire({
		title: "Confirmación de nueva contraseña",
		text: "¿Seguro que desea cambiar su contraseña?",
		icon: "question",
		showCancelButton: true,
		reverseButtons: true,
		allowOutsideClick: false,
		cancelButtonColor: "#d33",
		confirmButtonColor: "#3085d6",
		confirmButtonText: "Enviar",
		cancelButtonText: "Cancelar",
	});
}

//Giuliano. (Para personalizar casi todo) 22/09/22
function customConfirmationButton(title = "Confirmación de carga", text = "¿Desea enviar este registro?", confirmButtonText = "Enviar", cancelButtonText = "Cancelar", clases = '') {
	return Swal.fire({
		title: title,
		text: text,
		icon: "question",
		showCancelButton: true,
		customClass: clases,
		reverseButtons: true,
		allowOutsideClick: true,
		cancelButtonColor: "#d33",
		confirmButtonColor: "#3085d6",
		confirmButtonText: confirmButtonText,
		cancelButtonText: cancelButtonText,
	});
}

function customShowErrorAlert(title = "Error", text = "Ocurrió un error", clases = '') {
	return Swal.fire({
		icon: "error",
		title: title,
		customClass: clases,
		text: text,
	});
}

function customSuccessAlert(title= "Registro exitoso",successText = "Se completó el registro.", clases = '') {
	return Swal.fire({
		title: title,
		text: successText,
		icon: "success",
		customClass: clases,
		allowOutsideClick: false,
	});
}
function customCanceledActionAlert(title="Operación Cancelada",text= "No se han realizado cambios", clases = '') {
	return Swal.fire({
		title: title,
		text: text,
		customClass: clases,
		icon: "error"
});
}