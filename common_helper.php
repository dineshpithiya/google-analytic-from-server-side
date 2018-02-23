<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//Set success response
function _success($status_code=200,$message="SUCCESS",$data=NULL)
{
    $ci=& get_instance();
    _set_header_status($status_code, $message);
    header('Content-type: application/json');
    echo json_encode(
        array(
            'status'=>$status_code,
            'csrf'=>$ci->security->get_csrf_hash(),
            'msg'=>$message,
            'data'=>$data
        )); exit;    }
//Set error response
function _error($status_code=400,$message="FAILD",$data=NULL)
{
    $ci=& get_instance();
    _set_header_status($status_code, $message);
    header('Content-type: application/json');
    echo json_encode(
        array(
            'status'=>$status_code,
            'csrf'=>$ci->security->get_csrf_hash(),
            'msg'=>$message,
            'data'=>$data
        )); exit;
}
//Get header information
function _header()
{
    return getallheaders();
}
//Current date and time
function _date_time($format='Y-m-d H:i:s')
{
    return date($format);
}
//Preformatted text
function _pre($data)
{
    echo"<pre>";
    print_r($data);
    echo"</pre>";
    exit();
}
//Break line
function _br()
{
    echo"<br>";
}
//check user logged in or not
function _IsLogin()
{
    $CI = & get_instance();
    $isLoggedIn = $CI->session->userdata('is_logged_in');
    if($isLoggedIn)
    {
        return $isLoggedIn;
    }
    else
    {
        return false;
    }
}
//encryption
function _encryption($plain_text="")
{
    $ci=&get_instance();
    return $ci->encryption->encrypt($plain_text);
}
//decritpion
function _decryption($ciphertext="")
{
    $ci=&get_instance();
    return $ci->encryption->decrypt($ciphertext);
}
//set encrypt via key
function _encryption_key($plain_text="",$key="key_required_to_encrypt",$url_safe=TRUE)
{
    $ci=&get_instance();
    return $ci->encrypt->encode($plain_text, $key, $url_safe);
}
//set decript via key
function _decryption_key($ciphertext="",$key="key_required_to_encrypt",$url_safe=TRUE)
{
    $ci=&get_instance();
    return  $ci->encrypt->decode($ciphertext, $key, $url_safe);
}

//Generate nth charecter length token
function _GeraHash($qtd)
{
    $Caracteres = 'ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789';
    $QuantidadeCaracteres = strlen($Caracteres);
    $Hash=NULL;
    for($x=1;$x<=$qtd;$x++)
    {
        $Posicao = rand(0,$QuantidadeCaracteres);
        $Hash .= substr($Caracteres,$Posicao,1);
    }
    return $Hash;
}
function _com_create_guid()
{
    mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
    $charid = strtoupper(md5(uniqid(rand(), true)));
    $hyphen = chr(45);// "-"
    $uuid = chr(123)// "{"
        .substr($charid, 0, 8).$hyphen
        .substr($charid, 8, 4).$hyphen
        .substr($charid,12, 4).$hyphen
        .substr($charid,16, 4).$hyphen
        .substr($charid,20,12)
        .chr(125);// "}"

    $uuid=trim($uuid,'{');
    $uuid=trim($uuid,'}');

    $ci=&get_instance();
    $uuid.="_".$ci->session->userdata['login_id'];
    return $uuid;
}

/**
 * @param string $prefix  //folder id 
 * @return string
 */
function _generate_uniqid($prefix='')
{
    if($prefix!= ''){
        $prefix .= '-';
    }else{
        $prefix = uniqid().'-';
    }
    $uuid =uniqid($prefix.time().'-');
    return $uuid;
}
//Set expire time
function _set_expire_time($time)
{
    $exipretime = date('Y-m-d H:i:s', strtotime($time));
    /*echo date('Y-m-d H:i:s'); bre();
    echo date('Y-m-d H:i:s', strtotime('4 minute')); bre();
    echo date('Y-m-d H:i:s', strtotime('6 hour')); bre();
    echo date('Y-m-d H:i:s', strtotime('2 day')); bre();
    echo date('Y-m-d H:i:s', strtotime('1 month')); bre();
    echo date('Y-m-d H:i:s', strtotime('1 year'));*/
    return $exipretime;
}
//get user id
function userId()
{
    $ci=& get_instance();
    return $ci->session->userdata('login_id');
}
//Conver data to JSON format
function _json($data)
{
    header('Content-type: application/json');
    return json_encode($data);
}
//Common curl request
function _curl($data,$method)
{
    $headers = [
        "devicetokens: website",
        "devicetype: website",
        "language: en",
        "Content-Type:multipart/form-data"
    ];
    $url=REST_API_URL.$method;
    $handle = curl_init($url);

    curl_setopt($handle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($handle, CURLOPT_USERPWD, 'freelap:password');

    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_POST, true);
    curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
    curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
    $result=curl_exec($handle);
    curl_close($handle);
    return $result;
}
//success response
//Get current selected header menu
function _is_menu_active($class="")
{
    $CI = & get_instance();
    if($CI->uri->segment("2")==$class)
    {
        return "active";
    }
}

