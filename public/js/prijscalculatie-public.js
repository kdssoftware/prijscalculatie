// (function($){'use strict';$(window).load(function(){prijscalculatie_init();});})(jQuery);
var TPX_prijscalculatie_keuzes = [];
const TPX_prijscalculatie_grid = `<div id="TPX_prijscalculatie_grid"></div>`;

function prijscalculatie_init(){
	let working_div = document.getElementById("TPX_prijscalculatie");
	working_div.innerHTML += TPX_prijscalculatie_grid;
	render();
}

function partial_select_item (){
	let partial = "" ;
	// partial += `<label for="workshop_keuze">Voeg een workshop toe</label>`
	partial +=`<select style="width: 100%;" onchange="select()" name="item_keuze" id="item_keuze">`;
	partial += `<option selected="" disabled="">Voeg een item toe</option>`;
	for (const workshop of workshops) {
		let disabled = TPX_prijscalculatie_keuzes.filter(function(k){return k.value==="WS-"+workshop.ID}).length>0;
		partial+= `<option data-id="${workshop.ID}" data-item="WS" data-min_prijs="${workshop.min_prijs}" data-naam="${workshop.naam}" data-prijs_pp="${workshop.prijs_pp}" data-winstmarge=${workshop.winstmarge} value="WS-${workshop.ID}" id="item_WS-${workshop.ID}" ${disabled?`disabled=""`:""}>${workshop.naam} (Workshop)</option>`
	}
	for (const item of items) {
		let disabled = TPX_prijscalculatie_keuzes.filter(function(k){return k.value==="A-"+item.ID}).length>0;
		partial+= `<option data-id="${item.ID}" data-item="A" data-winkelprijs_pp="${item.winkelprijs_pp}" data-winstmarge="${item.winstmarge}" data-naam="${item.naam}" value="A-${item.ID}" id="item_A-${item.ID}" ${disabled?`disabled=""`:""}>${item.naam} (Activiteit)</option>`
	}
	partial +=`</select>`;
	return partial;
}

function select(){
	let select = document.getElementById("item_keuze");
	let item = document.getElementById("item_"+select.value).dataset;
	if(item.item==="A"){
		//item
		TPX_prijscalculatie_keuzes.push({
			...item,
			value:String(select.value)
		});
		// items = items.filter(function(i){return i.ID!==item.id});
		document.getElementById("item_A-"+item.id).disable = true;
	}else{
		//workshop
		TPX_prijscalculatie_keuzes.push({
			...item,
			value:String(select.value)
		});
		// workshops = workshops.filter(function(i){return i.ID!==item.id});
		document.getElementById("item_WS-"+item.id).disable = true;
	}
	render();
	render_total();
}

function pp(item){
	let select = document.getElementById("item_keuze");
	let this_item = document.getElementById("item_"+item).dataset;
	let grid = document.getElementById("TPX_prijscalculatie_grid");
	let subtotal = document.getElementById("subtotal_"+item);
	let input_pp = document.getElementById("input_pp_"+item)
	let dienstprijs = document.getElementById("dienstprijs_"+item)
	let val = input_pp.value;
	if(this_item.item==="A"){
		//item
		if(val==="" || Number(val)<=0){
			subtotal.innerText = "- EUR";
		}else if(Number(val)>0){
			let subtotal_val = Number(dienstprijs.dataset.number)*Number(val);
			subtotal.innerText = space(subtotal_val)+" EUR";
			subtotal.dataset.number = subtotal_val;
		}
	}else{
		//workshop
		if(val==="" || Number(val)<=0){
			subtotal.innerText = "- EUR";
		}else if(Number(val)>0){
			let valN = Number(val);
			let prijs_pp = Number(this_item.prijs_pp);
			let min_prijs = Number(this_item.min_prijs);
			let prijs = (prijs_pp*valN)>min_prijs?prijs_pp*valN:min_prijs;
			let subtotal_val = Math.round(prijs+(prijs*Number(this_item.winstmarge)/100.0));
			subtotal.innerText = space(subtotal_val)+" EUR";
			subtotal.dataset.number = subtotal_val;
		}
	}
	render_total();
}

function toevoeg_button (){
	let btn = `<button value="Toevoegen" />`;
	return btn;
}

function render(){
	let html = "";
	html += `<div>item</div><div>Code item</div><div>Dienstprijs</div><div>Aantal personen</div><div>subtotaal</div><div></div>`;
	for (const keuze of TPX_prijscalculatie_keuzes) {
		if(keuze.item==="A"){
			//item
			let pp = Number(document.getElementById("input_pp_"+keuze.value)?.value||1);
			let dienstprijs = Number(Math.round(Number(keuze.winkelprijs_pp))+Number(keuze.winkelprijs_pp)*Number(keuze.winstmarge)/100.0);
			let dienstprijs_view = space(dienstprijs)+" EUR";
			html += `<div>${keuze.naam}</div><div>${keuze.value}</div><div data-number="${dienstprijs}" id="dienstprijs_${keuze.value}">${dienstprijs_view}</div><input id="input_pp_${keuze.value}" type="number" oninput="pp('${keuze.value}')" value="${pp}" /><div class="TPX_prijscalculatie_subtotal" data-number="${dienstprijs}" id="subtotal_${keuze.value}">${dienstprijs*pp.toFixed(2)} EUR</div><button class="TPX_delete_row" onClick="verwijder('${keuze.value}')">X</button>`;
		}else{
			let pp = Number(document.getElementById("input_pp_"+keuze.value)?.value||1);	
			let prijs = (Number(keuze.prijs_pp)*pp)>Number(keuze.min_prijs)?Number(keuze.prijs_pp)*pp:Number(keuze.min_prijs);
			let dienstprijs = Math.round(prijs+(prijs*Number(keuze.winstmarge)/100.0));
			let dienstprijs_view = dienstprijs+" EUR";
			html += `<div>${keuze.naam}</div><div>${keuze.value}</div><div data-number="${dienstprijs}" id="dienstprijs_${keuze.value}">${keuze.prijs_pp} EUR / pp of ${dienstprijs_view}</div><input id="input_pp_${keuze.value}" type="number" oninput="pp('${keuze.value}')" value="${pp}" /><div class="TPX_prijscalculatie_subtotal" data-number="${dienstprijs*pp}" id="subtotal_${keuze.value}">${dienstprijs.toFixed(2)} EUR</div><button class="TPX_delete_row" onClick="verwijder('${keuze.value}')">X</button>`;
		}
	}
	html += partial_select_item();
	let total = 0;
	for (const subtotal of document.querySelectorAll(".TPX_prijscalculatie_subtotal")) {
		total += Number(subtotal.dataset.number);
	}
	html += `<div></div><div></div><div id="TPX_prijscalculatie_totaal_text">Totaal:</div><div id="TPX_prijscalculatie_totaal">${total}</div>`
	let grid = document.getElementById("TPX_prijscalculatie_grid");
	grid.innerHTML = html;
}

function verwijder(item){
	TPX_prijscalculatie_keuzes = TPX_prijscalculatie_keuzes.filter(function(k){
		return (k.value!==item);
	});
	
	render();
}
function render_total(){
	let total = 0;
	for (const subtotal of document.querySelectorAll(".TPX_prijscalculatie_subtotal")) {
		total += Number(subtotal.dataset.number);
	}
	document.getElementById("TPX_prijscalculatie_totaal").innerText = total+" EUR";
}

//function that spaces currency amount in digits of 3
function space(n) {
    // return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
	return n;
}
