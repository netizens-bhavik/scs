<option value="">Select User</option>
@foreach($users as $user)
    <option value="{{ $user['id'] }}">{{ucwords($user['role_name'])}}-{{ucwords($user['country_name'])}}-{{ ucwords($user['name']) }}</option>
@endforeach
