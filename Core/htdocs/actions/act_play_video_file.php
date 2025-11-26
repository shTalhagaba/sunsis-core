<?php
class play_video_file implements IAction
{
	public function execute(PDO $link)
	{
		$video_file_id = isset($_REQUEST['video_file_id']) ? $_REQUEST['video_file_id'] : '';
		$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';

		$video_file = DAO::getObject($link, "SELECT * FROM video_files WHERE id = '{$video_file_id}'");

		$full_file_path = Repository::getRoot() . '/' . $username . '/videos/' . $video_file->file_name;

		$stream = new VideoStream($full_file_path);
		$stream->start();

	}
}