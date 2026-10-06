<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script>
const $=(s,r=document)=>r.querySelector(s),$$=(s,r=document)=>[...r.querySelectorAll(s)];
const reduce=matchMedia('(prefers-reduced-motion: reduce)').matches, fine=matchMedia('(pointer: fine)').matches;
$('#yr').textContent=new Date().getFullYear();

/* nav: glass on scroll, drawer, scrollspy */
const hd=$('header'),dr=$('#drawer'),bg=$('#burger');
addEventListener('scroll',()=>hd.classList.toggle('sc',scrollY>8),{passive:true});
bg.onclick=()=>{const o=dr.classList.toggle('open');bg.setAttribute('aria-expanded',o);bg.textContent=o?'✕':'☰';document.body.style.overflow=o?'hidden':''};
$$('.drawer a').forEach(a=>a.addEventListener('click',()=>{dr.classList.remove('open');bg.textContent='☰';document.body.style.overflow=''}));
const links=$$('.menu a'),spy=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting)links.forEach(l=>l.classList.toggle('on',l.getAttribute('href')==='#'+e.target.id))}),{rootMargin:'-45% 0px -50% 0px'});
$$('section[id]').forEach(s=>spy.observe(s));

/* scroll reveal + count-up */
const io=new IntersectionObserver(es=>es.forEach(e=>{if(!e.isIntersecting)return;e.target.classList.add('in');io.unobserve(e.target);
  const n=$('.num',e.target);if(n&&!reduce)count(n)}),{threshold:.15});
$$('.rv').forEach(el=>io.observe(el));
function count(n){const to=+n.dataset.to,pre=n.dataset.pre||'',suf=n.dataset.suf||'',t0=performance.now();
  (function f(t){const p=Math.min(1,(t-t0)/1400),v=Math.round(to*(1-Math.pow(1-p,3)));n.textContent=pre+v+suf;if(p<1)requestAnimationFrame(f)})(t0)}

/* cursor ring, spotlight, card glow + tilt, magnetic buttons */
const ring=$('#ring'),hero=$('.hero');let rx=0,ry=0,cx=0,cy=0;
if(fine&&!reduce){
  addEventListener('pointermove',e=>{cx=e.clientX;cy=e.clientY;ring.classList.add('on');
    const r=hero.getBoundingClientRect();hero.style.setProperty('--mx',(e.clientX-r.left)+'px');hero.style.setProperty('--my',(e.clientY-r.top)+'px');
    ring.classList.toggle('hv',!!e.target.closest('a,button,summary,input,select'))});
  (function loop(){rx+=(cx-rx)*.18;ry+=(cy-ry)*.18;ring.style.transform=`translate(${rx}px,${ry}px)`;requestAnimationFrame(loop)})();
  $$('.card').forEach(c=>{
    c.addEventListener('pointermove',e=>{const r=c.getBoundingClientRect(),x=e.clientX-r.left,y=e.clientY-r.top;
      c.style.setProperty('--gx',x+'px');c.style.setProperty('--gy',y+'px');
      c.style.transform=`perspective(800px) rotateX(${((y/r.height)-.5)*-6}deg) rotateY(${((x/r.width)-.5)*6}deg)`});
    c.addEventListener('pointerleave',()=>c.style.transform='')});
  $$('.mag').forEach(b=>{
    b.addEventListener('pointermove',e=>{const r=b.getBoundingClientRect();b.style.transform=`translate(${((e.clientX-r.left)/r.width-.5)*10}px,${((e.clientY-r.top)/r.height-.5)*8}px)`});
    b.addEventListener('pointerleave',()=>b.style.transform='')});
}

/* forms (demo only: wire to your real endpoint) */
$('#form').addEventListener('submit',e=>{e.preventDefault();$('#ok').classList.add('show');e.target.reset()});
$('#nl').addEventListener('submit',e=>{e.preventDefault();e.target.reset()});

