<!DOCTYPE html>
<?php
require_once('D:\programmirovanie\OpenServer\domains\chat_for_chatting\ChatProject\classes\Story.php');
$story = Story::alive();
?>
<html lang="ru">
<head>
  <meta charset="utf-8" />
  <title>Chat</title>
  <link rel="stylesheet" type="text/css" href="style/style.css" />


</head>
<body>
  <div class="background" id="background">


    <form id="chat" action="" >
      <div class="caption">
        <h3>Классный Чатик</h3>
      </div>
            <div class = "chat-fields" id="chat-fields">
            <input type="text" name="chat-user" id="chat-user" placeholder="Name">
            <textarea type="text" name="chat-message" id="chat-message" placeholder="Message"></textarea>
            <input type="button" id="button" value="Send">
            <div id = "field_empty"></div>
            </div>
            <div class="chat-result" id="chat-result">
              <?php
              //вывод старых сообщений
               $story->previousMessages();
              ?>
        </div>
      </div>
    </form>
  </div>
  <div id="clone-chat-message">
  </div>
   <script src="script/jquery-3.5.1.min.js"></script>
  <script src="script/script.js"></script>
</body>
</html>
