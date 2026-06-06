<?php
/**
 * Title: Home - Contact
 * Slug: serensweb-child/home-contact
 * Categories: serensweb-child
 * Description: Dark contact section with a meta list and the validated contact form (posts to serensweb/v1/contact).
 */
?>
<!-- wp:html -->
<section class="section section--dark" id="contact" data-screen-label="Contact">
  <div class="hero__mesh" aria-hidden="true" style="opacity:0.5;"></div>
  <div class="wrap contact__grid" style="position:relative;z-index:1;">
    <div class="contact__lead reveal">
      <h2>Have a project in <em>mind?</em></h2>
      <p class="lede" style="margin-top:22px;max-width:42ch;">Tell me a little about what you're building. I reply to every serious enquiry within one business day.</p>
      <div class="contact__meta">
        <a href="mailto:hello@serensweb.dev"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2.5"/><path d="m4 7 8 6 8-6"/></svg> hello@serensweb.dev</a>
        <div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="11" r="3"/><path d="M12 2a8 8 0 0 0-8 8c0 5.5 8 12 8 12s8-6.5 8-12a8 8 0 0 0-8-8Z"/></svg> Remote, working worldwide</div>
        <div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg> Currently booking for Q3</div>
      </div>
    </div>

    <div class="reveal">
      <form class="form" id="contactForm" novalidate>
        <div class="field" data-field="name">
          <label for="f-name">Your name</label>
          <input id="f-name" name="name" type="text" placeholder="Jane Doe" autocomplete="name" />
          <div class="field__err">Please enter your name.</div>
        </div>
        <div class="field" data-field="email">
          <label for="f-email">Email</label>
          <input id="f-email" name="email" type="email" placeholder="jane@company.com" autocomplete="email" />
          <div class="field__err">Please enter a valid email address.</div>
        </div>
        <div class="field" data-field="message">
          <label for="f-message">Project details</label>
          <textarea id="f-message" name="message" placeholder="What are you building, and what's the timeline?"></textarea>
          <div class="field__err">Please add a few details (10+ characters).</div>
        </div>
        <button type="submit" class="btn btn--primary">
          Send message
          <svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </button>
      </form>

      <div class="form__success" id="formSuccess" role="status">
        <div class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg></div>
        <h3>Message sent. Thank you.</h3>
        <p>I'll be in touch within one business day. In the meantime, feel free to reply to the confirmation email with anything else.</p>
        <button type="button" class="btn btn--ghost" id="resetForm">Send another</button>
      </div>
    </div>
  </div>
</section>
<!-- /wp:html -->
