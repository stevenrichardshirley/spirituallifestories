<?
if (!class_exists("CMenu"))
{
    class CMenu
    {
        var $mGrupos;

        function CMenu()
        {
            $this->mGrupos = null;
        }

        function AddGrupo($g)
        {
            $grupo->Grupo = $g;
            $grupo->mItems = null;
            $this->mGrupos[count($this->mGrupos)] = $grupo;
            return count($this->mGrupos)-1;
        }

        function AddOpcao($idxgrupo, $btn, $pag)
        {
            $m->Botao = $btn;
            $m->Pagina = $pag;
            $this->mGrupos[$idxgrupo]->mItems[ count($this->mGrupos[$idxgrupo]->mItems) ] = $m;
        }


    }
}

?>