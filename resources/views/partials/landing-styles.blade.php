<style>
:root{color-scheme:dark;box-sizing:border-box;padding-top:env(safe-area-inset-top,0px);padding-bottom:env(safe-area-inset-bottom,0px);
--bg:#05070D;--sf:#0B1020;--el:#111936;--bd:rgba(255,255,255,.08);--bd2:rgba(255,255,255,.14);--tx:#F8FAFC;--t2:#A8B3C7;--mu:#6B7790;--cy:#22D3EE;--bl:#3B82F6;--in:#6366F1;--ok:#10B981;
--g:linear-gradient(90deg,#22D3EE,#3B82F6 55%,#6366F1);--mono:'JetBrains Mono',ui-monospace,Menlo,monospace}
*,*::before,*::after{box-sizing:border-box}
html{scroll-behavior:smooth;scroll-padding-top:88px}
body{margin:0;background:var(--bg);color:var(--tx);font:400 1rem/1.65 Inter,system-ui,-apple-system,Segoe UI,sans-serif;-webkit-font-smoothing:antialiased;overflow-x:hidden}
a{color:inherit;text-decoration:none}
.wrap{max-width:1320px;margin:0 auto;padding:0 24px}
@media(min-width:900px){.wrap{padding:0 32px}}
.ic{width:20px;height:20px;fill:none;stroke:currentColor;stroke-width:1.75;stroke-linecap:round;stroke-linejoin:round;flex:none}
.grad{background:var(--g);-webkit-background-clip:text;background-clip:text;color:transparent}
h1,h2,h3{margin:0;letter-spacing:-.02em;line-height:1.1}
h1{font-size:clamp(2.5rem,6vw,4.5rem);font-weight:700}
h2{font-size:clamp(1.75rem,3.5vw,2.75rem);font-weight:700}
h3{font-size:1.25rem;font-weight:600}
.eb{font:500 .75rem var(--mono);letter-spacing:.12em;text-transform:uppercase;color:var(--cy);margin-bottom:16px;display:block}
.sub{color:var(--t2);max-width:60ch;margin:16px 0 0}
section{padding:72px 0}@media(min-width:900px){section{padding:112px 0}}
.head{margin-bottom:48px}.head.c{text-align:center}.head.c .sub{margin-inline:auto}
/* buttons */
.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;height:44px;padding:0 20px;border-radius:12px;font:600 .95rem Inter,sans-serif;border:1px solid transparent;cursor:pointer;transition:background .15s,box-shadow .15s,border-color .15s,transform .15s;will-change:transform}
.btn.lg{height:52px;padding:0 28px;font-size:1rem}
.btn.p{background:linear-gradient(180deg,#3B82F6,#2563EB);color:#fff;box-shadow:0 8px 24px -8px rgba(59,130,246,.55)}
.btn.p:hover{background:linear-gradient(180deg,#4B8FF8,#2F6DF0);box-shadow:0 10px 30px -6px rgba(59,130,246,.7)}
.btn.s{border-color:var(--bd2);color:var(--tx);background:transparent}.btn.s:hover{background:rgba(255,255,255,.06)}
.btn .ic{width:18px;transition:transform .2s}.btn:hover .ic{transform:translateX(3px)}
:focus-visible{outline:2px solid var(--cy);outline-offset:3px;border-radius:8px}
/* nav */
header{position:sticky;top:0;z-index:50;transition:background .2s,border-color .2s,backdrop-filter .2s;border-bottom:1px solid transparent}
header.sc{background:rgba(5,7,13,.72);backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);border-color:var(--bd)}
.nav{height:72px;display:flex;align-items:center;gap:24px}
.logo{display:flex;align-items:center;gap:10px;font-weight:700;font-size:1.1rem}
.logo svg{width:28px;height:28px}.logo small{font:400 .7rem var(--mono);color:var(--mu);display:block;line-height:1;margin-top:2px;font-weight:400}
.menu{display:none;margin-left:auto;gap:4px}
.menu a{padding:8px 14px;border-radius:8px;color:var(--t2);font-size:.92rem;font-weight:500;transition:color .15s,background .15s}
.menu a:hover,.menu a.on{color:var(--tx);background:rgba(255,255,255,.06)}
.cta{display:none;gap:10px}
.burger{margin-left:auto;width:44px;height:44px;border-radius:10px;background:transparent;border:1px solid var(--bd2);color:var(--tx);cursor:pointer}
.drawer{display:none;position:fixed;inset:72px 0 0;background:rgba(5,7,13,.97);padding:24px;flex-direction:column;gap:6px;z-index:49}
.drawer.open{display:flex}.drawer a:not(.btn){padding:14px 8px;font-size:1.3rem;font-weight:600;border-bottom:1px solid var(--bd)}.drawer .btn{margin-top:16px}
@media(min-width:1000px){.menu,.cta{display:flex}.burger,.drawer{display:none!important}.cta{margin-left:8px}}
/* hero */
.hero{padding:40px 0 72px;position:relative;overflow:hidden}
.hero .in{display:grid;gap:32px;align-items:center}
@media(min-width:900px){.hero{padding:56px 0 96px;min-height:calc(100vh - 72px);display:flex;align-items:center}.hero .in{grid-template-columns:1.15fr .85fr;width:100%}}
.spot{position:absolute;inset:0;pointer-events:none;background:radial-gradient(520px circle at var(--mx,70%) var(--my,30%),rgba(59,130,246,.13),transparent 60%)}
.pill{display:inline-flex;align-items:center;gap:10px;padding:6px 14px;border:1px solid var(--bd2);border-radius:999px;font:500 .78rem var(--mono);color:var(--t2);margin-bottom:24px}
.dot{width:7px;height:7px;border-radius:50%;background:var(--ok);box-shadow:0 0 10px var(--ok)}
.hero .sub{font-size:1.125rem;margin:24px 0 32px}
.btns{display:flex;flex-wrap:wrap;gap:12px}
.trust{display:flex;flex-wrap:wrap;gap:12px 28px;margin-top:40px;color:var(--t2);font-size:.88rem}
.trust span{display:inline-flex;align-items:center;gap:8px}.trust .ic{color:var(--cy);width:18px}
.stage{position:relative;aspect-ratio:1/1;width:100%;max-width:560px;margin:0 auto}
.stage::before{content:"";position:absolute;inset:8%;background:radial-gradient(circle,rgba(59,130,246,.28),transparent 68%);filter:blur(30px)}
.stage canvas,.stage svg.fb{position:absolute;inset:0;width:100%;height:100%}
.scroll{position:absolute;left:50%;bottom:20px;transform:translateX(-50%);font:.72rem var(--mono);color:var(--mu);letter-spacing:.1em;display:none}
@media(min-width:900px){.scroll{display:block}.scroll::after{content:"";display:block;width:1px;height:22px;background:var(--mu);margin:8px auto 0;animation:sl 1.8s ease-in-out infinite}}
@keyframes sl{0%,100%{transform:scaleY(.3);transform-origin:top}50%{transform:scaleY(1)}}
/* cards */
.grid{display:grid;gap:20px}
@media(min-width:760px){.g2{grid-template-columns:repeat(2,1fr)}.g3{grid-template-columns:repeat(3,1fr)}.g4{grid-template-columns:repeat(4,1fr)}}
.card{position:relative;background:var(--sf);border:1px solid var(--bd);border-radius:16px;padding:28px;transition:border-color .2s,transform .15s;transform-style:preserve-3d}
.card::before{content:"";position:absolute;inset:-1px;border-radius:inherit;padding:1px;background:radial-gradient(260px circle at var(--gx,50%) var(--gy,0%),rgba(34,211,238,.55),transparent 60%);-webkit-mask:linear-gradient(#000 0 0) content-box,linear-gradient(#000 0 0);-webkit-mask-composite:xor;mask-composite:exclude;opacity:0;transition:opacity .2s;pointer-events:none}
.card:hover::before{opacity:1}
.ib{width:44px;height:44px;border-radius:12px;display:grid;place-items:center;background:linear-gradient(135deg,rgba(34,211,238,.16),rgba(99,102,241,.16));border:1px solid rgba(34,211,238,.22);color:var(--cy);margin-bottom:20px}
.card p{color:var(--t2);margin:10px 0 0;font-size:.95rem}
.card ul{list-style:none;padding:0;margin:18px 0 0;display:grid;gap:8px;font-size:.9rem;color:var(--t2)}
.card li{display:flex;gap:10px;align-items:flex-start}.card li::before{content:"";width:6px;height:6px;border-radius:50%;background:var(--cy);margin-top:9px;flex:none}
.more{display:inline-flex;gap:6px;align-items:center;margin-top:20px;font-weight:600;font-size:.9rem;color:var(--cy)}.more .ic{width:16px;transition:transform .2s}.card:hover .more .ic{transform:translateX(3px)}
/* process */
.steps{counter-reset:s;display:grid;gap:20px}
@media(min-width:900px){.steps{grid-template-columns:repeat(4,1fr)}}
.step{border-top:1px solid var(--bd2);padding-top:20px}
.step b{font:500 .8rem var(--mono);color:var(--cy)}.step h3{margin:10px 0 6px;font-size:1.15rem}.step p{margin:0;color:var(--t2);font-size:.92rem}
/* stats */
.stats{display:grid;gap:32px;text-align:center}
@media(min-width:760px){.stats{grid-template-columns:repeat(4,1fr)}}
.num{font-size:clamp(2.5rem,5vw,3.5rem);font-weight:700;letter-spacing:-.03em;line-height:1;background:var(--g);-webkit-background-clip:text;background-clip:text;color:transparent}
.stats p{color:var(--t2);margin:12px auto 0;max-width:26ch;font-size:.92rem}
.strip{margin-top:64px;padding:32px;border:1px solid var(--bd2);border-radius:20px;background:linear-gradient(135deg,rgba(34,211,238,.08),rgba(99,102,241,.1));display:flex;flex-wrap:wrap;gap:20px;align-items:center;justify-content:space-between}
.strip h3{font-size:1.5rem}
/* contact */
.cg{display:grid;gap:40px}@media(min-width:900px){.cg{grid-template-columns:.8fr 1.2fr;gap:64px}}
.ci{list-style:none;padding:0;margin:32px 0 0;display:grid;gap:20px}
.ci li{display:flex;gap:14px}.ci .ic{color:var(--cy);margin-top:3px}.ci small{display:block;font:.72rem var(--mono);color:var(--mu);text-transform:uppercase;letter-spacing:.1em}.ci span{color:var(--t2)}
form{background:var(--sf);border:1px solid var(--bd);border-radius:16px;padding:28px;display:grid;gap:18px}
@media(min-width:640px){form{grid-template-columns:1fr 1fr}.full{grid-column:1/-1}}
label{display:block;font-weight:500;font-size:.88rem;margin-bottom:8px}
input,select{width:100%;height:48px;background:var(--bg);border:1px solid var(--bd2);border-radius:10px;color:var(--tx);padding:0 14px;font:inherit;font-size:.95rem;transition:border-color .15s,box-shadow .15s}
input::placeholder{color:var(--mu)}
input:focus,select:focus{outline:none;border-color:var(--cy);box-shadow:0 0 0 3px rgba(34,211,238,.2)}
.note{font-size:.8rem;color:var(--mu);margin:0}
.ok{display:none;padding:12px 16px;border-radius:10px;background:rgba(16,185,129,.12);border:1px solid rgba(16,185,129,.4);color:#6EE7B7;font-size:.9rem}.ok.show{display:block}
/* faq */
.faq{max-width:760px;margin:0 auto;display:grid;gap:10px}
details{background:var(--sf);border:1px solid var(--bd);border-radius:12px;transition:border-color .2s}
details[open]{border-color:rgba(34,211,238,.35)}
summary{list-style:none;cursor:pointer;padding:18px 20px;font-weight:600;display:flex;justify-content:space-between;gap:16px;align-items:center}
summary::-webkit-details-marker{display:none}summary::after{content:"+";font:400 1.4rem var(--mono);color:var(--cy);line-height:1}details[open] summary::after{content:"–"}
details p{margin:0;padding:0 20px 20px;color:var(--t2);font-size:.95rem}
/* footer */
footer{border-top:1px solid var(--bd);background:var(--sf);padding:64px 0 32px}
.fg{display:grid;gap:36px}@media(min-width:900px){.fg{grid-template-columns:1.4fr .7fr 1fr 1.2fr}}
footer h4{margin:0 0 14px;font-size:.85rem;font:500 .75rem var(--mono);letter-spacing:.12em;text-transform:uppercase;color:var(--mu)}
footer ul{list-style:none;padding:0;margin:0;display:grid;gap:8px;color:var(--t2);font-size:.92rem}footer a:hover{color:var(--tx)}
footer p{color:var(--t2);font-size:.92rem;margin:12px 0 0;max-width:36ch}
.nl{display:flex;gap:8px;margin-top:12px}.nl input{height:44px}
.copy{margin-top:48px;padding-top:24px;border-top:1px solid var(--bd);text-align:center;color:var(--mu);font-size:.85rem}
/* motion */
.rv{opacity:0;transform:translateY(16px);transition:opacity .6s ease,transform .6s ease;transition-delay:var(--d,0ms)}.rv.in{opacity:1;transform:none}
.ring{position:fixed;left:0;top:0;width:34px;height:34px;margin:-17px 0 0 -17px;border:1.5px solid rgba(34,211,238,.7);border-radius:50%;pointer-events:none;z-index:100;opacity:0;transition:width .2s,height .2s,margin .2s,background .2s,opacity .2s}
.ring.on{opacity:1}.ring.hv{width:56px;height:56px;margin:-28px 0 0 -28px;background:rgba(34,211,238,.1)}
@media(prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important;scroll-behavior:auto!important}.rv{opacity:1;transform:none}.ring{display:none}}
/* cyber background layer */
.bg-cyber{position:fixed;inset:0;z-index:-1;pointer-events:none;overflow:hidden;background:#12161f}
.bg-cyber svg{width:100%;height:100%;display:block;animation:bgdrift 48s ease-in-out infinite alternate}
@keyframes bgdrift{from{transform:scale(1.06) translate3d(0,0,0)}to{transform:scale(1.14) translate3d(-1.5%,-1%,0)}}
@media(prefers-reduced-motion:reduce){.bg-cyber svg{animation:none}}
</style>
