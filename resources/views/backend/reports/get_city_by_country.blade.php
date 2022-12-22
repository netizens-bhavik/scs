<option value="">Select City</option>
@foreach ($data as $city)
    <option value="{{ $city['id'] }}">{{ $city['city_name'] }}
    </option>
@endforeach
