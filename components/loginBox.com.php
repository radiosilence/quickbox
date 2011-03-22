<?php # Gets information about the session so that loginbox template component has something to display.
$this->cOutput['user'] = (isset($_SESSION['user']) ? $_SESSION['user'] : false);
?>