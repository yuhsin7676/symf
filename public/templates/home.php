<html>
    <link rel="stylesheet" href="style.css">
<body>
    
    <h3 id="top_user_title">Пользователь: <?php echo $username?></h3>
    
    <select id="user_list" onchange="choose_user()"></select>
    <button onclick="find_all_post()">Показать посты</button>
    <button id="button_new_post" onclick="document.location.href = '/new_post'" disabled>Написать пост</button>
    <button onclick="document.location.href = '/new_user'">Создать пользователя</button>
    <div id="post_list"></div>
    <div id="post_container"></div>
    <div id="comment_container"></div>
    
</body>
</html>
<script src="jquery-3.3.1.min.js"></script>
<script>

// Зададим глобальные переменные
var user_list = document.getElementById("user_list");
var button_new_post = document.getElementById("button_new_post");
var post_list = document.getElementById("post_list");
var post_container = document.getElementById("post_container");
var comment_container = document.getElementById("comment_container");

// Установим текущего пользователя
var currentUser_id = <?php echo $user;?>;

// Заполним список пользователей
find_all_user();

//
function create_comment(post){

    var text = document.getElementById("my_comment").value;
    var author = currentUser_id;

    $.ajax({
        url: "/create_comment",
        method: "POST",
        data:{
            post: post,
            author: author,
            text: text
        },
        success: function(data){
            find_comment_by_post(post);
        }
    });

}

// Очищает блоки "post_list", "post_container" и "comment_container" от содержимого
function clear(){
    post_list.innerHTML = "";
    post_container.innerHTML = "";
    comment_container.innerHTML = "";
}

//
function choose_user(){

    var user = user_list.value;
    var username = document.querySelector("#user_list option[value = '" + user + "']").innerHTML;

    $.ajax({
        url: "/choose_user",
        method: "POST",
        data:{
            user: user,
            username: username
        },
        success: function(data){
            document.getElementById("top_user_title").innerHTML = "Пользователь:" + username;
            currentUser_id = user;
            if(user_list.value != 0)
                button_new_post.disabled = false;
            else
                button_new_post.disabled = true;
        },
        error: function(data){
            user_list.value = currentUser_id;
            alert("Произошла ошибка выбора пользователя");
        }
    });
    
}

//
function find_all_user(){

    $.ajax({
        url: "/find_all_user",
        method: "POST",
        success: function(data){
            user_list.innerHTML = "<option value = '0' checked>Не выбран</option>";
            for(var key in data)
                user_list.innerHTML += "<option value = " + data[key].id + ">" + data[key].id + ": " + data[key].name + "</option>";

            user_list.value = currentUser_id;
            if(user_list.value != 0)
                button_new_post.disabled = false;
            else
                button_new_post.disabled = true;
        }
    });  
    
}

//
function find_all_post(){

    $.ajax({
        url: "/find_all_post",
        method: "POST",
        success: function(data){
            clear();
            for(var key in data){
                var post_container = "<div class='post_item'>";
                post_container += "<p><strong>" + data[key].title + "</strong></p>";
                post_container += "<p>" + data[key].preview + "</p>";
                post_container += "<p><button onclick='find_post(" + data[key].id + ")'>Читать</button></p>";
                post_container += "</div>";
                post_list.innerHTML += post_container;
            }
        }
    });  
    
}

//
function find_post(id){

    $.ajax({
        url: "/find_post",
        method: "POST",
        data:{
            id: id
        },
        success: function(data){
            clear();
            var post_header = "<div class='post_header'>";
            post_header += "<p><strong>" + data.author.name + "</strong> " + data.created_at.date + "</p>";
            post_header += "<h3>" + data.title + "</h3>";
            post_header += "<h5>" + data.preview + "</h5>";
            post_header += "</div>";
            post_container.innerHTML += post_header;
            post_container.innerHTML += "<p>" + data.text + "</p>";
            post_container.innerHTML += "<button onclick='find_comment_by_post(" + id + ")'>Показать комментарии</button>";
        }
    });  
    
}

//
function find_comment_by_post(post){

    $.ajax({
        url: "/find_comment_by_post",
        method: "POST",
        data:{
            post: post
        },
        success: function(data){
            comment_container.innerHTML = "";
            for(var key in data){
                
                var comment_item = "<div class='comment_item'>";
                comment_item += "<p class='comment_header'><strong>" + data[key].author.name + "</strong> " + data[key].created_at.date + "</p>";
                comment_item += "<p>" + data[key].text + "</p>";
                comment_item += "</div>";
                comment_container.innerHTML += comment_item;
            }
            comment_container.innerHTML += "<textarea id = 'my_comment' placeholder = 'Оставьте здесь комментарий.'></textarea><br>";
            comment_container.innerHTML += "<button onclick='create_comment(" + post + ", " + currentUser_id + ")'>Отправить</button>";
        }
    });

}

</script>
