window.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('formulario');
  const inputs = form.querySelectorAll('input');

  form.addEventListener('submit', function(event) {
    if (!validateForm()) {
      event.preventDefault(); // Evita que el formulario se envíe si no es válido
    }
  });

  inputs.forEach(function(input) {
    input.addEventListener('blur', function() {
      validateInput(this); // Valida el campo de entrada cuando pierde el foco
    });
  });

  function validateForm() {
    let isValid = true;

    inputs.forEach(function(input) {
      if (!validateInput(input)) {
        isValid = false; // Verifica si los datos introducidos no son válidos
      }
    });

    return isValid;
  }

  function validateInput(input) {
    const grupo = input.parentElement;
    const small = grupo.querySelector('.error-message');
    const validIcon = grupo.querySelector('.valid-icon');
    const invalidIcon = grupo.querySelector('.invalid-icon');

    const patterns = {
      nombre: /^[a-zA-ZÀ-ÿ\s]{1,25}$/,
      apellido1: /^[a-zA-ZÀ-ÿ\s]{1,40}$/,
      apellido2: /^[a-zA-ZÀ-ÿ\s]{1,40}$/,
      email: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/,
      login: /^[a-zA-Z0-9\_\-]{4,16}$/,
      password: /^.{4,8}$/
    };

    if (input.value.match(patterns[input.name])) {
      small.style.display = 'none';
      validIcon.style.opacity = 1;
      invalidIcon.style.opacity = 0;
      input.classList.remove('invalid');
      return true;
    } else {
      small.textContent = 'Formato inválido';
      small.style.display = 'block';
      validIcon.style.opacity = 0;
      invalidIcon.style.opacity = 1;
      input.classList.add('invalid');
      return false;
    }
  }
});
