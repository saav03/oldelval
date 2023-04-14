/*
===========================
Indices de las Estadísticas
INCIDENTES
===========================
*/

/* == (II AP) == */
const ind_cant_personal = document.getElementById("indicador_gral_1");
const ind_cant_accidentes = document.getElementById("indicador_title10");
let ii_ap = document.getElementById("indice_12");

ind_cant_accidentes.addEventListener("change", () => {
  let trabajadores_accidentados = parseInt(ind_cant_accidentes.value);
  let trabajadores_expuestos = parseInt(ind_cant_personal.value);
  let total = (trabajadores_accidentados * 1000) / trabajadores_expuestos;
  ii_ap.value = total.toFixed(2);
});
ind_cant_personal.addEventListener("change", () => {
  let trabajadores_accidentados = parseInt(ind_cant_accidentes.value);
  let trabajadores_expuestos = parseInt(ind_cant_personal.value);
  let total = (trabajadores_accidentados * 1000) / trabajadores_expuestos;
  ii_ap.value = total.toFixed(2);
});

/* == (IF AP CD) == */
const ind_acc_dias_perdidos = document.getElementById("indicador_9");
const ind_hs_hombres_trabajadas = document.getElementById("indicador_gral_2");

let if_ap_cd = document.getElementById("indice_13");

ind_hs_hombres_trabajadas.addEventListener("change", () => {
  let num_acci_dias_perdidos = parseInt(ind_acc_dias_perdidos.value);
  let horas_hombres_trabajadas = parseInt(ind_hs_hombres_trabajadas.value);
  let total = (num_acci_dias_perdidos / horas_hombres_trabajadas) * 1000000;
  if_ap_cd.value = total.toFixed(2);
});
ind_acc_dias_perdidos.addEventListener("change", () => {
  let num_acci_dias_perdidos = parseInt(ind_acc_dias_perdidos.value);
  let horas_hombres_trabajadas = parseInt(ind_hs_hombres_trabajadas.value);
  let total = (num_acci_dias_perdidos / horas_hombres_trabajadas) * 1000000;
  if_ap_cd.value = total.toFixed(2);
});

/* == (IF AAP) == */
const ind_acc_fatal = document.getElementById("indicador_8");
let if_aap = document.getElementById("indice_14");

ind_hs_hombres_trabajadas.addEventListener("change", () => {
  let accid_operativo_fatal = parseInt(ind_acc_fatal.value);
  let horas_hombres_trabajadas = parseInt(ind_hs_hombres_trabajadas.value);
  let total = (accid_operativo_fatal * 1000000) / horas_hombres_trabajadas;
  if_aap.value = total.toFixed(2);
});
ind_acc_fatal.addEventListener("change", () => {
  let accid_operativo_fatal = parseInt(ind_acc_fatal.value);
  let horas_hombres_trabajadas = parseInt(ind_hs_hombres_trabajadas.value);
  let total = (accid_operativo_fatal * 1000000) / horas_hombres_trabajadas;
  if_aap.value = total.toFixed(2);
});

/* == (IF AP SD) == */
const ind_acc_sin_dias_perdidos = document.getElementById("indicador_6");
let if_ap_sd = document.getElementById("indice_15");

for (let i = 0; i < all_ind_subts.length; i++) {
  all_ind_subts[i].addEventListener("change", () => {
    let trabajador_accid_operativo_sd = parseInt(ind_acc_sin_dias_perdidos.value);
    let horas_hombres_trabajadas = parseInt(ind_hs_hombres_trabajadas.value);
    let total =
      (trabajador_accid_operativo_sd * 1000000) / horas_hombres_trabajadas;
    if_ap_sd.value = total.toFixed(2);
  });
}

ind_hs_hombres_trabajadas.addEventListener("change", () => {
  let trabajador_accid_operativo_sd = parseInt(ind_acc_sin_dias_perdidos.value);
  let horas_hombres_trabajadas = parseInt(ind_hs_hombres_trabajadas.value);
  let total =
    (trabajador_accid_operativo_sd * 1000000) / horas_hombres_trabajadas;
  if_ap_sd.value = total.toFixed(2);
});

/* == (IG AP) == */
const ind_dias_perdidos = document.getElementById("indicador_title11");
let ig_ap = document.getElementById("indice_16");

ind_hs_hombres_trabajadas.addEventListener("change", () => {
  let cant_dias_perdidos = parseInt(ind_dias_perdidos.value);
  let horas_hombres_trabajadas = parseInt(ind_hs_hombres_trabajadas.value);
  let total = (cant_dias_perdidos * 1000000) / horas_hombres_trabajadas;
  ig_ap.value = total.toFixed(2);
});
ind_dias_perdidos.addEventListener("change", () => {
  let cant_dias_perdidos = parseInt(ind_dias_perdidos.value);
  let horas_hombres_trabajadas = parseInt(ind_hs_hombres_trabajadas.value);
  let total = (cant_dias_perdidos * 1000000) / horas_hombres_trabajadas;
  ig_ap.value = total.toFixed(2);
});

