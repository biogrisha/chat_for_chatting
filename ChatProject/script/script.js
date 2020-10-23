function message(text){
    jQuery('#chat-result').append(text);
};
function empty(text){
let warnings = [
  "Что-то не так",
 "Проверьте, пожалуйста, все ли ввели?",
 "Тут поле пустое",
 "Что-то забыли указать?",
 "Будьте внимательнее, вводите все!"
];
let number =  Math.floor((Math.random() * 5));
    jQuery('#field_empty').text(warnings[number]);
};
function comeback(){
  $('#field_empty').text('');
  $('#chat-message').val('');
  $('#clone-chat-message').html("1");
  let height = $('#clone-chat-message').height();
  $('#chat-message').height(height);
}
jQuery(document).ready(function ($) {
    var socket = new WebSocket("ws://localhost:8080/chat_for_chatting/ChatProject/server.php");
    socket.onopen = function () {
        message("<div>Соединение установлено</div>");
    };
    socket.onerror = function (error) {
        message("<div>Ошибка при соединении" + (error.message ? error.message : "") + "</div>");
    };
    socket.onclose = function () {
        message("<div>Соединение закрыто</div");
    };
    socket.onmessage = function (event) {
        var data = JSON.parse(event.data);
                message("<div>"+data.message+ "</div>");
    };
    $("#button").on("click", function(){
      var message = {
        chat_message:$("#chat-message").val(),
        chat_user:$("#chat-user").val(),
      };
      if(message.chat_user !== "" && message.chat_message !== ""){
      $("#chat-user").attr("type","hidden");
      socket.send(JSON.stringify(message));
      comeback();
      return false;
    }else{
      empty();
    };
    });
    $('#chat-message').bind('input', function(){
      let message = $(this).val();
      message = message.replace(/[<>]/g,"h");
      message = message.replace(/\n/g,"<br/>");
      $('#clone-chat-message').html(message+"<br/>");
      let height = $('#clone-chat-message').height();
      if(height >17 && height<80){
      $('#chat-message').height(height);
  };
});
});
