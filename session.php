<?php
// when user successfully login he will be brought on this page and this code create session for user and redirect user to index.phpß
if(($_SERVER['REQUEST_METHOD']=='GET') and (isset($_GET['username']) and (isset($_GET['userrole']))) ){
    session_start();
    $_SESSION['username']=$_GET['username'];
    $_SESSION['userrole']=$_GET['userrole'];
    $_SESSION['LoggedIn']=true;


    header('Location:index.php');
}


?>

<?php 
// when user click Logout button he will be brought on this code and this code will clear user's session and rediredt to index.php
    if($_SERVER['REQEUST_METHOD']=="GET" or (isset($_GET['Logout'])))
    {
        echo "this ";
        if($_GET['Logout']==true){
            session_start();
            unset($_SESSION['LoggedIn']);
            unset($_SESSION['username']);
            unset($_SESSION['userrole']);
            echo $_SESSION['username'];
            session_unset();
            session_destroy();
            header('Location:index.php?showError=You Have been Logged out');
            die();
        }
    }
?>