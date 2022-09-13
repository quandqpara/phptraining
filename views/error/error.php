<?php
echo "error";
echo "<br>";
echo "Session";
showLog($_SESSION, true);
echo "Request";
showLog($_REQUEST, true);
echo "Server";
showLog($_SERVER);