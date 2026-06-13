    </main>
  </div>
</div>
<script>
function showToast(msg,type='info'){
  const c=document.getElementById('toastContainer');
  const t=document.createElement('div');
  t.className=`toast ${type}`;
  t.innerHTML=`<span>${msg}</span>`;
  c.appendChild(t);
  setTimeout(()=>{t.style.opacity='0';t.style.transition='.2s';setTimeout(()=>t.remove(),200);},3500);
}
async function apiFetch(url,opts={}){
  const r=await fetch(url,{headers:{'Content-Type':'application/json'},...opts});
  const d=await r.json();
  if(!r.ok) throw new Error(d.message||'Error');
  return d;
}
</script>
</body>
</html>
