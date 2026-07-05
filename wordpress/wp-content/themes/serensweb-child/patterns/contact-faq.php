<?php
/**
 * Title: Contact FAQ
 * Slug: serensweb-child/contact-faq
 * Categories: serensweb-child
 * Description: Questions I get asked, as a native details/summary accordion. Copy comes from serensweb_faq_items() so the visible answers and the FAQPage schema stay identical.
 */
?>
<!-- wp:html -->
<section class="section section--dark2" id="faq" data-screen-label="FAQ">
  <div class="wrap">
    <div class="section-head reveal">
      <h2>Questions I get <em>asked</em></h2>
      <p class="lede">Short answers, no padding. If yours is not covered, the form above reaches me directly.</p>
    </div>
    <div class="faq reveal">
      <?php foreach ( serensweb_faq_items() as $question => $answer ) : ?>
      <details class="faq__item">
        <summary>
          <span><?php echo esc_html( $question ); ?></span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
        </summary>
        <p><?php echo esc_html( $answer ); ?></p>
      </details>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<!-- /wp:html -->
