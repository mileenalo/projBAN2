<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
* Verificação de ações requisitadas via AJAX:
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if(isset($_GET["a"])){
    include("./script/classes/Sale.php");
	
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Buscar conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "lista_user"){
        $Sale = new Sale();

        $res = $Sale->listSaleInfo();
		$ped = "";

		if($res != ""){
			echo '<div class="table-responsive">';
			echo '<table id="tb_lista" class="table table-striped table-hover table-sm" style="font-size: 10pt">';
				echo '<thead>';
					echo '<tr>';
						echo '<th colspan="2" style="text-align: left">Produto</th>';
						echo '<th colspan="2" style="text-align: left">Detalhes</th>';
						echo '<th style="text-align: center">Preço</th>';
						echo '<th style="text-align: center">Quantidade</th>';
						echo '<th style="text-align: center">Ação</th>';
					echo '</tr>';
				echo '</thead>';
				echo '<tbody style="cursor: row-resize">';
                foreach($res as $r){
					
					if($ped != $r->_id){
						if($r["in_number"] != ""){
							$disabled = "disabled";
						}else{
							$disabled = "";
						}
						echo '<tr>';
						echo '	<td colspan="1" class="text-white text-right bg-secondary">'.$r->_id.'</td>';
						echo '	<td colspan="1" class="text-white text-right bg-secondary">'.$r->cs_name.'</td>';
						echo '	<td colspan="1" class="text-white text-left bg-secondary">Vendedor: '.$r->usu_name.'</td>';
						echo '	<td colspan="1" class="text-white text-center bg-secondary">NF '.$r->in_number.'/'.$r->in_serie.'</td>';
						echo '	<td colspan="1" class="text-white text-center bg-secondary">Total R$'.str_replace(".",",",$r->sl_finalPrice).'</td>';
						echo '	<td colspan="1" class="text-white text-right bg-secondary">';
						if($r->in_number == ""){
							echo '		Faturar <i title="Editar" onclick="fat_pedido(\''.$r->_id.'\')" class="fas fa-edit" style="cursor: pointer"></i>';
						}
						echo '	</td>';
                        echo '	<td td colspan="2" class="text-white text-right bg-secondary">';
						if($r->in_number == ""){
							echo '		Excluir <i title="Deletar Pedido" onclick="del_ped(\''.$r->_id.'\')" class="fas fa-trash" style="cursor: pointer"></i>';
						}
						echo '	</td>';
						echo '</tr>';
					}

					echo '<tr>';
						echo '<td colspan="2" style="text-align: left">'.$r->pr_description.'</td>';
						echo '<td colspan="2" style="text-align: left">'.$r->pr_detail.'</td>'; 
						echo '<td colspan="1" style="text-align: center">'.str_replace(".",",",$r->pr_price).'</td>';
						echo '<td colspan="1" style="text-align: right;">';
							echo '<div class="input-group">';
								echo '<input onfocus="this.select()" onblur="altera_item(this, this.value, \''.$r->sli_saleItemId.'\', \''.$r->sl_saleId.'\');" style="text-align: end;" type="number" value="'.$r->sli_quantity.'" class="input-form-sm"'.$disabled.'>';
							echo '</div>';
						echo '</td>';
						echo '<td td colspan="1" style="text-align: center">';
						if($r->in_number == ""){
							echo '	<i title="Deletar Item" onclick="del_item(\''.$r->sli_saleItemId.'\')" class="fas fa-trash" style="cursor: pointer"></i>';
						}
						echo '</td>';
					echo '</tr>';
					$ped = $r->sl_saleId;
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
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Buscar conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "inclui_pedido"){
        $Sale = new Sale();

        $seller = $_POST["seller"];
        $cliente = $_POST["cliente"];
		$date = date("Ymd");

        $res = $Sale->createSale($cliente, $seller, $date);
		
		echo $res;
		die();
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Buscar conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "inclui_item"){
        $Sale = new Sale();

        $quantity = $_POST["quantity"];
        $c_recno = $_POST["c_recno"];
		$pedido = $_POST["pedido"];

        $res = $Sale->editItens($pedido, $c_recno, $quantity, 1);
		
		echo $res;
		die();
	}

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Edita conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "altera_quant"){
        $Sale = new Sale();

        $quantity = $_POST["quantity"];
        $item = $_POST["c_recno"];
		$pedido = $_POST["pedido"];
		
        $res = $Sale->editItens($pedido, $item, $quantity, 2);
		
        echo $res;
	}

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Deleta conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "del_pedido"){
        $Sale = new Sale();

        $id = $_POST["id"];

        $res = $Sale->deleteSale($id);
		
        echo $res;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Deleta conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "del_item"){
        $Sale = new Sale();

        $id = $_POST["id"];

        $res = $Sale->deleteItens($id);
		
        echo $res;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Deleta conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "show_credit"){
        
		echo '<form id="frm_general_edit" name="frm_general">';
		echo '	<div class="row mb-3">';
		echo '		<div class="col">';
        echo '      	<input type="text" style="text-align: left" aria-describedby="frm_idCard" class="form-control form-control-lg" name="frm_id" id="frm_id" hidden>';
		echo '			<label for="frm_numberCard" class="form-label">Número:</label>';
		echo '			<input type="text" style="text-align: left" aria-describedby="frm_numberCard" class="form-control form-control-lg" name="frm_numberCard" id="frm_numberCard" placeholder="">';
		echo '		</div>';
		echo '	</div>';
		echo '	<div class="row mb-3">';
		echo '		<div class="col">';
		echo '			<label for="frm_vencCard" class="form-label">Vencimento:</label>';
		echo '			<input type="text" style="text-align: left" aria-describedby="frm_vencCard" class="form-control form-control-lg" name="frm_vencCard" id="frm_vencCard" placeholder="">';
		echo '		</div>';
		echo '	</div>';
		echo '	<div class="row mb-3">';
		echo '		<div class="col">';
		echo '			<label for="frm_csvCard" class="form-label">CSV:</label>';
		echo '			<input type="text" style="text-align: left" aria-describedby="frm_csvCard" class="form-control form-control-lg" name="frm_csvCard" id="frm_csvCard" placeholder="">';
		echo '		</div>';
		echo '	</div>';
		echo '	<div class="row mb-3">';
		echo '		<div class="col">';
		echo '			<label for="frm_nameCli" class="form-label">Nome:</label>';
		echo '			<input type="text" style="text-align: left" aria-describedby="frm_nameCli" class="form-control form-control-lg" name="frm_nameCli" id="frm_nameCli" placeholder="">';
		echo '		</div>';
		echo '	</div>';
		echo '	<div class="row mb-3">';
		echo '		<div class="col">';
		echo '			<label for="frm_cpf" class="form-label">CPF:</label>';
		echo '			<input type="text" style="text-align: left" aria-describedby="frm_cpf" class="form-control form-control-lg" name="frm_cpf" id="frm_cpf" placeholder="">';
		echo '		</div>';
		echo '	</div>';
		echo '</form>';
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Deleta conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "fatura_pedido"){
		
		$id = $_POST["id"];
		$tipo = $_POST["tipo"];
		if(isset($_POST["card"])){ 
			$card = $_POST["card"];
		}else{
			$card = "0000";
		}

		$Sale = new Sale();

        $res = $Sale->closeSale($id, $tipo, $card);

        echo $res;
	}

    die();
}

