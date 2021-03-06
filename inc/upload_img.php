<?php
$target_file = $target_dir . basename($_FILES["image"]["name"]);


//
//// Strip HTML Tags
//$clear = strip_tags($target_file);
//// Clean up things like &amp;
//$clear = html_entity_decode($clear);
//// Strip out any url-encoded stuff
//$clear = urldecode($clear);
//// Replace non-AlNum characters with space
//$clear = preg_replace('/[^A-Za-z0-9]/', '', $clear);
//// Replace Multiple spaces with single space
//$clear = preg_replace('/ +/', '', $clear);
//// Trim the string of leading/trailing space
//$clear = trim($clear);



$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false) {
        //echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
      
        
    } else {
       // echo "File is not an image."; 
        $_SESSION["message"] = "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
   // echo "Sorry, FILE NAME already exists.";
    $_SESSION["message"] = "Sorry, FILE NAME already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["image"]["size"] > 5000000) {
    //echo "Sorry, your file is too large.";
    $_SESSION["message"] = "Sorry, your file is too large. 5000kb is the max file size.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" && $imageFileType != "JPG" && $imageFileType != "PNG" ) {
    //echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $_SESSION["message"] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    $_SESSION["message"] .= "    Your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        //FILE UPLOADED!
        
            $user_id = $_SESSION["user_id"];
            $username = $_SESSION["username"];
      
        // unmark all as current, then insert current image    // Perform Update

    $reset_query  = "UPDATE profile_img SET ";
    $reset_query .= "current = 0 ";
    $reset_query .= "WHERE user_id = {$user_id} ";
    $reset_result = mysqli_query($connection, $reset_query);


           
    $query  = "INSERT INTO profile_img (";
    $query .= "  user_id, filepath, current";
    $query .= ") VALUES (";
    $query .= "  {$user_id},'{$target_file}',1 ";
    $query .= ")";
    $result = mysqli_query($connection, $query);
         

    if ($result && mysqli_affected_rows($connection) == 1) {
      // Success
      $_SESSION["message"] = "Image Upload SUCCESSFUL!";
      redirect_to("member_profile.php?user_id={$_SESSION["user_id"]}");
    } else {
      // Failure
      $_SESSION["message"] = "Image uploaded. Filepath NOT written.";
    }//end fail or success redirect
        
        
  
       

        
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
    

?>