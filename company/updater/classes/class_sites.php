<?
if (!class_exists("CSites"))
{
    class CSites extends CDataClass
    {
        var $iCodUsuario;
        var $iCodCliente;
        var $iCodSite;
        var $bSomenteAtivos;
        
        function CSites()
        {
            $this->szChave   = 'CodigoSite';
            $this->szDisplay = 'Nome';
            $this->szTableName = 'Usuarios_Sites';
            $this->bSomenteAtivos = false;
        }    
        
        function GetSQL()
        {
            $filtro = '0=0';
            if ($this->iCodUsuario > 0 )
                $filtro = "CodigoUsuario=$this->iCodUsuario";
            if ($this->iCodCliente > 0)
                $filtro .= " AND CodigoCliente=$this->iCodCliente";
            if ($this->bSomenteAtivos)
                $filtro .= " AND Ativo=1";
            if ($this->iCodSite > 0)
                $filtro .= " AND CodigoSite=$this->iCodSite";
                
                
            return  "SELECT * FROM Usuarios_Sites WHERE $filtro ORDER BY Nome";
        }
        
        function PrintComboClientes($cod=0, $selprimeiro=false)
        {
            $cod = strlen($cod) == 0?0:$cod;
            $c = $this->szChave;
            $lista = explode(';', $this->szDisplay);
            $codfiliais = null;
            for ($i=0;$i<count($this->mItems);$i++)
                {
                    // listando os codigos das filiais
                    if (!is_array($codclientes) || !in_array($this->mItems[$i]->CodigoCliente, $codclientes))
                        $codclientes[] = $this->mItems[$i]->CodigoCliente;
                }
            if (count($codclientes) > 0)
            {
                $tt = implode(',', $codclientes);
                $qr = mysql_query("SELECT CodigoCliente, Nome FROM Usuarios_Clientes WHERE CodigoCliente IN ($tt) ORDER BY Nome");
                $primeiro = true;
                while ($obj = mysql_fetch_object($qr))
                {
                    print '<option value="0" style="color:#888888" disabled>Cliente '.$obj->Nome.'</option>';                    
                    for ($i=0;$i<count($this->mItems);$i++)
                        if ($this->mItems[$i]->CodigoCliente == $obj->CodigoCliente)
                        {
                            if ($selprimeiro)
                            {
                                $sel = ( ($this->mItems[$i]->CodigoSite == $cod) || ($cod==0 && $primeiro && $i==0)) ?'selected':'';
                                if ($cod==0 && $primeiro && $i==0)
                                    $primeiro = false;
                            }
                            else
                                $sel = ($this->mItems[$i]->CodigoSite == $cod)?'selected':'';
                            print '<option value="'.$this->mItems[$i]->CodigoSite.'" '.$sel.' >&nbsp;&nbsp;&nbsp;'.$this->mItems[$i]->Nome.'</option>';
                        }
                }
                // os sem cliente
                $primeiro = true;
                for ($i=0;$i<count($this->mItems);$i++)
                    if ($this->mItems[$i]->CodigoCliente <= 0)
                    {
                        if ($primeiro)
                        {
                            print '<option value="0" style="color:#888888" disabled>(sem cliente)</option>';                    
                            $primeiro = false;
                        }
                        
                        if ($selprimeiro)
                        {
                            $sel = ( ($this->mItems[$i]->CodigoSite == $cod) || ($cod==0 && $primeiro && $i==0)) ?'selected':'';
                            if ($cod==0 && $primeiro && $i==0)
                                $primeiro = false;
                        }
                        else
                            $sel = ($this->mItems[$i]->CodigoSite == $cod)?'selected':'';
                        print '<option value="'.$this->mItems[$i]->CodigoSite.'" '.$sel.' >&nbsp;&nbsp;&nbsp;'.$this->mItems[$i]->Nome.'</option>';
                    }
                
            }

        }        
        
    }
}

?>