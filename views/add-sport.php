<style>
.categories{
    padding:5px;
}
</style>

<?php
global $current_user;
if(isset($_GET['id']))
{
	$postId=$_GET['id'];
	$my_post=get_post($postId);
	$my_cat=get_the_category($my_post->ID);
}
if(isset($_POST['updatePost']))
{
    $up_id=$_POST['postId'];
	$up_title=$_POST['title'];
	$up_sample_image = $_FILES['sample_image']['name'];
	$up_post_content = $_POST['sample_content'];
	$up_category =array($_POST['category']);
	$up_post=array(
		'ID'=> $up_id,
		'post_title'=> $up_title,
         'post_content'=> $up_post_content,
	);
	$up_id=wp_update_post($up_post);	
	wp_set_post_categories($up_id,$up_category);

	if (!function_exists('wp_generate_attachment_metadata'))
		{
			require_once(ABSPATH . "wp-admin" . '/includes/image.php');
			require_once(ABSPATH . "wp-admin" . '/includes/file.php');
			require_once(ABSPATH . "wp-admin" . '/includes/media.php');
		}
        if ($_FILES)
		{
			foreach ($_FILES as $file => $array)
			{
				if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK)
				{
					return "upload error : " . $_FILES[$file]['error'];
				}
				$attach_id = media_handle_upload( $file, $up_id );
			}
		}
        if ($attach_id > 0)
		{
			//and if you want to set that image as Post then use:
			update_post_meta($up_id, '_thumbnail_id', $attach_id);
		}
	
}
if(isset($_POST['submitpost']))
{
         $post_title = $_POST['title'];
		 $sample_image = $_FILES['sample_image']['name'];
		 $post_content = $_POST['sample_content'];
		 $category =array($_POST['category']);

        $new_post = array(
			'post_title' => $post_title,
			'post_content' => $post_content,
			'post_status' => 'publish',
			'post_type' => "sports",
			// 'post_category' => $category
		);
        $pid = wp_insert_post($new_post);
		// add_post_meta($pid, 'meta_key', true);
		wp_set_post_categories($pid,$category);

        if (!function_exists('wp_generate_attachment_metadata'))
		{
			require_once(ABSPATH . "wp-admin" . '/includes/image.php');
			require_once(ABSPATH . "wp-admin" . '/includes/file.php');
			require_once(ABSPATH . "wp-admin" . '/includes/media.php');
		}
        if ($_FILES)
		{
			foreach ($_FILES as $file => $array)
			{
				if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK)
				{
					return "upload error : " . $_FILES[$file]['error'];
				}
				$attach_id = media_handle_upload( $file, $pid );
			}
		}
        if ($attach_id > 0)
		{
			//and if you want to set that image as Post then use:
			update_post_meta($pid, '_thumbnail_id', $attach_id);
		}
        // $my_post1 = get_post($attach_id);
		// $my_post2 = get_post($pid);
		// $my_post = array_merge($my_post1, $my_post2);
}
?>
    <?php
global $user_id;
$args = array(
    "taxonomy"   => "category",
    "post_type" => "sports",      
    "orderby"   => "name",
    'hide_empty' => false,
    "order"     => "DESC"
);
$catList = get_categories($args);
foreach($catList as $catlists)
{
    echo "<a id='catVal' class='categories' href='".$catlists->term_id."'>".$catlists->name."</a>";
}
?>

<div class="col-sm-12">
	<h3>Add New Post</h3>
	<form class="form-horizontal" name="form" method="post" enctype="multipart/form-data">
		<input type="hidden" name="postId" <?php if(isset($_GET['id'])){?> value="<?php echo $my_post->ID ?>" <?php } ?>/>
		<div class="col-md-12">
			<label class="control-label">Title</label>
			<input type="text" class="form-control" name="title" <?php if(isset($_GET['id'])){?> value="<?php echo $my_post->post_title ?>" <?php } ?>/>
		</div>

		<div class="col-md-12">
			<label class="control-label">Sample Content</label>
			<textarea class="form-control" rows="8" name="sample_content" <?php if(isset($_GET['id'])){?> value="<?php echo $my_post->post_content ?>" <?php } ?>><?php if(isset($_GET['id'])){?> <?php echo $my_post->post_content ?> <?php } ?></textarea>
		</div>

		<div class="col-md-12">
			<label class="control-label">Choose Category</label>
			<select name="category" class="form-control">
			<?php if(isset($_GET['id'])){?> <option value="<?php echo $my_cat[0]->cat_ID ?>"><?php echo $my_cat[0]->cat_name ?></option> <?php } ?>
				<?php
                $args = array(
                    "taxonomy"   => "category",
                    "post_type" => "sports",      
                    "orderby"   => "name",
                    'hide_empty' => false,
                    "order"     => "DESC"
                );
				$catList = get_categories($args);
				foreach($catList as $listval)
				{
					echo '<option value="'.$listval->term_id.'">'.$listval->name.'</option>';
				}
				?>
			</select>
		</div>

		<div class="col-md-12">
			<label class="control-label">Upload Post Image</label>
			<input type="file" name="sample_image" class="form-control"/>
		</div>

		<div class="col-md-12">
			<?php
			if(isset($_GET['id']))
			{
				?>
				<input type="submit" class="btn btn-primary" value="UPDATE" name="updatePost" />
				<?php
			}
			else{
				?>
				<input type="submit" class="btn btn-primary" value="SUBMIT" name="submitpost" />
				<?php
			}
			?>
			
		</div>
	</form>
	<div class="clearfix"></div>
</div>