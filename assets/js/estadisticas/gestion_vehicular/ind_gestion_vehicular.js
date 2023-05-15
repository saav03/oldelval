 /*
===========================
Indices de las Estadísticas
GESTION VEHICULAR
===========================
*/

 /* == Indice de Conducción Regular == */

 const total_conductores = document.querySelectorAll('.total_conductores');

 let conductores_total_value = 0;
 let indicador_conduccion_bien = 0;
 let indicador_conduccion_regular = 0;
 let indicador_conduccion_mal = 0;

 let ind_con_bien = document.querySelector('.ind_con_bien');
 let ind_con_regular = document.querySelector('.ind_con_regular');
 let ind_con_mal = document.querySelector('.ind_con_mal');
 
 let div_ind_regular = document.querySelector('#div_ind_regular');
 let div_ind_mal = document.querySelector('#div_ind_mal');

 for (let i = 0; i < total_conductores.length; i++) {
     total_conductores[i].addEventListener('change', () => {
         let total = 0;
         for (let j = 0; j < total_conductores.length; j++) {
             total = parseInt(total) + parseInt(total_conductores[j].value);
         }

         for (let f = 0; f < total_conductores.length; f++) {
            if (total_conductores[f].classList.contains('total_bien')) {
                indicador_conduccion_bien = (parseInt(total_conductores[f].value) * 100) / parseInt(total);
                ind_con_bien.value = indicador_conduccion_bien.toFixed(2);
            }
            if (total_conductores[f].classList.contains('total_regular')) {
                indicador_conduccion_regular = (parseInt(total_conductores[f].value) * 100) / parseInt(total);
                ind_con_regular.value = indicador_conduccion_regular.toFixed(2);
                if (ind_con_regular.value > 0.3) {
                    div_ind_regular.style.display = 'block';
                } else {
                    div_ind_regular.style.display = 'none';
                }
            }
            if (total_conductores[f].classList.contains('total_mal')) {
                indicador_conduccion_mal = (parseInt(total_conductores[f].value) * 100) / parseInt(total);
                ind_con_mal.value = indicador_conduccion_mal.toFixed(2);
                if (ind_con_mal.value > 0.1) {
                    div_ind_mal.style.display = 'block';
                } else {
                    div_ind_mal.style.display = 'none';
                }
            }
         }
     });
 }
