<!-- country codes (ISO 3166) and Dial codes. -->
@foreach($phone_code as $code)
<option value="{{ $code->id }}" @if (Input::old('mobile_code') == $code->id) echo ' selected="selected"'; @elseif(Input::old('landline_code') == $code->id) echo ' selected="selected"'; @endif>{{ $code->code }} {{ $code->country }}</option>
@endforeach
