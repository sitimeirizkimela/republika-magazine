<?php
    /******************************************/
    /*      Single Sign On PHP Library        */
    /*                                        */
    /*      based on http://goo.gl/2W7dM      */
    /*       last modified: 30 Nov 2011       */
    /******************************************/

    class Sso_client
    {
        # SSO configs (config/custom.php - $config['sso'])
        var $sso_server = 'localhost';
        #var $sso_key			= null;		// deprecated
        #var $sso_secret		= null;		// deprecated
        var $sso_pass_401 = FALSE;
        var $sso_expire = 1800;
        var $sso_auto_attach = TRUE;
        var $sso_save_to_sess = FALSE;
        var $sso_unit_id = 0;
        var $sso_api_key = null;
        var $sso_debug_mode = TRUE;

        # user vars
        var $user_info = null;
        var $sso_token = null;

        var $current_url = null;
        var $domain = null;
        var $cookie_name = 'session_token3';

        var $ci;

        function __construct($config = array())
        {
            if (!function_exists('curl_init'))
                die('FATAL ERROR: PHP cURL module is not loaded yet. Please check your config at php.ini');

            if (is_array($config) && !empty($config)) {
                foreach ($config as $k => $v)
                    if (property_exists($this, $k)) $this->$k = $v;
            }

            if (empty($this->current_url)) $this->current_url = $this->_get_url();
            if (!$this->domain)
            $this->domain = gethostbyaddr($_SERVER['REMOTE_ADDR']);
            #$this->domain = $this->get_domain($this->current_url);

            /* load configs for CI */
            $this->ci =& get_instance();
            $sso_cfg  = $this->ci->config->item('sso');
            foreach ($sso_cfg as $key => $val)
            {
                $var_name        = 'sso_' . $key;
                $this->$var_name = $val;
            }
            $this->ci->load->library('user_agent');
            /* end load configs */

            # check for token cookie
            #var_dump($_COOKIE);exit;
            if (isset($_COOKIE[$this->cookie_name])) {
                $this->sso_token = $_COOKIE[$this->cookie_name];
                #setcookie($this->cookie_name, $this->sso_token, time() + $this->sso_expire, '/', '.' . $this->domain);
                $this->_setCookie();
            }
            
            # attach session to SSO server if auto_attach is enabled
            if ($this->sso_auto_attach && !$this->sso_token && ($this->ci->agent->is_mobile() || $this->ci->agent->is_browser())) {
                $this->attach();
                exit;
            }
        }

        function login($login = null, $password = null)
        {
            list($ret, $body) = $this->_sso_request('login', array(
                    'login'            => $login,
                    'passhash'         => md5($password),
                    'ip_address'       => $_SERVER['REMOTE_ADDR'])
            );
            #var_dump($ret, $body) ;die;

            switch ($ret)
            {
                case 200:
                    $this->user_info = json_decode($body, TRUE);
                    if ($this->sso_save_to_sess) {
                        session_start();
                        foreach ($this->user_info as $key => $val)
                        {
                            $_SESSION[$key] = $val;
                        }
                    }
                    return TRUE;
                case 401:
                    if ($this->sso_pass_401) header("HTTP/1.1 401 Unauthorized");
                    return $body;
                case 406:
                    if ($body == 'Not attached')
                        $this->attach();
                    return null;
                default:
                    throw new Exception("ERROR $ret" . (!empty($body) ? ': "' . substr(str_replace("\n", " ", trim(strip_tags($body))), 0, 256) . '".' : '.'));
            }
        }

        function logout()
        {
            if ($this->sso_save_to_sess) {
                session_start();
                session_destroy();
            }

            list($ret, $body) = $this->_sso_request('logout');
            if ($ret != 200) throw new Exception("SSO failure: The server responded with a $ret status
		" . (!empty($body) ? ': "' . substr(str_replace("\n", " ", trim(strip_tags($body))), 0, 256) . '".' : '.'));

            //$_COOKIE[$this->cookie_name] = '';
            //setcookie($this->cookie_name, '', 1, '/');
            return TRUE;
        }

        function user_info($fields = null, $where = null)
        {
            if ($this->sso_save_to_sess && empty($where)) {
                session_start();
                if (isset($_SESSION)) {
                    $this->user_info = $_SESSION;
                    if (empty($fields))
                        return $this->user_info;
                    else {
                        $ret    = array();
                        $fields = explode(',', $fields);
                        foreach ($fields as $field)
                        {
                            $field          = trim($field);
                            $return[$field] = isset($this->user_info[$field]) ? $this->user_info[$field] : null;
                        }
                        return $return;
                    }
                } else {
                    if ($this->sso_pass_401) header("HTTP/1.1 401 Unauthorized");
                    return null;
                }
            } else {

                if (empty($where))
                    list($ret, $body) = $this->_sso_request('info', array('fields' => $fields));
                else {
                    if (is_numeric($where))
                        $query = array('id' => $where);
                    else
                        $query = array('email' => $where);
                    list($ret, $body) = $this->_sso_request('info', array('fields' => $fields,
                                                                          'query'  => serialize($query)));
                }

                switch ($ret)
                {
                    case 200:
                        $this->user_info = json_decode($body, TRUE);
                        return $this->user_info;
                    case 401:
                        if ($this->sso_pass_401) header("HTTP/1.1 401 Unauthorized");
                        return null;
                    case 406:
                        if ($body == 'Not attached') {
                            if ($this->ci->agent->is_mobile() || $this->ci->agent->is_browser())
                                $this->attach();
                            return null;
                        }
                    default:
                        throw new Exception("SSO failure: The server responded with a $ret status" . (!empty($body) ? ': "' . substr(str_replace("\n", " ", trim(strip_tags($body))), 0, 256) . '".' : '.'));
                }
            }
        }

        function register($data = array())
        {
            if (isset($data['password'])) $data['password'] = md5($data['password']);
            list($ret, $body) = $this->_sso_request('register', $data);
            if ($ret == 200) {
                $return = json_decode($body, TRUE);
                return $return;
            }
            else throw new Exception($body);
        }

        function activate($activation_code = null)
        {
            list($ret, $body) = $this->_sso_request('activate', array('hash' => $activation_code));
            if ($ret == 200) {
                $return = json_decode($body, TRUE);
                return $return;
            }
            else throw new Exception($body);
        }

        function update($data = array(), $id = 0)
        {
            list($ret, $body) = $this->_sso_request("update/$id", $data);
            if ($ret == 200) {
                if ($body == 'OK') return TRUE;
                else return $body;
            }
            else throw new Exception($body);
        }

        function attach($token = null)
        {
            if (!$this->sso_token) {
                $this->sso_token = md5(uniqid(rand(), TRUE));
                #setcookie($this->cookie_name, $this->sso_token, time() + $this->sso_expire, '/', '.' . $this->domain);
                $this->_setCookie();
            }

            header("Location: http://$this->sso_server/auth/attach/$this->sso_unit_id/$this->sso_api_key/$this->sso_token/" . base64_encode($this->current_url), TRUE, 302);
        }

        function _sso_request($cmd, $vars = null)
        {
            # send request to SSO server
            # if vars is a string, then vars will be sent as get data
            # if vars is an array, then vars will be sent as post data

            $url = "http://$this->sso_server/auth/$cmd";

            #var_dump($vars, $cmd, $url, $_COOKIE);die;

            if (!empty($vars) && is_string($vars)) $url .= $vars;

            $curl = curl_init($url);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_COOKIE, "session_token=" . $this->get_token());
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('X-CLIENT: SSO_Client'));

            if (!empty($vars) && is_array($vars)) {
                curl_setopt($curl, CURLOPT_POST, TRUE);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $vars);
            }

            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, TRUE);

            $body = curl_exec($curl);
            $ret  = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if($this->sso_debug_mode){
				//error_log(date('Y-m-d H:i:s')."\tURL: $url\tTOKEN: ".$this->get_token()."\tPOST: ".http_build_query($vars)."\tRESPONSE: $body\tRESNO: $ret\n",3,'/tmp/sso_client.log');
			}
            #var_dump($body);exit;
            if (curl_errno($curl) != 0) throw new Exception("SSO failure: HTTP request to server failed. <br />URL: $url<br />" . curl_error($curl));

            return array($ret, $body);
        }

        function _generate_sid() # deprecated
        {
            if (!$this->sso_token) return null;
            return "SSO-{$this->sso_secret}-{$this->sso_token}-" . md5('session' . $this->sso_token . md5($_SERVER['HTTP_USER_AGENT']) . $this->sso_key);
        }
        
        private function _setCookie(){
			setcookie($this->cookie_name, $this->sso_token, time() + $this->sso_expire);
		}

        function _get_url()
        {
            $pageURL = 'http';
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on")
                $pageURL .= "s";
            $pageURL .= "://";

            if ($_SERVER["SERVER_PORT"] != "80" || $_SERVER["SERVER_PORT"] != "84")
                $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
            else
                $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];

            //var_dump($_SERVER, $pageURL);
            //die;

            $this->current_url = $pageURL;

            return $pageURL;
        }

        function get_domain($url)
        {
            $pieces = parse_url($url);
            $domain = isset($pieces['host']) ? $pieces['host'] : '';
            if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
                return $regs['domain'];
            }
            return false;
        }
        
        function get_token(){
			return @$_COOKIE[$this->cookie_name];
		}

        function debug($str = null)
        {
            $fp = fopen("debug.log", "a+");
            fwrite($fp, date('[Y-m-d H:i:s]') . "\n" . $str . "\n");
            fclose($fp);
        }
    }
