<?php

require_once(ABSPATH ."wp-admin/includes/class-wp-list-table.php");

class ListTable extends WP_List_Table{

    public function prepare_items()
    {
        $orderBy=isset($_GET['orderby'])?trim($_GET['orderby']):"";
        $order=isset($_GET['order'])?trim($_GET['order']):"";
        $search_term=isset($_POST['s'])? trim($_POST['s']):"";
        $datas=$this->list_table_data($orderBy,$order,$search_term);
        $per_page=3;
        $current_page=$this->get_pagenum();
        $total_items=count($datas);
        $this->set_pagination_args(array(
            'total_items'=>$total_items,
            'per_page'=>$per_page
        ));
        $this->items=array_slice($datas,(($current_page-1)*$per_page),$per_page);
        $columns=$this->get_columns();
        $this->process_bulk_action();
        $hidden=$this->get_hidden_columns();
        $sortable=$this->get_sortable_columns();
        $this->_column_headers=array($columns,$hidden,$sortable);
    }
    
    public function list_table_data($orderBy='',$order='',$search_term='')
    {
        global $wpdb;
        if(!empty($search_term))
        {
            $data=$wpdb->get_results(
                "SELECT * FROM `wp_assoc_sports` WHERE name LIKE '%$search_term%'"
            );
        }
        else
        {
            if($orderBy=='id' && $order=='desc')
            {
                $data=$wpdb->get_results(
                    "SELECT * FROM `wp_assoc_sports` ORDER BY id DESC"
                );
            }
            else
            {
                $data=$wpdb->get_results(
                    "SELECT * FROM `wp_assoc_sports` WHERE status='publish'"
                );
            }
        }
        $post_array=array();
        if(count($data)>0)
        {
            foreach($data as $index=>$post)
            {
                $post_array[]=array(
                    "id"=>$post->id,
                    "image"=>$post->image,
                    "name"=>$post->name,
                   "description"=>$post->description,
                    "category"=>$post->category,
                );
            }
        }
        return $post_array;
    }
    public function get_hidden_columns()
    {
        return array("");
    }
    public function get_sortable_columns()
    {
        return array(
            "id"=>array("id",true)
        );
    }
    // public function get_views() {
	// 	return array(
    //         "all"=>"all"
    //     );
	// }
	// public function views() {
	// 	$screen = get_current_screen();

	// 	$views = $this->get_views();
	// 	$views = apply_filters( 'views_' . $screen->id, $views );

	// 	if ( empty( $views ) )
	// 		return;

	// 	echo "<ul class='subsubsub'>\n";
	// 	foreach ( $views as $class => $view ) {
	// 		$views[ $class ] = "\t<li class='$class'>$view";
	// 	}
	// 	echo implode( " |</li>\n", $views ) . "</li>\n";
	// 	echo "</ul>";
	// }
    public function get_bulk_actions()
    {
        $action=array(
            "edit"=>"Edit",
            "trash"=>"Move To Trash"
        );
        return $action;
    }
    public function process_bulk_action()
    {
        global $wpdb;
        if($this->current_action() === 'trash')
        {
            foreach($_POST['post'] as $id)
            {
                $wpdb->update(
                    "wp_assoc_sports",
                    array("status"=>"trash"),
                    array("id"=>$id)
                );
            }
        }
    }
    public function get_columns(){
        $columns=array(
            "cb"=>"<input type='checkbox'/>",
            "id"=>"ID",
            "image"=>"Image",
            "name"=>"Name",
            "description"=>"Description",
            "category"=>"Category",
        );
        return $columns;
    }
    public function column_cb($item)
    {
        return sprintf('<input type="checkbox" name="post[]" value="%s"/>',$item['id']);
    }
    
    public function column_default($item,$column_name)
    {
        switch($column_name)
        {
            case 'id':
                return "<span>$item[$column_name]</span>";
                case 'image':
                    return "<img src='$item[$column_name]' width='100px'>";
                    case 'name':
                    case 'description':
                        return wp_trim_words($item[$column_name],3);
                        case 'category':
                        return $item[$column_name];
                        default:
                        return "No Value";
        }
    }
    public function column_name($item)
    {
        $action=array(
            "edit"=>"<a href='edit.php?post_type=".$_GET['post_type']."&page=".$_GET['page']."&action=edit-action&id=".$item['id']."'>Edit</a>",
            "delete"=>"<a href='edit.php?post_type=".$_GET['post_type']."&page=".$_GET['page']."&action=trash-action&id=".$item['id']."'>Trash</a>"
        );
        return sprintf('%1$s %2$s',$item['name'],$this->row_actions($action));
    }
}
function show_data_list_table()
{
    $table= new ListTable();
    $table->prepare_items();
    echo "<h3>This is Wp List Table</h3>";
    echo "<form method='post' name='frm_search_post' action='".$_SERVER['PHP_SELF']."?post_type=sports&page=associte-sports'>";
    $table->search_box("Search Post","search_post_id");
    $table->display();
    echo"</form>";
}
show_data_list_table();
?>
