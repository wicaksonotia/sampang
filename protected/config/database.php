<?php

// This is the database connection configuration.
return array(
    //'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
    // uncomment the following lines to use a MySQL database

    'connectionString' => 'pgsql:host=localhost;port=5432;dbname=db_pkb',
    'emulatePrepare' => true,
    'username' => 'postgres',
    'password' => 'h3l10s_db',
    'charset' => 'utf8',
);
