<?
if (!class_exists("CLogin"))
{
    class CLogin
    {
        var $szEmail;
        var $szSenha;
        var $iCodigo;
        var $szNome;
        var $iTipo;
        var $iCodCliente;
        
        function CLogin()
        {
            $this->iCodFuncionario=0;
        }
        
        function DoLogin($email, $senha)
        {
            if (strlen($email) == 0 || strlen($senha) == 0)
                return false;
            $this->szSenha = $senha;
            
            if ($email == usuario_master && $senha == senha_master)
            {
                $this->iTipo = tipo_login_master;
                $this->szNome = 'Master';
                return true;
            }

            
            // tentando logar como se fosse um usuário
            $r = mysql_fetch_row(mysql_query("SELECT Senha, CodigoUsuario, Nome FROM Usuarios WHERE Email='$email'"));
            if (strcmp($r[0], $senha) == 0)
            {
                $this->iCodigo = $r[1];
                $this->szNome  = $r[2];
                $this->iTipo = tipo_login_usuario;
                return true;
            }
            else
            {
                // tentando como cliente
                $r = mysql_fetch_row(mysql_query("SELECT C.Senha, C.CodigoCliente, C.Nome, C.CodigoUsuario, U.Nome as Usuario FROM 
                                                        Usuarios_Clientes C INNER JOIN Usuarios U ON C.CodigoUsuario=U.CodigoUsuario WHERE C.Email='$email'"));
                if (strcmp($r[0], $senha) == 0)
                {
                    $this->iCodigo = $r[3]; // codigo do usuario
                    $this->szNome  = $r[2];
                    $this->iCodCliente = $r[1];
                    $this->iTipo = tipo_login_cliente;
                    return true;
                } else
                    return false;
                
            }
        }


    }
}

?>