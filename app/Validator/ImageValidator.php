<?php
namespace App\Validator;

use Intervention\Image\ImageManager;
use Intervention\Image\ImageManagerStatic as Image;

/*
$manager = new ImageManager();
$resized = $manager->make($path)->resize(192, 108);
deci tre sa stim calea
$path = $request->file('avatar')->store('avatars');
noi tre sa verificam /tmp;
*/
class ImageValidator \Illuminate\Validation\Validator {
	/*
	pune min_size ca parametru suplimentar;ala e de fapt in FileValidator
	$value=path;
	*/
	public function validateImage($attribute, $value, $parameters) {
		$imagePath = $value;
		try {
			$image = Image::make($value);
			return true;	
		} catch (Exception $e) {
			return false;
		}
	}
}
?>