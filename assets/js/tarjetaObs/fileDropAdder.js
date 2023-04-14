class fileDropAdder {
  constructor(
    container,
    inputName,
    galeria, {
      maxFileNumber = 3,
      allowedFiles = ["image", "pdf"],
      hasDescriptionInput = true,
    } = {}
  ) {
    this.container = container;
    this.inputName = inputName;
    this.galeria = galeria;
    this.maxFileNumber = maxFileNumber;
    this.allowedFiles = allowedFiles;
    this.hasDescriptionInput = hasDescriptionInput;
    this.files = 0;
    this.numberOfFiles = 0;
  }

  init() {
    /*
    Muestra el formato para poder arrastrar los archivos dentro desde el sistema
    */
    if (
      this.maxFileNumber <= 0 ||
      !Array.isArray(this.allowedFiles) ||
      (Array.isArray(this.allowedFiles) && this.allowedFiles.length < 1)
    ) {
      alert("Error al renderizar fileDropAdder: Parametros incorrectos");
    } else {
      const safeAllowedFiles = this.allowedFiles.map((type) =>
        type.toLowerCase()
      );
      this.allowedFiles = safeAllowedFiles;
      this.createInfo();
    }
  }

  createDivDrop() {
    let divDropArea = el("div#drop-area");

    ["dragenter", "dragover", "dragleave", "drop"].forEach((eventName) => {
      divDropArea.addEventListener(eventName, this.preventDefaults, false);
    });

    ["dragenter", "dragover"].forEach((eventName) => {
      divDropArea.addEventListener(eventName, () => {
        divDropArea.classList.add("highlight");
      });
    });

    ["dragleave", "drop"].forEach((eventName) => {
      divDropArea.addEventListener(eventName, () => {
        divDropArea.classList.remove("highlight");
      });
    });

    divDropArea.addEventListener("drop", this.handleFiles);
    return divDropArea;
  }

  preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
  }

  handleFiles(e) {
    let files = [];

    if (e.type == "drop") {
      let dt = e.dataTransfer;
      files = dt.files;
    } else {
      files.push(e.target.files[0]);
    }
    let archivos = [...files];
    /* Lo hago a la fuerza porque this.allowedFiles no me lo toma, me lo recibe como undefined */
    let archivos_permitidos = ["image", "pdf"];

    archivos.forEach((file) => {
      if (
        (archivos_permitidos.includes("pdf") &&
          file.type.includes("application/pdf")) ||
        (archivos_permitidos.includes("image") && file.type.includes("image/"))
      ) {
        if (arrayImgs.length < 3) {
          let s4 = Math.floor((1 + Math.random()) * 0x10000)
            .toString(16)
            .substring(1);
          file.id = s4;
          arrayImgs.push(file);
          let reader = new FileReader();
          reader.readAsDataURL(file);
          reader.onloadend = function () {
            let galeria = document.getElementById("gallery");
            let div = el("div.d-flex", {
              style: "width: 85%; margin: 0 auto;",
            });
            let enlaceImg = el('a', {href: GET_BASE_URL() + "/uploads/tarjetaObs/"+reader.result});
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
            let btnDel = el("button.btnDel", {
              "data-id": s4
            }, "Eliminar");

            btnDel.onclick = (e) => {

              e.preventDefault();
              btnDel.parentElement.remove();
              for (let i = 0; i < arrayImgs.length; i++) {
                if (arrayImgs[i].id == btnDel.getAttribute("data-id")) {
                  arrayImgs.splice(i, 1); // 1 es la cantidad de elemento a eliminar
                }
              }

            };
            mount(div, img);
            mount(div, textarea);
            mount(div, btnDel);
            mount(galeria, div);
          };
        } else {
          customShowErrorAlert('¡Error!', '¡Máximo 3 archivos por carga!', 'swal_edicion');
        }
        
      } else {
        customShowErrorAlert('¡Error!', '¡Tipo de archivo invalido!', 'swal_edicion');
      }
    });
  }

  createInfo() {
    let allowedFilesText = "";
    if (this.allowedFiles.includes("image"))
      allowedFilesText += "PNG JPG JPEG ";
    if (this.allowedFiles.includes("pdf")) allowedFilesText += "PDF ";
    allowedFilesText.trim();

    const infoLabel = el(
      "p", {
        style: "color: black; font-size: 0.9em"
      },
      `Formatos permitidos: ${allowedFilesText} - Maximo ${this.maxFileNumber} archivos por carga`
    );

    let divDropArea = this.createDivDrop();
    let icono = el("i.fas fa-upload");
    let h5 = el("h5", "Subir Archivo");
    let a = el(
      "a", {
        style: "text-decoration: underline; cursor:pointer;"
      },
      "sube un archivo"
    );
    a.addEventListener("click", () => {
      const fileInput = el("input", {
        type: "file",
        style: "display: none"
      });

      fileInput.click();
      fileInput.addEventListener("change", this.handleFiles);
    });
    let pArrastre = el(
      "p", {
        style: "font-size: 14px;"
      },
      "Arrastra un archivo hasta aquí o ",
      a
    );
    let inputGenerico = el("input#fileElem", {
      type: "file",
      multiple: "true",
      name: "fileElem",
    });
    mount(this.container, infoLabel);

    mount(divDropArea, icono);
    mount(divDropArea, h5);
    mount(divDropArea, pArrastre);
    mount(divDropArea, inputGenerico);

    mount(this.container, divDropArea);
  }
}