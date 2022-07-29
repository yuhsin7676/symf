<html>
<body>
    
    <h3>Пользователь: <?php echo $username?></h3>
    
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
function choose_user(){

    var user = user_list.value;
    var username = document.querySelector("#user_list option[value = '" + user + "']").innerHTML;
    console.log(username);

    $.ajax({
        url: "/choose_user",
        method: "POST",
        data:{
            user: user,
            username: username
        },
        success: function(data){
            currentUser_id = user;
            if(user_list.value != 0)
                button_new_post.disabled = false;
            else
                button_new_post.disabled = true;
        },
        error: function(data){
            user_list.value = currentUser_id;
            console.log(data);
            alert("Произошла ошибка выбора пользователя");
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
                post_list.innerHTML += "<p>" + data[key].title + "<button onclick='find_post(" + data[key].id + ")'>Читать</button></p>";
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
            post_container.innerHTML = "<p><strong>" + data.author.name + "</strong>" + data.created_at.date + "</p>";
            post_container.innerHTML += "<h3>" + data.title + "</h3>";
            post_container.innerHTML += "<h5>" + data.preview + "</h5>";
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
                post_container.innerHTML += "<p><strong>" + data[key].author.name + "</strong>" + data[key].created_at.date + "</p>";
                post_container.innerHTML += "<p>" + data[key].text + "</p>";
            }
            comment_container.innerHTML += "<textarea id = 'my_comment' placeholder = 'Оставьте здесь комментарий.'></textarea>";
            comment_container.innerHTML += "<button onclick='create_comment(" + post + ", " + currentUser_id + ")'>Отправить</button>";
        }
    });

}

//
function create_comment(post, author){

    var text = document.getElementById("my_comment").value;

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

</script>
