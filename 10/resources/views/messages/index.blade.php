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
					let url = '{{ route("messages.hide", ["message" => ":message"]) }}';
					url = url.replace(':message', message.id);

					let userHtml = 
						'<tr>' +
							'<td>' + message.sender + '</td>' +
							'<td>' + message.receiver + '</td>' +	
							'<td>' + message.message + '</td>' +	
							'<td>' + message.created_at + '</td>' +	
							'<td>' + (message.is_read ? 'Yes' : 'No') + '</td>' +
							'<td>' + 
								(
									message.can_be_hidden 
										? '<form class="btn btn-warning p-0" method="POST" action="' + url + '">' +
											'{{ csrf_field() }}' +
											'{{ method_field("PATCH") }}' +
											'' +
												'<input type="submit" class="btn btn-warning" value="Hide">' +
											'' +
										'</form>'
										: ''
								) +
							'</td>' +
						'</tr>';

					document.querySelector('#table-users tbody').insertAdjacentHTML("beforeend", userHtml);
				});
			}
		);
</script>
@endsection