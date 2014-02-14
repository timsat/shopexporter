<script language="JavaScript" type="text/javascript">
 
   function postForm(action){
        document.module.action.value=action;
        document.module.submit();
   }
 
</script>
 
<?php echo $action; ?>Module is not installed yet
 
<form name="module" method="post">
 
<input name="action" type="hidden" value="" />
 
<input type="submit" onclick="postForm('install')" value="Install"/>
 
</form>
