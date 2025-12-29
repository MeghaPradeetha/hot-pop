<?php


namespace App\Http\Controllers\Manage;


class ManageFilesController extends \App\Http\Controllers\Controller
{

	// This is the default upload disk. You may change this to another disk.
	// See `config/filesystems.php` for config
	protected $uploadDisk = 'local';

	/**
	 *
	 * Show a file on browser
	 *
	 * @param $uuid
	 *
	 * @return mixed
	 * @throws \Hotpop\FileControl\Exceptions\FailedToResolvePathException
	 */
	public function publicView($uuid)
	{
		$file = $this->dataRepo->findByUuid($uuid);

		if (!$file) abort(404);

		// check if the user is allowed to see this file here
		if (!$file->allow_public_access) {
			$user = auth()->user();
			if (!$user) {
				// handle api requests
				if (request()->header('x-api-key')) {
					$user = \Illuminate\Support\Facades\Auth::user();
				}
			}
			if (!$user) abort(401);
		}

		// TODO: add any other file access permission checks here

		$filePath = $this->resolvePathFromFile($file);

		return response()->file($filePath);
	}

}
