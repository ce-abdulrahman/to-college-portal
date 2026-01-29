<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
trait FileUploadTrait
{
	function UploadImage(Request $request, $inputName, $oldImagePath = NULL, $path = "/uploads")
	{
		if ($request->hasFile($inputName)) {
            // Create the directory if it doesn't exist
			$image = $request->{$inputName};
			$ext = $image->getClientOriginalExtension();
            $imageName = 'media_'.uniqid().'.'.$ext;

			$image->move(public_path($path), $imageName);

            if($oldImagePath && File::exists(public_path($oldImagePath))) {
                // Delete old image if it exists
                File::delete(public_path($oldImagePath));
            }


            return $path . '/' . $imageName;
		}

        return NULL;

	}

    /**
     * Delete an image from the public path.
     *
     * @param string $imagePath
     */
    function DeleteImage(?string $path)
    {
        if ($path && File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }
}
