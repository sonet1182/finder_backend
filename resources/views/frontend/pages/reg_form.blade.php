@extends('frontend.layouts.master')

@section('content')

    <h4>Registration Form</h4>

    <div class="card card-default">
        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <h3 class="text-center">{{ $form->title }}</h3>
            <p class="card-text">{{ $form->description }}</p>
            <hr>

            <form action="{{ route('user.forms.submit', $form->id) }}" method="POST" enctype="multipart/form-data" class="row">
                @csrf

                @foreach ($form->fields as $field)
                    <div class="mb-3 col-md-6">
                        <label class="form-label">{{ $field->field_label }}:</label>

                        @if ($field->field_type == 'text')
                            <input type="text" name="fields[{{ $field->id }}]" class="form-control">
                        @elseif($field->field_type == 'textarea')
                            <textarea name="fields[{{ $field->id }}]" class="form-control"></textarea>
                        @elseif($field->field_type == 'select')
                            <select name="fields[{{ $field->id }}]" class="form-control">
                                @foreach (explode(',', $field->field_options) as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                        @elseif($field->field_type == 'radio')
                            @foreach (explode(',', $field->field_options) as $option)
                                <div class="form-check">
                                    <input type="radio" name="fields[{{ $field->id }}]" value="{{ $option }}"
                                        class="form-check-input">
                                    <label class="form-check-label">{{ $option }}</label>
                                </div>
                            @endforeach
                        @elseif($field->field_type == 'checkbox')
                            @foreach (explode(',', $field->field_options) as $option)
                                <div class="form-check">
                                    <input type="checkbox" name="fields[{{ $field->id }}][]"
                                        value="{{ $option }}" class="form-check-input">
                                    <label class="form-check-label">{{ $option }}</label>
                                </div>
                            @endforeach
                        @elseif($field->field_type == 'file')
                            <input type="file" name="fields[{{ $field->id }}]" class="form-control">
                        @endif
                    </div>
                @endforeach

                <div class="mb-3 col-md-12">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>


            </form>


        </div>
    </div>




@endsection