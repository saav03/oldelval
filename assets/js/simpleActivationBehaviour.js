//Includes Sweet Alert behaviour
//Auxiliars
 function activation(entityData, url){
    let form = new FormData();
    form.append('id', entityData.id);
    form.append('active', entityData.isActive ? 0 : 1);
    return $.ajax({
        type: "POST",
        url: url,
        data: form,
        processData: false,
        contentType: false,
        beforeSend: function(){
            loadingAlert();
        },
    });
}

//USE THIS FUNCTION IN FRONT
/* EXAMPLE IN USE (SEE usuario/add FOR IMPLEMENTATION)

*/
function addActivateHandler(buttonActivation, entityData, url, confirmButtonData, cancelButtonData) {
    buttonActivation.addEventListener('click', function(event) {
       buttonActivation.disabled = true;
       buttonActivation.classList.add('disabled');
       showActivationButton(!entityData.isActive).then((result) => {
           if(result.isConfirmed) {
             activation(entityData, url)
               .done(function(data){
                 swalWithBootstrapButtons.fire({
                      title: 'Exito',
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
           }else{
             canceledActionAlert();
             buttonActivation.disabled = false;
             buttonActivation.classList.remove("disabled");
           }
       });
    }, false);
}