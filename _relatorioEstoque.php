<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
* Verificação de ações requisitadas via AJAX:
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if(isset($_GET["a"])){
    include("./script/classes/Sale.php");
	
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Buscar conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "lista_estItem"){
        $Sale = new Sale();

        $res = $Sale->graphItensSale();
		
		if(count($res) > 0){
			echo '<div class="table-responsive">';
			echo '<table id="tb_lista" class="table table-striped table-hover table-sm" style="font-size: 10pt">';
				echo '<thead>';
					echo '<tr>';
						echo '<th style="text-align: left">Produto</th>';
						echo '<th style="text-align: center">Quantidade Vendida</th>';
                        echo '<th style="text-align: center">Quantidade Em Estoque</th>';
                        echo '<th style="text-align: center">Local</th>';
					echo '</tr>';
				echo '</thead>';
				echo '<tbody style="cursor: row-resize">';
                foreach($res as $r){
					echo '<tr>';
						echo '<td style="text-align: left">'.$r["pr_description"].'</td>';
						echo '<td style="text-align: center">'.$r["qtdVend"].'</td>';
                        echo '<td style="text-align: center">'.$r["qtdEst"].'</td>';
                        echo '<td style="text-align: center">'.$r["iv_location"].'</td>';
					echo '</tr>';
				}
				echo '</tbody>';
			echo '</table>';
			echo '</div>';
		}else{
			echo '<div class="alert alert-warning" role="alert">';
				echo 'Nenhum registro localizado!';
			echo '</div>';
		}
	}

    die();
}

// Includes para o script:
include("header.php");
include("dashboard.php");

?>
<link href="./css/timeline.css" rel="stylesheet">

<script type="text/javascript" src="./assets/js/jquery-3.6.0.min.js"></script>
<script src="./assets/js/jquery-ui.js"></script>
<script type="text/javascript">
	
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Listar itens:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const lista_itens = () => {
		if(ajax_div){ ajax_div.abort(); }
		ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?a=lista_estItem',
			type: 'post',
			data: { },
			beforeSend: function(){
				$('#div_conteudo').html('<div class="spinner-grow m-3 text-primary" role="status"><span class="visually-hidden">Aguarde...</span></div>');
			},
			success: function retorno_ajax(retorno) {
				$('#div_conteudo').html(retorno); 
			}
		});
	}

	// Evento inicial:
	$(document).ready(function() {
		//setInterval(function(){ list_items(); }, 57000); list_items();
		lista_itens();
	});

</script>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<div style="display: flex; flex: 1">
			<div style="flex: 1">
				<h1 class="h2">Estoque X Pedidos</h1>
			</div>
		</div>
	</div>

	<div id="div_conteudo"></div>
</main>

<?php include("bottom.php"); ?>
