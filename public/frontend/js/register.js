let name = document.querySelector('#name');
let email = document.querySelector('#email');
let password = document.querySelector('#password');
let termAgreement = document.querySelector('#terms-check input');

let sendData = document.querySelector('#register-button');


function termAgreementCheck() {
    
}


sendData.onclick = function() {

    var DataForm = [
        'name:' + name.value,
        'email:' + email.value,
        'password:' + password.value
    ];
}