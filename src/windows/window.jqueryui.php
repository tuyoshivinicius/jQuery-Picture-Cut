<style>
#JtuyoshiCrop #Painel{font-size:12px;padding-bottom:10px;margin:0}
#JtuyoshiCrop #Principal{position:relative;margin:0}
#JtuyoshiCrop #SelecaoRecorte{position:absolute;background-color:#FFF;border:2px #333 dotted;opacity:0.5}
</style>
<script>
$(function(){
	var $t = function (selector){ return $("#JtuyoshiCrop").find(selector); };
	$t(":button").button();
})
</script>
<div id="Painel">
	<span>
		<select id="SelectOrientacao" title="Choose the Orientation of the cutout area" >	    	
	    </select>  	
    </span>
    <span>
	   	<select id="SelectProporcao" title="Choose the Aspect Ratio of selection" >	    	
	    </select>
    </span>
    <span>
		<button id="button_crop_recortar">Recortar</button>
	    <button id="button_crop_original">Concluir</button>
    </span>
</div>
<div id="Principal">
	<div id='SelecaoRecorte'></div>
</div>