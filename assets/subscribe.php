<?php

// Email address verification
function isEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

if($_POST) {

    $mailchimp_api_key = '9a26c0232fbfe391a2886b795fd7932b-us12'; // enter your MailChimp API Key
    // ****
    $mailchimp_list_id = '4a18cf9503'; // enter your MailChimp List ID
    // ****

    $subscriber_email = addslashes(trim($_POST['email']));

    if(!isEmail($subscriber_email)) {
        $array = array();
        $array['valid'] = 0;
        $array['message'] = 'Insira um email válido!';
        echo json_encode($array);
    }
    else {
        $array = array();
        $merge_vars = array();

        require_once 'MailChimp.php';

        $MailChimp = new \Drewm\MailChimp($mailchimp_api_key);
        $result = $MailChimp->call('lists/subscribe', array(
                'id'                => $mailchimp_list_id,
                'email'             => array('email' => $subscriber_email),
                'merge_vars'        => $merge_vars,
                'double_optin'      => true,
                'update_existing'   => true,
                'replace_interests' => false,
                'send_welcome'      => false,
        ));

        if($result == false) {
            $array['valid'] = 0;
            $array['message'] = 'Ocorreu um erro, por favor tente mais tarde!';
        }
        else {
            $array['valid'] = 1;
            $array['message'] = 'Obrigado por inscrever-se!';
        }

            echo json_encode($array);

    }

}

?>
