<?php

namespace App\Http\Controllers\Admin;

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
use Yajra\DataTables\Facades\DataTables;

class WordController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('word_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Word::query()->select(sprintf('%s.*', (new Word)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'word_show';
                $editGate      = 'word_edit';
                $deleteGate    = 'word_delete';
                $crudRoutePart = 'words';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });
            $table->editColumn('arabic_word', function ($row) {
                return $row->arabic_word ? $row->arabic_word : "";
            });
            $table->editColumn('english_word', function ($row) {
                return $row->english_word ? $row->english_word : "";
            });
            $table->editColumn('bangla_word', function ($row) {
                return $row->bangla_word ? $row->bangla_word : "";
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.words.index');
    }

    public function create()
    {
        abort_if(Gate::denies('word_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.words.create');
    }

    public function store(StoreWordRequest $request)
    {
        $word = Word::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $word->id]);
        }

        return redirect()->route('admin.words.index');
    }

    public function edit(Word $word)
    {
        abort_if(Gate::denies('word_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.words.edit', compact('word'));
    }

    public function update(UpdateWordRequest $request, Word $word)
    {
        $word->update($request->all());

        return redirect()->route('admin.words.index');
    }

    public function show(Word $word)
    {
        abort_if(Gate::denies('word_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.words.show', compact('word'));
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
