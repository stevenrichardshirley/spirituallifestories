<?
include_once('header_classes.php');

$funcoes = new CFuncoes();

$tipos = new CTiposAcaoProspect();
$tipos->LoadFromDB();
if (strlen($codtipo) == 0)
    $codtipo = $tipos->GetCodeListArray();

$filiais= new CFiliais();
$filiais->LoadFromDB();
if (strlen($codfilial) == 0 || $objLogin->iFuncao == funcao_backoffice || $objLogin->iFuncao == funcao_supervisor)
    $codfilial = $objLogin->iCodigoFilial>0?$objLogin->iCodigoFilial:$filiais->mItems[0]->CodigoFilial;

$funcionarios = new CFuncionarios();
$funcionarios->iCodFilial = $codfilial;
$funcionarios->bSomenteAtivos = true;
$funcionarios->LoadFromDB();      

$tipoacao = new CTiposAcaoProspect();
$tipoacao->LoadFromDB();

$acoes = new CAcoesProspects(0);
    
if ($acao == 'remover')
{
    $acao = 'consultar';
    $acoes->DeleteFromDB($codremover);
} 

if ($acao == 'transferir')
{
    $do_transferir = true;
    $acao = 'consultar';
}

if ($acao == 'consultar')
{
    $acoes->iCodFilial = $codfilial;
    $acoes->mCodTipo = $codtipo;
    $acoes->szDetalhes = $detalhes;
    $acoes->szDataIni = strlen($dtini)==10?$funcoes->ConvertUserDateToMysqlDate($dtini):'';
    $acoes->szDataFim = strlen($dtfim)==10?$funcoes->ConvertUserDateToMysqlDate($dtfim):'';
    $acoes->iCodFuncionario = $codfuncionario;
    $acoes->bOrdemInversa = true;
    $acoes->LoadFromDB();
    
    if ($do_transferir)
    {
        // aplicando transferência
        $rr = $acoes->GetListFieldValues('CodigoProspect', true);
        if (count($rr) > 0)
        {
            $codigos = implode(',',$rr);
            mysql_query("UPDATE Prospects SET CodigoFuncionario=$codtransferir WHERE CodigoProspect IN ($codigos)");
            // agendamentos de transferência
            $agenda = new CAgendamentos(0);
            if (!$objLogin->bMaster && $objLogin->iCodFuncionario > 0)
                for ($i=0;$i<count($rr);$i++)        
                    $agenda->AgendamentoTransferencia($objLogin->iCodFuncionario, $codtransferir, $rr[$i]);            
            $msg = 'Prospect(s) transferido(s) com sucesso';
        }
    }
    
    // objeto de resultados
    $resultados = new CResultados();
    $resultados->AddColuna('Data',10);
    $resultados->AddColuna('Horário',5);
    $resultados->AddColuna('Funcionário',15);
    $resultados->AddColuna('Tipo',10);
    $resultados->AddColuna('Duração',5);
    $resultados->AddColuna('Detalhes',25);
    $resultados->AddColuna('Prospect',30);

}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
    <link rel="stylesheet" type="text/css" href="smoothness/jquery-ui-1.8.4.custom.css">
    <link rel="stylesheet" type="text/css" href="checklist/ui.dropdownchecklist.themeroller.css">
<link rel="stylesheet" href="includes/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<SCRIPT type="text/javascript" src="includes/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<link rel="stylesheet" type="text/css" href="visual.css">
     <script type="text/javascript" src="jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="jquery-ui-1.8.4.custom.min.js"></script>
    <script type="text/javascript" src="checklist/ui.dropdownchecklist.js"></script>

<script>
$(document).ready(function() {
    $("#stipo").dropdownchecklist( { width:200, explicitClose: '[ok]'} );
});

function onrel()
{
    window.open('relatorio.php?titulo=Listagem Detalhada de Ações');
}

function ontransferir()
{
    if (confirm('Deseja transferir todos os prospects listados para o funcionário selecionado?'))
    {
        document.ff.acao.value = 'transferir';
        return true;
    } else
        return false;
}

function onfiltrotipo(codtipo)
{
    document.ff.codtipo.value = codtipo;
    document.ff.acao.value = 'consultar';
    document.ff.submit();
}
function MM_formt(e,src,mask) {
        if(window.event) { _TXT = e.keyCode; } 
        else if(e.which) { _TXT = e.which; }
        if(_TXT > 47 && _TXT < 58) { 
  var i = src.value.length; var saida = mask.substring(0,1); var texto = mask.substring(i)
  if (texto.substring(0,1) != saida) { src.value += texto.substring(0,1); } 
     return true; } else { if (_TXT != 8) { return false; } 
  else { return true; }
        }
}

function ondel(cod)
{
    if (confirm('Remover a ação selecionada?'))
    {
        document.ff.codremover.value = cod;
        document.ff.acao.value = 'remover';
        document.ff.submit();
    }
}

