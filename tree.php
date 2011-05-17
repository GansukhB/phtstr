<?
  $menu_lang_array = explode(".", $_SESSION['lang'] );
  $menu_lang = $menu_lang_array[0]; 
?>
<script language="JavaScript" src="js/tree.js"></script>
<script language="JavaScript" src="js/tree_items_<?php echo $menu_lang ? $menu_lang: 'English'; ?>.js"></script>
<script language="JavaScript" src="js/tree_tpl.js"></script>

	<script language="JavaScript">
	<!--//
		new tree (TREE_ITEMS, TREE_TPL);
	//-->
	</script>
