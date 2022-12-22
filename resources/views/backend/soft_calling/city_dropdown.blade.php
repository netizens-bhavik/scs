<option value="">Select City</option>
@foreach ($cities as $item)
    @if ($selected_city_id)
        @if ($item['id'] == $selected_city_id)
            {
            <option value="{{ $item['id'] }}" selected>{{ $item['city_name'] }}</option>
            }
        @else
            <option value="{{ $item['id'] }}">{{ $item['city_name'] }}</option>
        @endif
    @else
        <option value="{{ $item['id'] }}">{{ $item['city_name'] }}</option>
    @endif
@endforeach
