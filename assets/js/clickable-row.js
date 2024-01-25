const initializeClickableRow = () => {
    let clickableRow = document.getElementsByClassName('clickable-row');
    for (let i = 0; i < clickableRow.length; i++) {
        clickableRow[i].onclick = () => {
            let url = clickableRow[i].getAttribute("data-url");
            window.open(url, "_blank");
        }
    }
}