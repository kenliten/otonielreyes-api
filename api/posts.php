<?php
require '../config.php';

header('Content-Type: application/json');

$slug = $_GET['slug'];
$id = $_GET['id'];
$token = $_GET['api_key'];
$json = file_get_contents('php://input');
$body = json_decode($json);
$collection = $db->otonielreyes->posts;

switch($_SERVER['REQUEST_METHOD'])
{
case 'GET':
	{
		if ($slug)
		{
			$post = $collection->findOne([ 'slug' => $slug ]);

			if ($post)
			{
				$result = array(
					'success' => true,
					'data' => $post
				);
			}
			else
			{
				$result = array(
					'success' => false,
					'data' => null
				);
			}
		}
		else
		{
			$posts = $collection->find();

			$result = array(
				'success' => true,
				'data' => $posts->toArray()
			);
		}
		break;
	}
case 'POST':
	{
		if ($token && in_array($token, $config['api_tokens']))
		{
			$insertOneResult = $collection->insertOne($body);
			$result = array(
				'success' => true,
				'data' => $insertOneResult->getInsertedId()
			);
		}
		else
		{
			$result = array(
				'success' => false,
				'data' => 'Missing or invalid api key'
			);
		}
		break;
	}
case 'PUT':
	{
		if ($token && in_array($token, $config['api_tokens']))
		{
			if ($id)
			{
				$updateResult = $collection->updateOne([ '_id' => $id ], $body);
				$result = array(
					'success' => $updateResult->getDeletedCount() == 1
				);
			}
			else
			{
				$result = array(
					'success' => false,
					'data' => 'No id provided'
				);
			}
		}
		else
		{
			$result = array(
				'success' => false,
				'data' => 'Missing or invalid api key'
			);
		}
		break;
	}
case 'DELETE':
	{
		if ($token && in_array($token, $config['api_tokens']))
		{
			if ($id)
			{
				$deleteResult = $collection->deleteOne([ '_id' => $id ]);
				$result = array(
					'success' => $deleteResult->getDeletedCount() == 1
				);
			}
			else
			{
				$result = array(
					'success' => false,
					'data' => 'No id provided'
				);
			}
		}
		else
		{
			$result = array(
				'success' => false,
				'data' => 'Missing or invalid api key'
			);
		}
		break;
	}
}

echo json_encode($result);