<?php
$result 		= new stdClass();
$result->passed	= false;
global $wpdb;

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


if (isset($_GET['call'])){
	switch($_GET['call']){
		case 'api_images':
			$page			= hasget('api_page') ? $_GET['api_page'] : 1;
			$total_per_page	= hasget('api_total_per_page') ? $_GET['api_total_per_page'] : 20;

			$limit			= $total_per_page;
			$offset			= $total_per_page * ($page - 1);

			// Fetch main uploads
			$query	= $wpdb->prepare('SELECT * FROM wp_posts WHERE post_type="attachment" LIMIT %d OFFSET %d', $limit, $offset);
			$images = $wpdb->get_results($query, OBJECT);

			$totalResult	= $wpdb->get_results('SELECT count(*) FROM wp_posts WHERE post_type="attachment"', ARRAY_A);
			$total			= $totalResult[0]['count(*)'];
			$total_pages	= ceil($total / $total_per_page);


			// Find all the post ids to fetch metadata
			// Additionally hash the results
			$ids		= array();
			$imageHash	= array();
			$newImages	= array();
			foreach($images as $value){
				$newImg					= new stdClass();
				$newImg->id				= $value->ID;
				$ids[] 					= $value->ID;
				$imageHash[$value->ID]	= $newImg;
				$newImages[]			= $newImg;
			}

			// Fetch all meta data
			if (count($newImages) > 0){
				$idList			= implode(', ', $ids);
				$metaResults 	= $wpdb->get_results("SELECT * FROM wp_postmeta WHERE post_id IN ($idList)", OBJECT);
				$dir			= wp_upload_dir();
				$prefix			= $dir['baseurl'] . '/';
				foreach($metaResults as $meta_entry){
					$related	= $imageHash[$meta_entry->post_id];
					switch ($meta_entry->meta_key){
						case '_wp_attached_file':
							//$related->main = $meta_entry->meta_value;
							break;
						case '_wp_attachment_metadata':
							$related->meta = unserialize($meta_entry->meta_value);
							$related->meta['file'] = $prefix . $related->meta['file'];
							$related->meta['sizes']['thumbnail']['file'] = $prefix . $related->meta['sizes']['thumbnail']['file'];
							$related->meta['sizes']['medium']['file'] = $prefix . $related->meta['sizes']['medium']['file'];
							break;
					}
				}
			}
			$result->passed = true;
			$result->data	= array(
				'api_page'				=> (int) $page,
				'api_total_per_page'	=> (int) $total_per_page,
				'api_total_pages'		=> (int) $total_pages,
				'api_rows'				=> $newImages
			);
			break;
	}
}
else{
	$result->message = "POST: 'call' parameter not set";
}

function hasget($name){
	return isset($_GET[$name]) && $_GET[$name] != "" && $_GET[$name] != null && $_GET[$name] != "null";
}


header("Content-Type:application/json");
echo json_encode($result);
