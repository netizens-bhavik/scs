<option value="">Select Company</option>
@foreach ($data as $company)
    <option value="{{ $company->id }}">{{ $company->company_name }}
    </option>
@endforeach
