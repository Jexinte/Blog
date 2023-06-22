const passwordCheckbox = document.getElementById('password-check')
const passwordInput = document.getElementById('password')

passwordCheckbox.addEventListener("change",() => {
  if(passwordCheckbox.checked) passwordInput.type = "text"
  else  passwordInput.type = "password"
})
