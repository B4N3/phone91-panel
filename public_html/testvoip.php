<?php

$cmd = "/usr/sbin/asterisk -rx 'core show channels concise'";
echo exec($cmd);

?>
