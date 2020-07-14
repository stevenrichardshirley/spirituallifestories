<?
if (!class_exists("CCamposAcoes"))
{
    class CCamposAcoes extends CDataClass
    {
        var $iCodAcao;
        
        function CCamposAcoes()
        {
            $this->szChave   = 'CodigoCampoAcao';
            $this->szDisplay = 'CampoDisplay';
            $this->szTableName = 'Acoes_Campos';
        }    
        
        function GetSQL()
        {
            $filtro = '0=0';
            if ($this->iCodAcao > 0 )
                $filtro = "CodigoAcao=$this->iCodAcao";
                
            return  "SELECT * FROM Acoes_Campos WHERE $filtro ORDER BY CodigoCampoAcao";
        }
    }
}

?>