// Includes para o script:
include("header.php");
include("dashboard.php");

?>
<style>
	.input-form-sm {
    	display: block;
    	width: 100%;
		height: 30;
    	padding: 0.375rem 0.75rem;
    	font-size: 1rem;
    	font-weight: 400;
    	line-height: 1.5;
    	color: #212529;
    	background-color: #fff;
    	background-clip: padding-box;
    	border: 1px solid #ced4da;
    	-webkit-appearance: none;
    	-moz-appearance: none;
    	appearance: none;
    	border-radius: 5px;
    	transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
	}
</style>

<link href="./css/timeline.css" rel="stylesheet">

<script type="text/javascript" src="./assets/js/jquery-3.6.0.min.js"></script>
<script src="./assets/js/jquery-ui.js"></script>
<script type="text/javascript">
	var a_itens = Array();
	var tipoPagto = 0;

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Listar itens:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const lista_itens = () => {
		if(ajax_div){ ajax_div.abort(); }
		ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?a=lista_user',
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
    
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Incluir itens:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const incluiPedido = () => {
        if(ajax_div){ ajax_div.abort(); }
		ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?a=inclui_pedido',
			type: 'post',
			data: { 
                seller: $("#frm_seller").val(),
                cliente: $("#frm_cliente").val(),
            },
			beforeSend: function(){
                $('#mod_formul').html('<div class="spinner-grow m-3 text-primary" role="status"><span class="visually-hidden">Aguarde...</span></div>');
			},
			success: function retorno_ajax(retorno) {
				$('#mod_formul').modal('hide');
				$("#frm_idPedido").val(retorno);
				$('#mod_formul2').modal('show');
			}
		});
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Incluir itens:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const incluiItens = (obj_value, c_recno) => {
        if(ajax_div){ ajax_div.abort(); }
		ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?a=inclui_item',
			type: 'post',
			data: { 
				quantity: obj_value,
                c_recno: c_recno,
				pedido: $("#frm_idPedido").val(),
            },
			success: function retorno_ajax(retorno) {
				if(retorno != "OK"){
                    alert("ERRO AO CADASTRAR ITEM! " + retorno);
                }
			}
		});
	}

	// Evento inicial:
	$(document).ready(function() {
		//setInterval(function(){ list_items(); }, 57000); list_items();
		lista_itens();
	});

   /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Altera item:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_act = $.ajax(null);
	const altera_item = (obj, obj_value, c_recno, pedido) => {
		if(ajax_act){ ajax_act.abort(); }
		ajax_act = $.ajax({
			cache: false,
			async: true,
			url: '?a=altera_quant',
			type: 'post',
			data: {
				quantity: obj_value,
                c_recno: c_recno,
				pedido: pedido,
			},
			beforeSend: function(){
				$(obj).prop("disabled", true);
			},
			success: function retorno_ajax(retorno) {
				$(obj).prop("disabled", false);
				if(retorno != 'OK'){ 
					alert(retorno); $(obj).select(); 
				}
				location.reload();
				lista_itens();
			}
		});
	}

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Excluir usuário:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	function del_item(id){
        if( confirm( "Deseja excluir o item do pedido?")){
            if(ajax_div){ ajax_div.abort(); }
		        ajax_div = $.ajax({
		    	cache: false,
		    	async: true,
		    	url: '?a=del_item',
		    	type: 'post',
		    	data: { 
                    id: id,
                },
		    	success: function retorno_ajax(retorno) {
                    if(retorno == "OK"){
						location.reload();
                    	lista_itens();  
                	}else{
                    	alert("ERRO AO DELETAR ITEM! " + retorno);
                	}
		    	}
		    });
        }else{
            lista_itens();
        }
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Excluir usuário:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	function del_ped(id){
        if( confirm( "Deseja excluir o pedido?")){
            if(ajax_div){ ajax_div.abort(); }
		        ajax_div = $.ajax({
		    	cache: false,
		    	async: true,
		    	url: '?a=del_pedido',
		    	type: 'post',
		    	data: { 
                    id: id,
                },
		    	success: function retorno_ajax(retorno) {
                    if(retorno == "OK"){
						location.reload();
                    	lista_itens();  
                	}else{
                   	 	alert("ERRO AO DELETAR PEDIDO! " + retorno);
                	}
		    	}
		    });
        }else{
            lista_itens();
        }
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* FATURA PEDIDO:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	function fat_pedido(id){
        if( confirm( "Deseja Finalizar o pedido?")){
			$("#mod_payment").modal("show");
			$("#frm_pedidoFat").val(id);
        }else{
            lista_itens();
        }
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* FATURA PEDIDO:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	function preencheCredito(id, type){
		console.log(id + " " + type);
        if(ajax_div){ ajax_div.abort(); }
		    ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?a=show_credit',
			type: 'post',
			data: {},
			success: function retorno_ajax(retorno) {
                $("#card_detail").html(retorno);
				tipoPagto = 1;
			}
		});
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* FATURA PEDIDO:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	function preencheDebito(id, type){
		console.log(id + " " + type);
        if(ajax_div){ ajax_div.abort(); }
		    ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?a=show_credit',
			type: 'post',
			data: {},
			success: function retorno_ajax(retorno) {
                $("#card_detail").html(retorno);
				tipoPagto = 2;
			}
		});
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* FATURA PEDIDO:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	function pagaBoleto(id, type){
		console.log(id + " " + type);
        if(ajax_div){ ajax_div.abort(); }
		    ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?a=paga_boleto',
			type: 'post',
			data: {},
			success: function retorno_ajax(retorno) {
                $("#card_detail").html(retorno);
				tipoPagto = 3;
			}
		});
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* FATURA PEDIDO:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	function faturaPed(){
		alert($("#frm_pedidoFat").val() + " " + tipoPagto + " " + $("#frm_numberCard").val());
        if(ajax_div){ ajax_div.abort(); }
		    ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?a=fatura_pedido',
			type: 'post',
			data: {
				id: $("#frm_pedidoFat").val(),
				tipo: tipoPagto,
				card: $("#frm_numberCard").val(),
			},
			success: function retorno_ajax(retorno) {
                if(retorno == "OK"){
					location.reload();
                	lista_itens();  
                }else{
                 	alert("ERRO AO FATURAR PEDIDO! " + retorno);
                }
			}
		});
	}


