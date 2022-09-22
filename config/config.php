<?php
//define db creds
define( 'DB_HOST', 'localhost');        //database host
define( 'DB_NAME', 'phptraining');      //database name
define( 'DB_USER', 'root');             //database username
define( 'DB_PASS', '');                 //database password

//facebook app key
define('FACEBOOK_APP_ID', '1929688277422277');
define('FACEBOOK_APP_SECRET', '2c812a2f9428e524fda285ba92c6d9a3');
define('FACEBOOK_REDIRECT_URI', 'https://4aba-14-248-83-33.jp.ngrok.io/frontend/front/index');
//define('FACEBOOK_REDIRECT_URI', 'https://phptraining.local/frontend/front/index');

//facebook graph
define('FB_GRAPH_VERSION', 'v6.0');
define('FB_GRAPH_DOMAIN', 'https://graph.facebook.com/');
define('FB_APP_STATE', 'eciphp');

//database
define('DEL_FLAG_ON', 1);
define('DEL_FLAG_OFF', 0);
define('DEFAULT_INS_ID', 1);

//acceptable upload file type
define('IMAGE_UPLOAD_FILE_TYPE', array('image/png', 'image/jpg', 'image/jpeg', 'image/svg', 'image/svg'));