//Get local timezone
function _getTimeZone()
{
    $CI = & get_instance();
    return ($CI->session->userdata('timeZone'))?$CI->session->userdata('timeZone'):'UTC';
}

//Dynamically add Javascript files to header page
if(!function_exists('add_js'))
{
    function add_js($file='')
    {
        $str = '';
        $ci = &get_instance();
        $header_js  = $ci->config->item('header_js');

        if(empty($file))
        {
            return;
        }

        if(is_array($file))
        {
            if(!is_array($file) && count($file) <= 0)
            {
                return;
            }
            foreach($file AS $item)
            {
                $header_js[] = $item;
            }
            $ci->config->set_item('header_js',$header_js);
        }
        else
        {
            $str = $file;
            $header_js[] = $str;
            $ci->config->set_item('header_js',$header_js);
        }
    }
}
//Dynamically add CSS files to header page
if(!function_exists('add_css'))
{
    function add_css($file='')
    {
        $str = '';
        $ci = &get_instance();
        $header_css = $ci->config->item('header_css');

        if(empty($file))
        {
            return;
        }
        if(is_array($file))
        {
            if(!is_array($file) && count($file) <= 0)
            {
                return;
            }
            foreach($file AS $item)
            {
                $header_css[] = $item;
            }
            $ci->config->set_item('header_css',$header_css);
        }
        else
        {
            $str = $file;
            $header_css[] = $str;
            $ci->config->set_item('header_css',$header_css);
        }
    }
}

if(!function_exists('put_headers'))
{
    function put_headers()
    {
        $str = '';
        $ci = &get_instance();
        $header_css = $ci->config->item('header_css');
        $header_js  = $ci->config->item('header_js');

        foreach($header_css AS $item)
        {
            $str .= '<link rel="stylesheet" href="'.base_url().'public/'.$item.'" type="text/css" />'."\n";
        }

        foreach($header_js AS $item)
        {
            $str .= '<script type="text/javascript" src="'.base_url().'public/'.$item.'"></script>'."\n";
        }
        return $str;
    }
}
function load_js()
{
    $str = '';
    $ci = &get_instance();
    //$header_css = $ci->config->item('header_css');
    $header_js  = $ci->config->item('header_js');

    foreach($header_js AS $item)
    {
        echo '<script type="text/javascript" src="'.base_url().'public/'.$item.'"></script>'."\n";
    }
}

function load_css()
{
    $str = '';
    $ci = &get_instance();
    $header_css = $ci->config->item('header_css');
    //$header_js  = $ci->config->item('header_js');

    foreach($header_css AS $item)
    {
        echo '<link rel="stylesheet" href="'.base_url().'public/'.$item.'" type="text/css" />'."\n";
    }
}
if(!function_exists('do_in_background'))
{
    /*==============example=============
    $params=array("BookName"=>'dd',"AuthorName"=>'dp',"Price"=>200);
    do_in_background('http://127.0.0.1/JT/api/dashboard/users', $params);
    $this->gm->insert("book",array("BookName"=>$_POST['BookName'],"AuthorName"=>$_POST['AuthorName'],"Price"=>$_POST['Price']));
    =================end===============*/
    function do_in_background($url, $params)
    {
        $post_string = http_build_query($params);
        $parts = parse_url($url);
        $errno = 0;
        $errstr = "";

        $fp = fsockopen($parts['host'], isset($parts['port']) ? $parts['port'] : 80, $errno, $errstr, 30);

        if(!$fp)
        {
            echo "Some thing Problem";
        }
        $out = "POST ".$parts['path']." HTTP/1.1\r\n";
        $out.= "Host: ".$parts['host']."\r\n";
        $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out.= "Content-Length: ".strlen($post_string)."\r\n";
        $out.= "Connection: Close\r\n\r\n";
        if (isset($post_string)) $out.= $post_string;
        fwrite($fp, $out);
        fclose($fp);
    }
}    