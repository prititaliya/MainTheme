<?php
// for signing up 
require 'db_connect.php';
$showError = '';
if (($_SERVER['REQUEST_METHOD'] == 'POST') && (isset($_POST['BtnSignUp']))) {
  $userName = $_POST['userName'];
  $userEmail = $_POST['userEmail'];
  $userPhoneNumber = $_POST['userPhoneNumber'];
  $userPassword = $_POST['userPassword'];
  $userConfirmPassword = $_POST['userConfirmPassword'];
  // echo $userName.'<br>';
  $bool = check($userName, $userPhoneNumber, $userPassword, $userConfirmPassword, $userEmail);
  if ($bool) {
    // if everything is ok then flow has came here
    // converting password to hasform inoreder to save in database
    $hasPassword = password_hash($userPassword, PASSWORD_DEFAULT);
    $query = "INSERT INTO `Users` (`userName`, `userEmail`, `phoneNumber`, `Password`, `userRole`) VALUES ('$userName', '$userEmail', '$userPhoneNumber', '$hasPassword', 'user')";
    $result = mysqli_query($conn, $query);
    // echo var_dump($result);
    echo mysqli_error($conn);
    if ($result == true) {
      // user is register and redirected to index.php page
      header('Location:index.php?singnup=true');
      die();
    } else {
    }
  } else {
  }
}
?>
<?php
function check($username, $userphonenumber, $userpassword, $userconfirmpassword, $useremail)
{
  require 'db_connect.php';
  // echo $username;
  $con = $conn;
  // check if password and confirmpassword match or not
  if ($userpassword == $userconfirmpassword) {
    // checkin if entered any of details is available in database or not
    // $check="SELECT * FROM `Users` where username='$username' or phoneNumber='$userphonenumber' or userEmail='$useremail'";
    // this $check query is only for production purpose real check query is above
    $check = "SELECT * FROM `Users` where username='$username'";
    // var_dump($check);
    $resultQuery = mysqli_query($conn, $check);
    $numOfRows = mysqli_num_rows($resultQuery);
    // echo $numOfRows;

    // if entered details is not availabe in database then flow goes inside this if statement 
    if ($numOfRows == 0) {
      // checkin if entered phonenumber contains 10 digit or not
      $len = strlen($userphonenumber);
      if ($len == 10) {
        // if everything goes right then returing true value to invokin fuction to precced further
        return true;
      } else {
        // if does't contain 10 digit
        $showError = "Phone number must be length of 10 digit";
        //heading to index.php with error message
        header("Location:index.php?showError=$showError");
        die();
      }
    } else {
      //if data is already available in database
      $showError = "username,phone number or emailaddress is Already exists";
      //heading to index.php with error message
      header("Location:index.php?showError=$showError");
      die();
    }
  } else {
    // if password does't match
    $showError = 'Plase enter the password carefully! it doesn\' match';
    //heading to index.php with error message
    header("Location:index.php?showError=$showError");
    die();
  }
}
?>

