@if($enabled)
    <div id="" style="display: none" aria-hidden="true">
        <input id="{{ $fieldName }}" name="{{ $fieldName }}" type="text" value="" autocomplete="nope" tabindex="-1">
        <input id="{{ $fieldTimeName }}" name="{{ $fieldTimeName }}" type="text" value="{{ microtime(true) }}"
            autocomplete="off" tabindex="-1">
    </div>
@endif
