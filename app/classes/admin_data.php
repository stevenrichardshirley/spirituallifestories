<?
if (!class_exists("admin_data"))
{
    class admin_data
    {
        var $mItems;
        var $szDisplay;
        var $szChave;
        var $szTableName;
        var $szOrder;
        var $szLastError;
        var $mDeleteTablesCheck;
        var $szFilters;
        
        function SetUp($table, $chave, $display)
        {
            $this->szDisplay   = $display;
            $this->szChave     = $chave;
            $this->szTableName = $table;     
        }
        
        function GetSQL()
        {
            if ($this->szOrder > '')
                $c = "`{$this->szOrder}`";
            else
            {
                $c = explode(';',$this->szDisplay);
                $c=$c[0];          
            }
            
            $filters = '0=0';
            if (isset($this->szFilters))
                $filters = $this->szFilters;
            
            return "SELECT * FROM $this->szTableName WHERE $filters ORDER BY $c";
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
                    $s[count($s)] = "`{$nome}`";
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
                        $s[count($s)] = "`$nome`=null";
                    else
                        $s[count($s)] = "`$nome`='$valor'";
                }
                return implode(',', $s);
            }
        }
        
        function TotalizarColuna($coluna)
        {
            $tot =0;
            for ($i=0;$i<count($this->mItems);$i++)
                $tot += $this->mItems[$i]->$coluna;
            return $tot;
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
            $this->BeforeSave($info, $cod);
            
            $novoreg = ($cod<=0);
            if ($cod <= 0 )
            {
                //$cod = NextCode($this->szTableName, $this->szChave);
                $campos  = $this->ExtractFieldList($info);
                $valores = $this->ExtractValues($info);
                $sql = "INSERT INTO $this->szTableName ($campos) VALUES ($valores)";
            }
            else
            {
                $camposvalores = $this->ExtractFieldAndValues($info);
                $sql = "UPDATE $this->szTableName SET $camposvalores WHERE $this->szChave=$cod";
            }
            
            mysql_query($sql) or die(mysql_error());
            
            if ($novoreg)
                $cod = mysql_insert_id();

            $this->AfterSave($info, $cod, $novoreg);
            
            return $cod;
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

        function TestDelete($cod)
        {
            if (is_array($this->mDeleteTablesCheck) && count($this->mDeleteTablesCheck) > 0)
            {
                for ($i=0;$i<count($this->mDeleteTablesCheck);$i++)
                {
                    $table = $this->mDeleteTablesCheck[$i];
                    $field = $this->szChave;
                    $c = mysql_fetch_row(mysql_query("SELECT count(*) FROM $table WHERE $field=$cod"));
                    if ($c[0] > 0)
                    {
                        $this->szLastError = 'Impossível excluir este registro pois ele está relacionado a um ou mais registros em "<B>'.$table.'</b>"';
                        return false;
                    }
                    
                }
                return true;
            }
            else
                return true;
            
        }
        
        function DeleteFromDB($cod)
        {
            if ($this->TestDelete($cod))
            {
                $this->BeforeDelete($cod);
                mysql_query("DELETE FROM $this->szTableName WHERE $this->szChave=$cod") or die(mysql_error());
                $this->AfterDelete($cod);
                return true;
            }
            else
                return false;
        }
        
        function BeforeSave($info, $cod)
        {
            // abstract
        }
        
        function AfterSave($info, $cod, $newreg)
        {
            // abstract
        }

        function BeforeDelete($cod)
        {
            // abstract
        }
  
        function AfterDelete($cod)
        {
            // abstract
        }
        function GetFieldListString($field)
        {
            $r = null;
            for ($i=0;$i<count($this->mItems);$i++)
                $r[] = $this->mItems[$i]->$field;
            if (count($r) > 0)
                return implode(',',$r);
            else
                return '';
        }

        function GetFieldListStringExclusive($field)
        {
            $r = null;
            for ($i=0;$i<count($this->mItems);$i++)
                if (!is_array($r) || !in_array($this->mItems[$i]->$field,$r))
                    $r[] = $this->mItems[$i]->$field;
            if (count($r) > 0)
                return implode(',',$r);
            else
                return '';
        }
        
        function LoadChilds($sql, $key, $varname)
        {
            // para melhor desempenho, montar $sql sempre ORDER BY $key !!!
            // ainda no $sql, usar %cods% para se referenciar aos codigos dos pais existentes em mItems
            if (count($this->mItems) > 0)
            {
                $cods = $this->GetFieldListString($key);
                $sql = str_replace('%cods%', $cods, $sql);
                $qr = mysql_query($sql);
                $idx = -1;
                while ($obj = mysql_fetch_object($qr))
                {
                    if ($idx == -1 || $this->mItems[$idx]->$key != $obj->$key)
                        $idx = $this->GetItemIndex($obj->$key);
                    eval("\$this->mItems[\$idx]->".$varname."[count(\$this->mItems[\$idx]->".$varname.")] = \$obj;");
                }
            }
        }
    }
}
?>