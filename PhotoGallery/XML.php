<?php

namespace PhotoGallery\Lib\XML;

function xml_findAttribute($object, $attribute) {

	if (! is_object($object) ) {
		return 0;
	}
	if (! $object->attributes() ) {
		return 0;
	}
	foreach($object->attributes() as $a => $b) {
		if ($a == $attribute) {
			$return = $b;
		}
	}
	if($return) {
		return $return;
	}
}

?>