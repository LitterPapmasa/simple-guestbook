<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
       
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css" media="screen">
        <link rel="stylesheet" type="text/css" href="css/review.css" media="screen">
    </head>
    <body>
        <div class="container">
            <h1>Отзывы:</h1>        
            <?php
            //+++++++++++++++++++++++++++++++++
            //Параметры подлключения
            $host = "localhost";
            $loginDb = "root";
            $passDb = "1";            
            $db = "guestbook";
            //++++++++++++++++++++++++++++++++++
            
            //-----Подключаемся к базе------------------        
            $link  =  mysqli_connect($host, $loginDb, $passDb);
            /* Проверка соединения */ 
            if (mysqli_connect_errno()) { 
                printf("Подключение невозможно: %s\n", mysqli_connect_error()); 
                exit(); 
            } //else echo "connection ok...";    
            
            // Проверяем наличие базы.
            $db_selected = mysqli_select_db($link, $db);

            if (!$db_selected) {
                // If we couldn't, then it either doesn't exist, or we can't see it.
                $sql = "CREATE DATABASE $db";
                mysqli_query($link, $sql);
                if (mysqli_select_db($link, $db)) {                         
                    $sql="CREATE TABLE IF NOT EXISTS `review` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `name` varchar(64) NOT NULL,
                      `mail` varchar(64) NOT NULL,
                      `pubdate` datetime NOT NULL,
                      `text` text NOT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
                    $re = mysqli_query($link, $sql);
                    echo "Database $db created successfully\n";                              
                }                                             
            }
            
            //----- записываем в базу---------------     
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (!empty($_POST['name']) and !empty($_POST['text'])){
                    // escape variables for security
                    $name = mysqli_real_escape_string($link, $_POST['name']);
                    $text = mysqli_real_escape_string($link, $_POST['text']);
                    $pubdate = date("Y-m-d H:i:s");
                
                    if (!empty($_POST['mail'])) {
                        $mail = mysqli_real_escape_string($link, $_POST['mail']);
                    }    
                    $query = "INSERT INTO review (name ,mail ,pubdate ,text)";
                    $query.= "VALUES ('$name', '$mail', '$pubdate', '$text')";                      

                    $result = mysqli_query($link, $query) or die("Error " . mysqli_error($link)) ;
                }
            }
            //--------------------------------------
            $query = "SELECT name, mail,text ,pubdate FROM review";
            $result = mysqli_query($link, $query);
            
            //-- Выводим таблицу с отзывами --------------    
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            ?>

            <div class="container">
                <div class="row bg-info forjambo ">
                    <div class="col-xs-2  col-sm-2 col-md-2  bg-warning forjambo2">
                        <div class="name"><?php echo $row['name'];?> </div>
                        <div class="mail"><?php echo $row['mail']?></div>
                        <div class="pubdate"><?php echo $row['pubdate']?></div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4  col-lg-4"><?php echo $row['text'];?> </div>
                    <div class=""></div>
                </div>
            </div>

               <?php
                }
		
                
            //------------------------------------------------        
                mysqli_close($link);
                ?>

             <!-- Фопма заполнения отзывов ----------->
            <form class="col-xs-9 col-sm-7 col-md-6   col-lg-5" role="form" action="index.php" method="POST">
                <div class=" form-group">
                    <h3>Добавить коментарий:</h3>
                    
                    <p>Имя: <input class="form-control" type="input" name="name"></p>
                    <p>Почта: <input class="form-control" type="input" name="mail"></p>
                    </div  >
                <div >
                        Ответ:<textarea class="form-control" name="text" autofocus=""></textarea><br>
                        <input class="btn-block btn btn-success" type="submit" value="submit">
                </div>
                
            </form>
        </div>

    </body>
</html>
