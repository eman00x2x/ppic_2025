<?php

namespace Main\Services;

use Main\Interfaces\IService as IService;
use Main\Model\VideoModel as Video;

class VideoService implements IService
{

    public Video $video;

    function __construct() {
        $this->video = new Video();
    }

    function list(array $request, string $target_url) {

		$this->video
			// Apply filters based on the request parameters.
			->filter(request: $request)
			// Arrange the video based on the request parameters default to created_at in descending order.
			->sort(request: $request, sorting: ["created_at" => "DESC"])
			// Retrieve the paginated list of video.
			->getList(
				// Determine the page number from the request, default to 1 if not provided.
				page: ($request['page'] ?? 1),
				// Determine the limit of video per page from the request, default to 20 if not provided.
				limit: ($request['rows'] ?? 20),
				// The target URL for pagination links.
				url: $target_url
			);

		return $this->video;

    }

    function get(int $id) {

        $this->video->getId($id);
		return $this->video;

    }

    function create(array $data) {

		$result = $this->video->saveNew(data: $data);

        if($result['status'] == 2) {
			return [
				"status" => 2,
				"type" => "error",
				"message" => $result['message']
			];
		}else {

			return [
				"status" => 1,
				"type" => "success",
				"message" => "Successfully save!"
			];

		}

    }

    function update(int $id, array $data) {

        $response = $this->video->getId(id: $id);

        if($response) {

            $result = $this->video->where([
				"video_id" => $id
			])->save(data: $data);

			return [
				"status" => $result['status'],
				"type" => "success",
				"message" => $result['message']
			];

        }

        return [
			"status" => 2,
			"type" => "error",
            "message" => "Video not found!"
		];

    }

    function destroy($id) {

        $data = $this->video->getId(id: $id);

        if($data) {

			$this->video->where([
				"video_id" => $id
			])->delete();

            return [
                "status" => 1,
                "type" => "success",
                "message" => "Video deleted!"
            ];

        }

        return [
			"status" => 2,
			"type" => "error",
            "message" => "Video Group not found!"
		];

    }

}