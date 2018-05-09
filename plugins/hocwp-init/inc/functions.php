<?php
function hocwp_init_hide_post_types() {
	$hide_types = array(
		'project',
		'home',
		'guide',
		'faq',
		'showcase',
		'reference',
		'example',
		'aff',
		'license'
	);

	return $hide_types;
}