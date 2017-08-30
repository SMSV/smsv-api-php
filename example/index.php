<?php

$accessKey = 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3'; // Paste your private access key here

if ($_SERVER['REQUEST_METHOD']!='POST') {
    echo 'Validation code will be sent to this phone:<br/>
    <form method="POST">
    <input type="hidden" name="action" value="sendOtp"/>
    +<input size="11" name="p" placeholder="Phone"/><br/>
    <input type="submit" value="Send OTP"/>
    </form>
    ';
}

elseif ($_SERVER['REQUEST_METHOD']=='POST') {

    require_once '../src/SMSV/api.php';

    $action = (string)$_POST['action'];

    $smsv = new \SMSV\api($accessKey);

    if ($action=='sendOtp') {
        $phone = (string)$_POST['p'];

        try {
            $sessionKey = $smsv->send($phone);
        }
        catch (Exception $e) {
            var_dump($e);
            die();
        }

        echo 'Please enter validation code:<br/>
    <form method="POST">
    <input type="hidden" name="action" value="validateOtp"/>
    <input type="hidden" name="sk" value="'.$sessionKey.'"/>
    <input size="6" name="c" placeholder="Code from SMS"/><br/>
    <input type="submit" value="Validate"/>
    </form>
    ';

    }
    elseif ($action=='validateOtp') {

        $sessionKey = (string)$_POST['sk'];
        $code = (string)$_POST['c'];

        try {
            $validationResult = $smsv->validate($sessionKey,$code);
        }
        catch (Exception $e) {
            var_dump($e);
            die();
        }

        die('Validation is OK');
    }
}