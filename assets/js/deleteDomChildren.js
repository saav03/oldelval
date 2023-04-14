function deleteDomChildren(dom){
    while (dom.firstChild) {
        dom.removeChild(dom.lastChild);
    }
}