function onconsultar()
{
    document.ff.acao.value = 'consultar';
    return true;
}

</script>
</head>
<body>
<form name="ff" method="post">
<input type="hidden" name="codremover">
<input type="hidden" name="acao">
<table width="100%">
    <tr>
        <td class="cad_title">Consulta detalhada de ações</td>
    </tr>
    <tr>
        <td >
            <fieldset>
                <legend class="group_label">Filtros</legend>
                <table width="100%">
                    <tr>
                        <td class="field_caption">
                            <table width="100%" >
                                <tr>
                                    <td width="30%" class="common">Filial</td>
                                    <td width="50%" class="common">Funcionário</td>
                                    <td width="20%" class="common">Tipo de ação</td>
                                </tr>
                                <tr >
                                    <tD><select name="codfilial" style="width:100%" <?if($objLogin->iFuncao == funcao_backoffice || $objLogin->iFuncao == funcao_supervisor){?>disabled<?}?> onchange="document.ff.submit()"><?$filiais->PrintCombo($codfilial);?></select></td>
                                    <td><select name="codfuncionario" style="width:100%">
                                        <option value="0">(todos)</option>
                                        <?$funcionarios->PrintCombo($codfuncionario)?>
                                    </select></td>
                                    <td><select name="codtipo[]" id="stipo" style="width:100%" multiple="multiple">
                                        <?
                                        for ($i=0;$i<count($tipos->mItems);$i++)
                                        {
                                        ?>
                                            <option <?if (is_array($codtipo) && in_array($tipos->mItems[$i]->CodigoTipoAcao,$codtipo)){?>selected<?}?> value="<?=$tipos->mItems[$i]->CodigoTipoAcao?>"><?=$tipos->mItems[$i]->TipoAcao?></option>
                                        <?
                                        }
                                        ?>
                                    </select></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="field_caption">
                            <table width="100%">
                                <tr>
                                    <td width="10%" class="common">Período</td>
                                    <td width="60%" class="common">Detalhes (parcial)</td>
                                    <td width="30%" rowspan="2" align="right"><input type="submit" value="Consultar" onclick="return onconsultar()"></td>                                
                                </tr>
                                <tr >
                                    <td nowrap class="common">De <input type="text" name="dtini" size="10" maxlength="10" onkeypress="return MM_formt(event,this,'##/##/####');" value="<?=$dtini?>"><input name="button" type="button" class="formulario" onClick="displayCalendar(document.ff.dtini,'dd/mm/yyyy',this)" value="..."> 
                                        até <input type="text" name="dtfim" size="10" maxlength="10" onkeypress="return MM_formt(event,this,'##/##/####');" value="<?=$dtfim?>"><input name="button" type="button" class="formulario" onClick="displayCalendar(document.ff.dtfim,'dd/mm/yyyy',this)" value="..."></td>
                                    <td class="common"><input type="text" name="detalhes" style="width:100%" value="<?=$detalhes?>"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </fieldset>
            <p align="center" class="aviso"><?=$msg?></p>
            <?
            if ($acao == 'consultar')
            {
            ?>
            <fieldset>
                <legend class="group_label">Ações encontradas</legend>
                
                <span class="titulo"><?=$grupos[$g]->Caption?></span>
                
                <table width="100%">
                    <tr>
                        <td width="10%" class="table_title">Data</td>
                        <td width="5%"class="table_title">Horário</td>
                        <td width="15%"class="table_title">Funcionário</td>
                        <td width="10%"class="table_title">Tipo</td>
                        <td width="5%"class="table_title">Duração</td>
                        <td width="25%"class="table_title">Detalhes</td>
                        <td width="30%"class="table_title">Prospect</td>
                    </tr>
                    <?
                    $tot_dur = 0;
                    $qt_tipos = null;
                    for ($i=0;$i<count($acoes->mItems);$i++)                    
                    {
                        $ii = $i%2==0?'0':'1';
                        list($data,$hora) = explode(' ', $acoes->mItems[$i]->DataHora);
                        list($ano,$mes,$dia) = explode('-',$data);
                        $dd = mktime(0,0,0,$mes,$dia,$ano);
                        
                        $qt_tipos[$acoes->mItems[$i]->CodigoTipoAcao]++;
                        
                        $durstr = intval($acoes->mItems[$i]->Duracao/60);
                        $durstr = str_pad($durstr, 2, '0', STR_PAD_LEFT).':'.str_pad($acoes->mItems[$i]->Duracao-($durstr*60), 2, '0', STR_PAD_LEFT);

                        $tot_dur += $acoes->mItems[$i]->Duracao;

                        $info[0] = $funcoes->ConvertMysqlDateToUserDate(substr($acoes->mItems[$i]->DataHora, 0,10)).' '.substr($funcoes->DiaSemana($dd),0,3);
                        $info[1] = substr($acoes->mItems[$i]->DataHora, 11,5);
                        $info[2] = $acoes->mItems[$i]->Funcionario;
                        $info[3] = $acoes->mItems[$i]->TipoAcao;
                        $info[4] = $durstr;
                        $info[5] = $acoes->mItems[$i]->Detalhes;
                        $info[6] = $acoes->mItems[$i]->Prospect;
                        $resultados->AddItem($info);
                        
                    ?>
                    <tr>
                        <td class="table_item<?=$ii?>"><?=$info[0]?></td>
                        <td class="table_item<?=$ii?>"><?=$info[1]?></td>
                        <td class="table_item<?=$ii?>"><?=$info[2]?></td>
                        <td class="table_item<?=$ii?>"><?=$info[3]?></td>
                        <td class="table_item<?=$ii?>"><?=$info[4]?></td>
                        <td class="table_item<?=$ii?>"><?=$info[5]?></td>
                        <td class="table_item<?=$ii?>"><a href="index2.php?acessar=cad_prospects.php&acao=editar&codsel=<?=$acoes->mItems[$i]->CodigoProspect?>"><?=$acoes->mItems[$i]->Prospect?></a></td>
                        <tD><?if ($objLogin->bMaster || $objLogin->iFuncao == funcao_gerente){?><a href="javascript:ondel(<?=$acoes->mItems[$i]->CodigoAcaoProspect?>)">[x]</a><?}?></td>
                    </tr>
                    <?
                    }
                    $durstr = intval($tot_dur/60);
                    $durstr = str_pad($durstr, 3, '0', STR_PAD_LEFT).':'.str_pad($tot_dur-($durstr*60), 2, '0', STR_PAD_LEFT)

                    ?>
                    <tr>
                        <td colspan="4">&nbsp;</td>
                        <td class="common"><center><?=$durstr?><br><font size="1" color="#999999">mmm:ss</td>
                    </tr>
                </table>
                <span class="field_caption">Total de ações listadas: <b><?=count($acoes->mItems)?></b><br><br><BR><BR>            
                
                <div align="right"><a href="javascript:onrel()"><img src="images/printer.jpg" border="0"></a>
                
                <br><br>
                Transferir TODOS os prospects acima listados para: <select name="codtransferir">
                    <?
                    $funcionarios->PrintCombo(0);
                    ?>
                </select>
                <input type="submit" value="Transferir" onclick="return ontransferir()">
                
                <?
                if (count($agendamentos->mItems) > 0)
                {
                ?>
                Transferir os agendamentos acima listados para <select name="codtransferir"><?$funcionarios->PrintCombo()?></select><input type="submit" value="Transferir" onclick="return ontransferir()">
                <?
                }
                ?>
            </fieldset>           
            
            <br><br><table align="center" style="border:1px solid #DDDDDD">
                    <tr>
                        <td colspan="4" height="30" class="common" style="background-color:#DDDDDD" align="center"><b>Análise de quantidade de ações por tipo</td>
                    </tr>
                    <tr>
                        <td ><img src="images/spacer.gif" height="1" width="100px"></td>
                        <td ><img src="images/spacer.gif" height="1" width="500px"></td>
                    </tr>
                    <tr>
                        <td class="common" style="border:1px solid #DDDDDD" align="center"><b>Tipo</td>
                        <td class="common" style="border:1px solid #DDDDDD" align="center">&nbsp;</td>
                        <td style="border:1px solid #DDDDDD" align="center" class="common"><b>%</td>
                        <td style="border:1px solid #DDDDDD" align="center" class="common"><b>Qtde</td>
                    </tr>
                    <?
                    $totgeral = count($acoes->mItems);
                    
                    for ($i=0;$i<count($tipos->mItems);$i++)
                    {
                        $larg = round(500*(intval($qt_tipos[$tipos->mItems[$i]->CodigoTipoAcao])/max(1,$totgeral)),0);
                    ?>
                        <tr>
                            <td class="common" style="border:1px solid #DDDDDD" align="left"><a href="javascript:onfiltrotipo(<?=$tipos->mItems[$i]->CodigoTipoAcao?>)" title="Clique para visualizar somente as ações deste tipo"><?=$tipos->mItems[$i]->TipoAcao?></a></td>
                            <td class="common" style="border:1px solid #DDDDDD"><div style="background-color:red; height:20px; width:<?=$larg?>px"></td>
                            <td class="common" style="border:1px solid #DDDDDD" align="center"><?=number_format(100*intval($qt_tipos[$tipos->mItems[$i]->CodigoTipoAcao])/max(1,$totgeral), 2, ',','')?>%</td>
                            <td class="common" style="border:1px solid #DDDDDD" align="center"><?=intval($qt_tipos[$tipos->mItems[$i]->CodigoTipoAcao])?></td>
                        </tr>
                    
                    <?
                    }
                    ?>
                </table>

            <?
            }
            ?>
        </td>
    </tr>
</table>
<?
if (session_is_registered('resultados'))
    session_unregister("resultados");
session_register("resultados");
?>
</form>
</body>
</html>