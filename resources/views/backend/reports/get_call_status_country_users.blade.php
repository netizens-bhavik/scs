<option value="">Select User</option>
@foreach ($company_users as $user)
    <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
@endforeach