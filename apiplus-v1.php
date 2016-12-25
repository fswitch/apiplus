<?php

class apiplus
{
    public function _api_auth($email,$password_md5)
    {
        
        $e = urlencode($email);
        $p = urlencode($password_md5);

        $q = 'email='.$e.'&password_md5='.$p;
        
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL,'http://brainapi.next.in.ua/v1/auth');
        curl_setopt($c, CURLOPT_HEADER, 0);
        curl_setopt($c, CURLOPT_HTTPHEADER, array('X-Requested-With: XMLHttpRequest'));
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c, CURLOPT_REFERER, '');
        //curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($c, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36');
        curl_setopt($c, CURLOPT_POSTFIELDS, $q);
        curl_setopt($c, CURLOPT_TIMEOUT, 15);
        curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 15);
        $rC = $this->_curl_redir_exec($c);
        curl_close($c);

        $js = json_decode($rC);
        
        $arr = array(
            'error' => 1,
            'error_code' => 0,
            'error_message' => '',
            'sid' => ''
        );
        
        if ($js->error == 0){
            $arr['error'] = 0;
            $arr['error_code'] = 0;
            $arr['error_message'] = '';
            $arr['sid'] = $js->sid;
        }
        else {
            $arr['error'] = 1;
            $arr['error_code'] = 0;
            $arr['error_message'] = $js->msg;
            $arr['sid'] = '';
        }
        
        return $arr;
    }
    
    public function _products_updates($sid,$day=0)
    {
        $arr = array(
            'error' => 1,
            'error_code' => 0,
            'error_message' => '',
            'products' => array()
        );
        
        if (!empty($sid) && in_array($day,range(0,7)))
        {
            $get = $this->_get_uri('http://brainapi.next.in.ua/v1/products_updates?sid='.$sid.'&day='.$day);
            $js = json_decode($get);
            if ($js->error == 1){
                return array('error'=>1,'error_code'=>$js->code,'error_message'=>$js->msg,'products'=>array());
            } else {
                return array('error'=>0,'error_code'=>0,'error_message'=>'','products'=>$js->products);
            }
        }
        
        return $arr;
    }
    
    public function _categories($sid)
    {
        $arr = array(
            'error' => 1,
            'error_code' => 0,
            'error_message' => '',
            'categories' => array()
        );
        
        if (!empty($sid))
        {
            $get = $this->_get_uri('http://brainapi.next.in.ua/v1/categories?sid='.$sid);
            $js = json_decode($get);
            if ($js->error == 1){
                return array('error'=>1,'error_code'=>$js->code,'error_message'=>$js->msg,'categories'=>array());
            } else {
                return array('error'=>0,'error_code'=>0,'error_message'=>'','categories'=>$js->categories);
            }
        }
        
        return $arr;
    }
    
    public function _vendors($sid)
    {
        $arr = array(
            'error' => 1,
            'error_code' => 0,
            'error_message' => '',
            'vendors' => array()
        );
        
        if (!empty($sid))
        {
            $get = $this->_get_uri('http://brainapi.next.in.ua/v1/vendors?sid='.$sid);
            $js = json_decode($get);
            if ($js->error == 1){
                return array('error'=>1,'error_code'=>$js->code,'error_message'=>$js->msg,'vendors'=>array());
            } else {
                return array('error'=>0,'error_code'=>0,'error_message'=>'','vendors'=>$js->vendors);
            }
        }
        
        return $arr;
    }
    
    public function _currency($sid)
    {
        $arr = array(
            'error' => 1,
            'error_code' => 0,
            'error_message' => '',
            'currency' => array()
        );
        
        if (!empty($sid))
        {
            $get = $this->_get_uri('http://brainapi.next.in.ua/v1/currency?sid='.$sid);
            $js = json_decode($get);
            if ($js->error == 1){
                return array('error'=>1,'error_code'=>$js->code,'error_message'=>$js->msg,'currency'=>array());
            } else {
                return array('error'=>0,'error_code'=>0,'error_message'=>'','currency'=>$js->currency);
            }
        }
        
        return $arr;
    }
    
    public function _product($sid,$prod_id)
    {
        $arr = array(
            'error' => 1,
            'error_code' => 0,
            'error_message' => '',
            'product' => array()
        );
        
        if (!empty($sid) && is_numeric($prod_id))
        {
            $get = $this->_get_uri('http://brainapi.next.in.ua/v1/product?sid='.$sid.'&id='.(int)($prod_id));
            $js = json_decode($get);
            if ($js->error == 1){
                return array('error'=>1,'error_code'=>$js->code,'error_message'=>$js->msg,'product'=>array());
            } else 
            {
                $dir = mb_substr($js->product->product_code, (mb_strlen($js->product->product_code)-2));
                if (!empty($js->product->picture)){
                    $js->product->picture = 'http://brain.next.in.ua/static/images/products/'.$dir.'/'.$js->product->picture;
                }
                
                $imgs = array();
                foreach ($js->product->images as $img)
                {
                    $imgs[] = 'http://brain.next.in.ua/static/images/products/'.$dir.'/'.$img;
                }
                $js->product->images = $imgs;
                return array('error'=>0,'error_code'=>0,'error_message'=>'','product'=>$js->product);
            }
        }
        
        return $arr;
    }
    
    public function _prices_retail($sid)
    {
        $arr = array(
            'error' => 1,
            'error_code' => 0,
            'error_message' => '',
            'prices_retail' => array()
        );
        
        if (!empty($sid))
        {
            $get = $this->_get_uri('http://brainapi.next.in.ua/v1/prices_retail?sid='.$sid);
            $js = json_decode($get);
            if ($js->error == 1){
                return array('error'=>1,'error_code'=>$js->code,'error_message'=>$js->msg,'prices_retail'=>array());
            } else 
            {
                return array('error'=>0,'error_code'=>0,'error_message'=>'','prices_retail'=>$js->prices_retail);
            }
        }
        
        return $arr;
    }
    
    public function _download_image($img_uri,$to_file)
    {
        $ch = curl_init ($img_uri);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        $raw=curl_exec($ch);
        curl_close ($ch);
        if (!file_exists($to_file))
        {
            $fp = fopen($to_file,'x');
            fwrite($fp, $raw);
            fclose($fp);
        }
        return 1;
    }
    
    private function _get_uri($uri='')
    {
        if (empty($uri)){
            return array('error'=>1,'msg'=>'No URI');
        }
        
        $c = curl_init();
        //curl_setopt($c, CURLOPT_VERBOSE, 1);
        curl_setopt($c, CURLOPT_HEADER, 0);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_REFERER, '');
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($c, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36');
        curl_setopt($c, CURLOPT_URL, $uri);
        $rC = $this->_curl_redir_exec($c);
        curl_close($c);
        
        return $rC;
    }
    
    private function _curl_redir_exec($ch,$header=0)
    {
        static $curl_loops = 0;
        static $curl_max_loops = 20;

        if ($curl_loops++ >= $curl_max_loops)
        {
            $curl_loops = 0;
            return FALSE;
        }
        if ($header == 1){
            curl_setopt($ch, CURLOPT_HEADER, true);
        }
        else {
            curl_setopt($ch, CURLOPT_HEADER, false);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $debbbb = $data;

        // list($header, $data) = explode("\n\n", $data, 2);
        $ex = explode("\n\n", $data, 2);
        $header = $ex[0];
        if (!empty($ex[1])){
            $data = $ex[1];
        }
        else {
            $data = '';
        }
        

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($http_code == 301 || $http_code == 302) {
            $matches = array();
            preg_match('/Location:(.*?)\n/', $header, $matches);
            $url = @parse_url(trim(array_pop($matches)));
            //print_r($url);
            if (!$url)
            {
                //couldn't process the url to redirect to
                $curl_loops = 0;
                return $data;
            }
            $last_url = parse_url(curl_getinfo($ch, CURLINFO_EFFECTIVE_URL));
            if (!isset($url['scheme'])){
                $url['scheme'] = $last_url['scheme'];
            }
            if (!isset($url['host'])){
                $url['host'] = $last_url['host'];
            }
            if (!isset($url['path'])){
                $url['path'] = $last_url['path'];
            }
            if (!isset($url['query']) && isset($last_url['query'])){
                $url['query'] = $last_url['query'];
            }
            else {
                $url['query'] = '';
            }
            
            $new_url = $url['scheme'] . '://' . $url['host'] . $url['path'] . ($url['query']?'?'.$url['query']:'');
            curl_setopt($ch, CURLOPT_URL, $new_url);
        //    debug('Redirecting to', $new_url);

            return $this->curl_redir_exec($ch,$header);
        } else {
            $curl_loops=0;
            return $debbbb;
        }
    }
    
    
}



function quote_smart($sql_link, $val)
{
    return sql_str($sql_link,$val);
}

function sql_str($sql_link, $value)
{
    if( is_array($value) ) {
        return array_map("quote_smart", array($sql_link, $value));
    } else {
        if( get_magic_quotes_gpc() ) {
            $value = stripslashes($value);
        }
        if( $value == '' ) {
            $value = '';
        } if( !is_numeric($value) || $value[0] == '0' ) {
            $value = "'".mysqli_real_escape_string($sql_link, $value)."'";
        }
        return $value;
    }
}
