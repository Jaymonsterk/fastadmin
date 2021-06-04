<?php

use app\common\model\Category;
use fast\Form;
use fast\Tree;
use think\Cache;
use think\Db;

if (!function_exists('build_select')) {

    /**
     * 生成下拉列表
     * @param string $name
     * @param mixed  $options
     * @param mixed  $selected
     * @param mixed  $attr
     * @return string
     */
    function build_select($name, $options, $selected = [], $attr = [])
    {
        $options = is_array($options) ? $options : explode(',', $options);
        $selected = is_array($selected) ? $selected : explode(',', $selected);
        return Form::select($name, $options, $selected, $attr);
    }
}

if (!function_exists('build_radios')) {

    /**
     * 生成单选按钮组
     * @param string $name
     * @param array  $list
     * @param mixed  $selected
     * @return string
     */
    function build_radios($name, $list = [], $selected = null)
    {
        $html = [];
        $selected = is_null($selected) ? key($list) : $selected;
        $selected = is_array($selected) ? $selected : explode(',', $selected);
        foreach ($list as $k => $v) {
            $html[] = sprintf(Form::label("{$name}-{$k}", "%s {$v}"), Form::radio($name, $k, in_array($k, $selected), ['id' => "{$name}-{$k}"]));
        }
        return '<div class="radio">' . implode(' ', $html) . '</div>';
    }
}

if (!function_exists('build_checkboxs')) {

    /**
     * 生成复选按钮组
     * @param string $name
     * @param array  $list
     * @param mixed  $selected
     * @return string
     */
    function build_checkboxs($name, $list = [], $selected = null)
    {
        $html = [];
        $selected = is_null($selected) ? [] : $selected;
        $selected = is_array($selected) ? $selected : explode(',', $selected);
        foreach ($list as $k => $v) {
            $html[] = sprintf(Form::label("{$name}-{$k}", "%s {$v}"), Form::checkbox($name, $k, in_array($k, $selected), ['id' => "{$name}-{$k}"]));
        }
        return '<div class="checkbox">' . implode(' ', $html) . '</div>';
    }
}


if (!function_exists('build_category_select')) {

    /**
     * 生成分类下拉列表框
     * @param string $name
     * @param string $type
     * @param mixed  $selected
     * @param array  $attr
     * @param array  $header
     * @return string
     */
    function build_category_select($name, $type, $selected = null, $attr = [], $header = [])
    {
        $tree = Tree::instance();
        $tree->init(Category::getCategoryArray($type), 'pid');
        $categorylist = $tree->getTreeList($tree->getTreeArray(0), 'name');
        $categorydata = $header ? $header : [];
        foreach ($categorylist as $k => $v) {
            $categorydata[$v['id']] = $v['name'];
        }
        $attr = array_merge(['id' => "c-{$name}", 'class' => 'form-control selectpicker'], $attr);
        return build_select($name, $categorydata, $selected, $attr);
    }
}

