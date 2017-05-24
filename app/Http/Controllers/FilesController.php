<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Image;
use Illuminate\Support\Facades\Response;



class FilesController extends Controller
{

	public function preview(File $file)
	{
	    $path = storage_path('app/') . $file->path . $file->name_thumbnail;
	    $handler = new \Symfony\Component\HttpFoundation\File\File($path);

	    $lifetime = 31556926; // One year in seconds

	    /**
	    * Prepare some header variables
	    */
	    $file_time = $handler->getMTime(); // Get the last modified time for the file (Unix timestamp)

	    $header_content_type = $handler->getMimeType();
	    $header_content_length = $handler->getSize();
	    $header_etag = md5($file_time . $path);
	    $header_last_modified = gmdate('r', $file_time);
	    $header_expires = gmdate('r', $file_time + $lifetime);

	    $headers = array(
	        'Content-Disposition' => 'inline; filename="' . $file->name_thumbnail . '"',
	        'Last-Modified' => $header_last_modified,
	        'Cache-Control' => 'must-revalidate',
	        'Expires' => $header_expires,
	        'Pragma' => 'public',
	        'Etag' => $header_etag
	    );

	    /**
	    * Is the resource cached?
	    */
	    $h1 = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $_SERVER['HTTP_IF_MODIFIED_SINCE'] == $header_last_modified;
	    $h2 = isset($_SERVER['HTTP_IF_NONE_MATCH']) && str_replace('"', '', stripslashes($_SERVER['HTTP_IF_NONE_MATCH'])) == $header_etag;

	    if ($h1 || $h2) {
	        return Response::make('', 304, $headers); // File (image) is cached by the browser, so we don't have to send it again
	    }

	    $headers = array_merge($headers, array(
	        'Content-Type' => $header_content_type,
	        'Content-Length' => $header_content_length
	    ));

	    return Response::make(file_get_contents($path), 200, $headers);
	}

}
