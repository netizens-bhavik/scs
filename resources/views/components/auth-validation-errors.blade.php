@props(['errors'])

@if ($errors->any())
    <div {{ $attributes }}>
        {{-- <div class="font-medium text-red-600">
            {{ __('Whoops! Something went wrong.') }}
        </div> --}}

        <ul class="p-0" style="list-style-type: none;">
            @foreach ($errors->all() as $error)
                <li>
                    <div class="alert alert-danger" role="alert">
                        {{ $error }}
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
@endif
