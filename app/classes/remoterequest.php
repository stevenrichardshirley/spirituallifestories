<?php
class RemoteRequest
{
	 /**
     * get function.
     * 
     * @access public
     * @param mixed $url
     * @param mixed array $get. (default: NULL)
     * @param array array $options. (default: array())
     * @return array
     */
    public function get($url, array $get= array(), array $options= array()) {
        $defaults = array( 
            CURLOPT_URL => $url. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($get), 
            CURLOPT_HEADER => 0, 
            CURLOPT_RETURNTRANSFER => TRUE, 
            CURLOPT_TIMEOUT => 4 
        ); 

        $ch = curl_init(); 
        curl_setopt_array($ch, ($options + $defaults)); 
        if( ! $result = curl_exec($ch)) { 
            trigger_error(curl_error($ch)); 
        } 
        curl_close($ch); 
        return $result;
    }
}
?>