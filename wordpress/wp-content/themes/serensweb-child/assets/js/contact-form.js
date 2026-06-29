/* =====================================================================
   SerensWeb contact form: client-side validation, then submit to the
   theme REST endpoint (serensweb/v1/contact) wired in includes/ajax-contact.php.
   Guarded on #contactForm. swContact (url + nonce) is localized in enqueue.php;
   if it is missing the form falls back to the client-only success state.
   ===================================================================== */
(function () {
  'use strict';
  const $ = (s, r = document) => r.querySelector(s);
  const $$ = (s, r = document) => [...r.querySelectorAll(s)];

  const form = $('#contactForm');
  const success = $('#formSuccess');
  if (!form) return;

  const validators = {
    name: v => v.trim().length >= 2,
    email: v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim()),
    message: v => v.trim().length >= 10,
  };
  function validateField(field) {
    const name = field.dataset.field;
    const input = $('input, textarea', field);
    const ok = validators[name](input.value);
    field.classList.toggle('invalid', !ok);
    return ok;
  }

  $$('.field', form).forEach(field => {
    const input = $('input, textarea', field);
    input.addEventListener('blur', () => { if (input.value) validateField(field); });
    input.addEventListener('input', () => { if (field.classList.contains('invalid')) validateField(field); });
  });

  function showSuccess() {
    form.style.display = 'none';
    if (success) success.classList.add('show');
  }

  form.addEventListener('submit', async e => {
    e.preventDefault();
    let allOk = true;
    $$('.field', form).forEach(field => { if (!validateField(field)) allOk = false; });
    if (!allOk) { $('.field.invalid input, .field.invalid textarea', form)?.focus(); return; }

    const payload = {
      name:    $('[name="name"]', form).value,
      email:   $('[name="email"]', form).value,
      message: $('[name="message"]', form).value,
    };

    // No endpoint configured: keep the prototype's client-only success.
    if (typeof swContact === 'undefined' || !swContact.url) { showSuccess(); return; }

    const btn = $('button[type="submit"]', form);
    if (btn) btn.disabled = true;
    try {
      const res = await fetch(swContact.url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': swContact.nonce },
        body: JSON.stringify(payload),
      });
      if (!res.ok) throw new Error('Request failed');
      showSuccess();
    } catch (err) {
      if (btn) btn.disabled = false;
      const msgField = $('[data-field="message"]', form);
      if (msgField) {
        msgField.classList.add('invalid');
        const errEl = $('.field__err', msgField);
        if (errEl) errEl.textContent = 'Something went wrong sending your message. Please email vandermerweseren@gmail.com.';
      }
    }
  });

  $('#resetForm')?.addEventListener('click', () => {
    form.reset();
    $$('.field', form).forEach(f => f.classList.remove('invalid'));
    if (success) success.classList.remove('show');
    form.style.display = 'grid';
    const btn = $('button[type="submit"]', form);
    if (btn) btn.disabled = false;
  });
})();
