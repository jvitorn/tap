let revealPassword = document.querySelector('#reveal-password');
let passwordInput = document.querySelector('#password');
let showIcon = document.querySelector('#show-icon');

revealPassword.onclick = function() {
	showIcon.setAttribute("class", "far fa-eye-slash");
	passwordInput.setAttribute("type", "text");
	
}


let email = document.querySelector('#email');
let password = document.querySelector('#password');

let sendData = document.querySelector('#send-button');

	sendData.onclick = function() {
		var dataForm = [
			'email:' + document.querySelector('#email').value,
			'password:'+ document.querySelector('#password').value
		];

		$.ajax({
			type: "post",
			url: "https://8001-fe82cc67-4f79-43c3-8b6d-652f6e8ce32d.ws-us02.gitpod.io/backend/user",
			data: dataForm,
			success: function(data) {
				
			},
			error: function(data) {
				alert("Error in send data for database");
				console.log(data);
			}
		});
	}
	

