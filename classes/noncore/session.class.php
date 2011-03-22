<?php # The session and user login manager class
class session
{
	private $userData;
	private $user;

	function __construct ($db)
	{
		$this->sDb = $db;
		session_start();
	}

	public function login ($user, $password)
	{
		$safeUser = janitor::cleanData($user, 'sql');
		$query = new query();
		$query->select()->from('userUsers')->joinLeft('userGroups', 'userUsers.group', 'id')->where('username', $user)->limit(
		'1');
		$result = $this->sDb->query($query);
		if ($this->sDb->numRows($result) > 0)
		{
			$row = $this->sDb->assoc($result);
			$safePassword = janitor::passwd($password, $row['salt']);
			if ($safePassword['passwd'] == $row['password'])
			{
				$this->user = $user;
				$this->userData = $row;
				$this->setCookie($user, $row['email']);
				$this->setSession($safeUser);
				return false;
			} else
			{
				return text::get('login/failedLogin');
			}
		} else
		{
			return text::get('login/failedLogin');
		}
	}

	private function setSession ()
	{
		$_SESSION['user'] = $this->user;
		$_SESSION['userdata'] = $this->userData;
		$_SESSION['userVars'] = janitor::userVars($this->userData['userVars']);
	}

	private function setCookie ($user, $email)
	{
		setcookie("user", $user, time() + 3600000);
		setcookie("hash", md5(md5($email) . $email), time() + 3600000);
	}

	public function checkCookie ($cookie)
	{
		$user = janitor::cleanData($cookie['user'], 'sql');
		$query = new query();
		$query->select()->from('userUsers')->joinLeft('userGroups', 'userUsers.group', 'id')->where('username', $user)->limit(
		'1');
		$result = $this->sDb->query($query);
		if ($this->sDb->numRows($result) > 0)
		{
			$data = $this->sDb->assoc($result);
			if ($cookie['hash'] == md5(md5($data['email']) . $data['email']))
			{
				$query = new query();
				$this->user = $user;
				$this->userData = $data;
				$this->setCookie($user, $_SESSION['userdata']['email']);
				$this->setSession();
			}
		}
	}

	public function logout ()
	{
		$_COOKIE["user"] = null;
		$_COOKIE["hash"] = null;
		
		foreach($_COOKIE as $k => $v)
		{
			$_COOKIE[$k] = null;
		}
		unset($_COOKIE);
		foreach($_SESSION as $k => $v)
		{
			$_SESSION[$k] = null;
		}
		unset($_SESSION);
		session_destroy();
	}
}
?>
