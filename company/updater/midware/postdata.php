<?
include_once('../header_classes.php');

// recebendo $siteid (codigo do site) e $nodeid (codigo do nó)  e $data (base64_encoded)

if ($siteid > 0)
{
    if ($nodeid > 0)
    {
        
        $agora = date('Y-m-d H:i:s');
        $data = base64_decode($data);

        if (mysql_query("INSERT INTO Nodes_Data (CodigoNode, DataHora, Data) VALUES ($nodeid, '$agora', '$data')"))
            print 'OK';
        else
            print 'error - '.mysql_error();
    }
    else
        print 'error';
    
} else
    print 'error';
  
?>

