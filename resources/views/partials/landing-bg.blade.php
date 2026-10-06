<div class="bg-cyber" aria-hidden="true">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 600" width="100%" height="100%" preserveAspectRatio="xMidYMid slice">
  <defs>
    <pattern id="hexGrid" width="60" height="52" patternUnits="userSpaceOnUse">
      <polygon points="30,2 56,17 56,47 30,62 4,47 4,17" fill="none" stroke="#3b4d6b" stroke-width="1" opacity="0.9"/>
    </pattern>
    <pattern id="dotGrid" width="40" height="40" patternUnits="userSpaceOnUse">
      <circle cx="20" cy="20" r="1" fill="#3b4d6b" opacity="0.7"/>
    </pattern>
    <radialGradient id="fadeOut" cx="50%" cy="50%" r="50%">
      <stop offset="0%" style="stop-color:#12161f;stop-opacity:0"/>
      <stop offset="100%" style="stop-color:#12161f;stop-opacity:1"/>
    </radialGradient>
    <linearGradient id="glowLine" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" style="stop-color:#22d3ee;stop-opacity:0"/>
      <stop offset="50%" style="stop-color:#22d3ee;stop-opacity:0.9"/>
      <stop offset="100%" style="stop-color:#3b82f6;stop-opacity:0"/>
    </linearGradient>
    <linearGradient id="glowLineV" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" style="stop-color:#60a5fa;stop-opacity:0"/>
      <stop offset="50%" style="stop-color:#60a5fa;stop-opacity:0.65"/>
      <stop offset="100%" style="stop-color:#60a5fa;stop-opacity:0"/>
    </linearGradient>
    <filter id="softGlow" x="-200%" y="-200%" width="500%" height="500%">
      <feGaussianBlur stdDeviation="3" result="b"/>
      <feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge>
    </filter>
  </defs>

  <rect width="800" height="600" fill="#12161f"/>
  <rect width="800" height="600" fill="url(#hexGrid)"/>
  <rect width="800" height="600" fill="url(#fadeOut)"/>

  <line x1="0" y1="150" x2="800" y2="150" stroke="url(#glowLine)" stroke-width="1.2"/>
  <line x1="0" y1="300" x2="800" y2="300" stroke="url(#glowLine)" stroke-width="0.8" opacity="0.7"/>
  <line x1="0" y1="450" x2="800" y2="450" stroke="url(#glowLine)" stroke-width="1.2" opacity="0.6"/>

  <line x1="200" y1="0" x2="200" y2="600" stroke="url(#glowLineV)" stroke-width="1.2"/>
  <line x1="600" y1="0" x2="600" y2="600" stroke="url(#glowLineV)" stroke-width="1.2" opacity="0.7"/>

  <g opacity="0.9">
    <circle cx="200" cy="150" r="4" fill="#22d3ee" filter="url(#softGlow)"/>
    <circle cx="600" cy="150" r="4" fill="#60a5fa"/>
    <circle cx="200" cy="450" r="4" fill="#60a5fa"/>
    <circle cx="600" cy="450" r="4" fill="#22d3ee"/>
    <circle cx="400" cy="300" r="6" fill="#22d3ee" opacity="0.6"/>
  </g>

  <g opacity="0.5" stroke="#22d3ee" stroke-width="1.6">
    <line x1="0" y1="80" x2="160" y2="80">
      <animate attributeName="x1" values="-160;800" dur="6s" repeatCount="indefinite"/>
      <animate attributeName="x2" values="0;960" dur="6s" repeatCount="indefinite"/>
    </line>
    <line x1="0" y1="220" x2="80" y2="220">
      <animate attributeName="x1" values="-80;800" dur="4s" repeatCount="indefinite" begin="1s"/>
      <animate attributeName="x2" values="0;880" dur="4s" repeatCount="indefinite" begin="1s"/>
    </line>
    <line x1="0" y1="380" x2="200" y2="380">
      <animate attributeName="x1" values="-200;800" dur="8s" repeatCount="indefinite" begin="2s"/>
      <animate attributeName="x2" values="0;1000" dur="8s" repeatCount="indefinite" begin="2s"/>
    </line>
  </g>
</svg>
</div>
