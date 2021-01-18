@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.word.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.words.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="arabic_word">{{ trans('cruds.word.fields.arabic_word') }}</label>
                            <input class="form-control" type="text" name="arabic_word" id="arabic_word" value="{{ old('arabic_word', '') }}" required>
                            @if($errors->has('arabic_word'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('arabic_word') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.word.fields.arabic_word_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="english_word">{{ trans('cruds.word.fields.english_word') }}</label>
                            <input class="form-control" type="text" name="english_word" id="english_word" value="{{ old('english_word', '') }}">
                            @if($errors->has('english_word'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('english_word') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.word.fields.english_word_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="bangla_word">{{ trans('cruds.word.fields.bangla_word') }}</label>
                            <input class="form-control" type="text" name="bangla_word" id="bangla_word" value="{{ old('bangla_word', '') }}" required>
                            @if($errors->has('bangla_word'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('bangla_word') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.word.fields.bangla_word_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="description">{{ trans('cruds.word.fields.description') }}</label>
                            <textarea class="form-control ckeditor" name="description" id="description">{!! old('description') !!}</textarea>
                            @if($errors->has('description'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('description') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.word.fields.description_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file
            .then(function (file) {
              return new Promise(function(resolve, reject) {
                // Init request
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '/admin/words/ckmedia', true);
                xhr.setRequestHeader('x-csrf-token', window._token);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.responseType = 'json';

                // Init listeners
                var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                xhr.addEventListener('error', function() { reject(genericErrorText) });
                xhr.addEventListener('abort', function() { reject() });
                xhr.addEventListener('load', function() {
                  var response = xhr.response;

                  if (!response || xhr.status !== 201) {
                    return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                  }

                  $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                  resolve({ default: response.url });
                });

                if (xhr.upload) {
                  xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                      loader.uploadTotal = e.total;
                      loader.uploaded = e.loaded;
                    }
                  });
                }

                // Send request
                var data = new FormData();
                data.append('upload', file);
                data.append('crud_id', '{{ $word->id ?? 0 }}');
                xhr.send(data);
              });
            })
        }
      };
    }
  }

  var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: [SimpleUploadAdapter]
      }
    );
  }
});
</script>

@endsection