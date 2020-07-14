<?
if (!class_exists("CClientes"))
{
    class CClientes extends CDataClass
    {
        var $iCodUsuario;
        
        function CClientes()
        {
            $this->szChave   = 'CodigoCliente';
            $this->szDisplay = 'Nome';
            $this->szTableName = 'Usuarios_Clientes';
        }    
        
        function GetSQL()
        {
            $filtro = '0=0';
            if ($this->iCodUsuario > 0 )
                $filtro = "CodigoUsuario=$this->iCodUsuario";
                
            return  "SELECT * FROM Usuarios_Clientes WHERE $filtro ORDER BY Nome";
        }
    }
}

?>