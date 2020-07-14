<?
if (!class_exists("CFuncoes"))
{
    class CFuncoes
    {
        function MakeDate($ano, $mes, $dia)
        {
            if ($mes < 10)
                $mes = "0".$mes;
            if ($dia < 10)
                $dia = "0".$dia;
            return $dia."/".$mes."/".$ano;
        }

        function DiaSemana($d)
        {
            $d = getdate($d);
            switch ($d['wday'])
            {
                case 0: return 'Domingo';break;
                case 1: return 'Segunda-feira';break;
                case 2: return 'Terça-feira';break;
                case 3: return 'Quarta-feira';break;
                case 4: return 'Quinta-feira';break;
                case 5: return 'Sexta-feira';break;
                case 6: return 'Sábado';break;
            }
        }

        function NomeMes($d)
        {
            $d = getdate($d);
            switch ($d['mon'])
            {
                case 1: return 'Janeiro'; break;
                case 2: return 'Fevereiro'; break;
                case 3: return 'Março'; break;
                case 4: return 'Abril'; break;
                case 5: return 'Maio'; break;
                case 6: return 'Junho'; break;
                case 7: return 'Julho'; break;
                case 8: return 'Agosto'; break;
                case 9: return 'Setembro'; break;
                case 10: return 'Outubro'; break;
                case 11: return 'Novembro'; break;
                case 12: return 'Dezembro'; break;
            }
        }

        function MakeFullDate($data)
        {
            $d = getdate($data);
            if ($d[mday] < 10)
                $d[mday] = "0".$d[mday];
            if ($d[mon] < 10)
                $d[mon] = "0".$d[mon];
            if ($d[hours] < 10)
                $d[hours] = "0".$d[hours];
            if ($d[minutes] < 10)
                $d[minutes] = "0".$d[minutes];
            if ($d[seconds] < 10)
                $d[seconds] = "0".$d[seconds];

            $data = $d[mday]."/".$d[mon]."/".$d[year]." - ".$d[hours].":".$d[minutes].":".$d[seconds];
            return $data;
        }

        function GetDateNow()
        {
            return date('Y-m-d H:i:s'); // retorna a data atual formatada no estilo do campo tipo DateTime do MySQL
        }

        function GetYearNow()
        {
            return date('Y');           // retorna o ano atual
        }

        function GetMonthNow()
        {
            return date('m');           // retorna o mes atual
        }

        function GetDayNow()
        {
            return date('d');           // retorna o dia atual
        }

        function ConvertUserDateToMysqlDate($data)
        {
            $com_aspas_simples = false;
            if (strlen($data) == 12)
            {
                $data = str_replace("'", "", $data);
                $com_aspas_simples = true;    
            }
            if (strlen($data) == 10)
            {
                $c = explode('/', $data);
                if ($com_aspas_simples)
                    return "'".$c[2].'-'.$c[1].'-'.$c[0]."'";
                return $c[2].'-'.$c[1].'-'.$c[0];
            }
            return '';
        }

        function ConvertMysqlDateToUserDate($data)
        {
            if (strlen($data) == 10)
            {
                $d = explode ('-', $data);
                return $d[2].'/'.$d[1].'/'.$d[0];
            }
            return '';
        }

        function ConvertMysqlFullDateToUserFullDate($fulldata)
        {
            $dados = explode(' ', $fulldata);
            return $this->ConvertMysqlDateToUserDate($dados[0])." - ".$dados[1];
        }

        function ConvertUserFullDateToMysqlFullDate($fulldata)
        {
            $com_aspas=false;
            if (strlen($fulldata) == 23)
            {
                $fulldata = str_replace("'", "", $fulldata);
                $com_aspas=true;
            }
            $dados = explode(' - ', $fulldata);
            
            if ($com_aspas)
                return "'".$this->ConvertUserDateToMysqlDate($dados[0])." ".$dados[1]."'";
            return $this->ConvertUserDateToMysqlDate($dados[0])." ".$dados[1];
        }

        function GetDateFromMysqlFullDate($fulldata)
        {
            $dados = explode(' ', $fulldata);
            $data = explode('-', $dados[0]);
            return $data;
        }

        function GetTimeFromMysqlFullDate($fulldata)
        {
            $dados = explode(' ', $fulldata);
            $hora = $dados[1];
            return $hora;
        }
        
        function UnitToPercentage($value)
        {
            // formatando valor para porcentagem
            $value = number_format($value, 2, ',','');
            return $value.'%';
        }
        
        function UnitsWith2Decimals($value)
        {
            // formatando valor para porcentagem
            $value = number_format($value, 2, ',','.');
            return $value;
        }
        
       function GetLastDiaMes($mes, $ano)
       {
            switch($mes)
            {
                case 1: return 31; break;
                case 2:
                    {
                        if ($ano%4 == 0)
                            return 29;
                        else
                            return 28;
                    }
                    break;
                case 3: return 31; break;
                case 4: return 30; break;
                case 5: return 31; break;
                case 6: return 30; break;
                case 7: return 31; break;
                case 8: return 31; break;
                case 9: return 30; break;
                case 10: return 31; break;
                case 11: return 30; break;
                case 12: return 31; break;
            }
       }
       
       function RemoverItemVetor($vetor, $pos)
       {
            if ($pos+1 < count($vetor))
                return array_merge(array_slice($vetor, 0, $pos), array_slice($vetor, $pos+1-count($vetor)));
            else
                return array_slice($vetor, 0, $pos);
       }      
    }
}

?>