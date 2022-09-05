<?php
if(isset($_POST['userregister']))
{
    $name= $_POST['username'];
    $email=$_POST['email'];
    $image=$_FILES['image'];
    $phone=$_POST['phone'];
    $password=$_POST['pswd'];
   if($name !="" && $email !="" && $image !="" && $phone !="" && $password !="")
   {
       $user_id=wp_create_user($name,$password,$email);
       if(! function_exists('wp_handle_upload'))
       {
           require_once(ABSPATH."wp-admin/includes/file.php");
       }
       $upload_overrides=array("test_form"=>false);
       $movefile=wp_handle_upload($image,$upload_overrides);
       add_user_meta($user_id,"wp_user_avatar",$movefile);
       add_user_meta($user_id,"user_status","0");
       add_user_meta($user_id,"user_phone",$phone);
   }
}
?>
<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" name="username">
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="text" class="form-control" name="email">
    </div>
    <div class="form-group">
        <label for="image">Image</label>
        <input type="file" class="form-control" name="image">
    </div>
    <div class="form-group">
        <label for="phone">Mobile Numner</label>
        <input type="text" class="form-control" name="phone">
    </div>
    <div class="form-group">
        <label for="pswd">Password</label>
        <input type="password" class="form-control" name="pswd">
    </div>
    <div class="text-center">
        <input type="submit" class="form-control bg-dark" name="userregister">
    </div>
</form>