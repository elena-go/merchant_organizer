<select name="country" class="country">
    <option>Choose Country:</option>
    @foreach($countries as $country)
    <option value="{{ $country->id }}" @if (Input::old('country') == $country->id) echo ' selected="selected"'; @endif>{{ $country->country }}</option>
    @endforeach
</select>