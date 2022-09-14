<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFaqRequest;
use App\Http\Requests\UpdateFaqRequest;
use App\Models\Faq;

class FaqController extends Controller
{

    const TITLE = 'Faq';
    const FOLDER_VIEW = 'faq.';
    const URL = 'faq.';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $title = self::TITLE;
        $subTitle = 'List Data';

        $rows = Faq::orderBy('created_at')
            ->get()
            ->map(fn($row) => [
                $row->judul,
                // $row->kata_kunci,
                '<nobr>' . 
                    '<a href="'.route(self::URL .'edit', $row->id).'" class="btn btn-info btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</a> ' .
                    '<button type="button" class="btn btn-danger btn-sm btnDelete" value="'.$row->id.'" id="'.route(self::URL . 'destroy', $row->id).'"><i class="fas fa-trash"></i> Hapus</button>' . 
                '</nobr>',
            ]);

        $heads = [
            'Judul',
            // 'Kata Kunci',
            ['label' => 'Aksi', 'no-export' => true, 'width' => 5],
        ];
        
        $config = [
            'data' => $rows,
            'order' => [[1, 'asc']],
            'columns' => [null, ['orderable' => false]],
        ];

        return view(self::FOLDER_VIEW . 'index', compact('title', 'subTitle', 'heads', 'config'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $title = self::TITLE;
        $subTitle = 'Tambah Data';

        return view(self::FOLDER_VIEW . 'create', compact('title', 'subTitle',));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFaqRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFaqRequest $request)
    {
        //
        $input = $request->all();
        
        // $input['kata_kunci'] = implode(',', $input['kata_kunci']);
        
        unset($input['files']);

        Faq::create($input);

        return redirect()
            ->route(self::URL . 'create')
            ->with('success', 'Tambah data berhasil...');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function show(Faq $faq)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function edit(Faq $faq)
    {
        //
        $title = self::TITLE;
        $subTitle = 'Edit Data';

        $row = $faq;
        // $row->kata_kunci = explode(',', $row->kata_kunci);

        return view(self::FOLDER_VIEW . 'edit', compact('title', 'subTitle', 'row'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFaqRequest  $request
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFaqRequest $request, Faq $faq)
    {
        //
        $input = $request->all();
        
        // $input['kata_kunci'] = implode(',', $input['kata_kunci']);
        
        unset($input['files']);        

        $faq->update($input);

        return redirect()
            ->route(self::URL . 'index')
            ->with('success', 'Perbarui data berhasil...');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function destroy(Faq $faq)
    {
        //
        $faq->delete();

        return response()->json('Success');
    }

    public function helps()
    {
        $title = self::TITLE;
        $subTitle = '';

        $rows = Faq::orderBy('judul', 'asc')->get();

        return view(self::FOLDER_VIEW . 'helps', compact('title', 'subTitle', 'rows'));
    }
}
