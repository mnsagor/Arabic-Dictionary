<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreWordRequest;
use App\Http\Requests\UpdateWordRequest;
use App\Http\Resources\Admin\WordResource;
use App\Models\Word;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WordApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('word_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WordResource(Word::all());
    }

    public function store(StoreWordRequest $request)
    {
        $word = Word::create($request->all());

        return (new WordResource($word))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Word $word)
    {
        abort_if(Gate::denies('word_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WordResource($word);
    }

    public function update(UpdateWordRequest $request, Word $word)
    {
        $word->update($request->all());

        return (new WordResource($word))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Word $word)
    {
        abort_if(Gate::denies('word_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $word->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
