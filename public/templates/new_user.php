<html>
    <link rel="stylesheet" href="style.css">
<body>
    
    <button onclick="document.location.href = '/home'">На главную</button>
    <p>Имя</p><input id="input_name" type="text"><br>
    <button onclick = "create_user()">Отправить</button>
    
</body>
</html>
<script src="jquery-3.3.1.min.js"></script>
<script>

//
function create_user(){

    var name = document.getElementById("input_name").value;

    $.ajax({
        url: "/create_user",
        method: "POST",
        data:{
            name: name
        },
        success: function(data){
            alert("Пользователь создан успешно!");
        }
    });

}

</script>
