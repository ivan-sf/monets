<?php 


define("DS", DIRECTORY_SEPARATOR);
define("ROOT", realpath(dirname(__FILE__)) . DS);
define('DB', 'u254469571_monets');
define('NOMBRE_IMPRESORA', 'TITAN22');
define('DIRAPP', 'monets');
define('HOSTDB', 'mysql.hostinger.co');
define('USERDB', 'u254469571_monetsroot');
define('PASSDB', 'Root1234');
define("URL", 'https://titancomercial.co/' . DIRAPP . "/");
define("URLLOCAL", 'http://192.168.100.52/' . DIRAPP . "/");
define("URL_SITIO", "#");


$con  = new mysqli(HOSTDB,USERDB,PASSDB,DB);

?>
<script>
	const URL_SITIO = "#";
	const URL_APP = "https://titancomercial.co/";
	const dir_app = "monets";
</script>
<script>
	const URL_APP = "https://titancomercial.co/";
</script>
