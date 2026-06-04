/* =====================================================================
   SerensWeb — Tweaks island (React). Drives the token-based theme:
   the accent maps to a SINGLE CSS variable (--color-primary), exactly
   how the WordPress theme.json palette would be rethemed.
   ===================================================================== */
const { useEffect, useRef } = React;

const ACCENTS = {
  orange: { primary: '#F6821F', strong: '#E5751A', label: 'Orange' },
  blue:   { primary: '#2F73E8', strong: '#2861C9', label: 'Blue'   },
  purple: { primary: '#8B5CF6', strong: '#7A48E0', label: 'Purple' },
  green:  { primary: '#1FA463', strong: '#198A53', label: 'Green'  },
};

const SW_TWEAK_DEFAULTS = /*EDITMODE-BEGIN*/{
  "accent": "orange",
  "viewport": 1280,
  "motion": true
}/*EDITMODE-END*/;

function SerensTweaks() {
  const [t, setTweak] = useTweaks(SW_TWEAK_DEFAULTS);
  const accentInit = useRef(false);

  // Accent → route through the live footer switcher so both stay in sync
  // (updates the CSS token, footer dot state, and localStorage)
  useEffect(() => {
    // On first mount, adopt whatever the footer switcher already restored
    // from localStorage, so the panel and the live site agree on load.
    if (!accentInit.current) {
      accentInit.current = true;
      const saved = window.__serensSavedAccent;
      if (saved && saved !== t.accent) { setTweak('accent', saved); return; }
    }
    if (window.__serensApplyAccent) {
      window.__serensApplyAccent(t.accent, true);
    } else {
      const a = ACCENTS[t.accent] || ACCENTS.orange;
      document.documentElement.style.setProperty('--color-primary', a.primary);
      document.documentElement.style.setProperty('--color-primary-strong', a.strong);
    }
  }, [t.accent]);

  // Viewport preview: a continuous slider that morphs the site from full
  // desktop down to a framed phone, driving the --vp-w token + container queries.
  useEffect(() => {
    const w = Number(t.viewport);
    const full = w >= 1280;
    document.documentElement.style.setProperty('--vp-w', full ? '100%' : w + 'px');
    document.body.classList.toggle('vp-preview', !full);
    document.body.classList.toggle('vp-mobile', w <= 480);
    window.scrollTo({ top: 0 });
    setTimeout(() => window.__serensRevealCheck && window.__serensRevealCheck(), 450);
  }, [t.viewport]);

  // Motion
  useEffect(() => {
    document.body.classList.toggle('no-anim', !t.motion);
  }, [t.motion]);

  // map accent key -> swatch hex for the color control
  const accentHex = (ACCENTS[t.accent] || ACCENTS.orange).primary;
  const hexToKey = (hex) => Object.keys(ACCENTS).find(k => ACCENTS[k].primary === hex) || 'orange';

  // Viewport readout: width while constrained, "Desktop" at full bleed
  const vp = Number(t.viewport);
  const vpLabel = vp >= 1280 ? 'Desktop' : vp <= 480 ? `Mobile · ${vp}px` : `${vp}px`;

  return (
    <TweaksPanel title="Tweaks">
      <TweakSection label="Brand accent" />
      <TweakColor
        label="Accent token"
        value={accentHex}
        options={[ACCENTS.orange.primary, ACCENTS.blue.primary, ACCENTS.purple.primary, ACCENTS.green.primary]}
        onChange={(hex) => setTweak('accent', hexToKey(hex))}
      />
      <TweakSection label="Preview" />
      <TweakRow label="Viewport" value={vpLabel}>
        <input
          type="range"
          className="twk-slider"
          min={360}
          max={1280}
          step={4}
          value={vp}
          onChange={(e) => setTweak('viewport', Number(e.target.value))}
        />
        <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '10px', letterSpacing: '.04em', color: 'rgba(41,38,27,.45)', marginTop: '1px' }}>
          <span>Mobile</span>
          <span>Desktop</span>
        </div>
      </TweakRow>
      <TweakToggle
        label="Scroll animations"
        value={t.motion}
        onChange={(v) => setTweak('motion', v)}
      />
    </TweaksPanel>
  );
}

ReactDOM.createRoot(document.getElementById('tweaks-root')).render(<SerensTweaks />);
