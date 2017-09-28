<?php

function create_user($username, $passwd) {
  if($username == "" || $passwd == ""){
    return "";
  }

  $message = "";
  $users = getUsers();
  foreach ($users as $user) {
    if($user == $username){
      $message = "User " . $username . "already exists. Password was updated.";
      break;
    }
  }

  $out = shell_exec('sudo useradd -m ' . $username);
  $out = shell_exec('echo '. $username .':'. $passwd .' | sudo chpasswd');
  $out = shell_exec('sudo chown -R '. $username .':'. $username .' /home/'. $username . '/');
  if($message == ""){
    $message = "The new user " . $username . " has been successfully created.";
  }
  return $message;
}

function create_students($total, $passwd_prefix) {
  if($passwd_prefix == ""){
    return "";
  }

  /* 1. GET LAST USER ACCOUNT */
  $students = getStudents();
  $max=0;

  foreach ($students as $student) {
    $n = (int) str_replace("student","", $student);
    if($n > $max){
      $max = $n;
    }
    create_user($student, $passwd_prefix . $n);
  }

  if($total == "" || $total == 0){
    return "The password for students has been updated.<br> New password is <i>".$passwd_prefix."[student number]</i>.<br>E.g. For student5, password will be <i>".$passwd_prefix."5</i>";
  }

  /*  2. ADD NEW USERS */
  for ($x = 0; $x < $total; $x++) {
    $max=$max + 1;
    create_user("student" . $max, $passwd_prefix . $max);
  }

  return $total . " new student accounts have been created. The password for previous students has been updated.<br> New password is <i>".$passwd_prefix."[student number]</i>.<br>E.g. For student5, password will be <i>".$passwd_prefix."5</i>";
}

function delete_students() {
  /*  1. GET ALL STUDENT ACCOUNTS */
  $students = getStudents();
  /*  2. REMOVE ALL ACCOUNTS */
  delete_user($students);
  return "All student accounts were successfully removed";
}

function delete_user($users) {
  if($users == ""){
    return "";
  }

  /* TODO: CHECK IF REMOVABLE USER */
  $deleted="";
  foreach ($users as $user) {
    $out = shell_exec('sudo deluser --remove-home ' . $user);
    $deleted = $deleted . " " . $user;
  }
  return "The users " . $deleted . " have been successfully removed.";
}

function getUsers() {
  $out = shell_exec('ls /home/');
  return explode("\n", rtrim($out, "\n"));
}

function getStudents() {
  $out = shell_exec('ls /home/ | grep "student[0-9]*$"');
  return explode("\n", rtrim($out, "\n"));
}

function validAdminSession(){
  return true;
}
?>
