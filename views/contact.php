<?php
$pageTitle = 'Contact Us – Electronics Store in Amravati';
$pageDesc  = 'Contact TT Electro Store – your trusted electronic components store in Amravati, Maharashtra. Visit us at Trademark Complex, Gadge Nagar or call +91 7721892429.';
?>
<div class="max-w-5xl mx-auto px-4 py-12">

  <!-- Page header -->
  <div class="text-center mb-12">
    <p class="section-label justify-center"><i class="fa-solid fa-headset"></i> Get in touch</p>
    <h1 class="section-title text-3xl">Contact Us</h1>
    <p class="section-subtitle text-base mt-1">We're here to help! Reach out for support, partnerships, or feedback.</p>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-5 gap-7">

    <!-- Form -->
    <div class="lg:col-span-3 rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/8 shadow-sm p-7" x-data="contactForm()">
      <h2 class="font-bold text-slate-900 dark:text-white text-lg mb-5 flex items-center gap-2">
        <i class="fa-solid fa-paper-plane text-blue-600 dark:text-blue-400 text-base"></i>
        Send a Message
      </h2>
      <div class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Your Name <span class="text-red-500">*</span></label>
            <input type="text" x-model="form.name" placeholder="John Doe"
                   class="input-base w-full px-4 py-2.5 text-sm">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Email <span class="text-red-500">*</span></label>
            <input type="email" x-model="form.email" placeholder="you@email.com"
                   class="input-base w-full px-4 py-2.5 text-sm">
          </div>
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Phone</label>
          <input type="tel" x-model="form.phone" placeholder="+91 77218 92429"
                 class="input-base w-full px-4 py-2.5 text-sm">
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Subject</label>
          <select x-model="form.subject"
                  class="input-base w-full px-4 py-2.5 text-sm">
            <option value="">Select a subject</option>
            <?php foreach(['Order Support','Product Inquiry','3D Printing','Partnership','Feedback','Other'] as $s): ?>
            <option value="<?= $s ?>"><?= $s ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Message <span class="text-red-500">*</span></label>
          <textarea x-model="form.message" rows="5" placeholder="Tell us how we can help..."
                    class="input-base w-full px-4 py-2.5 text-sm resize-none"></textarea>
        </div>

        <div x-show="success"
             class="flex items-start gap-2 text-sm text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-500/10 px-4 py-3 rounded-xl border border-green-200 dark:border-green-500/20">
          <i class="fa-solid fa-circle-check mt-0.5 flex-shrink-0"></i>
          Message sent! We'll get back to you within 24 hours.
        </div>
        <div x-show="error" x-text="error"
             class="flex items-start gap-2 text-sm text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-500/10 px-4 py-3 rounded-xl border border-red-200 dark:border-red-500/20"></div>

        <button @click="submit()" :disabled="loading||success"
                class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm transition-all disabled:opacity-50 flex items-center justify-center gap-2 shadow-lg shadow-blue-500/20">
          <span x-show="!loading&&!success" class="flex items-center gap-2">
            <i class="fa-solid fa-paper-plane text-xs"></i> Send Message
          </span>
          <span x-show="loading" x-cloak class="flex items-center gap-2">
            <span class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
            Sending...
          </span>
          <span x-show="success" x-cloak class="flex items-center gap-2">
            <i class="fa-solid fa-check text-xs"></i> Sent!
          </span>
        </button>
      </div>
    </div>

    <!-- Contact info sidebar -->
    <div class="lg:col-span-2 space-y-3.5">
      <?php
      $contacts = [
        ['fa-location-dot','Visit Our Store','First Floor, Office No. 31, TT Electronics<br>Trademark Complex, near Gadge Baba Temple<br>Gadge Nagar, Amravati, Maharashtra 444603','blue'],
        ['fa-phone','Call / WhatsApp','+91 77218 92429<br><span class="text-xs">Mon–Sat, 9am–6pm IST</span>','green'],
        ['fa-envelope','Email Us','support@ttelectro.in<br>business@ttelectro.in','purple'],
        ['fa-clock','Response Time','We typically reply within 24 hours','amber'],
      ];
      foreach($contacts as [$icon,$title,$info,$color]): ?>
      <div class="flex items-start gap-3.5 p-4 rounded-2xl bg-white dark:bg-[hsl(222,47%,10%)] border border-slate-200 dark:border-white/6 shadow-sm">
        <span class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
          <?= match($color) {
            'blue'   => 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400',
            'green'  => 'bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400',
            'purple' => 'bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400',
            'amber'  => 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400',
            default  => 'bg-slate-100 dark:bg-white/8 text-slate-500',
          } ?>">
          <i class="fa-solid fa-<?= $icon ?> text-sm"></i>
        </span>
        <div>
          <p class="font-semibold text-slate-800 dark:text-slate-200 text-sm"><?= $title ?></p>
          <p class="text-slate-500 dark:text-slate-400 text-sm mt-0.5 leading-relaxed"><?= $info ?></p>
        </div>
      </div>
      <?php endforeach; ?>

      <!-- Google Maps embed -->
      <div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-white/6 shadow-sm">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.7!2d77.755!3d20.932!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjDCsDU1JzU1LjIiTiA3N8KwNDUnMTguMCJF!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin"
          width="100%" height="180" style="border:0;" allowfullscreen="" loading="lazy"
          title="TT Electro Store location – Trademark Complex, Gadge Nagar, Amravati"></iframe>
        <div class="px-3 py-2 bg-white dark:bg-[hsl(222,47%,10%)] text-xs text-slate-500 dark:text-slate-400">
          <i class="fa-solid fa-location-dot text-blue-500 mr-1"></i> Trademark Complex, Gadge Nagar, Amravati – 444603
        </div>
      </div>

      <!-- WhatsApp CTA -->
      <div class="rounded-2xl bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-500/20 p-5">
        <div class="flex items-center gap-2 mb-3">
          <i class="fa-brands fa-whatsapp text-green-600 dark:text-green-400 text-lg"></i>
          <p class="font-semibold text-slate-800 dark:text-slate-200 text-sm">Quick WhatsApp Support</p>
        </div>
        <p class="text-slate-500 dark:text-slate-400 text-xs mb-3">Get instant help from our team via WhatsApp.</p>
        <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>" target="_blank" rel="noopener"
           class="flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-green-500 hover:bg-green-600 text-white font-semibold text-sm transition-all hover:scale-[1.01]">
          <i class="fa-brands fa-whatsapp text-base"></i>
          Chat on WhatsApp
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Local Business Structured Data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ElectronicsStore",
  "name": "TT Electro Store",
  "description": "Premium electronic components, Arduino, Raspberry Pi, sensors, and 3D printing services in Amravati, Maharashtra.",
  "url": "<?= APP_URL ?>",
  "telephone": "+91-7721892429",
  "email": "support@ttelectro.in",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "First Floor, Office No. 31, Trademark Complex, near Gadge Baba Temple",
    "addressLocality": "Gadge Nagar, Amravati",
    "addressRegion": "Maharashtra",
    "postalCode": "444603",
    "addressCountry": "IN"
  },
  "openingHoursSpecification": {
    "@type": "OpeningHoursSpecification",
    "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],
    "opens": "09:00",
    "closes": "18:00"
  }
}
</script>

<script>
function contactForm(){
  return{
    form:{name:'',email:'',phone:'',subject:'',message:''},
    loading:false, error:'', success:false,
    async submit(){
      if(!this.form.name||!this.form.email||!this.form.message){this.error='Please fill all required fields';return;}
      this.loading=true; this.error='';
      try{
        await apiFetch('/api/contact',{method:'POST',body:JSON.stringify(this.form)});
        this.success=true;
        this.form={name:'',email:'',phone:'',subject:'',message:''};
      }catch(e){this.error=e.message;}
      this.loading=false;
    }
  }
}
</script>
