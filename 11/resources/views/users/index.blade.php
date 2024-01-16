@extends('layouts.bootstrap')

@section('content')
<h1>Users</h1>

@if ($canCreateUsers)
    <p>
		<a href="{{ route('users.create') }}" class="btn btn-primary">Add New User</a>
	</p>
@endif

@if ($error !== '')
	<div class="alert alert-danger" role="alert">
		{{ $error }}
	</div>
@endif

@if ($message !== '')
	<div class="alert alert-success" role="alert">
		{{ $message }}
	</div>
@endif

<table class="table table-striped" id="table-users">
	<thead>
		<tr>
			<th>User ID</th>
			<th>Username</th>
			<th>Email</th>
			<th>Role</th>
			<th>Active</th>
			<th>Send Message</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody></tbody>
</table>

<script>
	fetch('{{ env("APP_URL") }}/api/users')
		.then(function (response) {
			return response.json();
		}).then(
			function (response) {
				response.data.forEach(user => {
					let url = '{{ route("users.edit", ["user" => ":user"]) }}';
					url = url.replace(':user', user.id);
					
					let sendMessageUrl = '{{ route("messages.create", ["user" => ":user"]) }}';
					sendMessageUrl = sendMessageUrl.replace(':user', user.id);

					let userHtml = 
						'<tr>' +
							'<td>' + user.id + '</td>' +
							'<td>' + user.username + '</td>' +	
							'<td>' + user.email + '</td>' +	
							'<td>' + user.role + '</td>' +	
							'<td>' + (user.isActive ? 'Yes' : 'No') + '</td>' +
							'<td><a href="' + sendMessageUrl + '" class="btn btn-info">Send Message</a></td>' +
							'<td><div class="btn-group" role="group">' + 
								(
									user.canBeUpdated 
										? '<a href="' + url + '" class="btn btn-warning">Edit</a>' 
										: ''
								) + ' ' +
								(
									user.canBeDeleted
										? '<form class="btn btn-danger p-0" method="POST" action="/users/' + user.id + '">' +
											'{{ csrf_field() }}' +
											'{{ method_field("DELETE") }}' +
											'' +
												'<input type="submit" class="btn btn-danger delete-button" value="Delete">' +
											'' +
										'</form>'
										: ''
								) +
							'</div></td>' +
						'</tr>';

					document.querySelector('#table-users tbody').insertAdjacentHTML("beforeend", userHtml);
                    
                    const deleteButtons = document.getElementsByClassName('delete-button');
                    for (const deleteButton of deleteButtons) {
                        deleteButton.onclick = function () {
                            return confirm('Are you sure you want to delete the user?');
                        }
                    };
				});
			}
		);
</script>
@endsection