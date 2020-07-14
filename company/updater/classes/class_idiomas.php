<?
if (!class_exists("CIdiomas"))
{
    class CIdiomas extends CDataClass
    {
        var $iCodSite;
        
        function CIdiomas()
        {
            $this->szChave   = 'CodigoIdioma';
            $this->szDisplay = 'Idioma';
            $this->szTableName = 'Idiomas';
            $this->szOrderBy = 'CodigoIdioma';
        }    
        
        function GetSQL()
        {
            $filtro = '0=0';
            if ($this->iCodSite > 0)
                $filtro = "EXISTS(SELECT SI.CodigoIdioma FROM Sites_Idiomas SI WHERE SI.CodigoSite=$this->iCodSite AND SI.CodigoIdioma=I.CodigoIdioma)";
            return "SELECT I.* FROM Idiomas I WHERE $filtro ORDER BY I.CodigoIdioma";
        }
    }
}

?>