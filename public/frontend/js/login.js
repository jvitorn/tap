let email = document.querySelector('#email');
let password = document.querySelector('#password');

let sendData = document.querySelector('#send-button');

function getData(){
	let dataForm = {
		"name": email.value,
		"password": password.value
	}
}

sendData.onclick = getData;