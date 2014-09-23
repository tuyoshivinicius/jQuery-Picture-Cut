<style>
#JtuyoshiCrop #Painel{font-size:12px;position:absolute;top:0;left:0;z-index:3;width:100%;-webkit-box-shadow:0 0 5px 2px #999;box-shadow:0 0 5px 2px #999;padding:5px}
#JtuyoshiCrop .visivel{opacity:1;-webkit-transition:all 1s ease;-moz-transition:all 1s ease;-ms-transition:all 1s ease;-o-transition:all 1s ease;transition:all 1s ease}
#JtuyoshiCrop .invisivel{opacity:0;-webkit-transition:all 1s ease;-moz-transition:all 1s ease;-ms-transition:all 1s ease;-o-transition:all 1s ease;transition:all 1s ease}
#JtuyoshiCrop #Painel > select,button,span{font-size:10px}
#JtuyoshiCrop #Principal{position:relative;margin:0}
#JtuyoshiCrop #SelecaoRecorte{position:absolute;background-color:#FFF;border:2px #333 dotted;opacity:0.5}
</style>
<script>
$(function(){
	var $t = function (selector){ return $("#JtuyoshiCrop").find(selector); };
	$t(":button").button();			
	
	$t("#button_crop_fechar").button({
	  label:"Fechar",
      icons: {
        primary: "ui-icon ui-icon-circle-close"
      }
    }).click(function(){
		$("#JtuyoshiCrop").remove();
		$("#JtuyoshiCrop_model").remove();
	});
	

	$t("#Painel").data("visible","true");
	
	setTimeout(function(){		
			$t("#Painel").removeClass("visivel").addClass("invisivel");	
	},3000);
	
	
	
	$("#JtuyoshiCrop").bind("mousemove",function(event){
		
		var mouseX	= event.pageX-$(this).offset().left;
		var mouseY	= event.pageY-$(this).offset().top;

		if(mouseY<=40)
			$t("#Painel").addClass("visivel").removeClass("invisivel");
		else
			$t("#Painel").removeClass("visivel").addClass("invisivel");

	});
	
	
})
</script>
<div id="Painel" class="visivel">

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
	
    
    <button id="button_crop_fechar" style="float:right"></button>
    
</div>
<div id="Principal">
	<div id='SelecaoRecorte'></div>

</div>