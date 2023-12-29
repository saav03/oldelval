/* == Subir archivos en Estadísticas Incidentes == */

class uploadFile {
    constructor(container, inputName, {
        maxFileNumber = 3,
        allowedFiles = ['image', 'pdf', 'xlsx', 'docx'],
        hasDescriptionInput = true
    } = {}) {
        this.container = container;
        this.inputName = inputName;
        this.maxFileNumber = maxFileNumber;
        this.allowedFiles = allowedFiles;
        this.hasDescriptionInput = hasDescriptionInput;
        this.addButton;
        this.numberOfFiles = 0;
        this.previewContainer = this.createPreviewContainer();
    }

    init() {
        if (this.maxFileNumber <= 0 || !Array.isArray(this.allowedFiles) || (Array.isArray(this.allowedFiles) && this.allowedFiles.length < 1)) {
            alert("Error al renderizar fileAdder: Parametros incorrectos");
        } else {
            const safeAllowedFiles = this.allowedFiles.map(type => type.toLowerCase());
            this.allowedFiles = safeAllowedFiles;
            mount(this.container, this.previewContainer)
            this.createInfo();
            this.createInput();
            // mount(this.container, this.addButton);
        }
    }

    createPreviewContainer() {
        const previewContainer = el("div"); //Se podria estilar
        return previewContainer;
    }

    createInfo() {
        let allowedFilesText = "";
        if (this.allowedFiles.includes("image"))
            allowedFilesText += ".png .jpg .pdf .xlsx .docx";
        allowedFilesText.trim();
        let divRow = el('div.row');
        let divAdjFile = el('div.d-flex adj_file');
        let div;
        div = el('div.div_img_clip');
        let iconUpload = el('i.fa-solid fa-cloud-arrow-up');

        mount(div, iconUpload);
        mount(divAdjFile, div);

        div = el('div.adjuntar_archivo');
        let parrafo;
        this.addButton = el('p', 'Adjuntar Archivo');
        mount(div, this.addButton);
        mount(divAdjFile, div);

        parrafo = el('p.mt-2', `Formatos permitidos: ${allowedFilesText} - Maximo ${this.maxFileNumber} archivos por carga`);

        mount(divRow, divAdjFile);
        mount(divRow, parrafo);

        mount(this.container, divRow);
    }

    createInput() {
        const fileInput = el("input", {
            type: "file",
            style: "display: none",
            name: `${this.inputName}[]`
        });
        const fileContainer = el("div.fileAdderPreviewContainer", fileInput);
        fileInput.onchange = (fileInput) => {
            const input = fileInput.target;
            const file = input.files[0];
            //Verificamos que ingresen tipos aceptados

            if ((this.allowedFiles.includes("pdf") && file.type.includes("application/pdf")) || file.type.includes('xml') || file.type.includes('excel') || file.type.includes('word') ||  (this.allowedFiles.includes("image") && file.type.includes("image/"))) {
                if (this.numberOfFiles == this.maxFileNumber) {
                    alert('Supera la cantidad maxima de archivos')
                } else {
                    this.numberOfFiles++;
                    let preview = this.makePreview(input);
                    mount(fileContainer, preview);
                    this.createInput();
                }
            } else {
                alert("Tipo de archivo inválido");
            }
        }
        this.addButton.onclick = () => {
            fileInput.click();
        }
        mount(this.previewContainer, fileContainer);
    }

    makePreview(input) {
        const file = input.files[0];
        const curPreviewContainer = input.parentElement;
        let permissionFiles = ['application/pdf'];
        let divContainerAdj = el('div.row align-items-center mb-3', {
            style: 'box-shadow: 0px 0px 6px 0px rgb(239 236 236); border-radius: 10px;'
        });
        let divGral = el('div.d-flex col-xs-12 col-md-10 justify-content-between');
        let div = el('div.d-flex justify-content-evenly align-items-center');
        let imgClip;
        imgClip = el('img.img_clip', {
            // src: `${GET_BASE_URL()}/assets/img/PDF.svg`,
            style: 'margin-right: 20px;',
            alt: 'Adjunto imagen'
        });

        if (file.type.includes('pdf')) {
            imgClip.src = `${GET_BASE_URL()}/assets/img/PDF.svg`;
        } else if (file.type.includes('xml') || file.type.includes('excel')) {
            imgClip.src = `${GET_BASE_URL()}/assets/img/Excel.svg`;
        } else if (file.type.includes('word')) {
            imgClip.src = `${GET_BASE_URL()}/assets/img/Word.svg`;
        }

        let parrafo = el('p.m-0', file.name);

        mount(div, imgClip);
        mount(div, parrafo);

        let textarea = el('textarea.form-control sz_inp', {
            cols: '30',
            rows: '2',
            name: 'adj_desc[]',
            placeholder: 'Ingrese una breve descripción (Opcional)',
            style: 'width: 70%;border: 1px solid #f3f3f3;'
        });

        mount(divGral, div);
        mount(divGral, textarea);

        mount(divContainerAdj, divGral);

        divGral = el('div.col-xs-12 col-md-2 text-center', {
            title: 'Eliminar Adjunto'
        });
        let btn = el('button.btn-del_inc', el('i.fa-solid fa-trash-can'));

        btn.onclick = (e) => {
            e.preventDefault();
            curPreviewContainer.remove();
            this.numberOfFiles--;
        };

        mount(divGral, btn);
        mount(divContainerAdj, divGral);
        return divContainerAdj;
        // mount(this.previewContainer, divContainerAdj);

    }

    displayImage(input) {
        const file = input.files[0];
        const curPreviewContainer = input.parentElement;
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const preview = this.makePreview(file.type, e);
                mount(curPreviewContainer, preview);
                if (this.hasDescriptionInput) {
                    const descBox = this.makeDescriptionBox();
                    mount(curPreviewContainer, descBox);
                }
                const deleteButton = el("button.btn btn-danger fileAdderDelete", el("span", ["", el("i.fa fa-times")]));
                const deleteButtonDiv = el("div");
                deleteButton.onclick = () => {
                    this.previewContainer.removeChild(curPreviewContainer);
                    this.numberOfFiles--;
                    if (this.addButton.disabled) {
                        this.addButton.disabled = false;
                    }
                };
                // mount(curPreviewContainer, deleteButtonDiv);
                // mount(deleteButtonDiv, deleteButton);
                mount(curPreviewContainer, deleteButton);
            }
            reader.readAsDataURL(file);
        }
    }

    makeDescriptionBox() {
        return el("textarea.form-control fileAdderDescription", {
            name: `${this.inputName}-description[]`,
            placeholder: "Descripción del adjunto (opcional)",
            rows: 2
        });
    }

}