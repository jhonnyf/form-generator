<form action="{{ $form['action'] }}" method="{{ $form['method'] }}" class="{{ implode(' ', $form['class']) }}" autocomplete="{{ $form['autocomplete'] }}">
    @csrf

    <x-console-service-response-form />

    @foreach ($elements as $element)
        @if ($element['elementType'] == 'input')
            @if ($element['type'] == 'hidden')
                <input type="{{ $element['type'] }}" name="{{ $element['name'] }}" value="{{ $element['value'] }}">
            @else
                <div class="mb-3">
                    <label for="{{ $element['name'] }}">{{ $element['label'] }}</label>
                    <input type="{{ $element['type'] }}" name="{{ $element['name'] }}" id="{{ $element['name'] }}" class="form-control" {{ $element['required'] ? 'required' : '' }} {{ $element['readonly'] ? 'readonly' : '' }} value="{{ $element['value'] }}" maxlength="{{ $element['maxlength'] }}">
                </div>
            @endif                    
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

        @if ($element['elementType'] == 'textarea')
            <div class="mb-3">
                <label for="{{ $element['name'] }}">{{ $element['label'] }}</label>
                <textarea name="{{ $element['name'] }}" id="{{ $element['name'] }}" class="form-control">{{ $element['value'] }}</textarea>
            </div>
        @endif

        @if ($element['elementType'] == 'button')
            <div class="text-right">
                <button type="{{ $element['type'] }}" class="btn btn-primary">{{ $element['label'] }}</button>
            </div>
        @endif

    @endforeach
</form>