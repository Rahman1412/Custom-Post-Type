<?php
global $wpdb;
$message=$errname=$errDesc=$errCategory=$errImage="";
if(isset($_POST['sport_submit']) && $_SERVER['REQUEST_METHOD'] =='POST')
{
    $name=$_POST['sport_name'];
    $desc=$_POST['sport_desc'];
    $category=$_POST['sport_category'];
    $sample_image = $_FILES['sample_image'];
    if(empty($name))
    {
        $errname="The Name Field Is Required";
    }
    if(empty($desc))
    {
        $errDesc="The Description Field Is Required";
    }
    if(empty($category))
    {
        $errCategory="The Category Field Is Required";
    }
    if(empty($sample_image))
    {
        $errImage="The Image Field Is Required";
    }
    if($name !="" && $desc !="" && $category !="" && $sample_image !="")
    {
        if(! function_exists('wp_handle_upload'))
        {
            require_once(ABSPATH."wp-admin/includes/file.php");
        }
        $upload_overrides=array("test_form"=>false);
        $movefile=wp_handle_upload($sample_image,$upload_overrides);

        $args=array(
            "name"=>$name,
            "description"=>$desc,
            "category"=>$category,
            "image"=>$movefile['url'],
            "status"=>"publish"
        );
        $insert=$wpdb->insert(
            "wp_assoc_sports",
            $args
        );
        if($insert)
        {
            $message="Data Inserted Successfully.";
        }
        else
        {
            $message="Something Went Wrong.";
        }
    }
}
?>

<div class="container">
    <span class="text-danger"><?php echo $message ?></span>
    <form action="" method="POST" enctype="multipart/form-data" id="associate_form">
        <div class="form-group">
            <label for="name">Sprts Name</label>
            <input type="text" class="form-control" name="sport_name">
            <span class="text-danger"><?php echo $errname ?></span>
        </div>
        <div class="form-group">
            <label for="desc">Sports Description</label>
            <textarea type="text" rows="5" class="form-control" name="sport_desc"></textarea>
            <span class="text-danger"><?php echo $errDesc ?></span>
        </div>
        <div class="form-group">
            <label for="category">Sport Category</label>
            <select name="sport_category" id="" class="form-control">
            <option value=''>Select Category</option>
                <?php
                $args=array(
                    "taxonomy"=>"category",
                    "post_type"=>"sports",
                    "orderby"=>"name",
                    "hide_empty"=>false,
                    "order"=>"DESC"
                );
                $cate=get_categories($args);
                foreach($cate as $cat)
                {
                    echo"<option value='$cat->name'>$cat->name</option>";
                }
                ?>
                
            </select>
            <span class="text-danger"><?php echo $errCategory ?></span>
        </div>
        <div class="form-group">
            <label for="image">Sport Image</label>
            <input type="file" name="sample_image" class="form-control"/> 
            <span class="text-danger"><?php echo $errImage ?></span>
        </div>
        <input type="Submit" class="form-control" name="sport_submit" id="sport_submit">
    </form>
</div>