<?php
// for log in purpose
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['BtnLogin'])) {
  echo 'fh';
  require 'db_connect.php';
  $userName = $_POST['userName'];
  $userPassword = $_POST['userPassword'];
  echo $userName." ".$userPassword;
  $Loginquery = "SELECT * FROM `Users` where username='$userName' or phoneNumber='$userName' or userEmail='$userName'";
  $result = mysqli_query($conn, $Loginquery);
  // echo var_dump($result);
  $numOfRows = mysqli_num_rows($result);
  $row = mysqli_fetch_assoc($result);
  if ($numOfRows == 1) {
    if (password_verify($userPassword, $row['Password'])) {
      if ($userName == $row['userName'] && $row['userRole'] == 'admin') {
        $session = true;
        header("Location:session.php?username=$userName&&userrole=admin");
        die();
      }elseif($userName == $row['userName'] && $row['userRole'] == 'user'){
        $session = true;
        header("Location:session.php?username=$userName&&userrole=user");
        die();
      }
    } else {
      $showError = "password you entered is incorrect";
      header("Location:index.php?showError=$showError");
      die();
    }
  }
}
// from personal.php student login form
elseif(($_SERVER['REQUEST_METHOD']=='POST') &&  (isset($_POST['StudentSubmit'])) && (isset($_POST['SId']))){
        echo "yid";
        $sname=$_POST['StudentName'];
        $sid=$_POST['SId'];
        $spassword=$_POST['StudentPassword'];
        $semail=$_POST['StudentEmail'];
        $sphonenumber=$_POST['StudentPhoneNumber'];
        require 'db_connect.php';
        $sql="SELECT * FROM `teststudent` WHERE `studentId` = '$sid'";
        $chekinresult=mysqli_query($conn,$sql);
        $row=mysqli_fetch_assoc($chekinresult);
        $numOfRows=mysqli_num_rows($chekinresult);

        if($numOfRows==1){
          $query="SELECT * FROM `Users` where userEmail='$semail' ";
          $res=mysqli_query($conn,$query);
          echo $query;
          $rowuid=mysqli_fetch_assoc($res);
          $uid= $rowuid['userId'];
          $sqlrole="UPDATE `Users` SET `userRole` = 'student' WHERE `Users`.`userId` = $uid";
          $sqlsid="UPDATE `Users` SET `studentId` = '$sid' WHERE `Users`.`userId` =$uid";
          $sqlfid="UPDATE `Users` SET `FacultyId` = 'NULL' WHERE `Users`.`userId` =$uid";
          echo mysqli_error($conn);
          $resultrole=mysqli_query($conn,$sqlrole);
          $resultfid=mysqli_query($conn,$sqlfid);
          $resultsid=mysqli_query($conn,$sqlsid);
          echo mysqli_error($conn);
          // echo var_dump($resultid);
          echo $rowuid['userRole'];
          if ($sname == $rowuid['userName'] && $rowuid['userRole'] == 'student') {
            $session = true;
            header("Location:session.php?username=$sname&&userrole=student");
            die();
          }
    
        }
}
elseif($_SERVER['REQUEST_METHOD']=='POST' && ((isset($_POST['LoginFaculty']))) && (isset($_POST['facultyId']))){
  $faculty_id=$_POST['facultyId'];
  $faculty_name=$_POST['facultyName'];
  $faculty_email=$_POST['facultyEmail'];
  $faculty_password=$_POST['facultyPassword'];
  require 'db_connect.php';
  $sql="SELECT * FROM `Faculty` WHERE `FacultyId` = '$faculty_id'";
  $result=mysqli_query($conn,$sql);
  $row=mysqli_fetch_assoc($result);
  if ($row['FacultyRole']==1){
    $role='Hod';
  }else{
    $role='Faculty';
  }
  $numOfRows=mysqli_num_rows($result);
  if($numOfRows==1){
    $query="SELECT * FROM `Users` where userEmail='$faculty_email' ";
    $res=mysqli_query($conn,$query);
    $rowuid=mysqli_fetch_assoc($res);
    $uid=$rowuid['userId'];
    // echo mysqli_num_rows($res);
    echo ($uid);
    // echo var_dump(mysqli_num_rows($res));
    // $sqlrole="UPDATE `Users` SET `userRole` = '$role' WHERE `Users`.`userId` = $uid";
    $sqlrole="UPDATE `Users` SET `userRole` = '$role' WHERE `Users`.`userId` = $uid";
    $sqlsid="UPDATE `Users` SET `studentId` = '0' WHERE `Users`.`userId` =$uid";
    $sqlfid="UPDATE `Users` SET `FacultyId` = '$faculty_id' WHERE `Users`.`userId` =$uid";
    // echo $sqlsid;
    $resultrole=mysqli_query($conn,$sqlrole);
    // echo mysqli_error($conn);
    // echo var_dump($sqlrole);
    $resultfid=mysqli_query($conn,$sqlfid);
    $resultsid=mysqli_query($conn,$sqlsid);
    echo var_dump($faculty_name == $rowuid['userName'] && $rowuid['userRole'] == 'Faculty');
    if ($faculty_name == $rowuid['userName'] && $rowuid['userRole'] == 'Faculty') {
      $session = true;

      header("Location:session.php?username=$faculty_name&&userrole=Faculty");
      die();
    }elseif($faculty_name == $rowuid['userName'] && $rowuid['userRole'] == 'Hod'){
      $session = true;
      header("Location:session.php?username=$faculty_name&&userrole=Hod");
      die();
    }
  }

}
?>
<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>

  <?php

// this is for main header 
  $path = dirname(__FILE__);
  $path = substr($path, 18);
  $file=basename($_SERVER['PHP_SELF']);
  // echo $path;
  echo '<header class="p-3 bg-dark text-white">
