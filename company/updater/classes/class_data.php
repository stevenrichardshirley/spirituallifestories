<?
if (!class_exists("CDataClass"))
{
    class CDataClass
    {
        var $mItems;
        var $szDisplay;
        var $szChave;
        var $szTableName;
        var $szOrderBy;
        
        function SetUp($table, $chave, $display, $order='')
        {
            $this->szDisplay   = $display;
            $this->szChave     = $chave;
            $this->szTableName = $table;     
            $this->szOrderBy   = $order==''?$display:$order;
        }
        
        function GetSQL()
        {
            $c = strlen($this->szOrderBy)>0?$this->szOrderBy:$this->szDisplay;
            return "SELECT * FROM $this->szTableName ORDER BY $c";
        }
        
        function LoadFromDB()
        {
            $this->mItems=null;           
            $qr = mysql_query($this->GetSQL()) or die(mysql_error());
            while ($obj = mysql_fetch_object($qr))
                $this->mItems[ count($this->mItems) ] = $obj;
        }

        function ExtractFieldList($info)
        {
            $r = get_object_vars($info);
            if (is_array($r))
            {
                $s = null;
                foreach($r as $nome => $valor)
                    $s[count($s)] = $nome;
                return implode(',', $s);
            }
        }

        function ExtractValues($info)
        {
            $r = get_object_vars($info);
            if (is_array($r))
            {
                $s = null;
                foreach($r as $nome => $valor)
                {
                    if ( strcmp($valor, '(nulo)') == 0)
                        $s[count($s)] = "null";
                    else
                        $s[count($s)] = "'$valor'";
                }
                return implode(',', $s);
            }
        }

        function ExtractFieldAndValues($info)
        {
            $r = get_object_vars($info);
            if (is_array($r))
            {
                $s = null;
                foreach($r as $nome => $valor)
                {
                    if (strcmp($valor, '(nulo)')==0)
                        $s[count($s)] = "$nome=null";
                    else
                        $s[count($s)] = "$nome='$valor'";
                }
                return implode(',', $s);
            }
        }

        function PrintCombo($cod=0)
        {
            $c = $this->szChave;
            $lista = explode(';', $this->szDisplay);
            for ($i=0;$i<count($this->mItems);$i++)
            {
                $text = '';
                for ($j=0;$j<count($lista); $j++)
                {
                    $d = $lista[$j];
                    $text .= strlen($text)>0?' - ':'';
                    $text .= $this->mItems[$i]->$d;
                }

                $sel = '';
                if ($this->mItems[$i]->$c == $cod)
                {
                    $sel = 'selected';
                }
                print '<option value="'.$this->mItems[$i]->$c.'" '.$sel.' >'.$text.'</option>';
            }
        }

        function SaveToDB($info, $cod=0)
        {
            if ($cod <= 0 )
            {
                $cod = NextCode($this->szTableName, $this->szChave);
                $campos  = $this->ExtractFieldList($info);
                $valores = $this->ExtractValues($info);
                $sql = "INSERT INTO $this->szTableName ($this->szChave, $campos) VALUES ($cod, $valores)";
            }
            else
            {
                $camposvalores = $this->ExtractFieldAndValues($info);
                $sql = "UPDATE $this->szTableName SET $camposvalores WHERE $this->szChave=$cod";
            }
            mysql_query($sql) or die(mysql_error());

            $this->AfterSave($info, $cod);
            
            return $cod;
        }
        
        function AfterSave($info, $cod)
        {
            // abstract
        }

        function GetItem($cod)
        {
            $c = $this->szChave;
            for ($i=0;$i<count($this->mItems);$i++)
                if ($this->mItems[$i]->$c == $cod)
                    return $this->mItems[$i];
            return 0;
        }

        function GetItemIndex($cod)
        {
            $c = $this->szChave;
            for ($i=0;$i<count($this->mItems);$i++)
                if ($this->mItems[$i]->$c == $cod)
                    return $i;
            return -1;
        }

        function DeleteFromDB($cod)
        {
            mysql_query("DELETE FROM $this->szTableName WHERE $this->szChave=$cod") or die(mysql_error());
            $this->AfterDelete($cod);
        }
        
        function AfterDelete($cod)
        {
            // abstract
        }
    }
}
?>