if (!function_exists('build_toolbar')) {

    /**
     * 生成表格操作按钮栏
     * @param array $btns 按钮组
     * @param array $attr 按钮属性值
     * @return string
     */
    function build_toolbar($btns = null, $attr = [])
    {
        $auth = \app\admin\library\Auth::instance();
        $controller = str_replace('.', '/', strtolower(think\Request::instance()->controller()));
        $btns = $btns ? $btns : ['refresh', 'add', 'edit', 'del', 'import'];
        $btns = is_array($btns) ? $btns : explode(',', $btns);
        $index = array_search('delete', $btns);
        if ($index !== false) {
            $btns[$index] = 'del';
        }
        $btnAttr = [
            'refresh' => ['javascript:;', 'btn btn-primary btn-refresh', 'fa fa-refresh', '', __('Refresh')],
            'add'     => ['javascript:;', 'btn btn-success btn-add', 'fa fa-plus', __('Add'), __('Add')],
            'edit'    => ['javascript:;', 'btn btn-success btn-edit btn-disabled disabled', 'fa fa-pencil', __('Edit'), __('Edit')],
            'del'     => ['javascript:;', 'btn btn-danger btn-del btn-disabled disabled', 'fa fa-trash', __('Delete'), __('Delete')],
            'import'  => ['javascript:;', 'btn btn-info btn-import', 'fa fa-upload', __('Import'), __('Import')],
        ];
        $btnAttr = array_merge($btnAttr, $attr);
        $html = [];
        foreach ($btns as $k => $v) {
            //如果未定义或没有权限
            if (!isset($btnAttr[$v]) || ($v !== 'refresh' && !$auth->check("{$controller}/{$v}"))) {
                continue;
            }
            list($href, $class, $icon, $text, $title) = $btnAttr[$v];
            //$extend = $v == 'import' ? 'id="btn-import-file" data-url="ajax/upload" data-mimetype="csv,xls,xlsx" data-multiple="false"' : '';
            //$html[] = '<a href="' . $href . '" class="' . $class . '" title="' . $title . '" ' . $extend . '><i class="' . $icon . '"></i> ' . $text . '</a>';
            if ($v == 'import') {
                $template = str_replace('/', '_', $controller);
                $download = '';
                if (file_exists("./template/{$template}.xlsx")) {
                    $download .= "<li><a href=\"/template/{$template}.xlsx\" target=\"_blank\">XLSX模版</a></li>";
                }
                if (file_exists("./template/{$template}.xls")) {
                    $download .= "<li><a href=\"/template/{$template}.xls\" target=\"_blank\">XLS模版</a></li>";
                }
                if (file_exists("./template/{$template}.csv")) {
                    $download .= empty($download) ? '' : "<li class=\"divider\"></li>";
                    $download .= "<li><a href=\"/template/{$template}.csv\" target=\"_blank\">CSV模版</a></li>";
                }
                $download .= empty($download) ? '' : "\n                            ";
                if (!empty($download)) {
                    $html[] = <<<EOT
                        <div class="btn-group">
                            <button type="button" href="{$href}" class="btn btn-info btn-import" title="{$title}" id="btn-import-file" data-url="ajax/upload" data-mimetype="csv,xls,xlsx" data-multiple="false"><i class="{$icon}"></i> {$text}</button>
                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" title="下载批量导入模版">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu">{$download}</ul>
                        </div>
EOT;
                } else {
                    $html[] = '<a href="' . $href . '" class="' . $class . '" title="' . $title . '" id="btn-import-file" data-url="ajax/upload" data-mimetype="csv,xls,xlsx" data-multiple="false"><i class="' . $icon . '"></i> ' . $text . '</a>';
                }
            } else {
                $html[] = '<a href="' . $href . '" class="' . $class . '" title="' . $title . '"><i class="' . $icon . '"></i> ' . $text . '</a>';
            }
        }
        return implode(' ', $html);
    }
}

if (!function_exists('build_heading')) {

    /**
     * 生成页面Heading
     *
     * @param string $path 指定的path
     * @return string
     */
    function build_heading($path = null, $container = true)
    {
        $title = $content = '';
        if (is_null($path)) {
            $action = request()->action();
            $controller = str_replace('.', '/', request()->controller());
            $path = strtolower($controller . ($action && $action != 'index' ? '/' . $action : ''));
        }
        // 根据当前的URI自动匹配父节点的标题和备注
        $data = Db::name('auth_rule')->where('name', $path)->field('title,remark')->find();
        if ($data) {
            $title = __($data['title']);
            $content = __($data['remark']);
        }
        if (!$content) {
            return '';
        }
        $result = '<div class="panel-lead"><em>' . $title . '</em>' . $content . '</div>';
        if ($container) {
            $result = '<div class="panel-heading">' . $result . '</div>';
        }
        return $result;
    }
}

if (!function_exists('build_suffix_image')) {
    /**
     * 生成文件后缀图片
     * @param string $suffix 后缀
     * @param null   $background
     * @return string
     */
    function build_suffix_image($suffix, $background = null)
    {
        $suffix = mb_substr(strtoupper($suffix), 0, 4);
        $total = unpack('L', hash('adler32', $suffix, true))[1];
        $hue = $total % 360;
        list($r, $g, $b) = hsv2rgb($hue / 360, 0.3, 0.9);

        $background = $background ? $background : "rgb({$r},{$g},{$b})";

        $icon = <<<EOT
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
            <path style="fill:#E2E5E7;" d="M128,0c-17.6,0-32,14.4-32,32v448c0,17.6,14.4,32,32,32h320c17.6,0,32-14.4,32-32V128L352,0H128z"/>
            <path style="fill:#B0B7BD;" d="M384,128h96L352,0v96C352,113.6,366.4,128,384,128z"/>
            <polygon style="fill:#CAD1D8;" points="480,224 384,128 480,128 "/>
            <path style="fill:{$background};" d="M416,416c0,8.8-7.2,16-16,16H48c-8.8,0-16-7.2-16-16V256c0-8.8,7.2-16,16-16h352c8.8,0,16,7.2,16,16 V416z"/>
            <path style="fill:#CAD1D8;" d="M400,432H96v16h304c8.8,0,16-7.2,16-16v-16C416,424.8,408.8,432,400,432z"/>
            <g><text><tspan x="220" y="380" font-size="124" font-family="Verdana, Helvetica, Arial, sans-serif" fill="white" text-anchor="middle">{$suffix}</tspan></text></g>
        </svg>
EOT;
        return $icon;
    }
}


