<?
if (!class_exists("admin_login"))
{
    class admin_login
    {
        var $bMaster;
        // dados do funcionário logado
        var $szLogin;
        var $szSenha;
        var $iCodFuncao;
        var $szFuncao;
        var $iCodigo;
        var $szNome;
        
        function DoLogin($usuario, $senha)
        {
            $this->szLogin = $usuario;
            if (strlen($usuario) == 0 || strlen($senha) == 0)
                return false;
            $this->szSenha = $senha;
            if ($usuario == master_user && $senha == master_pass)
            {
                $this->bMaster = true;
                return true;
            }
            else
                return false;
        }
        
        function GetFuncaoStr()
        {
            if ($this->bMaster)
                return 'Senha master';
            else 
                return $this->szFuncao;
        }
    }
}

?>