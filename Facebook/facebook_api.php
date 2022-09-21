<?php
require_once 'config/config.php';

function makeFacebookApiCall($endPoint, $params)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $endPoint . '?' . http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_COOKIESESSION, false);

    $fbResponse = curl_exec($ch);
    $fbResponse = json_decode($fbResponse, TRUE);
    curl_close($ch);

    return array(
        'endpoint' => $endPoint,
        'params' => $params,
        'has_errors' => isset($fbResponse['error']) ? TRUE : FALSE,
        'error_message' => isset($fbResponse['error']) ? $fbResponse['error']['message'] : '',
        'fb_response' => $fbResponse
    );

}

function getFacebookLoginUrl()
{
    $endPoint = 'https://www.facebook.com/' . FB_GRAPH_VERSION . '/dialog/oauth';

    $params = array(
        'client_id' => FACEBOOK_APP_ID,
        'redirect_uri' => FACEBOOK_REDIRECT_URI,
        'state' => FB_APP_STATE,
        'scope' => 'email',
        'auth_type' => 'rerequest'
    );

    return $endPoint . '?' . http_build_query($params);
}

function getAccessToken($code)
{
    $endPoint = FB_GRAPH_DOMAIN . FB_GRAPH_VERSION . '/oauth/access_token';

    $params = array(
        'client_id' => FACEBOOK_APP_ID,
        'client_secret' => FACEBOOK_APP_SECRET,
        'redirect_uri' => FACEBOOK_REDIRECT_URI,
        'code' => $code
    );

    return makeFacebookApiCall($endPoint, $params);
}

function tryAndLoginWithFacebook($get)
{
    $status = 'fail';
    $message = '';
    if (isset($get['error'])) {
        $message = $get['error_description'] ?? '';
    } else {
        $accessTokenInfo = getAccessToken($get['code']);
        if ($accessTokenInfo['has_errors']) {
            $message = $get['error_description'] ?? '';
        } else {
            $_SESSION['fb_access_token'] = $accessTokenInfo['fb_response']['access_token'];

            $fbUserInfo = getFacebookUserInfo($_SESSION['fb_access_token']);

            if (!$fbUserInfo['has_errors'] && !empty($fbUserInfo['fb_response']['id']) && !empty($fbUserInfo['fb_response']['email'])) {
                $status = 'ok';
                $_SESSION['fb_user_info'] = $fbUserInfo['fb_response'];
                //REDIRECT TO LOGGED IN BY FACEBOOK PROCESSING PAGE

                $host = $_SERVER['HTTP_HOST'];
                header('Location: https://' . $host . '/frontend/front/processingFacebookData');
                exit;
            }
        }
    }

    return array(
        'status' => $status,
        'message' => $message,
    );
}

function getFacebookUserInfo($accessToken)
{
    $endPoint = FB_GRAPH_DOMAIN . 'me';

    $params = array(
        'fields' => 'first_name, last_name, email, picture',
        'access_token' => $accessToken,
    );

    return makeFacebookApiCall($endPoint, $params);
}