</script>

<!-- Modal formulário -->
<div class="modal" id="mod_formul">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" style="max-width: 70%;">
		<div class="modal-content">
			<div class="modal-header" style="align-items: center">
				<div style="display: flex; align-items: center">
					<div style="margin-right: 5px">
						<h2 style="margin: 0"><span class="badge bg-info text-white" style="padding: 8px" id="span_endereco_nome"></span></h2>
					</div>
					<div>
						<h5 id="tit_frm_formul" class="modal-title">Incluir Pedido</h5>
					</div>
				</div>
				<button type="button" style="cursor: pointer; border: 1px solid #ccc; border-radius: 10px" aria-label="Fechar" onclick="$('#mod_formul').modal('hide');">X</button>
			</div>
			<div class="modal-body modal-dialog-scrollable">
				<form id="frm_general" name="frm_general">
					<div class="row mb-3">
						<div class="col">
							<input type="text" style="text-align: left" aria-describedby="frm_seller" class="form-control form-control-lg" name="frm_seller" id="frm_seller" value="<?php echo $_COOKIE["u_id"]; ?>" hidden>
							<label for="frm_cliente" class="form-label">Cliente:</label>
							<select class="form-select form-control-lg" size="1" id="frm_cliente" name="frm_cliente">
								<option value="" selected></option>
								<?php
                                   	include_once("db_mongo.php");
								   	$db = new DBMongo(); 
   
								   	$field = "cs_name";
								   	$table = "tb_customer";
								   	$res = $db->searchAll($field, $table);

									foreach($res as $r){
										echo '<option value="'.$r->_id.'">'.trim($r->cs_name).'</option>';
									}
								?>
							</select>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" onclick="$('#mod_formul').modal('hide');">Cancelar</button>
				<button type="button" class="btn btn-primary" id="frm_OK" onclick="incluiPedido();"><img id="img_btn_ok" style="width: 15px; display: none; margin-right: 10px">OK</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal formulário -->
