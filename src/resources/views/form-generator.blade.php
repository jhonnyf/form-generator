<form action="{{ $form['action'] }}" method="{{ $form['method'] }}" autocomplete="{{ $form['autocomplete'] }}">
    @csrf

    {{-- <pre>
        @php
            print_r($elements);
        @endphp
    </pre> --}}

    <x-console-service-response-form />

    @foreach ($elements as $element)
        @if ($element['elementType'] == 'input')
            <div class="mb-3">
                <label for="{{ $element['name'] }}">{{ $element['label'] }}</label>
                <input type="{{ $element['type'] }}" name="{{ $element['name'] }}" id="{{ $element['name'] }}" class="form-control" {{ $element['required'] ? 'required' : '' }} value="{{ $element['value'] }}">
            </div>
        @endif

        @if ($element['elementType'] == 'select')
            <div class="mb-3">
                <label for="{{ $element['name'] }}">{{ $element['label'] }}</label>
                <select name="{{ $element['name'] }}" id="{{ $element['name'] }}" class="form-control custom-select" {{ $element['required'] ? 'required' : '' }}>
                    <option value="">Selecione uma opção</option>
                    @foreach ($element['options'] as $keyOption => $option)
                        <option value="{{ $keyOption }}" {{ $keyOption == $element['value'] ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        @if ($element['elementType'] == 'button')
            <div class="text-right">
                <button type="{{ $element['type'] }}" class="btn btn-primary">{{ $element['label'] }}</button>
            </div>
        @endif

    @endforeach
</form>