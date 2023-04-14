/* 
REQUIRES RE:DOM.js 
         BOOTSTRAP TABLE (CSS)
         ACTION TABLE (CSS)
         CLICKABLE-ROW (SOLO SI SE ESPECIFICA UN CLICKABLE ROW)
*/

class DynamicTable {
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
		mount(this.mainContainer, this.mainTable);
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
				this.tableCells.forEach((cellName) => {
					cells.push(
						el(
							"td",
							row[cellName]
              )
						);
				});
			} else {
				Object.entries(row).forEach(([key, elem]) => {
					cells.push(el("td", elem));
				});
			}
			if (this.clickableRow) {
				rows.push(
					el("tr.clickable-row", cells, {
						"data-url": this.clickableRow.url + row[this.clickableRow.rowId],
					})
				);
			} else {
				rows.push(el("tr", cells)); //Dejar esta linea y borrar el if else
			}
		});
		this.tableBody = el("tbody", rows);
		mount(this.mainTable, this.tableBody);
		if (this.clickableRow) { 
			initializeClickableRow(); //borrar el if y dejar esta linea
		}
	}

	/*
  generateTableRows(rowData) {
    const rows = [];
    rowData.forEach((row) => {
      const cells = [];
      Object.entries(row).forEach(([key, elem]) => {
        
        cells.push(el("td", elem));
      });
      if(this.clickableRow){
        rows.push(el("tr.clickable-row", cells, {'data-url': this.clickableRow.url+row[this.clickableRow.rowId]}));
      }else{
        rows.push(el("tr", cells));
      }
    });
    this.tableBody = el("tbody", rows);
    mount(this.mainTable, this.tableBody);
    if(this.clickableRow){
      initializeClickableRow();
    }
  }
  */
	clearTable() {
		if (this.tableBody != null) {
			const { unmount } = redom;
			unmount(this.mainTable, this.tableBody);
		}
	}
}

class Pager {
	constructor(dynamicTable, { totalEntitiesText } = {}) {
		this.maxPages = 0;
		this.currentPage = 1;
		this.totalEntities = 0;
		this.totalEntitiesText = totalEntitiesText
			? totalEntitiesText
			: "Total Rows";
		const totalEntitiesContainer = el(".total-entities-container");
		const prevButton = el("a", "Anterior");
		const prev = el("li", prevButton);
		const nextButton = el("a", "Siguiente");
		const next = el("li", nextButton);
		const selector = el("select.form-control", {'style': 'font-size: 13px;'});
		const ul = el("ul.paginator", [prev, selector, next]);
		const div = el(".paginator-container", [totalEntitiesContainer, ul]);
		this.prevButton = prevButton;
		this.nextButton = nextButton;
		this.selector = selector;
		this.domElement = div;
		this.parentTable = dynamicTable;
		this.totalEntitiesContainer = totalEntitiesContainer;
		mount(dynamicTable.mainContainer, div);
		//OBSERVERS
		prevButton.addEventListener("click", () => {
			if (this.currentPage > 1) {
				this.currentPage--;
				this.refresh();
				this.parentTable.refresh();
			}
		});
		nextButton.addEventListener("click", () => {
			if (this.currentPage < this.maxPages) {
				this.currentPage++;
				this.refresh();
				this.parentTable.refresh();
			}
		});
		selector.addEventListener("change", () => {
			this.currentPage = selector.value;
			this.refresh();
			this.parentTable.refresh();
		});
	}
	resetSelector() {
		const { setChildren } = redom;
		const options = [];
		for (let i = 1; i <= this.maxPages; i++) {
			options.push(el("option", `${i}`));
		}
		setChildren(this.selector, options);
		this.totalEntitiesContainer.textContent =
			this.totalEntitiesText + ": " + this.totalEntities;
		this.currentPage = 1;
	}
	refresh() {
		this.selector.value = this.currentPage;
	}
}

class LoadingIcon {
	constructor(container) {
		this.mainContainer = container;
		this.loadingElement = el(
			"i.loadingIcon fad fa-circle-notch fa-pulse fa-2x fa-fw"
		);
	}
	init() {
		//Usar ParentNode.children en lugar de childNodes para no traer nodos de texto en caso de haber espacios
		const children = this.mainContainer.children;
		for (let i = 0; i < children.length; i++) {
			children[i].classList.add("faded");
		}
		mount(this.mainContainer, this.loadingElement);
	}
	end() {
		const { unmount } = redom;
		const children = this.mainContainer.children;
		for (let i = 0; i < children.length; i++) {
			children[i].classList.remove("faded");
		}
		unmount(this.mainContainer, this.loadingElement);
	}
}