<div class="modal" id="mod_formul2">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" style="max-width: 70%;">
		<div class="modal-content">
			<div class="modal-header" style="align-items: center">
				<div style="display: flex; align-items: center">
					<div style="margin-right: 5px">
						<h2 style="margin: 0"><span class="badge bg-info text-white" style="padding: 8px" id="span_endereco_nome"></span></h2>
					</div>
					<div>
						<h5 id="tit_frm_formul" class="modal-title">Incluir Itens</h5>
					</div>
				</div>
				<button type="button" style="cursor: pointer; border: 1px solid #ccc; border-radius: 10px" aria-label="Fechar" onclick="$('#mod_formul2').modal('hide');">X</button>
			</div>
			<div class="modal-body modal-dialog-scrollable">
				<form id="frm_general2" name="frm_general2">
					<div class="row mb-3">
					<input type="text" style="text-align: left" aria-describedby="frm_idPedido" class="form-control form-control-lg" name="frm_idPedido" id="frm_idPedido" hidden>
						<div class="col">
							<?php
								include_once("db_mongo.php");
								$db = new DBMongo(); 

								$field = "pr_description";
								$table = "tb_products";
								$res = $db->searchAll($field, $table);

								if($res != ""){
									echo '<div class="table-responsive" style="padding-top: 10px">';
										echo '<table id="tb_lista" class="table table-striped table-hover table-sm" style="font-size: 10pt">';
											echo '<thead>';
												echo '<tr>';
													echo '<th style="text-align: left">Produto</th>';
													echo '<th style="text-align: left">Detalhes</th>';
													echo '<th style="text-align: center">Preço</th>';
													echo '<th style="text-align: center">Quantidade</th>';
												echo '</tr>';
											echo '</thead>';
											echo '<tbody>';
											foreach($res as $r){

												echo '<tr>';
													echo '<td style="text-align: left;">'.$r->pr_description.'</td>';
													echo '<td style="text-align: left">'.$r->pr_detail.'</td>';
													echo '<td style="text-align: center">R$'.$r->pr_price.'</td>';
													echo '<td colspan="1" style="text-align: right;">';
														echo '<div class="input-group">';
															echo '<input onfocus="this.select()" onchange="incluiItens(this.value, \''.$r->_id.'\');" style="text-align: end;" type="number" value="0" class="input-form-sm">';
														echo '</div>';
												echo '</tr>';
											}
											echo '</tbody>';
										echo '</table>';
									echo '</div>';
								}
							?>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" onclick="$('#mod_formul2').modal('hide');">Cancelar</button>
				<button type="button" class="btn btn-primary" id="frm_OK" onclick="$('#mod_formul2').modal('hide'); location.reload(); lista_itens();"><img id="img_btn_ok" style="width: 15px; display: none; margin-right: 10px">OK</button>
			</div>
		</div>
	</div>
