<?php
#your id

#bir session baslatalim burasi user login oldugunda ayni
#sayfada kalmasi ve login oldugunu anlamamizi sagliyor.

session_start();

//div alanlarinda gosterilen mesaj
$divMessage = "Busra's Insecure File Contents Server";

#Session'daki mesaj basarisiz bir login ise tum mesajlari kaldiriyoruz.
if(@$_SESSION['msg'] == 'Login Failure'){
  unset($_SESSION['msg']);
}

if(@$_SESSION['successMessage'] == 1){
  unset($_SESSION['successMessage']);
}

#this function // pull the userList file and check the user information..
#if you run this function you send two argv userpass ..
#bu fonksiyon tek bir paremetre aliyor oda sadece
#username alinan paremetre post esnasinda username:parola seklinde bu fonksiyona gonderiliyor.
#daha sonra fonksiyon ilgli dosyayi boslukdan ve satir sonunda ayirip username:password halinde array'a atiliyor.
#in arrray yardimiyla user listesi kontrol ediliyor.
#herhangi bir hatada buradaki fonksiyon false donecek.
function userControl($userpass){
  if(empty($userpass)){
    return;
  }
  $userLists = array();
  if(file_exists("./users_passwords.txt")){
    $userList = file_get_contents("./users_passwords.txt");
    if(!empty($userList)){
      foreach(explode("\n",$userList) as $x){
        if(!empty($x)){
        array_push($userLists,str_replace(" ",":",$x));
        }
       }
    }
  }
  if(in_array($userpass,$userLists)){
    return true;
  }
 
  return false;
}

#eger postla gelen islem login ise form datasindan gelen username ve parola 
#user ve pass seklinde degiskene atiliyor. daha sonra ilgli fonksiyon cagiriliyor.
#basarili yada basarisizsa session'daki msg alanlari guncelleniyor ve kullaniciya gosteriliyor.
if(isset($_POST['login'])){
  #if there is post , we process start // here
  #i will checking.. user userfile...
  $user = $_POST['user'];
  $pass = $_POST['pass'];
  if(!empty($user) && !empty($pass)){
    if(userControl("$user:$pass")){
      $_SESSION["login"] = "ok";
      $_SESSION['msg'] = "Successfull Login";
      $_SESSION['successMessage'] = 1;
    }else{
      echo  "<h1>username or password not correct,please try again!</h1>";
      $_SESSION['msg'] = "Login Failure";
      $error = true;
    }
  }

}

#eger kullanici cikis yapiyorsa session ve cookie paremetreleri siliniyor.
# ve kullanici yeniden ayni sayfaya yonlendiriyor.
if(isset($_POST['exit'])){
  session_destroy();
  setcookie('Files', null, -1, '/'); 
  header('Location: content_server.php');
}


#eger kullanici bir dosya cagiriyorsa dosya adi filename degiskenine setleniyor
#files dizini altinda ilgli dosya varsa dosya content alaninda gosteriliyor.
#ve kullanici her cagirim yaptiginda files cookie'sinin icine yaziliyor.
if(isset($_POST['file'])){
  #this line for security to directory traversel.. if you  open insecure you can open without basename;
  #$filename = basename($_POST['file_name']);
  $filename = $_POST['file_name'];

  if(isset($_COOKIE['Files'])){
    setcookie("Files", $_COOKIE['Files']."+".$filename);
  }else{
    setcookie("Files",$filename);
  }

  if(file_exists("./files/$filename")){
    $_SESSION['msg'] = "File Loaded ($filename)";
    $content = htmlentities(file_get_contents("./files/$filename"));
  }else{
    $_SESSION['msg'] = "File Not Found ($filename)";
  }
  $filename = $_POST['file_name'];
}
?>

<head>
   <title><?=empty($_SESSION['msg']) ? "Login" : $_SESSION['msg'];  ?></title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="" />
</head>
<body>
   <?php
   //herhangi bir hata yoksa ve session login'e ait bir durum yoksa login sayfasi tekrar gosteriliyor
   //ama yoksa login formu tekrar acilir.
    if(@$_SESSION["login"] !== "ok" &&  !(@$error))  {?>
	<div><h1><?=$divMessage;?></h1></div><br>
    <div id="loginMains">
           <form name="login" action="content_server.php" method="post">
              <div id="loginForm">
				Username:<input type="text" name="user" id="user"><br>
				Password:<input type="password" name="pass" id="user"><br><br>
                         <input type="submit" name="login" value="Login">
            </div>
    </form>
                      <?php }else{

} ?>
        <?php 
        //eger login varsa file load bolumu gosteriliyor.
        if(isset($_SESSION['login'])){?>
<div><h1><?=$divMessage;?></h1></div>
File Contents:
	<?php if(isset($_SESSION['successMessage'])){
    	 echo "<div><h1>(Successfully Logged In!)</h1></div>";
    } ?>
    <div id="loginMains">
           <form name="file" action="content_server.php" method="post">
             <textarea name="file_content" rows="20" cols="100" ><?=@$content;?></textarea><br>
               File Name:<input type="text" value="<?=!empty($filename) ? $filename : null ?>" name="file_name"><input type="submit" name="file" value="Load File">
               <input type="submit" name="exit" value="logoff">
            </div>
    </form>
<?php } ?>
</body>

</html>

