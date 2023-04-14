function removeChildren(dom){
    lastChild = dom.lastChild;
    while(lastChild){
        dom.removeChild(lastChild);
        lastChild = dom.lastChild;
    }
}

function toggleReadOnly(dom){
    dom.readOnly = !dom.readOnly;
}

function toggleRequired(dom){
    dom.required = !dom.required;
}

function toggleDisabled(dom){
    dom.disabled = !dom.disabled;
}

function toggleInputAvailability(dom){
    //Both Required and readOnly
    toggleRequired(dom);
    toggleReadOnly(dom);
    dom.value = "";
}

function toggleDisplay(dom, inline = false){
    let visible = 'block';
    if(inline){
        visible = 'inline-block'
    }
    if(!dom.style.display){
        dom.style.display = visible;
    }
    dom.style.display = dom.style.display == 'none' ? visible : 'none';
}

function toggleVisibility(dom){
    if(!dom.style.visibility){
        dom.style.visibility = 'visible';
    }
    dom.style.visibility = dom.style.visibility == 'hidden' ? 'visible' : 'hidden';
}

function redesignButton(buttonDom, newContent, newClasses){
    removeChildren(buttonDom);
    buttonDom.className = '';
    if(isArray(newContent)){
        newContent.forEach(content => buttonDom.appendChild(content));
    }else{
        buttonDom.appendChild(newContent);
    }
    if(isArray(newClasses)){
        newClasses.forEach(curClass => buttonDom.classList.add(curClass));
    }else{
        buttonDom.classList.add(newClasses);
    }
}

function updateSelect(selectDom, newOptionsArray, valueKey = 'id', nameKey = 'nombre'){
    //id - nombre
    removeChildren(selectDom);
    newOptionsArray.forEach(opt => selectDom.appendChild(el('option', {value: opt[valueKey]}, opt[nameKey])));
}