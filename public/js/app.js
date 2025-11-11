// Animations & interactions
// Scroll reveal
const io = new IntersectionObserver((entries)=>{
  entries.forEach(e=>{
    if(e.isIntersecting){ e.target.classList.add('in'); io.unobserve(e.target); }
  });
},{threshold:0.12});
document.querySelectorAll('.reveal').forEach(el=>io.observe(el));

// Ripple on buttons with .ripple
document.addEventListener('click', (e)=>{
  const b = e.target.closest('.ripple');
  if(!b) return;
  const r = b.getBoundingClientRect();
  b.style.setProperty('--x', (e.clientX - r.left) + 'px');
  b.style.setProperty('--y', (e.clientY - r.top) + 'px');
  b.classList.add('active');
  setTimeout(()=>b.classList.remove('active'), 450);
});
