@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.word.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.words.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.word.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $word->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.word.fields.arabic_word') }}
                                    </th>
                                    <td>
                                        {{ $word->arabic_word }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.word.fields.english_word') }}
                                    </th>
                                    <td>
                                        {{ $word->english_word }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.word.fields.bangla_word') }}
                                    </th>
                                    <td>
                                        {{ $word->bangla_word }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.word.fields.description') }}
                                    </th>
                                    <td>
                                        {!! $word->description !!}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.words.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection