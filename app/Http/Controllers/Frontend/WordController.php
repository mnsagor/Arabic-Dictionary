<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyWordRequest;
use App\Http\Requests\StoreWordRequest;
use App\Http\Requests\UpdateWordRequest;
use App\Models\Word;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class WordController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('word_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $words = Word::all();

        return view('frontend.words.index', compact('words'));
    }

    public function create()
    {
        abort_if(Gate::denies('word_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.words.create');
    }

    public function store(StoreWordRequest $request)
    {
        $word = Word::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $word->id]);
        }

        return redirect()->route('frontend.words.index');
    }

    public function edit(Word $word)
    {
        abort_if(Gate::denies('word_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.words.edit', compact('word'));
    }

    public function update(UpdateWordRequest $request, Word $word)
    {
        $word->update($request->all());

        return redirect()->route('frontend.words.index');
    }

    public function show(Word $word)
    {
        abort_if(Gate::denies('word_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.words.show', compact('word'));
    }

    public function destroy(Word $word)
    {
        abort_if(Gate::denies('word_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $word->delete();

        return back();
    }

    public function massDestroy(MassDestroyWordRequest $request)
    {
        Word::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('word_create') && Gate::denies('word_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Word();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
