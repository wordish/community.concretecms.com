<?php

// Override the lagoon env settings for now
if (isset($_SERVER['LAGOON_PROJECT'])) {
    putenv('CONCRETE5_ENV=lagoon');
}

require 'concrete/dispatcher.php';