if (!function_exists('get_ip')) {
    /**
     * 获取用户ip
     * @return string
     */
    function get_ip() {
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

                foreach ($arr as $ip) {
                    $ip = trim($ip);

                    if ($ip != 'unknown') {
                        $realip = $ip;
                        break;
                    }
                }
            } else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            } else if (isset($_SERVER['REMOTE_ADDR'])) {
                $realip = $_SERVER['REMOTE_ADDR'];
            } else {
                $realip = '0.0.0.0';
            }
        } else if (getenv('HTTP_X_FORWARDED_FOR')) {
            $realip = getenv('HTTP_X_FORWARDED_FOR');
        } else if (getenv('HTTP_CLIENT_IP')) {
            $realip = getenv('HTTP_CLIENT_IP');
        } else {
            $realip = getenv('REMOTE_ADDR');
        }

        preg_match('/[\\d\\.]{7,15}/', $realip, $onlineip);
        $realip = (!empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0');
        return $realip;
    }
}

if (!function_exists('public_pw')) {
    /**
     * 密码生成器
     * @param string $pw 分隔符
     * @return string
     */
    function public_pw($pw)
    {
        return md5(substr(md5($pw),2,29));
    }
}

if (!function_exists('checkTokenPower')) {
    /**
     * 验证token以及权限
     * @param int $uid
     * @return string
     */
    function checkTokenPower($token,$path=''){
        $admin = Rediss::checkToken($token);
        if($admin == false){
            ApiReturn(102);
        }
        if($admin['issuper'] != 1){
            $PermissionMenuList = Rediss::gitPermissionMenuList();
            $temp = [];
            foreach($PermissionMenuList as $v){
                if(!empty($v['path'])){
                    $temp[$v['id']] = strtolower(trim($v['path']));
                }
            }
            /*
            if(empty($path)){
                $path = strtolower($_SERVER['REQUEST_URI']);
                $path = explode('?',$path);
                $path =$path[0];
            }else{
                $path = strtolower($path);
                $path = explode('&',$path);
                $path =$path[0];
            }
            */
            $path = strtolower($path);
            $key = array_search($path,$temp);
            if($key === false){
                return $admin;
            }
            $permission = Rediss::getAdminPower($admin['id']);
            if(empty($permission)){
                ApiReturn(103);
            }
            if(!in_array($key, $permission)){
                ApiReturn(103);
            }
        }
        return $admin;
    }
}

if (!function_exists('public_order_number')) {
    /**
     * 订单号
     * @param int $uid
     * @return string
     */
    function public_order_number($uid){
        list($msec, $sec) = explode(' ', microtime());
        $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        return $uid.'_'.date('YmdHis').'_'.$msectime;
    }
}

