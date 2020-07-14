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
    }
}

?>