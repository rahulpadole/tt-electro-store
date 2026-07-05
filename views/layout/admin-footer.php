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
  const text=await r.text();
  let d={};
  try{ d=JSON.parse(text); }catch{ throw new Error(text||'Invalid server response'); }
  if(!r.ok||d.success===false) throw new Error(d.message||'Request failed');
  return d;
}
function adminNotifBell(){
  return {
    open:false, unread:0, items:[], _timer:null,
    init(){
      this.fetchData();
      this._timer=setInterval(()=>this.fetchData(),15000);
    },
    async fetchData(){
      try{
        const r=await apiFetch('/api/admin/notifications?limit=20');
        this.unread=r.data.unread_count;
        this.items=r.data.items;
      }catch(e){ /* silent — never break the admin UI on poll failure */ }
    },
    toggle(){
      this.open=!this.open;
      if(this.open) this.fetchData();
    },
    async openNotif(n){
      if(!n.is_read){
        n.is_read=1;
        this.unread=Math.max(0,this.unread-1);
        try{ await apiFetch(`/api/admin/notifications/${n.id}/read`,{method:'PATCH'}); }catch(e){}
      }
    },
    async markAllRead(){
      this.items.forEach(n=>n.is_read=1);
      this.unread=0;
      try{ await apiFetch('/api/admin/notifications/read-all',{method:'PATCH'}); }catch(e){}
    },
    timeAgo(dateStr){
      const s=Math.floor((Date.now()-new Date(dateStr.replace(' ','T')))/1000);
      if(s<60) return 'just now';
      if(s<3600) return Math.floor(s/60)+'m ago';
      if(s<86400) return Math.floor(s/3600)+'h ago';
      return Math.floor(s/86400)+'d ago';
    }
  };
}
</script>
</body>
</html>
