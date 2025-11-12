document.addEventListener('DOMContentLoaded', function(){
  var eye = '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/></svg>';
  var eyeOff = '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 3l18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M10.58 10.58A3 3 0 0012 15a3 3 0 002.42-4.42" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M2 12s4-7 10-7c2.27 0 4.22.83 5.81 2.02M22 12s-4 7-10 7c-2.27 0-4.22-.83-5.81-2.02" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
  var inputs = document.querySelectorAll('input.form-input[type="password"]');
  inputs.forEach(function(input){
    var group = input.closest('.form-group');
    if(!group) return;
    var btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'pw-toggle';
    btn.setAttribute('aria-label','Show password');
    btn.innerHTML = eyeOff;
    btn.addEventListener('click', function(){
      var isText = input.type === 'text';
      input.type = isText ? 'password' : 'text';
      btn.setAttribute('aria-label', isText ? 'Show password' : 'Hide password');
      btn.innerHTML = isText ? eyeOff : eye;
    });
    var wrap = document.createElement('span');
    wrap.className = 'pw-wrap';
    group.insertBefore(wrap, input);
    wrap.appendChild(input);
    wrap.appendChild(btn);
  });
});