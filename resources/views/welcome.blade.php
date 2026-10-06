@extends('layouts.landing')

@section('content')
<section class="hero" style="padding-bottom:72px">
  <div class="spot"></div>
  <div class="wrap in">
    <div>
      <span class="pill"><i class="dot"></i>Offensive &amp; Defensive Security</span>
      <h1>Find the weaknesses <span class="grad">before attackers do.</span></h1>
      <p class="sub">SecureOps helps teams uncover vulnerabilities, respond to incidents, and stay audit-ready, with security engineers who work as an extension of your team.</p>
      <div class="btns"><a href="#contact" class="btn p lg mag">Book a Consultation <svg class="ic"><use href="#i-arrow"/></svg></a><a href="#services" class="btn s lg">Explore Services</a></div>
      <div class="trust">
        <span><svg class="ic"><use href="#i-check"/></svg>Certified security engineers</span>
        <span><svg class="ic"><use href="#i-target"/></svg>Web, API &amp; infrastructure testing</span>
        <span><svg class="ic"><use href="#i-file"/></svg>Detailed, actionable reports</span>
      </div>
    </div>
    <div class="stage" id="stage"><canvas id="c" aria-label="Animated particle shield"></canvas></div>
  </div>
  <div class="scroll">SCROLL</div>
</section>

<section id="services"><div class="wrap">
  <div class="head rv"><span class="eb">Services</span><h2>Security services built around real attack paths</h2><p class="sub">From the first scan to the final report, we cover the full lifecycle of finding, fixing, and proving security.</p></div>
  <div class="grid g2">
    <article class="card rv"><div class="ib"><svg class="ic"><use href="#i-target"/></svg></div><h3>Penetration Testing</h3><p>Authorized web, API, and network testing that simulates real attackers and proves impact.</p><ul><li>OWASP Top 10 &amp; API Security coverage</li><li>Manual exploitation, not just scanner output</li><li>Retest after remediation</li></ul><a href="#contact" class="more">Learn more <svg class="ic"><use href="#i-arrow"/></svg></a></article>
    <article class="card rv" style="--d:80ms"><div class="ib"><svg class="ic"><use href="#i-search"/></svg></div><h3>Security Assessment</h3><p>Evaluate your security posture across applications, infrastructure, and configuration.</p><ul><li>Vulnerability assessment</li><li>Configuration &amp; architecture review</li><li>Prioritized risk ranking</li></ul><a href="#contact" class="more">Learn more <svg class="ic"><use href="#i-arrow"/></svg></a></article>
    <article class="card rv"><div class="ib"><svg class="ic"><use href="#i-zap"/></svg></div><h3>Incident Response</h3><p>Rapid triage, containment, and root-cause analysis when something goes wrong.</p><ul><li>Alert triage &amp; investigation</li><li>Containment guidance</li><li>Post-incident report</li></ul><a href="#contact" class="more">Learn more <svg class="ic"><use href="#i-arrow"/></svg></a></article>
    <article class="card rv" style="--d:80ms"><div class="ib"><svg class="ic"><use href="#i-check"/></svg></div><h3>Compliance Audit</h3><p>Map your controls to the standards your customers and regulators expect.</p><ul><li>Gap analysis</li><li>Control mapping</li><li>Audit-ready documentation</li></ul><a href="#contact" class="more">Learn more <svg class="ic"><use href="#i-arrow"/></svg></a></article>
  </div>
</div></section>

<section style="padding-top:0"><div class="wrap">
  <div class="head rv"><span class="eb">Process</span><h2>A clear process, from scoping to retest</h2></div>
  <div class="steps">
    <div class="step rv"><b>01</b><h3>Scope</h3><p>Agree on targets, rules of engagement, and success criteria.</p></div>
    <div class="step rv" style="--d:80ms"><b>02</b><h3>Test</h3><p>Our engineers probe your systems the way a real attacker would.</p></div>
    <div class="step rv" style="--d:160ms"><b>03</b><h3>Report</h3><p>Findings with evidence, impact, and step-by-step fixes.</p></div>
    <div class="step rv" style="--d:240ms"><b>04</b><h3>Retest</h3><p>We verify every fix, so closed means closed.</p></div>
  </div>
</div></section>

<section id="why" style="padding-top:0"><div class="wrap">
  <div class="head c rv"><span class="eb">Why SecureOps</span><h2>Why teams choose SecureOps</h2></div>
  <div class="grid g3">
    <article class="card rv"><div class="ib"><svg class="ic"><use href="#i-users"/></svg></div><h3>Certified Engineers</h3><p>Hands-on practitioners with recognized certifications, not just tool operators.</p></article>
    <article class="card rv" style="--d:80ms"><div class="ib"><svg class="ic"><use href="#i-file"/></svg></div><h3>Actionable Reports</h3><p>Every finding includes evidence, impact, and step-by-step remediation.</p></article>
    <article class="card rv" style="--d:160ms"><div class="ib"><svg class="ic"><use href="#i-clock"/></svg></div><h3>Responsive Support</h3><p>Direct access to the engineers who tested your systems.</p></article>
  </div>