</div>


<!-- Modal formulário -->
<div class="modal" id="mod_formul_edit">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" style="max-width: 70%;">
		<div class="modal-content">
			<div class="modal-header" style="align-items: center">
				<div style="display: flex; align-items: center">
					<div style="margin-right: 5px">
						<h2 style="margin: 0"><span class="badge bg-info text-white" style="padding: 8px" id="span_endereco_nome"></span></h2>
					</div>
					<div>
						<h5 id="tit_frm_formul_edit" class="modal-title">Editar Usuário</h5>
					</div>
				</div>
				<button type="button" style="cursor: pointer; border: 1px solid #ccc; border-radius: 10px" aria-label="Fechar" onclick="$('#mod_formul').modal('hide');">X</button>
			</div>
			<div class="modal-body modal-dialog-scrollable">
				<form id="frm_general_edit" name="frm_general">
					<div class="row mb-3">
						<div class="col">
                            <input type="text" style="text-align: left" aria-describedby="frm_id" class="form-control form-control-lg" name="frm_id" id="frm_id" hidden>
							<label for="frm_nome_edit" class="form-label">Nome:</label>
							<input type="text" style="text-align: left" aria-describedby="frm_nome_edit" class="form-control form-control-lg" name="frm_nome_edit" id="frm_nome_edit" placeholder="">
						</div>
					</div>

					<div class="row mb-3">
						<div class="col">
							<label for="frm_user_edit" class="form-label">E-mail:</label>
							<input type="text" style="text-align: left" aria-describedby="frm_user_edit" class="form-control form-control-lg" name="frm_user_edit" id="frm_user_edit" placeholder="">
						</div>
					</div>

					<div class="row mb-3">
						<div class="col">
							<label for="frm_senha_edit" class="form-label">Senha:</label>
							<input type="password" style="text-align: left" aria-describedby="frm_senha_edit" class="form-control form-control-lg" name="frm_senha_edit" id="frm_senha_edit" placeholder="">
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" onclick="$('#mod_formul_edit').modal('hide');">Cancelar</button>
				<button type="button" class="btn btn-primary" id="frm_OK" onclick="editUser();"><img id="img_btn_ok" style="width: 15px; display: none; margin-right: 10px">OK</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal geral -->
