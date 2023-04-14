let dropArea = document.getElementById("drop-area");

/* == Con esto evitamos que los eventos se comporten distintos (Como generar un submit por ejemplo) == */
["dragenter", "dragover", "dragleave", "drop"].forEach((eventName) => {
  // dropArea.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
  e.preventDefault();
  e.stopPropagation();
}

/* == Se le agregan estilos una vez que estemos arrastrando archivos para darle la sensación al cliente que agregó un archivo == */
["dragenter", "dragover"].forEach((eventName) => {
  // dropArea.addEventListener(eventName, highlight, false);
});
["dragleave", "drop"].forEach((eventName) => {
  // dropArea.addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
  dropArea.classList.add("highlight");
}

function unhighlight(e) {
  dropArea.classList.remove("highlight");
}

/* == Eliminación de Archivos == */
let cantidad = 0;
let maxFiles = 3;

function handleDrop(e) {
  let dt = e.dataTransfer;
  let files = dt.files;
  handleFiles(files);
}

dropArea.addEventListener("drop", handleDrop, false);

/**
 * Convierte los archivos en una matriz o array para poder iterar sobre todos esos archivos
 */
function handleFiles(files) {
  cantidad = cantidad + 1;
  if (cantidad <= maxFiles) {
    files = [...files];
    // files.forEach(uploadFile)
    files.forEach(previewFile);
  } else {
    alert('La cantidad supera a los tres archivos');
  }
}

/**
 * Creamos una previsualización junto con su descripcióm
 */
function previewFile(file) {
  let reader = new FileReader();
  reader.readAsDataURL(file);
  reader.onloadend = function () {
    let galeria = document.getElementById("gallery");
    let div = el("div.d-flex", {
      style: "width: 85%; margin: 0 auto;"
    });
    let img = el("img", {
      src: reader.result
    });
    let textarea = el("textarea.form-control sz_inp", {
      name: "files-description[]",
      id: "img_description",
      cols: "30",
      rows: "3",
      style: "height:120px;",
      placeholder: "Ingrese una breve descripción del adjunto (Opcional)",
    });
    let btnDel = el("button.btnDel", {}, "Eliminar");
    mount(div, img);
    mount(div, textarea);
    mount(div, btnDel);
    mount(galeria, div);
    // img.src = reader.result
    // document.getElementById('gallery').appendChild(img)
  };
}

/**
 * Subir archivos adjuntos a la BD (Capaz que se use en el submit de toda la inspección entera)
 */
function uploadFile(file) {
  let url = "YOUR URL HERE";
  let formData = new FormData();

  formData.append("file", file);

  fetch(url, {
      method: "POST",
      body: formData,
    })
    .then(() => {
      /* Done. Inform the user */
    })
    .catch(() => {
      /* Error. Inform the user */
    });
}