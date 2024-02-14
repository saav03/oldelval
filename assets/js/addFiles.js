/*redom: el, mount, text, setAttr*/
class addFiles {
    constructor(container, inputName, {maxFileNumber = 3, allowedFiles = ['image', 'pdf'], hasDescriptionInput = true} = {}){
        this.container = container;
        this.inputName = inputName;
        this.maxFileNumber = maxFileNumber;
        this.allowedFiles = allowedFiles;
        this.hasDescriptionInput = hasDescriptionInput;
        this.addButton = this.createAddButton();
        this.numberOfFiles = 0;
        this.previewContainer = this.createPreviewContainer();
    }

    init(){
        /*
            -Mostrar mensaje de formatos y maximo de archivos
            -Preparar una seccion donde se vea cada adjunto agregado:
                -Vista previa
                -Descripcion (si se pusiese)
                -Boton eliminar
            -Mostrar boton nuevo adjunto
        */
       if(this.maxFileNumber <=0 || !Array.isArray(this.allowedFiles) || (Array.isArray(this.allowedFiles) && this.allowedFiles.length < 1)){
            alert("Error al renderizar addFiles: Parametros incorrectos");
       }else{
            const safeAllowedFiles = this.allowedFiles.map(type => type.toLowerCase());
            this.allowedFiles = safeAllowedFiles;
            this.createInfo();
            mount(this.container, this.previewContainer)
            this.createInput();
            mount(this.container, this.addButton);
       }
    }

    createAddButton(){
        const addButton = el("button.btn btn_add", {type: "button"}, el("span", ["Adjuntar Archivo ", el("i.fa fa-arrow-circle-up")]));
        return addButton;
    }

    createPreviewContainer(){
        const previewContainer = el("div"); //Se podria estilar
        return previewContainer;
    }

    createInfo(){
        let allowedFilesText = "";
        if(this.allowedFiles.includes("image"))
                allowedFilesText+="PNG JPG JPEG ";
            if(this.allowedFiles.includes("pdf"))
                allowedFilesText+="PDF ";
            allowedFilesText.trim();
        const infoLabel = el("p", {style: "color: black; font-size: 0.9em"}, `Formatos permitidos: ${allowedFilesText} - Maximo ${this.maxFileNumber} archivos por carga`);
        mount(this.container, infoLabel);
    }

    createInput(){
        const fileInput = el("input", {type: "file", style:"display: none", name: `${this.inputName}[]`});
        const fileContainer = el("div.fileAdderPreviewContainer", fileInput);
        fileInput.onchange = (fileInput) => {
            const input = fileInput.target;
            const file = input.files[0];
            //Verificamos que ingresen tipos aceptados
            if((this.allowedFiles.includes("pdf") && file.type.includes("application/pdf") )|| (this.allowedFiles.includes("image") && file.type.includes("image/"))){
                this.displayImage(input);
                this.createInput();
                this.numberOfFiles++;
                if(this.numberOfFiles===this.maxFileNumber){
                    this.addButton.disabled = true;
                }
            }else{
                alert("Tipo de archivo invalido");
            }
        }
        this.addButton.onclick = () => {
            fileInput.click();
        }
        mount(this.previewContainer, fileContainer);
    }

    displayImage(input){
        const file = input.files[0];
        const curPreviewContainer = input.parentElement;
        if(file){
            const reader = new FileReader();
            reader.onload = (e) => {
                const preview = this.makePreview(file.type, e);
                mount(curPreviewContainer, preview);
                if(this.hasDescriptionInput){
                    const descBox = this.makeDescriptionBox();
                    mount(curPreviewContainer, descBox);
                }
                const deleteButton = el("button.btn btn-danger fileAdderDelete", el("span", el('i.fa-solid fa-trash')));
                const deleteButtonDiv = el("div");
                deleteButton.onclick = () => {
                    this.previewContainer.removeChild(curPreviewContainer);
                    this.numberOfFiles--;
                    if(this.addButton.disabled){
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

    makeDescriptionBox(){
       return el("textarea.form-control fileAddDescription", {name: `${this.inputName}-description[]`, placeholder: "Descripción del adjunto (opcional)", rows: 2});
    }

    makePreview(fileType, reader){
        let preview;
        if(this.isPDF(fileType)){
            preview = el("img.fileAdderPDF");
            setAttr(preview, {src: `${GET_BASE_URL()}/assets/img/PDF_ADJ.png`});
            // preview = el('i.fa fa-file-pdf-o fileAddPDF');
        }else{
            preview = el("img.fileAddImage");
            setAttr(preview, {src: reader.target.result});
        }
        return preview;
    }

    isPDF(fileType){
        const safeFileType = fileType.toLowerCase();
        return safeFileType.includes("pdf");
    }

}