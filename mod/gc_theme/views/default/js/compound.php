elgg.provide('elgg.thewire');

function setPath(f) {
	document.getElementById('mypath').value = f;
}

function browse() {
	document.getElementById('realFile').click()
}

function clearIt(f) {
	f.value='';
	var d = document.getElementById('browser');
	var olddiv = document.getElementById('realFile');
	var new_element = document.createElement( 'input' );
	new_element.type = 'file';
	new_element.id='RealFile';
	new_element.onchange = function() {document.getElementById('mypath').value = document.getElementById('realFile').value;};
	d.replaceChild( new_element,olddiv );
};

elgg.register_hook_handler('init', 'system', elgg.thewire.init);
