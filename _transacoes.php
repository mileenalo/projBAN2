<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
* Verificação de ações requisitadas via AJAX:
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if(isset($_GET["a"])){
    include("./script/classes/Transactions.php");
	
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Buscar conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "lista_transacoes"){
        $Transaction = new Transactions();

        $res = $Transaction->listTransaction();
		
		if(count($res) > 0){
			echo '<div class="table-responsive">';
			echo '<table id="tb_lista" class="table table-striped table-hover table-sm" style="font-size: 10pt">';
				echo '<thead>';
					echo '<tr>';
						echo '<th style="text-align: left">Id</th>';
						echo '<th style="text-align: left">Cliente</th>';
                        echo '<th style="text-align: center">Pedido</th>';
                        echo '<th style="text-align: center">Tipo de pagamento</th>';
                        echo '<th style="text-align: center">Mensagem</th>';
                        echo '<th style="text-align: center">NF</th>';
                        echo '<th style="text-align: center">Data</th>';
					echo '</tr>';
				echo '</thead>';
				echo '<tbody style="cursor: row-resize">';
                foreach($res as $r){
					echo '<tr>';
						echo '<td style="text-align: left">'.$r["tr_transactionId"].'</td>';
						echo '<td style="text-align: left">'.$r["cs_name"].'</td>';
                        echo '<td style="text-align: center">'.$r["sl_saleId"].'</td>';
                        echo '<td style="text-align: center">'.$r["pa_description"].'</td>';
                        echo '<td style="text-align: center">'.$r["tr_messagem"].'</td>';
                        echo '<td style="text-align: center">'.$r["in_number"].'/'.$r["in_serie"].'</td>';
                        echo '<td style="text-align: center">'.substr($r["tr_date"], 8, 2).'/'.substr($r["tr_date"], 5, 2).'/'.substr($r["tr_date"], 0, 4).substr($r["tr_date"], 10).'</td>';
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
			url: '?a=lista_transacoes',
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
				<h1 class="h2">Transações</h1>
			</div>
		</div>
	</div>

	<div id="div_conteudo"></div>
</main>

<?php include("bottom.php"); ?>
