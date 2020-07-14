<?
if (!class_exists("CTiposAcoes"))
{
    class CTiposAcoes extends CDataClass
    {
        function CTiposAcoes()
        {
            $this->szChave   = 'CodigoTipoAcao';
            $this->szDisplay = 'TipoAcao';
            $this->szTableName = 'Acoes_Tipos';
        }    
    }
}

?>