<!-- this file only for those who have id that is related to the college -->
<?php
// inserting data to the student table 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['BtnTestSubmitStudent'])) {
    include 'db_connect.php';
    $username = $_POST['StudentName'];
    $useremail = $_POST['StudentEmail'];
    $userphonenumber = $_POST['StudentPhoneNumber'];
    $CompYear = $_POST['StudentCompYear'];
    $CGPA = $_POST['StudentCGPA'];
    $userpassword = $_POST['StudentPassword'];
    $userconfirmpassword = $_POST['StudentConfirmPassword'];
    $bool = checkStudent($username, $userphonenumber, $userpassword, $userconfirmpassword, $useremail);
    if ($bool) {
        include 'db_connect.php';
        $haspassword = password_hash($userpassword, PASSWORD_DEFAULT);
        $query = "INSERT INTO `teststudent` (`studentName`, `studentEmail`, `phoneNumber`, `complitionyear`, `cgpa`, `Password`) VALUES ( '$username', '$useremail', '$userpassword', '$CompYear', '$CGPA', '$haspassword')";
        $resultQuery = mysqli_query($conn, $query);
        $sql = "SELECT * FROM `teststudent` WHERE `studentEmail` LIKE '$useremail'";
        $resultSql = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($resultSql);
    }
}

?>
<?php
function checkStudent($username, $userphonenumber, $userpassword, $userconfirmpassword, $useremail)
{
    require 'db_connect.php';
    // echo $username;
    $con = $conn;
    // check if password and confirmpassword match or not
    if ($userpassword == $userconfirmpassword) {
        // checkin if entered any of details is available in database or not
        // $check="SELECT * FROM `Users` where username='$username' or phoneNumber='$userphonenumber' or userEmail='$useremail'";
        // this $check query is only for production purpose real check query is above
        $check = "SELECT * FROM `teststudent` where studentName='$username'";
        // var_dump($check);
        $resultQuery = mysqli_query($conn, $check);
        // $numOfRows = mysqli_num_rows($resultQuery);
        // echo $numOfRows;

        // if entered details is not availabe in database then flow goes inside this if statement 
        // if ($numOfRows == 0) {
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
}
//   else {
//     // if password does't match
//     $showError = 'Plase enter the password carefully! it doesn\' match';
//     //heading to index.php with error message
//     header("Location:index.php?showError=$showError");
//     die();
//   }
// }
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Hello, world!</title>
</head>

<body>
    <h1>this is for only those who have Id related to college</h1>
    <?php include 'header.php' ?>
    <div class="row container">
        <div class="card container mx-1 my-2" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Student Register Form</h5>
                <p class="card-text">You need to have StudentId to Login as Student</p>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#signUpStudentModal">
                    Login as Student
                </button>
            </div>
        </div>
        <div class="card mx-1 my-2" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Faculty Register Form</h5>
                <p class="card-text">You need to have Faculty to Login as Faculty</p>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#LoginFaculty">
                    Login as Faculty
                </button>
            </div>
        </div>
    </div>
    <!-- Student Login Form -->
    <!-- login form for those who have student id  -->
    <div class="modal fade" id="signUpStudentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Login as Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/Project/wp-content/themes/MainTheme/header.php" method="post">
                        <div class="mb-3">
                            <label for="InputSId" class="form-label">Student Id</label>
                            <input type="text" class="form-control" name="SId" id="SId" aria-describedby="emailHelp">
                        </div>
                        <div class="mb-3">
                            <label for="InputStudentName" class="form-label">Studentname</label>
                            <input type="text" class="form-control" name="StudentName" id="StudentName" aria-describedby="emailHelp">
                        </div>
                        <div class="mb-3">
                            <label for="InputEmail1" class="form-label">Email address</label>
                            <input type="email" class="form-control" name="StudentEmail" id="StudentEmail" aria-describedby="emailHelp">
                        </div>
                        <div class="mb-3">
                            <label for="StudentPhoneNumber" class="form-label">Phone</label>
                            <input type="Phone" class="form-control" name="StudentPhoneNumber" id="StudentPhoneNumber" aria-describedby="emailHelp">
                        </div>
                        <div class="mb-3">
                            <label for="InputStudentPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" name="StudentPassword" id="StudentPassword" aria-describedby="emailHelp">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="StudentSubmit" value="StudentSubmit" name="StudentSubmit" class="btn btn-primary">Submit</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="LoginFaculty" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/Project/wp-content/themes/MainTheme/header.php" method="POST">
                        <div class="mb-3">
                            <label for="exampleInputfacultyId" class="form-label">Faculty Id</label>
                            <input type="text" class="form-control" name="facultyId" id="facultyId" aria-describedby="facultyId">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputfacultyName" class="form-label">Faculty Name</label>
                            <input type="text" class="form-control" name="facultyName" id="facultyName" aria-describedby="facultyName">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputfacultyEmail" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="facultyEmail" name="facultyEmail" aria-describedby="facultyEmail">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputfacultyPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="facultyPassword" name="facultyPassword">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="LoginFaculty" value="LoginFaculty" class="btn btn-primary">Submit</button>
                    </form>
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
<?php include 'footer.php'; ?>