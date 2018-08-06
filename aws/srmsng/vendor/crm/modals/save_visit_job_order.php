 <? 
	print_r($_REQUEST);
	
	if(empty($_REQUEST["formfield_1"])) 
		$_IS_ERROR = true;
	if(empty($_REQUEST["formfield_2"])) 
		$_IS_ERROR = true;
		
	if($_IS_ERROR)
		include("input_form.html");
	
?>

<? 	if(!$_IS_ERROR){	?>
<script>
	window.parent.fn_test("[<?=$_REQUEST["formfield_1"];?>]value from form2 ++");
	//jQuery.fn.modalBox('close');
</script>
<input type="button" value="Close" onclick="jQuery.fn.modalBox('close');">
<? }?>
