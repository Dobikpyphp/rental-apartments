<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
	public function index()
	{
		$post = Post::find(1);
		dd($post);
	}

	public function index1()
	{
		$posts = Post::all();
		foreach ($posts as $post) {
			dump($post->title);
		}
		dd('end');
	}

	public function index3()
	{
		$post = Post::where('is_published', 1)->first();
		dump($post->title);
		dd('end');
	}

	public function create()
	{
		$postsArr = [
			[
				'title' => 'title of post from vscode',
				'content' => 'some interesting content',
				'image' => 'imageblabla.jpg',
				'likes' => 20,
				'is_published' => 1,
			],
			[
				'title' => 'another title of post from vscode',
				'content' => 'another some interesting content',
				'image' => 'another imageblabla.jpg',
				'likes' => 50,
				'is_published' => 1,
			],
		];

		// Post::create([
		// 	'title' => 'title of post from vscode',
		// 	'content' => 'some interesting content',
		// 	'image' => 'imageblabla.jpg',
		// 	'likes' => 20,
		// 	'is_published' => 1
		// ]);

		foreach ($postsArr as $item) {
			// dd($item);
			Post::create($item);
		}

		dd('created');
	}

	public function update()
	{
		$post = Post::find(5);
		// dd($post->title);

		$post->update([
			'title' => 'update',
			'content' => 'update',
			'image' => 'update',
			'likes' => 500,
			'is_published' => 0,
		]);

		dd('updated');
	}
}