<div class="modal" id="mod_general" tabindex="-1" style="z-index: 1400 !important">
	<div id="mod_general" class="modal-dialog modal-dialog-scrollable modal-xl" tabindex="-1">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="tit_frm_general" class="modal-title"></h5>
				<button type="button" id="btn_general_close" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
			</div>
			<div class="modal-body modal-dialog-scrollable" id="modmenu_content">
			</div>
		</div>
	</div>
</div>


<!-- Modal geral -->
<div class="modal" id="mod_payment" tabindex="-1" style="z-index: 1400 !important">
	<div id="mod_payment" class="modal-dialog modal-dialog-scrollable modal-xl" tabindex="-1">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="tit_frm_general" class="modal-title"></h5>
				<button type="button" id="btn_general_close" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
			</div>
			<div class="modal-body modal-dialog-scrollable" id="mod_payment_det">
				<div class="table-responsive">
					<table id="tb_lista" class="table table-striped table-hover table-sm" style="font-size: 10pt">
						<div class="row mb-3">
							<div class="col">
								<label for="frm_pedidoFat" class="form-label">Pedido:</label>
								<input type="text" style="text-align: left" aria-describedby="frm_pedidoFat" class="form-control form-control-lg" name="frm_pedidoFat" id="frm_pedidoFat" placeholder="" disabled>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="input-group mb-3">
									<button type="button" onclick="preencheCredito( $('#frm_pedidoFat').val(), 1)" class="btn btn-primary"><i class="fa fa-plus-circle" style="margin-right: 5px">Cartão de Crédito</i></button>
								</div>
							</div>
							<div class="col">
								<div class="input-group mb-3">
									<button type="button" onclick="preencheDebito($('#frm_pedidoFat').val(), 2)" class="btn btn-primary"><i class="fa fa-plus-circle" style="margin-right: 5px">Cartão de Débito</i></button>
								</div>
							</div>
							<div class="col">
								<div class="input-group mb-3">
									<button type="button" onclick="pagaBoleto($('#frm_pedidoFat').val(), 3); $('#card_detail').html('Clique em pagar para finalizar o pedido!');" class="btn btn-primary"><i class="fa fa-plus-circle" style="margin-right: 5px">Boleto Bancário</i></button>
								</div>
							</div>
						</div>
						<div class="modal-body modal-dialog-scrollable" id="card_detail">
						</div>
					</table>
					<div class="row">
						<div class="col">
							<div class="input-group mb-3">
								<button type="button" onclick="faturaPed();" class="btn btn-primary"><i class="fa fa-plus-circle" style="margin-right: 5px"></i>Pagar</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<div style="display: flex; flex: 1">
			<div style="flex: 1">
				<h1 class="h2">Pedidos</h1>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col">
			<div class="input-group mb-3">
				<button type="button" onclick="$('#mod_formul').modal('show');" class="btn btn-primary"><i class="fa fa-plus-circle" style="margin-right: 5px"></i>Incluir</button>
			</div>
		</div>
	</div>

	<div id="div_conteudo"></div>
</main>

<?php include("bottom.php"); ?>
