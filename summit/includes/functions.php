<?php

	function confirm_query($result_set) {
		if (!$result_set) 
			{
	    		die("Database query failed: " . mysql_error());
	    	}
	}
	
	function mysql_clean($value) { 
		$value = stripslashes($value);
		$value = mysql_real_escape_string($value);
		return $value;
	}
	
	function redirect_to($location = NULL) {
		if ($location !=NULL) {
			header("Location: {$location}");
			exit;
		}
	}
	
	function limit_words($string, $word_limit) {
	 $string = strip_tags($string);
     $words = explode(' ', $string);
     return implode(' ', array_slice($words, 0, $word_limit));
   }
   
   function slug($text)
	{
	
		$title = preg_replace("/(.*?)([A-Za-z0-9\s]*)(.*?)/", "$2", $title);
		$title = preg_replace('/\%/',' percent',$title); 
		$text = preg_replace('/\@/',' at ',$text); 
		$text = preg_replace('/\&/',' and ',$text); 
		$text = preg_replace('/\s[\s]+/','-',$text);    // Strip off multiple spaces 
		$text = preg_replace('/[\s\W]+/','-',$text);    // Strip off spaces and non-alpha-numeric 
		$text = preg_replace('/^[\-]+/','',$text); // Strip off the starting hyphens 
		$text = preg_replace('/[\-]+$/','',$text); // // Strip off the ending hyphens 
		$text = strtolower($text); 
	
		// trim and lowercase
		$text = strtolower(trim($text, '-'));
		return $text;
	}
	
	function slug_file($text)
	{
		$ext = substr($text, -4);
		$text = str_replace($ext, '', $text);

		// replace all non letters or digits with -
		$text = preg_replace('/\W+/', '-', $text);
	
		// trim and lowercase
		$text = strtolower(trim($text, '-'));
		$text = $text . $ext;
		return $text;
	} 


 
function exportMysqlToCsv($table,$filename = 'export.csv')
{
    $csv_terminated = "\n";
    $csv_separator = ",";
    $csv_enclosed = '"';
    $csv_escaped = "\\";
    $sql_query = "select * from $table";
 
    // Gets the data from the database
    $result = mysql_query($sql_query);
    $fields_cnt = mysql_num_fields($result);
 
 
    $schema_insert = '';
 
    for ($i = 0; $i < $fields_cnt; $i++)
    {
        $l = $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed,
            stripslashes(mysql_field_name($result, $i))) . $csv_enclosed;
        $schema_insert .= $l;
        $schema_insert .= $csv_separator;
    } // end for
 
    $out = trim(substr($schema_insert, 0, -1));
    $out .= $csv_terminated;
 
    // Format the data
    while ($row = mysql_fetch_array($result))
    {
        $schema_insert = '';
        for ($j = 0; $j < $fields_cnt; $j++)
        {
            if ($row[$j] == '0' || $row[$j] != '')
            {
 
                if ($csv_enclosed == '')
                {
                    $schema_insert .= $row[$j];
                } else
                {
                    $schema_insert .= $csv_enclosed . 
					str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $row[$j]) . $csv_enclosed;
                }
            } else
            {
                $schema_insert .= '';
            }
 
            if ($j < $fields_cnt - 1)
            {
                $schema_insert .= $csv_separator;
            }
        } // end for
 
        $out .= $schema_insert;
        $out .= $csv_terminated;
    } // end while
 
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Length: " . strlen($out));
    // Output to browser with appropriate mime type, you choose ;)
    header("Content-type: text/x-csv");
    //header("Content-type: text/csv");
    //header("Content-type: application/csv");
    header("Content-Disposition: attachment; filename=$filename");
    echo $out;
    exit;
 
}
 
?>