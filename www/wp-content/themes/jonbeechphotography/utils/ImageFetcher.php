<?php
class ImageFetcher {
	public $result;


	public function __construct(){
		$result 			= new stdClass();
		$result->passed		= false;


		$this->result 			= new stdClass();
		$this->result->passed	= false;
	}

	function route_getImages() {
		global $wpdb;
		$page			= $this->hasget('api_page') ? $_GET['api_page'] : 1;
		$total_per_page	= $this->hasget('api_total_per_page') ? $_GET['api_total_per_page'] : 20;
		$term_id		= $this->hasget('api_term_id') ? $_GET['api_term_id'] : null;

		$limit			= $total_per_page;
		$offset			= $total_per_page * ($page - 1);

		// Fetch main uploads

		$mainQuery		= "
			FROM $wpdb->posts
			LEFT JOIN $wpdb->term_relationships ON($wpdb->posts.ID = $wpdb->term_relationships.object_id)
			LEFT JOIN $wpdb->term_taxonomy ON($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
			LEFT JOIN $wpdb->terms ON($wpdb->term_taxonomy.term_id = $wpdb->terms.term_id)
			WHERE $wpdb->posts.post_type = 'attachment'
		";

		// Add the category where attrs if needed
		if ($term_id) {
			$mainQuery	.= "
					AND $wpdb->term_taxonomy.taxonomy = 'media_category'
					AND $wpdb->terms.term_id IN ($term_id)
				";
		}

		// Add the limit and offset for the data fetch
		$dataQuery		= $wpdb->prepare(
			"SELECT $wpdb->posts.*" .
			$mainQuery .
			"
				ORDER BY $wpdb->posts.post_date DESC
				LIMIT %d OFFSET %d
			",
			$limit,
			$offset);

		$images 		= $wpdb->get_results($dataQuery, OBJECT);

		$totalResult	= $wpdb->get_results("SELECT count(*)" . $mainQuery, ARRAY_A);
		$total			= $totalResult[0]['count(*)'];
		$total_pages	= max(ceil($total / $total_per_page), 1);


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
			$metaResults 	= $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE $wpdb->postmeta.post_id IN ($idList)", OBJECT);
			$dir			= wp_upload_dir();
			$prefix			= $dir['baseurl'] . '/';
			foreach($metaResults as $meta_entry){
				$related	= $imageHash[$meta_entry->post_id];
				switch ($meta_entry->meta_key){
					case '_wp_attachment_metadata':
						$related->meta 			= unserialize($meta_entry->meta_value);
						$related->meta['file']	= $prefix . $related->meta['file'];
						$originalInfo			= pathinfo($related->meta['file']);
						$thumbPrefix			= $originalInfo['dirname'] . '/';

						$related->meta['sizes']['thumbnail']['file'] = $thumbPrefix . $related->meta['sizes']['thumbnail']['file'];
						$related->meta['sizes']['medium']['file'] = $thumbPrefix . $related->meta['sizes']['medium']['file'];
						break;
					case '_wp_attached_file':
					case '_edit_lock':
						break;
					default:
						$poop = "";
				}
			}
		}
		$this->result->passed = true;
		$this->result->data	= array(
			'api_page'				=> (int) $page,
			'api_total_per_page'	=> (int) $total_per_page,
			'api_total_pages'		=> (int) $total_pages,
			'api_rows'				=> $newImages
		);
	}

	function route_getPageNumber() {
		global $wpdb;
		$total_per_page	= $this->hasget('api_total_per_page') ? $_GET['api_total_per_page'] : 20;
		$term_id		= $this->hasget('api_term_id') ? $_GET['api_term_id'] : null;
		$api_id			= $this->hasget('api_image_id') ? $_GET['api_image_id'] : null;
		if (is_null($api_id)){
			$this->result->message = 'Missing required parameter: "api_image_id"';
		}
		else{
			// Fetch main uploads
			$mainQuery		= "
				SELECT $wpdb->posts.*
				FROM $wpdb->posts
				LEFT JOIN $wpdb->term_relationships ON($wpdb->posts.ID = $wpdb->term_relationships.object_id)
				LEFT JOIN $wpdb->term_taxonomy ON($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
				LEFT JOIN $wpdb->terms ON($wpdb->term_taxonomy.term_id = $wpdb->terms.term_id)
				WHERE $wpdb->posts.post_type = 'attachment'
			";

			// Add the category where attrs if needed
			if ($term_id) {
				$mainQuery	.= "
					AND $wpdb->term_taxonomy.taxonomy = 'media_category'
					AND $wpdb->terms.term_id IN ($term_id)
				";
			}

			$mainQuery 		.= "ORDER BY $wpdb->posts.post_date DESC";

			// Add the limit and offset for the data fetch
			$images 		= $wpdb->get_results($mainQuery, OBJECT);
			$total			= count($images);
			$index			= null;
			for ($i = 0; $i < $total; $i++) {
				$value		= $images[$i];
				if ($value->ID == $api_id){
					$index	= $i;
					break;
				}
			}
			$pageNumber		= ceil(($index + 1) / $total_per_page);

			$this->result->passed = true;
			$this->result->data	= array(
				'api_page' => (int) $pageNumber
			);
		}
	}

	function hasget($name){
		return isset($_GET[$name]) && $_GET[$name] != "" && $_GET[$name] != null && $_GET[$name] != "null";
	}

}
?>