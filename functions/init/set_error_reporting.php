<?php
/* set error reporting
config boolean $verbose_errors = false by default

*/
// uncomment when developing, comment for shipping version
error_reporting(E_ALL);
#error_reporting (E_ERROR | E_WARNING | E_PARSE);
#error_reporting(0);
// Disable showing errors, but report them in the log.
ini_set("display_errors", 0);

