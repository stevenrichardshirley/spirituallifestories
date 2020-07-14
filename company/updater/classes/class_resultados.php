<?
if (!class_exists("CResultados"))
{
    class CResultados
    {
        var $mColunas;
        var $mInfo;
        var $szRodape;

        function CResultados()
        {
            $this->mColunas = null;
            $this->mInfo = null;
        }

        function AddColuna($titulo, $tamanho, $alinhamento='L')
        {
            $i = count($this->mColunas);
            $this->mColunas[$i]->Titulo = $titulo;
            $this->mColunas[$i]->Tamanho = $tamanho;
            $this->mColunas[$i]->Alinhamento = $alinhamento;

        }

        function AddItem($info)
        {
            if (count($info) == count($this->mColunas))
            {
                $i = count($this->mInfo);
                $this->mInfo[$i] = $info;
            }
        }

        function GetAlign($idxcol)
        {
            $s = $this->mColunas[$idxcol]->Alinhamento;
            if ($s == 'C')
                return 'center';
            else if ($s == 'R')
                return 'right';
            else
                return 'left';

        }


    }

}

?>