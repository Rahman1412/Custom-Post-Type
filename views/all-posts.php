
<div class="container">
    
    <table class="table table-striped table-dark">
        <thead>
            <tr>
              <th>#ID</th>
                <th>Post Title</th>
                <th>Post Description</th>
                <!-- <th>Post Category</th> -->
                <th>Post Image</th>
                <th>Post Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $args=array(
                "post_type"=>"sports",
                'posts_per_page' => 3,
                'paged' => $paged,
            );
           // $posts=get_posts($args);
           $data =  new WP_Query($args);
           $postData = $data->posts;
            foreach($postData as $post)
            {
                $postDta = get_post_meta($post->ID);
                $postImgId =$postDta['_thumbnail_id'][0];
                $postImgUrl = get_post_meta($postImgId);
                $featureImg = $postImgUrl['_wp_attached_file'][0];
                if(!empty( $postImgId)){
                   $imgUrl="http://localhost/project/wordpress_project/wordpress_6/wp-content/uploads/".$featureImg."";
                }else{
                   $imgUrl="";
                }
                ?>
                <tr>
                <th scope="row"><?php echo $post->ID ?></th>
                    <th><?php echo $post->post_title ?></th>
                    <td><?php echo wp_trim_words($post->post_content,3) ?></td>
                    <!-- <td><?php echo "HEllo" ?></td> -->
                    <td><img src="<?php echo $imgUrl; ?>"></td>
                    <td>
                        <input type="hidden" id="postValue" value="<?php echo $post->ID ?>">
                        <span><a data-toggle="modal" data-target="#exampleModal"><i class="fa-solid fa-trash-can text-danger"></i></a></span>|
                        <span><a href="http://localhost/project/wordpress_project/wordpress_6/sport-post?id=<?php echo $post->ID ?>"><i class="fa-solid fa-pen-to-square text-info"></i></a></span>
                    </td>
                </tr>
                          <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
          <input type="hidden" id="postActionId" value="<?php echo $post->ID ?>">
          <h5>Are you sure you want to delete this post?</h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button id="del_post" type="button" class="btn btn-primary">Delete</button>
      </div>
    </div>
  </div>
</div>

                <?php
            }
            ?>
            <?php
             $total_pages =  $data->max_num_pages;

if ($total_pages > 1){

    $current_page = max(1, get_query_var('paged'));

    echo paginate_links(array(
        'base' => get_pagenum_link(1) . '%_%',
        'format' => '/page/%#%',
        'current' => $current_page,
        'total' => $total_pages,
        'prev_text'    => __('« prev'),
        'next_text'    => __('next »'),
    ));
}   
?>
        </tbody>
    </table>
    </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $('#del_post').on('click',function(e){
            e.preventDefault();
            var ajaxurl= "<?php echo admin_url('admin-ajax.php') ?>";
            var id= $('#postActionId').val();
            $.ajax({
                url : ajaxurl,
                data: {
                    id: id,
                    action:"delete_post",
                    param:"postDel",
                },
                type:"POST",
                dataType:"JSON",
                success:function(res)
                {
                    if(res.status == 200)
                    {
                        $(".modal").hide();
                        $(".modal-backdrop").removeClass("show");
                        alert("Post Have been deleted successfully.");
                        location.reload();
                    }
                }
            });
        });
    });
</script>