<?php
    // We're going to be using the Medoo namespace.
    use Medoo\Medoo;

    /* SQL credentials */
    $db = new Medoo([
        'database_type' => 'mysql',
        'database_name' => 'aberdock',
        'server' => '11.0.0.3',
        'username' => 'aberdock',
        'password' => 'ASecurePasswordForTheAberDockAccount'
    ]);
?>