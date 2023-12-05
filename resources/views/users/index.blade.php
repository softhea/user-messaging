@extends('layouts.bootstrap')

@section('content')
<h1>Users</h1>

@if ($canCreateUsers)
    <p>
		<a href="create-user" class="btn btn-primary">Add New User</a>
	</p>
@endif

@if ($error !== '')
	<p class="error">{{ $error }}</p>
@endif

@if ($message !== '')
	<p class="message">{{ $message }}</p>
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
					let userHtml = 
						'<tr>' +
							'<td>' + user.id + '</td>' +
							'<td>' + user.username + '</td>' +	
							'<td>' + user.email + '</td>' +	
							'<td>' + user.role + '</td>' +	
							'<td>' + (user.isActive ? 'Yes' : 'No') + '</td>' +
							'<td><a href="send-message?user_id=' + user.id + '" class="btn btn-info">Send Message</a></td>' +
							'<td>' + 
								(
									user.canBeUpdated 
										? '<a href="update-user?id=' + user.id + '" class="btn btn-warning">Edit</a>' 
										: ''
								) + ' ' +
								(
									user.canBeDeleted
										? '<a href="delete-user?id=' + user.id + '" class="delete-button btn btn-danger">Delete</a>' 
										: ''
								) +
							'</td>' +
						'</tr>';
					document.getElementById('table-users').getElementsByTagName('tbody')[0].insertAdjacentHTML("beforeend", userHtml);
                    
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