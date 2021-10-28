<?php

// Override the lagoon env settings for now
if (isset($_SERVER['LAGOON_PROJECT'])) {
    putenv('CONCRETE5_ENV=lagoon');
}

const DEFAULT_ERROR_REPORTING = E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_USER_DEPRECATED;

require 'concrete/dispatcher.php';
