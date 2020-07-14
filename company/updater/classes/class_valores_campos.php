<?
if (!class_exists("CValoresCampos"))
{
    class CValoresCampos extends CDataClass
    {
        var $iCodCampo;
        
        function CValoresCampos()
        {
            $this->szChave   = 'CodigoValor';
            $this->szDisplay = 'ValorDisplay';
            $this->szTableName = 'Campos_ListaValores';
        }    
        
        function GetSQL()
        {
            $filtro = '0=0';
            if ($this->iCodCampo > 0 )
                $filtro = "CodigoCampoAcao=$this->iCodCampo";
                
            return  "SELECT * FROM Campos_ListaValores WHERE $filtro ORDER BY ValorDisplay";
        }
    }
}

?>