/* 
REQUIRES RE:DOM.js 
         BOOTSTRAP TABLE (CSS)
         ACTION TABLE (CSS)
         CLICKABLE-ROW (SOLO SI SE ESPECIFICA UN CLICKABLE ROW)
*/
//const { el, mount } = redom;

class DynamicTableCellsEditable {
    constructor(
        container,
        {
            tableCSSClass,
            pageSize,
            getDataCallback,
            //totalEntriesNumber,
            tableHeader,
            tableCells,
            totalDataCallback,
            clickableRow,
            pager,
        } = {}
    ) {
        this.mainContainer = container;
        this.mainTable = el(`table.${tableCSSClass}`);
        this.pageSize = pageSize;
        // this.totalEntriesNumber = totalEntriesNumber;
        this.tableHeader = tableHeader;
        this.tableCells = tableCells;
        this.tableBody = null;
        //this.currentPage = 0;
        this.getDataCallback = getDataCallback;
        mount(this.mainContainer, el('div.table-responsive', { style: 'margin-bottom: 10px;' }, this.mainTable));
        this.totalDataCallback = totalDataCallback;
        this.pager = new Pager(this, {
            totalEntitiesText: pager?.totalEntitiesText,
        });
        this.selectionChange = false;
        this.filters = [];
        this.clickableRow = clickableRow;
        this.loadingIcon = new LoadingIcon(this.mainContainer);
    }
    addFilterField(DOMInput, event = "change") {
        //AddFilterField solo agrega listeners a los selectores determinados, a fin de refrescar la tabla cuando cambien.
        //La responsabilidad de que hacer con la informacion del selector en si es del respectivo getDataCallback y/o
        //totalDataCallback
        DOMInput.addEventListener(event, () => {
            this.calculateMaxPages();
            //this.refresh();
        });
    }
    init() {
        //this.currentPage = 1;
        this.calculateMaxPages();
        //this.refresh();
        mount(this.mainTable, this.generateTableHead());
    }
    calculateMaxPages() {
        this.totalDataCallback()
            .then((result) => {
                return result.status == 200 ? result.text() : 0;
            })
            .then((result) => {
                this.pager.totalEntities = result;
                this.pager.maxPages = Math.ceil(result / this.pageSize);
                this.pager.resetSelector();
                this.refresh();
            })
    }
    refresh() {
        this.loadingIcon.init();
        this.getDataCallback(
            (this.pager.currentPage - 1) * this.pageSize,
            this.pageSize
        )
            .then((result) => {
                return result.status == 200 ? result.json() : [];
            })
            .then((rowData) => {
                this.clearTable();
                this.generateTableRows(rowData);
                this.loadingIcon.end();
            });
    }
    generateTableHead(thead = null) {
        thead = thead ? thead : this.tableHeader;
        const tableHeadTh = [];
        thead.forEach((title) => {
            tableHeadTh.push(el("th", title));
        });
        const tableHead = el("thead", el("tr", tableHeadTh));
        return tableHead;
    }

    generateTableRows(rowData) {
        const rows = [];
        rowData.forEach((row) => {
            const cells = [];
            if (this.tableCells) {
                this.tableCells.forEach((cellData) => {

                    let content, tdClass = "", style;
                    if (!isObject(cellData)) {
                        content = row[cellData];
                    } else {
                        let key, styleProp, classProp;
                        key = cellData.key;
                        styleProp = cellData.style;
                        classProp = cellData.class;
                        if (key) {
                            if (isFunction(key)) {
                                content = key(row);
                            } else {
                                content = row[key];
                            }
                        } else {
                            content = "Error en el nombre de celda";
                        }

                        if (styleProp) {
                            if (isFunction(styleProp)) {
                                style = styleProp(row);
                            } else {
                                style = styleProp;
                            }
                        }

                        if (classProp) {
                            if (isFunction(classProp)) {
                                tdClass = classProp(row);
                            } else {
                                tdClass = classProp;
                            }
                        }
                    }
                    let td;
                    if (cellData.noClickableRow) {
                        td = el(`td.${tdClass}`, content);
                    } else {
                        let url;
                        if (row[this.clickableRow.secondRowId]) {
                            url = this.clickableRow.url + row[this.clickableRow.rowId] + '/' + row[this.clickableRow.secondRowId];
                        } else {
                            url = this.clickableRow.url + row[this.clickableRow.rowId];
                        }

                        td = el(`td.${tdClass} clickable-row`, content, {
                            "data-url":url,
                        });
                        $(td).mousedown(function(e){
                            //1: izquierda, 2: medio/ruleta, 3: derecho
                             if(e.which == 2) 
                                 {
                                    window.open(url)
                                 }
                         });
                    }
                    if (style) {
                        td.style = style;
                    }

                    cells.push(td);
                });
            } else {
                Object.entries(row).forEach(([key, elem]) => {
                    cells.push(el("td", elem));
                });
            }

            rows.push(
                el("tr", cells)
            );

        });
        this.tableBody = el("tbody", rows);
        mount(this.mainTable, this.tableBody);
        if (this.clickableRow) {
            initializeClickableRow();
        }
    }

    clearTable() {
        if (this.tableBody != null) {
            const { unmount } = redom;
            unmount(this.mainTable, this.tableBody);
        }
    }
}

