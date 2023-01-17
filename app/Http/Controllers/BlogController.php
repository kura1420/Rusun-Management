<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    //
    const TITLE = 'Blog';
    const FOLDER_VIEW = 'blog.';
    const URL = 'blog';

    public function index()
    {
        $title = self::TITLE;

        $rows = \App\Models\Program::where([
            ['status', 2],
            ['publish', 1]
        ])
        ->latest('publish_at')
        ->paginate(10)
        ->through(function ($program) {
            $program->keterangan = Str::limit($program->keterangan, 300);
            $program->publish_at = Carbon::parse($program->publish_at)->diffForHumans();

            return $program;
        });

        return view(self::FOLDER_VIEW . 'index', compact('title', 'rows'));
    }

    public function programShow($slug)
    {
        $row = \App\Models\Program::where('slug', $slug)->first();

        $title = self::TITLE . ' - ' . $row->nama;

        return view(self::FOLDER_VIEW . 'show', compact('title', 'row'));
    }
}
