#!/bin/sh

set -x
php server.php stop
#php flash_policy.php

php server.php start