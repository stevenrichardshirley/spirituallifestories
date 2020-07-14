<?
if (!class_exists("CUsuarios"))
{
    class CUsuarios extends CDataClass
    {
        function CUsuarios()
        {
            $this->szChave   = 'CodigoUsuario';
            $this->szDisplay = 'Nome';
            $this->szTableName = 'Usuarios';
        }    
    }
}

?>