if (!function_exists('logs')) {
    /**
     * 写日志
     * @param $data : 数据
     * @param $fileName : 写入哪个日志
     */
    function logs($data = null,$fileName = 'default'){
        if(is_null($data) || is_null($fileName)){
            return false;
        }

        $path = RUNTIME_PATH . 'log/' . $fileName;
        if(!is_dir($path)){
            $mkdir_re = mkdir($path,0777,TRUE);
            if(!$mkdir_re){
                logs($data,$fileName);
            }
        }

        $filePath = $path . "/" . date("Y-m-d",time()).".txt";

        $time = date("Y-m-d H:i:s",time());
        if(is_array($data)){
            $data = json_encode($data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        }

        $re = file_put_contents($filePath, $time." ".var_export($data,TRUE)."\r\n\r\n" , FILE_APPEND);

        if(!$re){
            logs($data,$fileName);
        }else{
            return false;
        }
        return true;
    }
}

if (!function_exists('api_curl')) {
    /**
     * curl
     * @param array $data 参数
     * @param string $url
     * @param string $method post
     * @param int $timeOut 超时
     * @return string
     */
    function api_curl($data,$url ,$method = 'post',$timeOut = 30){
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeOut);
        curl_setopt ( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 JDB/1.0' );
        curl_setopt ( $ch, CURLOPT_ENCODING, 'UTF-8' );
        curl_setopt ( $ch, CURLOPT_MAXREDIRS, 3 );
        curl_setopt ( $ch, CURLOPT_HEADER, false);
        curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt ( $ch, CURLOPT_NOSIGNAL, true );
        curl_setopt ( $ch, CURLOPT_MAXREDIRS, 3 );
        curl_setopt ( $ch, CURLOPT_MAXREDIRS, 3 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false);
        if($method == 'post'){
            curl_setopt( $ch, CURLOPT_POST, 1 );
            curl_setopt ( $ch, CURLOPT_URL, $url );
            curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }else{
            curl_setopt ( $ch, CURLOPT_URL, $data);
        }
        $res = curl_exec ( $ch );
        curl_close ( $ch );
        return $res;
    }
}

if (!function_exists('get_sign')) {
    /**
     * 生成api密钥串
     * @param array $data 参数
     * @param string $url
     * @param string $method post
     * @param int $timeOut 超时
     * @return string
     */
    function get_sign($data,$apiKey){
        foreach ($data as $key => $value) {
            if ($value == '' or $key == 'sign' or $key == 'SIGN') {
                unset($data[$key]);
            }
        }
        ksort($data); //键名从低到高进行排序
        $post_url = '';
        foreach ($data as $key=>$value){
            $post_url .= $key.'='.$value.'&';
        }
        $stringSignTemp = $post_url.'apiKey='.$apiKey;
        //echo $stringSignTemp;
        $sign = md5($stringSignTemp);
        return strtoupper($sign);
    }
}

if (!function_exists('str_encryption')) {
    /**
     * 参数加密
     * @param string $txt 要加密的字符串
     * @param string $key 密匙
     * @return string
     */
    function str_encryption($txt, $key) {
        $inputArr = json_decode($txt, true);//转为数组
        $input = json_encode($inputArr, JSON_UNESCAPED_UNICODE);//转为json字符串
        //进行Aes加密
        $data = openssl_encrypt($input, 'AES-128-ECB', $key);
        return urlencode($data);
    }
}
if (!function_exists('str_decryption')) {
    /**
     * 参数解密
     * @param string $txt 要解密的字符串
     * @param string $key
     * @return array
     */
    function str_decryption($txt, $key) {
        //移除前6后5的字符串
        $txt = substr($txt,6,strlen($txt));
        $txt = substr($txt,0,strlen($txt)-5);
        $input = urldecode($txt);
        //$input = $txt;
        $input = openssl_decrypt($input, 'AES-128-ECB', $key);
        return $input;
    }
}
if (!function_exists('str_decryption_nodecode')) {
    /**
     * 参数解密
     * @param string $txt 要解密的字符串
     * @param string $key
     * @return array
     */
    function str_decryption_nodecode($txt, $key) {
        //移除前6后5的字符串
        $txt = substr($txt,6,strlen($txt));
        $txt = substr($txt,0,strlen($txt)-5);
        $input = openssl_decrypt($txt, 'AES-128-ECB', $key);
        return $input;
    }
}

if (!function_exists('generate_password')) {
    /**
     * 随机密码
     * @param int $length 长度
     * @return string
     */
    function generate_password( $length = 8 ) {
        // 密码字符集，可任意添加你需要的字符
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_[]{}<>~`+=,.;:/?|';
        $password = '';
        for ( $i = 0; $i < $length; $i++ )
        {
            // 第一种是使用 substr 截取$chars中的任意一位字符；
            // $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
            // 第二种是取字符数组 $chars 的任意元素
            $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }
        return $password;
    }
}
if (!function_exists('generate_str')) {
    /**
     * 随机密码
     * @param int $length 长度
     * @return string
     */
    function generate_str( $length = 8 ) {
        // 密码字符集，可任意添加你需要的字符
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';
        for ( $i = 0; $i < $length; $i++ )
        {
            // 第一种是使用 substr 截取$chars中的任意一位字符；
            // $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
            // 第二种是取字符数组 $chars 的任意元素
            $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }
        return $password;
    }
}

if (!function_exists('get_tree_children')) {
    /**
     * tree 子菜单
     * @param array $data 数据
     * @param string $childrenname 子数据名
     * @param string $keyName 数据key名
     * @param string $pidName 数据上级key名
     * @return array
     */
    function get_tree_children(array $data, string $childrenname = 'children', string $keyName = 'id', string $pidName = 'pid')
    {
        $list = array();
        foreach ($data as $value) {
            $list[$value[$keyName]] = $value;
        }
        static $tree = array(); //格式化好的树
        foreach ($list as $item) {
            if (isset($list[$item[$pidName]])) {
                $list[$item[$pidName]][$childrenname][] = &$list[$item[$keyName]];
            } else {
                $tree[] = &$list[$item[$keyName]];
            }
        }
        return $tree;
    }
}

if (!function_exists('get_vip_list')) {
    function get_vip_list(){
        $list = (new app\admin\model\config\ConfigVip)->order("vnum","asc")->column("vname",'vnum');
        return array_reverse($list,true);
    }
}

if (!function_exists('yj_pay_ds')) {

    function yj_pay_ds($sf,$data,$bug=0){
        $key = $sf['paykey']; //商户密钥
        $params=array();
        $params['memberid']=$sf['memberid'];//商户编号（这里是测试号）
        $params['orderid']=$data['orderid'];//商户订单号
        $params['amount']=$data['amount'];//订单金额(元)
        $params['notifyurl']=$data['notifyurl']; //异步通知URL
        $params['returnurl']='http://www.baidu.com'; //同步跳转
        $params['sign']=get_sign($params,$key); //签名
        $url = $sf['payurl']; //订单网关
        $result= get_curl($params,$url);
        $result=json_decode($result,true);
        if($bug == 1){
            var_dump($sf);
            var_dump($data);
            var_dump($params);
            var_dump($url);
            exit;
        }
        return $result;
    }
}


if (!function_exists('yj_pay_df')) {

    function yj_pay_df($sf,$data,$bug=0){
        $key = $sf['paykey']; //商户密钥
        $params=array();
        $params['memberid']=$sf['memberid'];//商户编号（这里是测试号）
        $params['orderid']=$data['orderid'];//商户订单号
        $params['amount']=$data['amount'];//订单金额(元)
        $params['paycode']=3; //支付编号(8001:phonepe;8002:paytm;)
        $params['payname']=trim($data['payname']); //代付账户名
        $params['paynumber']=$data['paynumber']; //代付账户
        $params['ifsccode']=$data['ifsccode'];
        $params['notifyurl']=$data['notifyurl']; //异步通知URL
        $params['returnurl']="http://www.baidu.com"; //同步跳转
        $params['sign']=get_sign($params,$key); //签名
        $url = "http://api.cctvfu.com/v1/dfpay"; //订单网关
        print_r($params);exit();
        $result= get_curl($params,$url);
        $result=json_decode($result,true);
        if($bug == 1){
            var_dump($sf);
            var_dump($data);
            var_dump($params);
            var_dump($url);
            exit;
        }
        return $result;
    }
}

//通过ID获取单个三方配置信息
function getSfById($sid,$type=0){
    $key = "Config:Sf:".$sid;
    $ret = Cache::store('redis')->get($key);
    if(empty($ret) || $type ==1){
        $ret=[];
        $data = db("user_deposit_sf")->where('id',$sid)->where('status',1)->find();
        if(!empty($data)){
            $json = json_decode($data['json'],true);
            $data=array_merge($data,$json);
            $data['showmoney'] = explode(',',$data['showmoney']);
            //var_dump($data);exit;
            $ret = $data;
            Cache::store('redis')->set($key,json_encode($ret, JSON_UNESCAPED_UNICODE),1800);
        }else{
            return false;
        }
    }else{
        $ret = json_decode($ret,true);
    }
    return $ret;
}

//获取公共配置key=value
function getConfigSysKeyVal($type=0)
{
    $key = "Config:ConfigSysKeyValue";
    $ret = Cache::store('redis')->get($key);
    if(empty($ret) || $type ==1){
        $ret=db("config_sys")->column("value","key");
        Cache::store('redis')->set($key,json_encode($ret, JSON_UNESCAPED_UNICODE),1800);
    }else{
        $ret = json_decode($ret,true);
    }
    return $ret;
}