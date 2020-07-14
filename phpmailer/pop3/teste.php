<?
require('pop3.php');
include_once('includes/crmBDs.php');

$servicos = array('mminternet'=>array('usuario'=>'naoresponda@mminternet.com.br', 'senha'=>'WgtjgNGojv0RJ1', 'host'=>'mmhost.mminternet.com.br'));

function ExtractEmail($s)
{
    $s = str_replace(';', ' ', $s);
    $ll = explode(' ', $s);
    for ($i=0;$i<count($ll);$i++)
        if (strpos($ll[$i], '@') !== false)
            return $ll[$i];
    return '';
    
}

foreach ($servicos as $nomebd=>$dados)
{
    $conexao = CRMBD_ByName($nomebd, $arrayCRM_BD);
    $conn = mysql_connect($conexao['host'],$conexao['user'], $conexao['pass']);
    mysql_select_db($conexao['user']);
    
    $pop3=new pop3_class;
    $pop3->hostname=$dados['host'];             
    $pop3->port=110;                         
    $pop3->tls=0;                            
    $pop3->realm="";                         
    $pop3->workstation="";                   
    $apop=0;                                 
    $pop3->authentication_mechanism="USER";  
    $pop3->debug=0;                          
    $pop3->html_debug=1;                     
    $pop3->join_continuation_header_lines=1; 
    $pop3->Open();
    $pop3->Login($dados['usuario'],$dados['senha'],$apop);
    $pop3->Statistics($messages,$size);
    if ($messages > 0)
    {
        for ($i=1;$i<=$messages;$i++)
        {
            $result=$pop3->ListMessages("",$i);
            $pop3->RetrieveMessage($i,$headers,$body,-1);
            for ($j=0;$j<count($body);$j++)
                if (stripos($body[$j],'Original-Recipient: ') !== false)
                {
                    $email = ExtractEmail($body[$j]);
                    if (strlen($email) > 0)
                    {
                        print $email.'<br>';
                        //mysql_query("UPDATE Clientes SET EmailValido=0 WHERE Email='$email'", $conn);
                        
                    }
                }
            $pop3->DeleteMessage($i);
        }    
    }
    $pop3->Close();
    
    mysql_close($conn);
}

?>