/* hero 3D: particles forming a shield + lock */
(function(){
  const stage=$('#stage'),canvas=$('#c');let renderer;
  try{renderer=new THREE.WebGLRenderer({canvas,alpha:true,antialias:true})}catch(_){renderer=null}
  if(!renderer||!window.THREE){canvas.remove();stage.insertAdjacentHTML('beforeend','<svg class="fb" viewBox="0 0 24 24" style="padding:12%"><use href="#shield"/></svg>');return}
  renderer.setPixelRatio(Math.min(devicePixelRatio,2));
  const scene=new THREE.Scene(),camera=new THREE.PerspectiveCamera(45,1,.1,100);

  // gambar bentuk ke canvas 2D lalu ambil titik dari pikselnya
  const S=512,c2=document.createElement('canvas');c2.width=c2.height=S;const g=c2.getContext('2d');
  g.strokeStyle=g.fillStyle='#fff';g.lineWidth=16;g.lineJoin='round';
  g.stroke(new Path2D('M256 40 L440 110 V250 C440 360 360 440 256 480 C152 440 72 360 72 250 V110 Z'));
  g.fillRect(196,240,120,100);g.lineWidth=18;g.beginPath();g.arc(256,235,40,Math.PI,0);g.stroke();
  g.globalCompositeOperation='destination-out';g.beginPath();g.arc(256,282,13,0,7);g.fill();g.fillRect(251,285,10,30);
  const d=g.getImageData(0,0,S,S).data,pts=[];
  for(let y=0;y<S;y++)for(let x=0;x<S;x++)if(d[(y*S+x)*4+3]>128)pts.push([x,y]);

  const N=innerWidth<700?7000:20000,st=new Float32Array(N*3),tg=new Float32Array(N*3),rn=new Float32Array(N);
  for(let i=0;i<N;i++){const[p,q]=pts[(Math.random()*pts.length)|0];
    tg.set([(p/S-.5)*7,-(q/S-.5)*7,(Math.random()-.5)*.35],i*3);
    // awan awal berbentuk BOLA kecil (jauh di dalam viewport) supaya intro menyatu, tidak kotak
    const u=Math.random(),v=Math.random(),th=2*Math.PI*u,ph=Math.acos(2*v-1),rr=2.2*Math.cbrt(Math.random());
    st.set([rr*Math.sin(ph)*Math.cos(th),rr*Math.sin(ph)*Math.sin(th),rr*Math.cos(ph)],i*3);rn[i]=Math.random()}
  const geo=new THREE.BufferGeometry();
  geo.setAttribute('position',new THREE.BufferAttribute(st,3));geo.setAttribute('aTarget',new THREE.BufferAttribute(tg,3));geo.setAttribute('aRand',new THREE.BufferAttribute(rn,1));
  const mat=new THREE.ShaderMaterial({transparent:true,depthWrite:false,blending:THREE.AdditiveBlending,
    uniforms:{uTime:{value:0},uP:{value:reduce?1:0},uM:{value:new THREE.Vector2(999,999)},uPR:{value:renderer.getPixelRatio()}},
    vertexShader:`uniform float uTime,uP,uPR;uniform vec2 uM;attribute vec3 aTarget;attribute float aRand;varying float vY;varying float vP;
      void main(){float p=smoothstep(0.,1.,clamp(uP*1.3-aRand*.3,0.,1.));vec3 pos=mix(position,aTarget,p);
      pos.x+=sin(uTime*.6+aRand*20.)*.04;pos.y+=cos(uTime*.5+aRand*17.)*.04;
      vec2 dd=pos.xy-uM;float f=smoothstep(1.5,0.,length(dd));pos.xy+=normalize(dd+1e-4)*f*.8;pos.z+=f*.6;
      vY=aTarget.y;vP=p;vec4 mv=modelViewMatrix*vec4(pos,1.);gl_PointSize=(2.+aRand*2.)*uPR*(10./-mv.z);gl_Position=projectionMatrix*mv;}`,
    fragmentShader:`varying float vY;varying float vP;void main(){float a=smoothstep(.5,.1,length(gl_PointCoord-.5));
      float fade=smoothstep(0.2,0.65,vP);
      vec3 col=mix(vec3(.13,.83,.93),vec3(.39,.4,.95),smoothstep(-3.,3.,-vY));gl_FragColor=vec4(col,a*.85*fade);}`});
  const pts3=new THREE.Points(geo,mat);scene.add(pts3);

  const mouse=new THREE.Vector2(999,999),tilt={x:0,y:0};let halfH=4.14,asp=1;
  function resize(){const r=stage.getBoundingClientRect();if(!r.width)return;renderer.setSize(r.width,r.height,false);asp=r.width/r.height;
    camera.aspect=asp;camera.position.z=asp<1?10/asp:10;halfH=Math.tan(THREE.MathUtils.degToRad(22.5))*camera.position.z;camera.updateProjectionMatrix()}
  new ResizeObserver(resize).observe(stage);resize();
  if(!reduce)addEventListener('pointermove',e=>{const r=stage.getBoundingClientRect(),
    nx=(e.clientX-r.left)/r.width*2-1,ny=-((e.clientY-r.top)/r.height*2-1);
    mouse.set(nx*halfH*asp,ny*halfH);tilt.x=Math.max(-1,Math.min(1,nx));tilt.y=Math.max(-1,Math.min(1,ny))});

  let vis=true,intro=reduce?1:0,last=performance.now();const t0=last;
  new IntersectionObserver(es=>vis=es[0].isIntersecting).observe(stage);
  renderer.setAnimationLoop(now=>{const dt=(now-last)/1000;last=now;if(!vis)return;
    if(intro<1)intro=Math.min(1,intro+dt/2.4);
    mat.uniforms.uP.value=1-Math.pow(1-intro,3);mat.uniforms.uTime.value=(now-t0)/1000;mat.uniforms.uM.value.lerp(mouse,.15);
    pts3.rotation.y+=0.004*(1-intro)+(tilt.x*.25-pts3.rotation.y)*.06;pts3.rotation.x+=(-tilt.y*.15-pts3.rotation.x)*.06;
    renderer.render(scene,camera)});
})();
</script>
