<html>
<body style="padding:0px;margin:0px;">

<?if($_GET['unsub']==1){?>
<A HREF="https://www.paypal.com/cgi-bin/webscr?cmd=_subscr-find&alias=YVQ929STGGYRY" target="_blank">
<IMG SRC="https://www.paypalobjects.com/en_US/i/btn/btn_unsubscribe_SM.gif" BORDER="0">
</A>
<?}else{?>
<center>
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
    <input type="hidden" name="invoice" value="<?=$_GET['userid']?>">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="hosted_button_id" value="QJ5PDY3SGVFWL">
    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
    </form>
</center>
<?}?>

    

</body>