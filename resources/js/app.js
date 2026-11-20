document.querySelectorAll('[data-password-toggle]').forEach((button) => {
  button.addEventListener('click', () => {
    const input = document.getElementById(button.dataset.passwordToggle);
    const visible = input.type === 'password';
    input.type = visible ? 'text' : 'password';
    button.setAttribute('aria-pressed', String(visible));
    button.setAttribute(
      'aria-label',
      (visible ? 'Sembunyikan' : 'Tampilkan') + ' ' + input.labels[0].textContent.toLowerCase(),
    );
    button.querySelector('i').className = visible ? 'bx bx-show' : 'bx bx-hide';
  });
});

let pendingConfirmation;
const modalElement = document.getElementById('confirmation-modal');
if (modalElement) {
  modalElement.addEventListener('hidden.bs.modal', () => {
    pendingConfirmation = undefined;
  });
  document.getElementById('confirmation-submit').addEventListener('click', () => {
    if (!pendingConfirmation) return;
    const { form, submitter } = pendingConfirmation;
    form.dataset.confirmed = 'true';
    window.bootstrap.Modal.getInstance(modalElement).hide();
    form.requestSubmit(submitter);
  });
}

document.addEventListener('submit', (event) => {
  const form = event.target;
  if (!(form instanceof HTMLFormElement)) return;
  if (form.dataset.confirm && form.dataset.confirmed !== 'true') {
    event.preventDefault();
    pendingConfirmation = { form, submitter: event.submitter };
    document.getElementById('confirmation-message').textContent = form.dataset.confirm;
    window.bootstrap.Modal.getOrCreateInstance(modalElement).show();
    return;
  }
  delete form.dataset.confirmed;
  form.querySelectorAll('button[type="submit"]').forEach((button) => {
    button.disabled = true;
  });
});

window.addEventListener('pageshow', () => {
  document.querySelectorAll('button[type="submit"]').forEach((button) => {
    button.disabled = false;
  });
});