</div></section>

<section id="threat-landscape" style="padding-top:0"><div class="wrap">
  <div class="head c rv"><span class="eb">Threat Landscape</span><h2>The cost of waiting is measurable</h2></div>
  <!-- TODO: verify each figure and add a cited source under it before publishing -->
  <div class="stats">
    <div class="rv"><div class="num" data-pre="$" data-to="6" data-suf="T">$6T</div><p>Estimated global cybercrime damage annually</p></div>
    <div class="rv" style="--d:80ms"><div class="num" data-to="30" data-suf="B">30B</div><p>Data records breached each year</p></div>
    <div class="rv" style="--d:160ms"><div class="num" data-to="74" data-suf="%">74%</div><p>Of breaches involve the human element</p></div>
    <div class="rv" style="--d:240ms"><div class="num" data-to="280" data-suf="d">280d</div><p>Average time to identify &amp; contain a breach</p></div>
  </div>
  <div class="strip rv"><h3>Ready to see where you stand?</h3><a href="#contact" class="btn p lg mag">Book a Consultation <svg class="ic"><use href="#i-arrow"/></svg></a></div>
</div></section>

<section id="contact" style="padding-top:0"><div class="wrap cg">
  <div class="rv"><span class="eb">Contact</span><h2>Talk to a security engineer</h2><p class="sub">Tell us about your environment and goals. We'll reply within one business day with next steps.</p>
    <ul class="ci">
      <li><svg class="ic"><use href="#i-pin"/></svg><div><small>Office</small><span>{{ config('brand.address', '18 Office Park Building, 21th Floor Unit C, Jl. TB Simatupang No.18, Jakarta 12520') }}</span></div></li>
      <li><svg class="ic"><use href="#i-phone"/></svg><div><small>Phone</small><span>{{ config('brand.phone', '(+62) 811-4441-1988') }}</span></div></li>
      <li><svg class="ic"><use href="#i-mail"/></svg><div><small>Email</small><span>{{ config('brand.email', 'sales@nemosecurity.com') }}</span></div></li>
    </ul></div>
  <form id="form" class="rv" style="--d:100ms" method="POST" action="#">
    @csrf
    <div><label for="n">Full Name</label><input id="n" name="name" required placeholder="Enter your full name"></div>
    <div><label for="e">Email</label><input id="e" name="email" type="email" required placeholder="you@company.com"></div>
    <div><label for="co">Company Name</label><input id="co" name="company" placeholder="Enter your company name"></div>
    <div><label for="w">Company Website</label><input id="w" name="website" placeholder="company.com"></div>
    <div><label for="in">Industry</label><select id="in" name="industry"><option>Select your option</option><option>Finance</option><option>Technology</option><option>Government</option><option>Healthcare</option><option>Other</option></select></div>
    <div><label for="ar">Main Area of Operations</label><select id="ar" name="area"><option>Select your option</option><option>Indonesia</option><option>Southeast Asia</option><option>Global</option></select></div>
    <div class="full"><label for="sv">Product &amp; Services</label><select id="sv" name="service"><option>Select your option</option><option>Penetration Testing</option><option>Security Assessment</option><option>Incident Response</option><option>Compliance Audit</option></select></div>
    <div class="full"><div class="ok" id="ok" role="status" aria-live="polite">Thanks! We'll get back to you within one business day.</div></div>
    <div class="full" style="display:flex;flex-wrap:wrap;gap:16px;align-items:center"><button class="btn p lg" type="submit">Send Message</button><p class="note">We only use your details to respond to your inquiry.</p></div>
  </form>
</div></section>

<section id="faq" style="padding-top:0"><div class="wrap">
  <div class="head c rv"><span class="eb">FAQ</span><h2>Frequently asked questions</h2></div>
  <div class="faq rv">
    <details name="f" open><summary>What services do you provide?</summary><p>Penetration testing, security assessment, incident response, and compliance audit, delivered by certified security engineers.</p></details>
    <details name="f"><summary>Who can benefit from your services?</summary><p>Any organization that runs web apps, APIs, or infrastructure and needs proof that its defenses hold up, from startups to enterprises.</p></details>
    <details name="f"><summary>How do you protect our data?</summary><p>All testing is authorized and scoped in writing. Findings and evidence are handled under strict access control and shared only through agreed channels.</p></details>
    <details name="f"><summary>Do you offer customized solutions?</summary><p>Yes. Every engagement is scoped around your systems, risk profile, and timeline, with no one-size-fits-all packages.</p></details>
    <details name="f"><summary>How do we get started?</summary><p>Send us a message. We'll schedule a short call to understand your goals, then share a scoped proposal.</p></details>
  </div>
</div></section>
@endsection
