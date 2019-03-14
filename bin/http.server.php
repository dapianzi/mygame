<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/1
 * Time: 11:52
 */
define('APP_PATH', dirname(dirname(__FILE__)));
require APP_PATH . '/lib/Swoolf/Loader.php';

$app = new \Swoolf\App(APP_PATH . '/conf/http.ini');
$app->on('request', function($request, $response) {
    switch ($request->server['request_uri']){
        case '/favicon.ico': {
            $response->status('404');
            break;
        }
        case '/msgpack': {
            $response->end(file_get_contents(APP_PATH . '/test/ws.msgpack.test.html'));
            break;
        }
        case '/json': {
            $response->end(file_get_contents(APP_PATH . '/test/ws.json.test.html'));
            break;
        }
        case '/protobuf': {
            $response->end(file_get_contents(APP_PATH . '/test/ws.protobuf.test.html'));
            break;
        }
        case '/msgpack/pack': {
            $data = $request->post['data'];
            \Swoolf\Log::warm($data);
            $response->end(base64_encode(msgpack_pack(json_decode($data, TRUE))));
            break;
        }
        case '/msgpack/unpack': {
            // msgpack
            $buf = substr($request->rawContent(), 4);
            \Swoolf\Log::warm($buf);
//                $response->end(json_encode(msgpack_unpack($buf)));
            $response->end(json_encode(msgpack_unpack(base64_decode($buf))));
            break;
        }
        default: {
            $response->end(file_get_contents(APP_PATH . '/test/ws.test.html'));
        }
    }
});
$app->run();