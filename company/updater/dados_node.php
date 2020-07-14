<?
include_once('header_classes.php');
$funcoes = new CFuncoes();

$lista = null;

$qr = mysql_query("SELECT DataHora, Data FROM Nodes_Data WHERE CodigoNode=$codsel ORDER BY DataHora");
while ($obj=mysql_fetch_object($qr))
    $lista[] = $obj;
    
if ($exportar == 1)
{
    header('Expires: 0');
    header('Cache-control: private');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Content-Description: File Transfer');
    header('Content-Type: application/vnd.ms-excel');
    header('Content-disposition: attachment; filename="exportar.csv"');
    
    print 'Data/hora;Dado'.chr(13).chr(10);
    for ($i=0;$i<count($lista);$i++)
        print $funcoes->ConvertMysqlDateToUserDate(substr($lista[$i]->DataHora,0,10)).' '.substr($lista[$i]->DataHora, 11, 5).';'.
                $lista[$i]->Data.';'.chr(13).chr(10);
    exit;
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<link rel="stylesheet" type="text/css" href="visual.css">
<script>
</script>
</head>
<body>
<form name="ff" method="post">
<input type="hidden" name="acao">
<table width="100%">
    <tr>
        <td class="cad_title">Dados de usuário cadastrados no item selecionado</td>
    </tr>
    <tr>
        <td >
            <table width="100%">
                <tr>
                    <Td class="table_title" width="10%">Data/hora</td>
                    <Td class="table_title" width="90%">Informação</td>
                </tr>
            <?
            for ($i=0;$i<count($lista);$i++)
            {
                $obj = $lista[$i]
            ?>
                <Tr>
                    <Td nowrap class="table_item0"><?=$funcoes->ConvertMysqlDateToUserDate(substr($obj->DataHora,0,10))?><br><font size=1><?=substr($obj->DataHora, 11, 5)?></font></td>
                    <Td class="table_item0"><?=$obj->Data?></td>
                </tr>
            <?
            }
            ?>
            </table>
            
            <br><BR><a href="dados_node.php?codsel=<?=$codsel?>&exportar=1" target="_blank">Exportar em CSV</a>

        </td>
    </tr>
</table>
</form>  
</body>
</html>