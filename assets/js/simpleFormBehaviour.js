//Includes Sweet Alert behaviour
//Auxiliars
function validar(formulario, url){
    return $.ajax({
        type: "POST",
        url: url,
        data: new FormData(formulario),
        processData: false,
        contentType: false,
        beforeSend: function(){
            loadingAlert();
        },
    });
}

const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger'
    },
    buttonsStyling: false
});

//USE THIS FUNCTION IN FRONT
/* EXAMPLE IN USE (SEE usuario/add FOR IMPLEMENTATION)

*/
function addSubmitHandler(formulario, buttonSubmitId, confirmButtonData, cancelButtonData) {
    formulario.addEventListener('submit', function(event) {
       event.preventDefault();
       const buttonSubmit = document.getElementById(buttonSubmitId);
       buttonSubmit.disabled = true;
       buttonSubmit.classList.add('disabled');
       showConfirmationButton().then((result) => {
           if(result.isConfirmed) {
           validar(formulario, formulario.action)
             .done(function(data){
               swalWithBootstrapButtons.fire({
                    title: 'Registro exitoso',
                    text: "¿Que desea hacer?",
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: confirmButtonData.text,
                    cancelButtonText:  cancelButtonData.text,
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        let confirmUrl = confirmButtonData.url;
                        if(confirmButtonData.appendResult)
                            confirmUrl+=`/${data}`;
                        window.location.replace(confirmUrl);
                    } else if (
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        let cancelUrl = cancelButtonData.url;
                        if(cancelButtonData.appendResult)
                            cancelUrl+=`/${data}`;
                        window.location.replace(cancelUrl);
                    }
                })
             })
             .fail((err, textStatus, xhr) =>{
               let errors = JSON.parse(err.responseText);
               errors = errors.join(". ");
               showErrorAlert(null, errors);
               buttonSubmit.disabled = false;
               buttonSubmit.classList.remove('disabled');
             })
         }else{
           canceledActionAlert();
           buttonSubmit.disabled = false;
           buttonSubmit.classList.remove("disabled");
         }
       })
    }, false);
}