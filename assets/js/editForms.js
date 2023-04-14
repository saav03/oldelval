//Requires that the form is under fieldset tags
//Check menu view for use example
function triggerEdition(buttonEdit, fieldset, buttonSave, buttonActivate){
	let editing = false;
	//Inputs
	const inputs = [...fieldset.querySelectorAll("input:not([type=checkbox]), input:not([type=radio])")];
	const checkable = [...fieldset.querySelectorAll("input[type=checkbox], input[type=radio]")];
	const selects = [...fieldset.getElementsByTagName('select')];
	const textAreas = [...fieldset.getElementsByTagName('textarea')];
	const allInputs = [...inputs, ...selects, ...textAreas];
	const initialInputs = allInputs.map(input => { return {id: input.id, value: input.value}});
	const initialCheckable = checkable.map(input => { return {id: input.id, checked: input.checked}});
	// buttons //
	const initialButtonEditContent = [...buttonEdit.childNodes];
	const initialButtonEditClasses = [...buttonEdit.classList];
	const cancelButtonContent = [document.createTextNode("Cancelar Edición "), el("i.fas fa-times")];
	const cancelButtonClasses = ["btn", "btn-secondary"];
	buttonEdit.addEventListener('click', function(event) {
		editing = !editing;
		fieldset.disabled = !fieldset.disabled;
		toggleDisabled(buttonSave);
		toggleVisibility(buttonSave);
		if(buttonActivate){
			toggleDisplay(buttonActivate, true);
		}
		if(editing){
		    redesignButton(buttonEdit, cancelButtonContent, cancelButtonClasses);
		}else{
		   redesignButton(buttonEdit, initialButtonEditContent, initialButtonEditClasses);
		   //Recover initial values
		   allInputs.forEach(input => {
		        let initial = initialInputs.find(initial => initial.id == input.id);
		        if(initial){
		            input.value = initial.value;
		        }
		   });
		   checkable.forEach(input => {
		        let initial = initialCheckable.find(initial => initial.id == input.id);
		        if(initial){
		            input.checked = initial.checked;
		        }
		   });
		}
	});
}