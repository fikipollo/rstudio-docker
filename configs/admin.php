<!DOCTYPE html>
<html>
<head>
  <title>RStudio admin site</title>
  <!--ONLINE REQUIREMENTS-->
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet" type='text/css'>
  <link href="https://opensource.keycdn.com/fontawesome/4.7.0/font-awesome.min.css" rel="stylesheet" type='text/css'>
  <!--ONLINE REQUIREMENTS-->
  <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <script type="text/javascript">
  function closeSession(){
    var dest = document.location.href.replace("//","//log:out@");
    var xhr = new XMLHttpRequest();
    xhr.open("POST", dest, true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.send();
    location.reload();
  }
  </script>
  <style>
  body {max-width: 1024px; margin: auto;background-color:#333;}
  .box{border: 5px solid #eee; padding: 10px; border-radius: 10px;}
  </style>
</head>

<body>
  <?php
  include 'admin_tools.php';

  $message="";

  if (!validAdminSession()){
    echo "<a class='btn btn-danger pull-right' onclick='javascript:closeSession()'> <i class='fa fa-sign-out' aria-hidden='true'></i> Logout</a>";
    echo "<h1 style='color:#fff;'>Not allowed</h1>";
    exit;
  }

  if (isset($_POST['action'])) {
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    switch ($_POST['action']) {
      case 'create_user':
      $message = create_user($_POST['username'], $_POST['passwd']);
      break;
      case 'create_students':
      $message = create_students($_POST['total'], $_POST['passwd_prefix']);
      break;
      case 'delete_students':
      $message = delete_students();
      break;
      case 'delete_user':
      $message = delete_user($_POST['delete_user']);
      break;
    }
  }
  ?>
  <a class='btn btn-danger pull-right' onclick='javascript:closeSession()'> <i class='fa fa-sign-out' aria-hidden='true'></i> Logout</a>
  <h1 style='color:#fff;'>Welcome to RStudio admin site</h1>
  <div style="background-color:#fff;padding: 80px 60px;" class="row">
    <div class='col-sm-12' style='margin-bottom:10px;'>
      <?php
      if ($message != "") {
        echo "<div class='well'><p class='text-success'>" . $message . "</p></div>";
      }
      ?>
    </div>

    <div class='col-sm-6'>
      <form action="admin.php" method="post" class="box"  style='margin-bottom:20px;'>
        <h2>Create new user</h2>
        <p class='text-info'>Create new users for RStudio. <br>You can use this form to change the user password.</p>
        <input type="hidden" name="action" value="create_user">
        <div class="form-group">
          <label for="user">User name:</label>
          <input type="text" class="form-control" id="user" name="username">
        </div>
        <div class="form-group">
          <label for="passwd">Password:</label>
          <input type="password" class="form-control" id="passwd" name="passwd">
        </div>
        <button type="submit" class="btn btn-success">Create user</button>
      </form>
      <form action="admin.php" method="post" class="box">
        <h2>Create new student accounts</h2>
        <p class='text-info'>
          Type the number of accounts to be added, and the prefix for the password for all the student accounts.<br>
          To update the password for all students, leave blank the field "Number of accounts" and enter the new password prefix.
        </p>
        <input type="hidden" name="action" value="create_students">
        <div class="form-group">
          <label for="total">Number of accounts:</label>
          <input type="number" class="form-control" id="total" name="total">
        </div>
        <div class="form-group">
          <label for="passwd_prefix">Password prefix:</label>
          <input type="text" class="form-control" id="passwd_prefix" name="passwd_prefix">
        </div>
        <button type="submit" class="btn btn-success">Create student accounts</button>
        <p class='well well-sm text-info' style="margin-top:10px;">
          <i class='fa fa-info-circle'></i><b> About student accounts</b><br>
          Student accounts are the fastest way to create new accounts for a course or workshop.<br>
          Each account will be <b>student<i>N</i></b> with password <b>password_prefix<i>N</i></b>, where <i>N</i> is the number of the student.<br>
          E.g. Using "supersecret" as password prefix, student5 should use <i>supersecret5</i> as password.<br>
        </p>
      </form>
    </div>
    <div class='col-sm-6 box' style='margin-bottom:20px;'>
      <h2>Delete users</h2>
      <p class='text-info'>Please choose the accounts that you want to remove.</p>
      <form action="admin.php" method="post" style=" float: left; margin-right: 10px; ">
        <input type="hidden" name="action" value="delete_students">
        <button type="submit" class="btn btn-warning">Delete all student accounts</button>
      </form>
      <form action="admin.php" method="post">
        <input type="hidden" name="action" value="delete_user">
        <button type="submit" class="btn btn-danger">Delete selected users</button>
        <table class="table">
          <thead>
            <tr>
              <th style=" width: 30px;"></th>
              <th>User name</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $users = getUsers();
            foreach ($users as $value) {
              echo "<tr><td><input type='checkbox' name='delete_user[]' value='" . $value . "'></td><td>" . $value . "</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </form>
    </div>
  </div>
</body>
</html>