<div class="container">
  <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
    <a href="index.php" class="btn btn-warning small d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
        <!--<img src="/' . $path . '/mainicon.php"  alt="icon"> -->
        <img src="https://img.icons8.com/external-becris-solid-becris/64/000000/external-education-literary-genres-becris-solid-becris.png"/>
    </a>

    <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">

        <li class="nav-link px-2 text-white mr-1'; if($file=="index.php"){ echo ''; }?>"> <a class="btn btn-outline-warning" href="<?php echo '/'.$path."/index.php".'';?>"> Home </a></li>
        <li class="nav-link px-2 text-white mr-1" > <a class="btn btn-outline-warning " href="<?php echo '/'.$path."/about.php".'';?>"> About </a></li>
        <li class="nav-link px-2 text-white mr-1" > <a class="btn btn-outline-warning " href="<?php echo '/'.$path."/department.php".'';?>"> Academic </a></li>
        <li class="nav-link px-2 text-white mr-1" > <a class="btn btn-outline-warning " href="<?php echo '/'.$path."/gallary.php".'';?>"> Gallery </a></li>
        <li class="nav-link px-2 text-white mr-1" > <a class="btn btn-outline-warning " href="<?php echo '/'.$path."/downloads.php".'';?>"> downloads </a></li>
      
    </ul>

    <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
      <input type="search" class="form-control form-control-dark" placeholder="Search..." aria-label="Search">
    </form>

    <div class="text-end">
    <!-- Button trigger modal -->
  <?php 
  // this is for if user is loogedin then he will see only Logout Button and profile button 
  if (isset($_SESSION['LoggedIn']) and isset($_SESSION['username'])) {
    echo '
      <!-- Button trigger modal -->
    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#profileModal">
      ' . $_SESSION['username'] . '
    </button>
      <a href="/' . $path . '/session.php?Logout=true"> <button type="button" id="BtnLogout" name="BtnLogout" class="btn btn-outline-light me-2">Logout</button>';
  } /*this is for if user is't logged in then he will see Login And Sign up button*/else {
    echo '
      <button type="button" class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#loginModal" type="button" class="btn btn-outline-light me-2">Login</button>
      <button type="button" class="btn btn-warning" type="button"  data-bs-toggle="modal" data-bs-target="#signUpModal">Sign-up</button>';
  }
  echo '</div>
      </div>
      </div>
      </header>';
  ?>
  <!-- this modal is for sign up this will be shown up when user click signup Button -->
  <div class="modal fade" id="signUpModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Sign Up</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="/Project/wp-content/themes/MainTheme/header.php" method="post">
            <div class="mb-3">
              <label for="InputUserName" class="form-label">Username</label>
              <input type="text" class="form-control" name="userName" id="userName" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
              <label for="InputEmail1" class="form-label">Email address</label>
              <input type="email" class="form-control" name="userEmail" id="userEmail" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
              <label for="userPhoneNumber" class="form-label">Phone</label>
              <input type="Phone" class="form-control" name="userPhoneNumber" id="userPhoneNumber" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
              <label for="InputuserPassword" class="form-label">Password</label>
              <input type="password" class="form-control" name="userPassword" id="userPassword" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
              <label for="InputuserConfirmPassword" class="form-label">Confirm Password</label>
              <input type="password" class="form-control" name="userConfirmPassword" id="userConfirmPassword" aria-describedby="emailHelp">
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="BtnSubmit" name="BtnSignUp"  class="btn btn-primary">Submit</button>
          <a type="button" href="/<?php echo $path?>/Personal.php" class="btn btn-outline-dark">have id related to college</a>
        </div>
        </form>
      </div>
    </div>
  </div>

  <!-- this modal for login this will will be poped up when user click Login Button -->
  <!-- Modal -->
  <div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Login</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="/Project/wp-content/themes/MainTheme/header.php" method="post">
            <div class="mb-3">
              <label for="exampleInputuserName" class="form-label">username,Email id or phone Number</label>
              <input type="text" class="form-control" id="userName" name="userName" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
              <label for="exampleInputuserPassword" class="form-label">Password</label>
              <input type="password" class="form-control" id="userPassword" name="userPassword">
            </div>
            <div class="mb-3 form-check">
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="BtnSubmit" name="BtnLogin" value="Login" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- this modal for Profile details will be shown in this Modal this will be poped up when user click on username  -->
  <!-- Modal -->
  <div class="modal fade modal-dialog modal-dialog-scrollable" id="profileModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel"><?php echo $_SESSION['username'] ?>'s profile</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <table class="table">
  <thead>
    <?php 
    // this will generate table for userdetails 
      require 'db_connect.php';
    $user=$_SESSION['username'];
      $tableQuery="SELECT * FROM `Users` where userName='$user'";
      $tbresult=mysqli_query($conn,$tableQuery);
       $tbrow=mysqli_fetch_assoc($tbresult);
      //  this is for First row of table 
       echo ' <tr><th scope="col">#</th>
       <th scope="col">Details</th>
       </tr></thead><tbody>';
      //  this is body content of table
       foreach ($tbrow as $key => $value){
         if($key=='Password')
         continue;
        echo '<tr><td scope="col">'.$key.'</td>
        <td scope="col">'.$value.'</td>';
       }
    ?>
    </tbody>
</table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Optional JavaScript; choose one of the two! -->

  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
</body>

</html>

<?php 
  header("Location:index.php");
?>