<?php 
class himEvent {
    public function __construct() {
        add_action('init', [$this, 'createEventPost']);
        add_action( 'wp_enqueue_scripts',[$this, 'enqueScripts'] );
       	add_action( 'add_meta_boxes', [$this,'himwebRegisterCustomFields'] );
        add_action( 'save_post', [$this, 'saveEventfiledsData'], 10, 3 );
        add_filter( 'single_template',[ $this, 'overrideSingleTemplate'] );
        add_filter( 'archive_template',[ $this, 'overrideArchiveTemplate'] );
        add_action( 'template_redirect', [ $this, 'eventDownload'] );
    }

    /*create Event post type */
    public function createEventPost() {
	    register_post_type( 'events ',
	   	 // CPT Options
	        array(
	            'labels' => array(
	                'name' => __( 'Events' ),
	                'singular_name' => __( 'event' )
	            ),
	            'public' => true,
	            'has_archive' => true,
	            'rewrite' => array('slug' => 'events'),
	            'show_in_rest' => true,
	            'supports' => array( 'title', 'editor','thumbnail' ),
	        )
	    );
    }

    /*css include */
    public function enqueScripts() {
	   $plugin_url = plugin_dir_url( __FILE__ );
    	wp_enqueue_style( 'customCss',  $plugin_url . "css/style.css");
	}	

	/* Create custom fields*/
	public function himwebRegisterCustomFields( $post ){
	    add_meta_box( 'event_details', __( 'Add Event Details', 'event_details' ), [$this, 'himwebShowData'], 'events' );
	}

	/* Create custom fields callback function*/
	public function himwebShowData( $post ) {
	    wp_nonce_field( basename( __FILE__ ), 'EventMetabox_nonce' ); //used later for security
	    if(get_post_meta($post->ID , 'event_date', true))
	    	$event_date = get_post_meta($post->ID , 'event_date', true);
		if(get_post_meta($post->ID , 'event_url', true))
	    	$event_url = get_post_meta($post->ID , 'event_url', true);
	 	if(get_post_meta($post->ID , 'event_location', true))
	    	$event_location = get_post_meta($post->ID , 'event_location', true);
	    $html="<table class='form-table'><tbody>
		      <tr>
		        <th scope='row'><label>Event date</label></th>
		        <td>
		        	<input type='date' class='datepick hasDatepicker' name='event_date' id='event_date' value='".@$event_date."'>
		     	</td>
		      </tr>
		      <tr>
		        <th scope='row'><label>Event url</label></th>
		        <td>
		         	<input type='url' id='event_url' name='event_url' value='".@$event_url."'>
		        </td>
		      </tr>
		      <tr>
		        <th scope='row'><label>Event location</label></th>
		        <td>
		         	<input type='text' id='event_location' name='event_location' value='".@$event_location."'>
		        </td>
		      </tr>
	      </tbody></table>";
		   echo $html;
	}

	/* save meta fields */
	public function saveEventfiledsData( $post_id ) {
	    // check for nonce to top xss
	   if (!isset($_POST["EventMetabox_nonce"]) || !wp_verify_nonce($_POST["EventMetabox_nonce"], basename(__FILE__)))
     	 return $post_id;
	    if(!current_user_can("edit_post", $post_id))
	        return $post_id;
	    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
	        return $post_id;
	   	   
	    if(isset($_POST["event_date"]) && !empty($_POST["event_date"])){
	    	$date = $_POST["event_date"];
	    	$url = $_POST["event_url"];
	    	$location = $_POST["event_location"];
	    	update_post_meta($post_id , 'event_date', $date );
	        update_post_meta($post_id , 'event_url', $url);
	        update_post_meta($post_id , 'event_location',$location);
	    }
	}

	/* overrider single  post page*/	
	public function overrideSingleTemplate( $single_template ) {
	    global $post;
	    $file = dirname(__FILE__) .'/single-'. $post->post_type .'.php';
	    if( file_exists( $file ) ) $single_template = $file;
	    return $single_template;
	}

	/* overrider archive page*/
	public function overrideArchiveTemplate( $archive_template ) {
	    global $post;
	    $file = dirname(__FILE__) .'/archive-'. $post->post_type .'.php';
	    if( file_exists( $file ) ) $archive_template = $file;
	    return $archive_template;
	}	

	/* Download Calender */
	public function eventDownload() {
        if ( isset( $_GET['event_details'] ) ) {
        		$file = dirname(__FILE__) .'/event-download.php';
	    		if( file_exists( $file ) ) include $file;                
                header('Content-Type: text/calendar; charset=utf-8');
                header('Content-Disposition: attachment; filename=invite.ics');
                $eventfile = new eventFileDownload(array(
                        'location' => $_POST['location'],
                        'dtstart' => $_POST['start_date'],
                        'dtend' => $_POST['end_date'],
                        'url' => $_POST['url'],
                        'summary' => $_POST['summary'],
                ));
                echo $eventfile->to_string();
                exit();
        }
	}
}
 
$himEvent = new himEvent();
?>


