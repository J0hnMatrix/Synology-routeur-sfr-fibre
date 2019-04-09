<?php
header('Content-Type: application/xml; charset=utf-8');
if (isset($_GET['method'])){
        $method = $_GET['method'];
        switch ($method) {
                case "system.getInfo":
                        echo file_get_contents('system.xml');
                        break;
                case 'lan.getHostsList':
                        echo file_get_contents('lan.xml');
                        break;
                case 'wan.getInfo':
                        echo file_get_contents('wan.xml');
                        break;
                case 'ftth.getInfo':
                        echo file_get_contents('ftth.xml');
                        break;
                case 'tv.getInfo':
                        echo file_get_contents('tv.xml');
                        break;
                case 'usb.getInfo':
                        echo file_get_contents('usb.xml');
                        break;


                }
return;
}
?>
