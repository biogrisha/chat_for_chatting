<?php
define('PORT', "8080");
require_once 'classes/Chat.php';
require_once 'classes/Story.php';
$chat = new Chat();
$story = Story::alive();

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
socket_bind($socket,0, PORT);
socket_listen($socket);
$clientSocketArray = array($socket);
$nullA = [];
while(true){
  $newSocketArray = $clientSocketArray;
  socket_select($newSocketArray, $nullA, $nullA,0,10);
  if(in_array($socket, $newSocketArray)){
    $newSocket = socket_accept($socket);
    $clientSocketArray[] = $newSocket;
    $newSocketArray[] = $newSocket;
    $header = socket_read($newSocket, 1024);
    $chat->sendHeaders($header, $newSocket, 'localhost/chat_for_chatting/ChatProject', PORT);
    socket_getpeername($newSocket, $client_ip_adress);
    $connectionACK= $chat->NewConnectinACK($client_ip_adress);
    $chat->send($connectionACK, $clientSocketArray);
    $newSocketArrayIndex = array_search($socket, $newSocketArray);
    unset($newSocketArray[$newSocketArrayIndex]);
}
foreach($newSocketArray as $newSocketArrayResource){
    while (socket_recv($newSocketArrayResource, $socketData, 1024, 0)>=1) {
    $socketMessage = $chat->unseal($socketData);
    $messageObj = json_decode($socketMessage);
if($messageObj !== null){
    $chatMessage = $chat->createChatMessage($messageObj->chat_user, $messageObj->chat_message);
    echo $messageObj->chat_message;
     $story->arrayRec($messageObj->chat_user, $messageObj->chat_message);
     $story->dateBaseRec();
    $chat->send($chatMessage,$clientSocketArray);
  }
    break 2;
  }
  $socketData =  @socket_read($newSocketArrayResource,1024, PHP_NORMAL_READ);
  if($socketData === false){
    socket_getpeername($newSocketArrayResource, $client_ip_adress);
    $connectionACK = $chat->newDisconectedACK($client_ip_adress);
    $chat->send($connectionACK,$clientSocketArray);
    $newSocketArrayIndex = array_search($newSocketArrayResource, $clientSocketArray);
    unset($clientSocketArray[$newSocketArrayIndex]);
    $story->disconnected();
  }
}

}
socket_close($socket);
