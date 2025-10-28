// Pegar o arrastrar imagen al div#pastezone -> input hidden #image_base64 + preview
document.addEventListener('DOMContentLoaded', function(){
  const zone = document.getElementById('pastezone');
  const out  = document.getElementById('image_base64');
  const prev = document.getElementById('preview');
  if(zone){
    function toBase64(file){
      return new Promise((res,rej)=>{
        const r=new FileReader(); r.onload=()=>res(r.result); r.onerror=rej; r.readAsDataURL(file);
      });
    }
    zone.addEventListener('paste', async (e)=>{
      const items = e.clipboardData && e.clipboardData.items;
      if(!items) return;
      for(const it of items){
        if(it.type && it.type.startsWith('image/')){
          const file = it.getAsFile();
          const b64 = await toBase64(file);
          out.value = b64; prev.innerHTML=''; const img=new Image(); img.src=b64; prev.appendChild(img);
          e.preventDefault(); break;
        }
      }
    });
    zone.addEventListener('drop', async (e)=>{
      e.preventDefault();
      const f = e.dataTransfer.files[0];
      if(f && f.type.startsWith('image/')){
        const b64 = await toBase64(f);
        out.value=b64; prev.innerHTML=''; const img=new Image(); img.src=b64; prev.appendChild(img);
      }
    });
    zone.addEventListener('dragover', e=>e.preventDefault());
    zone.addEventListener('click', ()=>zone.focus());
  }

  // === Verificación de número de operación ===
  const opInput   = document.getElementById('operation_no');
  const feedback  = document.getElementById('op-feedback');
  const btnSave   = document.getElementById('btn-save');

  if(!opInput || !feedback){
    console.warn('[yape] No se encontró #operation_no o #op-feedback en el DOM');
    return;
  }

  // Utiliza una URL segura relativa al archivo actual (evita problemas de rutas)
  const checkUrl = new URL('./actions/check_operation.php', window.location.href);

  let typingTimer = null;
  let lastQueried = '';

  async function checkOp(value){
    try{
      checkUrl.searchParams.set('operation_no', value);
      console.log('[yape] Consultando', checkUrl.toString());
      const resp = await fetch(checkUrl.toString(), { credentials: 'same-origin' });
      if(!resp.ok){
        console.error('[yape] Respuesta HTTP no OK', resp.status);
        return;
      }
      const data = await resp.json();
      console.log('[yape] Resultado', data);

      if(!data.ok) return;

      if(data.exists){
        opInput.classList.remove('is-valid');
        opInput.classList.add('is-invalid');
        if(btnSave) btnSave.disabled = true;

        const nice = (s)=> s ? s.charAt(0).toUpperCase()+s.slice(1) : s;
        feedback.innerHTML =
          `<span class="text-danger fw-semibold">Duplicado</span>: ` +
          `ya existe con fecha <b>${data.deposit_date}</b> ${data.deposit_time ? 'a las <b>'+data.deposit_time+'</b> ' : ''}` +
          `(${nice(data.origin)}), chat <b>${data.chat}</b>, monto <b>S/ ${data.amount}</b>.`;
        feedback.classList.remove('text-muted');
      } else {
        opInput.classList.remove('is-invalid');
        opInput.classList.add('is-valid');
        if(btnSave) btnSave.disabled = false;
        feedback.textContent = 'Número disponible.';
        feedback.classList.add('text-muted');
      }
    }catch(err){
      console.error('[yape] Error consultando número de operación', err);
    }
  }

  function scheduleCheck(){
    const v = opInput.value.trim();
    if(!v) return;
    if(v === lastQueried) return;
    lastQueried = v;
    checkOp(v);
  }

  // Dispara en blur
  opInput.addEventListener('blur', scheduleCheck);

  // También dispara en change (algunos móviles disparan change pero no blur)
  opInput.addEventListener('change', scheduleCheck);

  // Y mientras escribe, con debounce (p. ej., 600ms sin teclear)
  opInput.addEventListener('input', ()=>{
    opInput.classList.remove('is-valid','is-invalid');
    if(btnSave) btnSave.disabled = false;
    if(feedback) feedback.textContent = '';
    clearTimeout(typingTimer);
    typingTimer = setTimeout(scheduleCheck, 600);
  });
});
