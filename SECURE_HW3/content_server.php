<?php
#your id

session_start();

if(@$_SESSION['msg'] == 'Login Failure'){
  unset($_SESSION['msg']);
}

#this function // pull the userList file and check the user information..
#if you run this function you send two argv userpass ..
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

if(isset($_POST['login'])){
  #if there is post , we process start // here
  #i will checking.. user userfile...
  $user = $_POST['user'];
  $pass = $_POST['pass'];
  if(!empty($user) && !empty($pass)){
    if(userControl("$user:$pass")){
      $_SESSION["login"] = "ok";
      $_SESSION['msg'] = "Successfull Login";
    }else{
      echo  "<h1>username or password not correct,please try again!</h1>";
      $_SESSION['msg'] = "Login Failure";
      $error = true;
    }
  }

}
if(isset($_POST['exit'])){
  session_destroy();
  setcookie('Files', null, -1, '/'); 
  header('Location: content_server.php');
}

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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
   <title><?=empty($_SESSION['msg']) ? "Login" : $_SESSION['msg'];  ?></title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="" />
</head>
<body>
   <?php if(@$_SESSION["login"] !== "ok" &&  !(@$error))  {?>
	<div><h1>Tacettin's Insecure File Contents Server</h1></div><br>
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
        <?php if(isset($_SESSION['login'])){?>
<div><h1>Tacettin's Insecure File Contents Server</h1></div><br>
    <div id="loginMains">
           <form name="file" action="content_server.php" method="post">
             <textarea name="file_content" rows="10" cols="50" ><?=@$content;?></textarea><br>
               File Name:<input type="text" value="<?=!empty($filename) ? $filename : null ?>" name="file_name"><input type="submit" name="file" value="Load File">
               <input type="submit" name="exit" value="logoff">
            </div>
    </form>
<?php } ?>
</body>

</html>
