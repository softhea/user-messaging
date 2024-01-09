@extends('layouts.bootstrap')

@section('content')
<h1>Messages</h1>

<table class="table table-striped" id="table-users">
	<thead>
		<tr>
			<th>Sender</th>
			<th>Receiver</th>
			<th>Message</th>
			<th>Date And Time</th>
			<th>Is Read</th>
			<th>Hide</th>
		</tr>
	</thead>
	<tbody></tbody>
</table>

<script>
	fetch('{{ route("api.messages.index") }}')
		.then(function (response) {
			return response.json();
		}).then(
			function (response) {
				response.data.forEach(message => {
					let url = '{{ route("users.edit", ["user" => ":user"]) }}';
					url = url.replace(':user', message.id);

					let userHtml = 
						'<tr>' +
							'<td>' + message.sender_id + '</td>' +
							'<td>' + message.receiver_id + '</td>' +	
							'<td>' + message.message + '</td>' +	
							'<td>' + message.created_at + '</td>' +	
							'<td>' + (message.is_read ? 'Yes' : 'No') + '</td>' +
							'<td>' + 
								(
									message.can_be_hidden 
										? '<a href="' + url + '" class="btn btn-warning">Hide</a>' 
										: ''
								)
							'</td>' +
						'</tr>';

					document.querySelector('#table-users tbody').insertAdjacentHTML("beforeend", userHtml);
				});
			}
		);
</script>
@endsection