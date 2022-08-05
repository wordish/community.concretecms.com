#!/bin/bash
set -euo pipefail

source "/tmp/community/.cdvariables"

cd $deploydir && php7.4 ./vendor/bin/concrete5 orm:generate:proxies
