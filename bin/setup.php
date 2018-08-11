<?php

chdir(dirname(__DIR__));
passthru('rm -rf var/tmp/*');
passthru('chmod 777 var/tmp');
passthru('chmod 777 var/log');
