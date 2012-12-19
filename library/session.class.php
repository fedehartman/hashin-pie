<?php

  /**
  * 
  * Singleton
  *
  */
  class Session 
  {
      private static $instance;

      public static $sessionID;
      
      public static  $flashMsg;
      
      protected $idioma;

      private function __construct()
      {
          session_start();
          self::$sessionID = session_id();
      }

      public static function instance()
      {
          if (!isset(self::$instance)) {
              $className = __CLASS__;
              self::$instance = new $className;
          }
          return self::$instance;
      }
      
      public function destroy()
      {
          foreach ($_SESSION as $var => $val) {
              $_SESSION[$var] = null;
          }
          session_destroy();
      }
      
      public function __clone()
      {
          trigger_error('Clone is not allowed for '.__CLASS__,E_USER_ERROR);
      }
      
      public function __get($var)
      {
          return $_SESSION[$var];
      }
      // }}}
      // {{{ __set($var,$val)
      /**
      * __set 
      * 
      * Using PHP5's overloading for setting and getting variables we can
      * use $session->var = $val and have it stored in the $_SESSION 
      * variable. To set an email address, for instance you would do the
      * following:
      *
      * <code>
      * $session->email = 'user@example.com';
      * </code>
      *
      * This doesn't actually store 'user@example.com' into $session->email,
      * rather it is stored in $_SESSION['email'].
      * 
      */
      
      public function __set($var,$val)
      {
          return ($_SESSION[$var] = $val);
      }

      public function __destruct()
      {
          session_write_close();
      }

      /**
       * Settea el mensaje a mostrar
       * @param String $msg Mensaje
       * @param String $class Clase del mensaje, puede er "error", "warning" o "notice"
       */
      function setFlash($msg,$class="info"){
        if($msg!="")
      	{
            $this->flashMsg = "<div class='flash'><div class='message $class'><p>".$msg."</p></div></div>";
        }
        else
        {
            $this->flashMsg = '';
        }
      }

      function getFlash(){
      	return $this->flashMsg;
      }

      function getAndClearFlash(){
      	$msg = $this->flashMsg;
        $this->flashMsg = '';
        return $msg;
      }
      
      function getLang()
      {
      	return $this->lang;
      }
      
      function setLang($lang)
      {
      	$this->lang=$lang;
      }
      
      function getLoggedUser() {
      
      	return $this->logged_user;
      }
      
      function setLoggedUser($user, $remember = false, $set_last_activity_time = true, $set_cookies = true) {
		if($set_last_activity_time) {
			$user->doSetUpdatedDate(false);
			$user->last_login = DateTimeValueLib::now()->toMySQL();
			$user->save();
		}

		/*if ($set_cookies) {
			$expiration = $remember ? REMEMBER_LOGIN_LIFETIME : SESSION_LIFETIME;
	
			Cookie::setValue('id', $user->getId(), $expiration);
			Cookie::setValue('token', $user->getTwistedToken(), $expiration);
	
			if($remember) {
				Cookie::setValue('remember', 1, $expiration);
			} else {
				Cookie::unsetValue('remember');
			} // if
		}*/
		$this->logged_user = $user;
	}
      
  }

?>