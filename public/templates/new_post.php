<html>
    <link rel="stylesheet" href="style.css">
<body>
    
    <h3>Пользователь: <?php echo $username?></h3>
    
    <button onclick="document.location.href = '/home'">На главную</button>
    <p>Название</p><input id="input_title" type="text">
    <p>Превью</p><textarea id="textarea_preview" type="text"></textarea>
    <p>Текст</p><textarea id="textarea_text"></textarea><br>
    <button onclick = "create_post()">Отправить</button>
    
</body>
</html>
<script src="jquery-3.3.1.min.js"></script>
<script>

//
var currentUser_id = <?php echo $user;?>;

//
function create_post(){

    var title = document.getElementById("input_title").value;
    var preview = document.getElementById("textarea_preview").value;
    var text = document.getElementById("textarea_text").value;

    $.ajax({
        url: "/create_post",
        method: "POST",
        data:{
            title: title,
            preview: preview,
            author: currentUser_id,
            text: text
        },
        success: function(data){
            alert("Пост создан успешно!");
        }
    });

}

</script>
