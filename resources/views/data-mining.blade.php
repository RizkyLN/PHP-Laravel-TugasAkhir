<?php
/**
 * Dummy Data Mining Tools — CRISP-DM (prototype)
 * Single-file: HTML + CSS + JS (+ PHP kecil). Bisa diletakkan di Laravel:
 *  - resources/views/crisp-dm.blade.php  (ganti ekstensi), atau
 *  - public/crisp-dm.php
 * Semua pemrosesan ML adalah SIMULASI di sisi browser.
 */
$appName = "DM Tools · CRISP-DM";
$csrf = function_exists('csrf_token') ? csrf_token() : '';
$serverTime = date('Y-m-d H:i');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="<?php echo htmlspecialchars($csrf); ?>">
<title><?php echo $appName; ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<style>
:root{
  --bg:#0a0f1a;--bg2:#0f1726;--card:#131d2f;--card2:#18243a;--line:#22324d;
  --steel:#5b8bc9;--steel2:#8fb4e3;--steel3:#c7d8ee;--text:#dbe5f3;--muted:#7f93b0;
  --ok:#4fc3a1;--warn:#e6b450;--bad:#e06c75;--sim:#e6b450;
  --metal:linear-gradient(135deg,#1b2a44 0%,#2a3f63 45%,#1a2740 100%);
  --r:10px;
}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Space Grotesk',system-ui,sans-serif;background:radial-gradient(1200px 600px at 80% -10%,#15223a 0%,var(--bg) 60%);color:var(--text);min-height:100vh;font-size:14px}
code,pre,.mono{font-family:'JetBrains Mono',monospace;font-size:12px}
.app{display:grid;grid-template-columns:250px 1fr;min-height:100vh}
aside{background:var(--bg2);border-right:1px solid var(--line);padding:20px 14px;position:sticky;top:0;height:100vh;overflow:auto}
.brand{font-weight:700;font-size:16px;letter-spacing:.3px;padding:0 8px 4px;color:var(--steel3)}
.brand small{display:block;color:var(--muted);font-weight:400;font-size:11px;margin-top:2px}
.prog{margin:16px 8px;height:6px;background:var(--line);border-radius:6px;overflow:hidden}
.prog>i{display:block;height:100%;background:linear-gradient(90deg,var(--steel),var(--steel2));transition:width .3s}
.progtxt{font-size:11px;color:var(--muted);margin:0 8px 14px}
.nav button{all:unset;display:flex;align-items:center;gap:10px;width:100%;box-sizing:border-box;padding:10px;border-radius:8px;cursor:pointer;color:var(--muted);margin-bottom:2px}
.nav button:hover{background:var(--card)}
.nav button.active{background:var(--metal);color:var(--steel3);box-shadow:inset 0 1px 0 #ffffff12}
.nav button.locked{opacity:.4;cursor:not-allowed}
.nav .n{width:24px;height:24px;border-radius:50%;border:1px solid var(--line);display:grid;place-items:center;font-size:11px;flex:none}
.nav .done .n{background:var(--ok);border-color:var(--ok);color:#06231a}
.nav .active .n{border-color:var(--steel2);color:var(--steel2)}
main{padding:28px 32px;max-width:1200px;width:100%}
h1{font-size:22px;font-weight:600;color:var(--steel3)}
h2{font-size:15px;font-weight:600;margin-bottom:12px;color:var(--steel3)}
.sub{color:var(--muted);margin:4px 0 20px}
.card{background:var(--card);border:1px solid var(--line);border-radius:var(--r);padding:18px;margin-bottom:16px}
.grid{display:grid;gap:14px}.g2{grid-template-columns:repeat(2,1fr)}.g3{grid-template-columns:repeat(3,1fr)}.g4{grid-template-columns:repeat(4,1fr)}
label{display:block;font-size:12px;color:var(--muted);margin-bottom:6px}
input[type=text],input[type=number],select,textarea{width:100%;background:var(--bg2);border:1px solid var(--line);color:var(--text);border-radius:8px;padding:9px 11px;font:inherit}
textarea{min-height:70px;resize:vertical}
input:focus,select:focus,textarea:focus{outline:none;border-color:var(--steel)}
.invalid{border-color:var(--bad)!important}
.err{color:var(--bad);font-size:11px;margin-top:4px;min-height:14px}
.chk{display:flex;align-items:center;gap:8px;color:var(--text);font-size:13px;margin:6px 0;cursor:pointer}
.chk input{accent-color:var(--steel)}
.btn{background:var(--card2);border:1px solid var(--line);color:var(--text);padding:9px 16px;border-radius:8px;cursor:pointer;font:inherit;font-weight:500}
.btn:hover{border-color:var(--steel)}
.btn.pri{background:linear-gradient(180deg,#5b8bc9,#3f6ca8);border-color:#6d9ad4;color:#fff}
.btn.pri:hover{filter:brightness(1.1)}
.btn:disabled{opacity:.4;cursor:not-allowed}
.btn.sm{padding:6px 10px;font-size:12px}
.foot{display:flex;justify-content:space-between;margin-top:20px;gap:10px}
.row{display:flex;gap:10px;align-items:center;flex-wrap:wrap}
.tbl{overflow:auto;max-height:340px;border:1px solid var(--line);border-radius:8px}
table{border-collapse:collapse;width:100%;font-size:12px}
th,td{padding:7px 10px;border-bottom:1px solid var(--line);text-align:left;white-space:nowrap}
th{background:var(--bg2);color:var(--steel2);font-weight:500;position:sticky;top:0}
tr:hover td{background:#ffffff05}
td.na{color:var(--bad);font-style:italic}
.kpi{background:var(--bg2);border:1px solid var(--line);border-radius:8px;padding:12px}
.kpi b{display:block;font-size:20px;color:var(--steel3);font-weight:600}
.kpi span{font-size:11px;color:var(--muted)}
.tag{display:inline-block;padding:2px 8px;border-radius:20px;font-size:11px;border:1px solid var(--line);color:var(--steel2)}
.tag.num{color:var(--ok);border-color:#2b5a4c}.tag.cat{color:var(--steel2)}.tag.txt{color:var(--warn);border-color:#5a4a26}
.sim{display:inline-flex;gap:6px;align-items:center;background:#e6b4501a;border:1px solid #e6b45055;color:var(--sim);padding:3px 10px;border-radius:20px;font-size:11px;font-weight:500}
.drop{border:1.5px dashed var(--line);border-radius:10px;padding:26px;text-align:center;color:var(--muted);cursor:pointer}
.drop:hover,.drop.over{border-color:var(--steel);color:var(--steel2)}
.alert{padding:10px 12px;border-radius:8px;font-size:13px;margin-bottom:12px;border:1px solid}
.alert.info{background:#5b8bc914;border-color:#5b8bc955;color:var(--steel2)}
.alert.warn{background:#e6b45014;border-color:#e6b45055;color:var(--warn)}
.alert.ok{background:#4fc3a114;border-color:#4fc3a155;color:var(--ok)}
.chart{position:relative;height:260px}
.cm{display:grid;gap:3px;font-size:12px}
.cm div{padding:12px;text-align:center;border-radius:4px}
.log{background:var(--bg2);border:1px solid var(--line);border-radius:8px;padding:10px;max-height:180px;overflow:auto}
.log div{padding:2px 0;color:var(--muted)}.log div b{color:var(--ok);font-weight:500}
pre{background:var(--bg2);border:1px solid var(--line);border-radius:8px;padding:12px;overflow:auto;color:var(--steel3)}
.tabs{display:flex;gap:4px;border-bottom:1px solid var(--line);margin-bottom:14px}
.tabs button{all:unset;padding:8px 14px;cursor:pointer;color:var(--muted);border-bottom:2px solid transparent}
.tabs button.on{color:var(--steel2);border-color:var(--steel2)}
.hide{display:none!important}
.algo{display:grid;grid-template-columns:repeat(auto-fill,minmax(170px,1fr));gap:8px}
.algo label{all:unset;border:1px solid var(--line);border-radius:8px;padding:10px;cursor:pointer;font-size:13px;display:flex;gap:8px;align-items:center}
.algo label:has(input:checked){border-color:var(--steel);background:var(--card2)}
.toast{position:fixed;bottom:20px;right:20px;background:var(--card2);border:1px solid var(--steel);padding:10px 16px;border-radius:8px;opacity:0;transform:translateY(10px);transition:.25s;pointer-events:none;z-index:9}
.toast.show{opacity:1;transform:none}
@media(max-width:900px){.app{grid-template-columns:1fr}aside{position:static;height:auto}.nav{display:flex;overflow:auto;gap:4px}.nav button{width:auto;white-space:nowrap}.g2,.g3,.g4{grid-template-columns:1fr 1fr}main{padding:18px}}
@media(max-width:560px){.g2,.g3,.g4{grid-template-columns:1fr}}
</style>
</head>
<body>
<div class="app">
<aside>
  <div class="brand">◆ DM Tools<small>CRISP-DM Prototype · <?php echo $serverTime; ?></small></div>
  <div class="prog"><i id="progBar" style="width:0"></i></div>
  <div class="progtxt" id="progTxt">0 dari 6 tahap selesai</div>
  <nav class="nav" id="nav"></nav>
  <div style="margin:18px 8px 0"><span class="sim">⚠ Hasil ML = simulasi</span></div>
  <div style="margin:10px 8px"><button class="btn sm" onclick="resetAll()">↺ Reset proyek</button></div>
</aside>
<main>

<!-- 1. BUSINESS UNDERSTANDING -->
<section class="step" data-step="0">
  <h1>1 · Business Understanding</h1>
  <p class="sub">Definisikan konteks bisnis sebelum menyentuh data.</p>
  <div class="card">
    <div class="grid g2">
      <div><label>Nama proyek *</label><input type="text" id="bu_name" placeholder="cth. Prediksi churn pelanggan"><div class="err"></div></div>
      <div><label>Target analisis *</label><input type="text" id="bu_target" placeholder="cth. Klasifikasi pelanggan berisiko churn"><div class="err"></div></div>
      <div><label>Tujuan bisnis *</label><textarea id="bu_goal" placeholder="Apa yang ingin dicapai?"></textarea><div class="err"></div></div>
      <div><label>Masalah bisnis *</label><textarea id="bu_problem" placeholder="Masalah yang ingin diselesaikan"></textarea><div class="err"></div></div>
      <div><label>Rencana proyek</label><textarea id="bu_plan" placeholder="Langkah, timeline, sumber daya"></textarea><div class="err"></div></div>
      <div><label>Kriteria keberhasilan *</label><textarea id="bu_success" placeholder="cth. Akurasi ≥ 80%, recall churn ≥ 70%"></textarea><div class="err"></div></div>
    </div>
  </div>
  <div class="foot"><span></span><button class="btn pri" onclick="next()">Lanjut →</button></div>
</section>

<!-- 2. DATA UNDERSTANDING -->
<section class="step hide" data-step="1">
  <h1>2 · Data Understanding</h1>
  <p class="sub">Unggah dataset CSV/Excel lalu eksplorasi struktur & kualitasnya.</p>
  <div class="card">
    <div class="drop" id="drop">⇪ Seret file ke sini atau <u>klik untuk memilih</u> (.csv, .xlsx, .xls)<input type="file" id="file" accept=".csv,.xlsx,.xls" hidden></div>
    <div class="row" style="margin-top:12px"><span style="color:var(--muted);font-size:12px">Tidak punya data?</span><button class="btn sm" onclick="loadSample()">Gunakan dataset contoh (churn, 300 baris)</button><span id="fileInfo" class="mono" style="color:var(--steel2)"></span></div>
  </div>
  <div id="duBody" class="hide">
    <div class="grid g4" id="duKpi" style="margin-bottom:16px"></div>
    <div class="card"><div class="tabs" id="duTabs">
      <button class="on" data-t="prev">Preview</button><button data-t="stat">Statistik</button><button data-t="type">Tipe Atribut</button><button data-t="viz">Visualisasi</button><button data-t="qual">Kualitas Data</button></div>
      <div data-p="prev"><div class="tbl" id="duPreview"></div><p style="color:var(--muted);font-size:11px;margin-top:6px">Menampilkan 50 baris pertama.</p></div>
      <div data-p="stat" class="hide"><div class="tbl" id="duStats"></div></div>
      <div data-p="type" class="hide"><div class="tbl" id="duTypes"></div></div>
      <div data-p="viz" class="hide">
        <div class="row" style="margin-bottom:10px"><label style="margin:0">Kolom:</label><select id="vizCol" style="width:220px"></select></div>
        <div class="grid g2"><div class="chart"><canvas id="cDist"></canvas></div><div class="chart"><canvas id="cMiss"></canvas></div></div>
      </div>
      <div data-p="qual" class="hide" id="duQual"></div>
    </div>
  </div>
  <div class="foot"><button class="btn" onclick="prev()">← Kembali</button><button class="btn pri" onclick="next()">Lanjut →</button></div>
</section>

<!-- 3. DATA PREPARATION -->
<section class="step hide" data-step="2">
  <h1>3 · Data Preparation</h1>
  <p class="sub">Bersihkan, seleksi, transformasi, dan ekstraksi fitur.</p>
  <div class="grid g2">
    <div class="card"><h2>Cleaning</h2>
      <label>Missing / null / empty</label>
      <select id="p_missing"><option value="none">Biarkan</option><option value="drop">Hapus baris</option><option value="mean" selected>Imputasi mean (numerik) / modus (kategori)</option><option value="median">Imputasi median / modus</option><option value="const">Isi konstanta</option></select>
      <input type="text" id="p_const" placeholder="Nilai konstanta (jika dipilih)" style="margin-top:6px">
      <label class="chk"><input type="checkbox" id="p_dup" checked> Hapus baris duplikat</label>
      <label style="margin-top:8px">Noisy data / outlier</label>
      <div class="row"><select id="p_noise" style="flex:1"><option value="none">Tidak ditangani</option><option value="iqr" selected>Clip IQR</option><option value="z">Hapus |z| &gt; ambang</option></select><input type="number" id="p_noiseP" value="1.5" step="0.1" style="width:90px" title="Faktor IQR / ambang z"></div>
    </div>
    <div class="card"><h2>Transformasi</h2>
      <label>Normalisasi numerik</label>
      <select id="p_norm"><option value="none">Tidak</option><option value="minmax" selected>Min-Max [0,1]</option><option value="z">Z-score (standardisasi)</option></select>
      <label style="margin-top:10px">Encoding kategori</label>
      <select id="p_enc"><option value="none">Tidak</option><option value="label" selected>Label encoding</option><option value="onehot">One-hot encoding</option></select>
      <label style="margin-top:10px">Tokenisasi teks</label>
      <label class="chk"><input type="checkbox" id="p_tok"> Aktifkan (lowercase, hapus tanda baca)</label>
      <label class="chk"><input type="checkbox" id="p_stop"> Hapus stopword (ID/EN)</label>
    </div>
  </div>
  <div class="grid g2">
    <div class="card"><h2>Seleksi kolom</h2><div id="p_cols" style="max-height:200px;overflow:auto"></div><div class="err" id="p_colsErr"></div></div>
    <div class="card"><h2>Ekstraksi fitur</h2>
      <select id="p_fe"><option value="none">Tidak ada</option><option value="tfidf">TF-IDF (kolom teks)</option><option value="bow">Bag of Words</option><option value="pca">PCA (simulasi, numerik)</option></select>
      <div class="grid g2" style="margin-top:10px"><div><label>Max fitur / komponen</label><input type="number" id="p_feK" value="10" min="1" max="50"></div><div><label>Kolom sumber</label><select id="p_feCol"></select></div></div>
      <p style="color:var(--muted);font-size:11px;margin-top:8px">TF-IDF & BoW dihitung nyata di browser; PCA adalah proyeksi tersimulasi.</p>
    </div>
  </div>
  <div class="card"><div class="row" style="justify-content:space-between"><h2 style="margin:0">Hasil preprocessing</h2><button class="btn pri" onclick="applyPrep()">▶ Terapkan preprocessing</button></div>
    <div id="prepOut" style="margin-top:12px;color:var(--muted)">Belum diterapkan.</div></div>
  <div class="foot"><button class="btn" onclick="prev()">← Kembali</button><button class="btn pri" onclick="next()">Lanjut →</button></div>
</section>

<!-- 4. MODELING -->
<section class="step hide" data-step="3">
  <h1>4 · Modeling</h1>
  <p class="sub">Pilih metode data mining terlebih dahulu, lalu algoritma yang kompatibel.</p>
  <div class="card"><h2>1) Metode data mining *</h2>
    <select id="m_method"><option value="">— Pilih metode —</option><option value="classification">Klasifikasi</option><option value="regression">Regresi</option><option value="clustering">Clustering</option><option value="association">Asosiasi</option><option value="anomaly">Anomaly Detection</option><option value="deep">Deep Learning</option><option value="ensemble">Ensemble Methods</option></select><div class="err" id="m_methodErr"></div>
  </div>
  <div id="m_rest" class="hide">
    <div class="card"><h2>2) Algoritma *</h2><div class="algo" id="m_algos"></div><div class="err" id="m_algoErr"></div></div>
    <div class="card"><h2>3) Konfigurasi</h2><div class="grid g3" id="m_params"></div></div>
    <div class="card"><div class="row" style="justify-content:space-between"><h2 style="margin:0">Training</h2><button class="btn pri" id="m_run" onclick="runModel()">▶ Latih model</button></div>
      <div id="m_out" style="margin-top:12px;color:var(--muted)">Model belum dilatih.</div></div>
  </div>
  <div class="foot"><button class="btn" onclick="prev()">← Kembali</button><button class="btn pri" onclick="next()">Lanjut →</button></div>
</section>

<!-- 5. EVALUATION -->
<section class="step hide" data-step="4">
  <h1>5 · Evaluation</h1>
  <p class="sub">Metrik performa sesuai jenis model, beserta interpretasi. <span class="sim">⚠ SIMULASI</span></p>
  <div id="evBody"></div>
  <div class="foot"><button class="btn" onclick="prev()">← Kembali</button><button class="btn pri" onclick="next()">Lanjut →</button></div>
</section>

<!-- 6. DEPLOYMENT -->
<section class="step hide" data-step="5">
  <h1>6 · Deployment</h1>
  <p class="sub">Ringkasan, artefak unduhan, dan panduan integrasi.</p>
  <div id="depBody"></div>
  <div class="foot"><button class="btn" onclick="prev()">← Kembali</button><button class="btn pri" onclick="finish()">✓ Selesai</button></div>
</section>

</main>
</div>
<div class="toast" id="toast"></div>

<script>
/* ================= STATE ================= */
const STEPS=["Business Understanding","Data Understanding","Data Preparation","Modeling","Evaluation","Deployment"];
const S={step:0,done:[0,0,0,0,0,0],bu:{},file:"",raw:[],cols:[],types:{},data:[],dcols:[],prep:null,model:null,eval:null};
let charts={};
const $=id=>document.getElementById(id);
const toast=m=>{const t=$('toast');t.textContent=m;t.classList.add('show');clearTimeout(t._);t._=setTimeout(()=>t.classList.remove('show'),2200)};
const isNA=v=>v===null||v===undefined||(typeof v==='string'&&(v.trim()===''||/^(na|nan|null|none|\?)$/i.test(v.trim())));
const num=v=>typeof v==='number'?v:(isNA(v)?NaN:Number(String(v).replace(',','.')));
const fmt=(v,d=3)=>v===null||v===undefined||Number.isNaN(v)?'—':(typeof v==='number'?(Number.isInteger(v)?v:v.toFixed(d)):v);
const esc=s=>String(s??'').replace(/[&<>"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));
let seed=42;const rnd=()=>{seed=(seed*16807)%2147483647;return(seed-1)/2147483646};
const clamp=(x,a,b)=>Math.max(a,Math.min(b,x));

/* ================= NAV ================= */
function renderNav(){
  $('nav').innerHTML=STEPS.map((s,i)=>{const lock=i>0&&!S.done.slice(0,i).every(Boolean)&&i>S.step;
    return `<button class="${i===S.step?'active':''} ${S.done[i]?'done':''} ${lock?'locked':''}" onclick="go(${i})"><span class="n">${S.done[i]?'✓':i+1}</span>${s}</button>`}).join('');
  const d=S.done.filter(Boolean).length;$('progBar').style.width=(d/6*100)+'%';$('progTxt').textContent=`${d} dari 6 tahap selesai`;
}
function show(i){document.querySelectorAll('.step').forEach(s=>s.classList.toggle('hide',+s.dataset.step!==i));S.step=i;renderNav();window.scrollTo(0,0);
  if(i===2)renderPrepCols(); if(i===3)renderModelSetup(); if(i===4)renderEval(); if(i===5)renderDeploy();}
function go(i){if(i<=S.step||S.done.slice(0,i).every(Boolean))show(i);else toast('Selesaikan tahap sebelumnya dulu');}
function prev(){if(S.step>0)show(S.step-1)}
function next(){const ok=validate(S.step);if(!ok)return;S.done[S.step]=1;save();show(S.step+1)}
function finish(){S.done[5]=1;renderNav();save();toast('Proyek CRISP-DM selesai ✓')}

function validate(i){
  if(i===0){let ok=true;['bu_name','bu_target','bu_goal','bu_problem','bu_success'].forEach(id=>{const e=$(id),v=e.value.trim(),bad=v.length<3;e.classList.toggle('invalid',bad);e.nextElementSibling.textContent=bad?'Wajib diisi (min. 3 karakter)':'';if(bad)ok=false});
    ['name','target','goal','problem','plan','success'].forEach(k=>S.bu[k]=$('bu_'+k).value.trim());if(!ok)toast('Lengkapi field wajib');return ok}
  if(i===1){if(!S.raw.length){toast('Unggah dataset terlebih dahulu');return false}return true}
  if(i===2){if(!S.prep){toast('Klik "Terapkan preprocessing" dulu');return false}return true}
  if(i===3){if(!S.model){toast('Latih model terlebih dahulu');return false}return true}
  return true;
}

/* ================= DATA LOAD ================= */
function parseCSV(text){
  const sep=(text.split('\n')[0].match(/;/g)||[]).length>(text.split('\n')[0].match(/,/g)||[]).length?';':',';
  const rows=[];let row=[],f='',q=false;
  for(let i=0;i<text.length;i++){const c=text[i];
    if(q){if(c==='"'){if(text[i+1]==='"'){f+='"';i++}else q=false}else f+=c}
    else if(c==='"')q=true;else if(c===sep){row.push(f);f=''}else if(c==='\n'||c==='\r'){if(c==='\r'&&text[i+1]==='\n')i++;row.push(f);rows.push(row);row=[];f=''}else f+=c}
  if(f||row.length){row.push(f);rows.push(row)}
  const hdr=rows.shift().map((h,i)=>h.trim()||'col_'+(i+1));
  return {cols:hdr,rows:rows.filter(r=>r.some(x=>x!=='')).map(r=>Object.fromEntries(hdr.map((h,i)=>[h,r[i]??''])))};
}
function handleFile(f){
  if(!f)return;const ext=f.name.split('.').pop().toLowerCase();
  if(!['csv','xlsx','xls'].includes(ext)){toast('Format harus CSV/XLSX/XLS');return}
  if(f.size>15e6){toast('Maksimum 15 MB untuk prototype');return}
  const rd=new FileReader();
  rd.onload=e=>{try{let res;
    if(ext==='csv')res=parseCSV(e.target.result);
    else{if(!window.XLSX){toast('Library Excel gagal dimuat');return}const wb=XLSX.read(e.target.result,{type:'array'});const js=XLSX.utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]],{defval:''});res={cols:Object.keys(js[0]||{}),rows:js}}
    if(!res.rows.length){toast('Dataset kosong');return}
    setData(res.cols,res.rows.slice(0,20000),f.name)}catch(err){toast('Gagal membaca file: '+err.message)}};
  ext==='csv'?rd.readAsText(f):rd.readAsArrayBuffer(f);
}
function loadSample(){
  seed=7;const cities=['Jakarta','Bandung','Surabaya','Medan','Makassar'],plans=['Basic','Standard','Premium'];
  const cm=['layanan cepat dan ramah','harga terlalu mahal','sinyal sering hilang','aplikasi mudah digunakan','pelayanan buruk lambat','puas dengan paket premium','tagihan tidak sesuai','internet stabil cepat'];
  const rows=[];for(let i=0;i<300;i++){const age=Math.round(18+rnd()*50),ten=Math.round(rnd()*72),mon=+(20+rnd()*100).toFixed(2),plan=plans[Math.floor(rnd()*3)],calls=Math.floor(rnd()*10);
    const p=0.15+(calls>5?.35:0)+(ten<12?.25:0)-(plan==='Premium'?.15:0);const churn=rnd()<p?'Yes':'No';
    rows.push({customer_id:'C'+String(1000+i),age:rnd()<.04?'':age,city:cities[Math.floor(rnd()*5)],plan,tenure_months:ten,monthly_charge:rnd()<.03?'':(rnd()<.02?mon*8:mon),support_calls:calls,total_spent:+(mon*ten).toFixed(2),feedback:cm[Math.floor(rnd()*cm.length)]+(churn==='Yes'&&rnd()<.5?' ingin berhenti':''),churn})}
  rows.push({...rows[3]},{...rows[10]},{...rows[25]});
  setData(Object.keys(rows[0]),rows,'sample_churn.csv');
}
function detectTypes(cols,rows){const t={};cols.forEach(c=>{const v=rows.map(r=>r[c]).filter(x=>!isNA(x));const n=v.filter(x=>!Number.isNaN(num(x))).length;const u=new Set(v).size;
  const avgLen=v.reduce((a,x)=>a+String(x).length,0)/(v.length||1);
  t[c]=v.length&&n/v.length>.9?(u<=2?'binary-num':'numeric'):(avgLen>15&&u>v.length*.05?'text':(/id$/i.test(c)&&u>v.length*.9?'identifier':'categorical'))});return t}
function setData(cols,rows,name){
  S.raw=rows;S.cols=cols;S.file=name;S.types=detectTypes(cols,rows);S.prep=null;S.model=null;S.done.fill(0,1);
  $('fileInfo').textContent=`✓ ${name} · ${rows.length} baris × ${cols.length} kolom`;renderDU();renderNav();toast('Dataset dimuat');save(false);
}

/* ================= DATA UNDERSTANDING ================= */
function colStats(rows,c,type){const all=rows.map(r=>r[c]);const miss=all.filter(isNA).length;const v=all.filter(x=>!isNA(x));
  const o={col:c,type,count:v.length,missing:miss,unique:new Set(v.map(String)).size};
  if(type==='numeric'||type==='binary-num'){const n=v.map(num).filter(x=>!Number.isNaN(x)).sort((a,b)=>a-b);const m=n.reduce((a,b)=>a+b,0)/n.length;
    const q=p=>n[Math.floor((n.length-1)*p)];o.mean=m;o.std=Math.sqrt(n.reduce((a,b)=>a+(b-m)**2,0)/(n.length-1||1));o.min=n[0];o.q1=q(.25);o.median=q(.5);o.q3=q(.75);o.max=n[n.length-1];
    const iqr=o.q3-o.q1;o.outliers=n.filter(x=>x<o.q1-1.5*iqr||x>o.q3+1.5*iqr).length;o.skew=n.reduce((a,b)=>a+((b-m)/(o.std||1))**3,0)/n.length}
  else{const f={};v.forEach(x=>f[x]=(f[x]||0)+1);const top=Object.entries(f).sort((a,b)=>b[1]-a[1])[0];o.top=top?top[0]:'—';o.freq=top?top[1]:0}
  return o}
function table(cols,rows,max=50){return `<table><thead><tr>${cols.map(c=>`<th>${esc(c)}</th>`).join('')}</tr></thead><tbody>${rows.slice(0,max).map(r=>`<tr>${cols.map(c=>isNA(r[c])?'<td class="na">null</td>':`<td>${esc(typeof r[c]==='number'?fmt(r[c],4):String(r[c]).slice(0,60))}</td>`).join('')}</tr>`).join('')}</tbody></table>`}
function dupCount(rows,cols){const s=new Set();let d=0;rows.forEach(r=>{const k=cols.map(c=>r[c]).join('\u0001');s.has(k)?d++:s.add(k)});return d}
function typeTag(t){return `<span class="tag ${t.includes('num')?'num':t==='text'?'txt':'cat'}">${t}</span>`}
function renderDU(){
  $('duBody').classList.remove('hide');const R=S.raw,C=S.cols;
  const st=C.map(c=>colStats(R,c,S.types[c]));S.stats=st;
  const miss=st.reduce((a,s)=>a+s.missing,0),dup=dupCount(R,C),cells=R.length*C.length;
  $('duKpi').innerHTML=[['Baris',R.length],['Kolom',C.length],['Missing cell',miss+` (${(miss/cells*100).toFixed(1)}%)`],['Duplikat',dup]].map(k=>`<div class="kpi"><b>${k[1]}</b><span>${k[0]}</span></div>`).join('');
  $('duPreview').innerHTML=table(C,R);
  const sc=['col','type','count','missing','unique','mean','std','min','q1','median','q3','max','top','freq'];
  $('duStats').innerHTML=`<table><thead><tr>${sc.map(c=>`<th>${c}</th>`).join('')}</tr></thead><tbody>${st.map(s=>`<tr>${sc.map(c=>c==='type'?`<td>${typeTag(s[c])}</td>`:`<td>${esc(fmt(s[c]))}</td>`).join('')}</tr>`).join('')}</tbody></table>`;
  $('duTypes').innerHTML=`<table><thead><tr><th>Atribut</th><th>Tipe terdeteksi</th><th>Skala</th><th>Contoh nilai</th></tr></thead><tbody>${C.map(c=>{const t=S.types[c];const ex=R.find(r=>!isNA(r[c]))?.[c];return `<tr><td>${esc(c)}</td><td>${typeTag(t)}</td><td>${t==='numeric'?'Rasio/Interval':t==='binary-num'?'Biner':t==='text'?'Teks bebas':t==='identifier'?'ID (abaikan)':'Nominal'}</td><td class="mono">${esc(String(ex).slice(0,40))}</td></tr>`}).join('')}</tbody></table>`;
  const issues=[];st.forEach(s=>{if(s.missing)issues.push(['warn',`<b>${esc(s.col)}</b>: ${s.missing} nilai kosong (${(s.missing/R.length*100).toFixed(1)}%)`]);if(s.outliers)issues.push(['warn',`<b>${esc(s.col)}</b>: ${s.outliers} outlier (aturan IQR 1.5×)`]);if(s.unique===1)issues.push(['warn',`<b>${esc(s.col)}</b>: konstan, tidak informatif`]);if(s.type==='identifier')issues.push(['info',`<b>${esc(s.col)}</b>: terdeteksi sebagai ID, sebaiknya tidak dipakai sebagai fitur`])});
  if(dup)issues.push(['warn',`${dup} baris duplikat terdeteksi`]);
  const score=Math.max(0,100-miss/cells*300-dup/R.length*200-issues.length*2).toFixed(0);
  $('duQual').innerHTML=`<div class="grid g3" style="margin-bottom:12px"><div class="kpi"><b>${score}/100</b><span>Skor kualitas data (heuristik)</span></div><div class="kpi"><b>${(100-miss/cells*100).toFixed(1)}%</b><span>Kelengkapan</span></div><div class="kpi"><b>${(100-dup/R.length*100).toFixed(1)}%</b><span>Keunikan baris</span></div></div>`+(issues.length?issues.map(i=>`<div class="alert ${i[0]}">${i[1]}</div>`).join(''):'<div class="alert ok">Tidak ditemukan masalah kualitas utama.</div>');
  $('vizCol').innerHTML=C.filter(c=>S.types[c]!=='identifier').map(c=>`<option>${esc(c)}</option>`).join('');drawViz();
}
function mkChart(id,cfg){if(charts[id])charts[id].destroy();if(!window.Chart)return;
  Chart.defaults.color='#7f93b0';Chart.defaults.borderColor='#22324d';Chart.defaults.font.family='Space Grotesk';
  charts[id]=new Chart($(id),{...cfg,options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:cfg.legend||false}},...(cfg.options||{})}})}
function drawViz(){const c=$('vizCol').value;if(!c)return;const t=S.types[c];const v=S.raw.map(r=>r[c]).filter(x=>!isNA(x));
  if(t==='numeric'){const n=v.map(num).filter(x=>!isNaN(x));const mn=Math.min(...n),mx=Math.max(...n),b=12,w=(mx-mn)/b||1;const h=Array(b).fill(0);n.forEach(x=>h[Math.min(b-1,Math.floor((x-mn)/w))]++);
    mkChart('cDist',{type:'bar',data:{labels:h.map((_,i)=>fmt(mn+i*w,1)),datasets:[{label:'Frekuensi',data:h,backgroundColor:'#5b8bc9'}]},options:{plugins:{title:{display:true,text:'Distribusi (histogram): '+c}}}})}
  else{const f={};v.forEach(x=>{const k=String(x).slice(0,25);f[k]=(f[k]||0)+1});const e=Object.entries(f).sort((a,b)=>b[1]-a[1]).slice(0,12);
    mkChart('cDist',{type:'bar',data:{labels:e.map(x=>x[0]),datasets:[{label:'Jumlah',data:e.map(x=>x[1]),backgroundColor:'#8fb4e3'}]},options:{indexAxis:'y',plugins:{title:{display:true,text:'Frekuensi kategori: '+c}}}})}
  mkChart('cMiss',{type:'bar',data:{labels:S.stats.map(s=>s.col),datasets:[{label:'Missing',data:S.stats.map(s=>s.missing),backgroundColor:'#e06c75'}]},options:{plugins:{title:{display:true,text:'Missing value per kolom'}}}})}

/* ================= DATA PREPARATION ================= */
function renderPrepCols(){
  const prevSel=S.prep?.selected;$('p_cols').innerHTML=S.cols.map(c=>`<label class="chk"><input type="checkbox" value="${esc(c)}" ${(prevSel?prevSel.includes(c):S.types[c]!=='identifier')?'checked':''}> ${esc(c)} ${typeTag(S.types[c])}</label>`).join('');
  const txt=S.cols.filter(c=>S.types[c]==='text');$('p_feCol').innerHTML=(txt.length?txt:S.cols).map(c=>`<option>${esc(c)}</option>`).join('');
}
const STOP=new Set('dan yang di ke dari untuk dengan ini itu atau pada adalah the a an of to and is in for on with ingin sangat'.split(' '));
function tokenize(s,stop){let t=String(s).toLowerCase().replace(/[^\p{L}\p{N}\s]/gu,' ').split(/\s+/).filter(Boolean);return stop?t.filter(w=>!STOP.has(w)):t}
function applyPrep(){
  const sel=[...$('p_cols').querySelectorAll('input:checked')].map(i=>i.value);
  if(sel.length<1){$('p_colsErr').textContent='Pilih minimal 1 kolom';return}$('p_colsErr').textContent='';
  const cfg={missing:$('p_missing').value,constVal:$('p_const').value,dup:$('p_dup').checked,noise:$('p_noise').value,noiseP:+$('p_noiseP').value||1.5,norm:$('p_norm').value,enc:$('p_enc').value,tok:$('p_tok').checked,stop:$('p_stop').checked,fe:$('p_fe').value,feK:clamp(+$('p_feK').value||10,1,50),feCol:$('p_feCol').value,selected:sel};
  if(cfg.missing==='const'&&!cfg.constVal){toast('Isi nilai konstanta');$('p_const').classList.add('invalid');return}$('p_const').classList.remove('invalid');
  const log=[];let rows=S.raw.map(r=>Object.fromEntries(sel.map(c=>[c,r[c]])));const n0=rows.length;
  const T={...S.types};const numC=sel.filter(c=>T[c]==='numeric'||T[c]==='binary-num'),catC=sel.filter(c=>T[c]==='categorical'),txtC=sel.filter(c=>T[c]==='text');
  // missing
  if(cfg.missing==='drop'){rows=rows.filter(r=>sel.every(c=>!isNA(r[c])));log.push(`Hapus baris dengan missing: <b>${n0-rows.length}</b> baris dihapus`)}
  else if(cfg.missing!=='none'){let filled=0;sel.forEach(c=>{const st=colStats(rows,c,T[c]);let fill;
      if(cfg.missing==='const')fill=numC.includes(c)?num(cfg.constVal)||0:cfg.constVal;else if(numC.includes(c))fill=cfg.missing==='median'?st.median:st.mean;else fill=st.top;
      rows.forEach(r=>{if(isNA(r[c])){r[c]=fill;filled++}})});log.push(`Imputasi (${cfg.missing}): <b>${filled}</b> sel diisi`)}
  numC.forEach(c=>rows.forEach(r=>{if(!isNA(r[c]))r[c]=num(r[c])}));
  if(cfg.dup){const b=rows.length,s=new Set();rows=rows.filter(r=>{const k=JSON.stringify(r);if(s.has(k))return false;s.add(k);return true});log.push(`Hapus duplikat: <b>${b-rows.length}</b> baris`)}
  if(cfg.noise!=='none'){let cnt=0;numC.forEach(c=>{const st=colStats(rows,c,'numeric');if(cfg.noise==='iqr'){const lo=st.q1-cfg.noiseP*(st.q3-st.q1),hi=st.q3+cfg.noiseP*(st.q3-st.q1);rows.forEach(r=>{if(typeof r[c]==='number'&&(r[c]<lo||r[c]>hi)){r[c]=clamp(r[c],lo,hi);cnt++}})}
      else{const b=rows.length;rows=rows.filter(r=>typeof r[c]!=='number'||Math.abs((r[c]-st.mean)/(st.std||1))<=cfg.noiseP);cnt+=b-rows.length}});log.push(`Noisy data (${cfg.noise}, p=${cfg.noiseP}): <b>${cnt}</b> nilai ditangani`)}
  // keep raw target copy before transforms
  const orig=rows.map(r=>({...r}));
  // tokenization
  if(cfg.tok&&txtC.length){txtC.forEach(c=>rows.forEach(r=>r[c]=tokenize(r[c],cfg.stop).join(' ')));log.push(`Tokenisasi ${txtC.length} kolom teks${cfg.stop?' + hapus stopword':''}`)}
  // feature extraction
  let newCols=[];
  if((cfg.fe==='tfidf'||cfg.fe==='bow')&&cfg.feCol){const docs=rows.map(r=>tokenize(r[cfg.feCol],cfg.stop));const df={};docs.forEach(d=>new Set(d).forEach(w=>df[w]=(df[w]||0)+1));
    const vocab=Object.entries(df).sort((a,b)=>b[1]-a[1]).slice(0,cfg.feK).map(x=>x[0]);const N=docs.length;
    rows.forEach((r,i)=>{const d=docs[i];vocab.forEach(w=>{const tf=d.filter(x=>x===w).length;r[(cfg.fe==='tfidf'?'tfidf_':'bow_')+w]=cfg.fe==='tfidf'?+(tf/(d.length||1)*Math.log((1+N)/(1+df[w])+1)).toFixed(4):tf})});
    newCols=vocab.map(w=>(cfg.fe==='tfidf'?'tfidf_':'bow_')+w);log.push(`${cfg.fe.toUpperCase()} pada "${esc(cfg.feCol)}": <b>${vocab.length}</b> fitur baru`)}
  if(cfg.fe==='pca'&&numC.length>=2){const k=Math.min(cfg.feK,numC.length);seed=11;const W=Array.from({length:k},()=>numC.map(()=>rnd()*2-1));
    rows.forEach(r=>W.forEach((w,j)=>r['PC'+(j+1)]=+numC.reduce((a,c,i)=>a+(r[c]||0)*w[i],0).toFixed(4)));newCols=W.map((_,j)=>'PC'+(j+1));log.push(`PCA (simulasi): ${k} komponen`)}
  // normalization
  if(cfg.norm!=='none'){numC.forEach(c=>{const st=colStats(rows,c,'numeric');rows.forEach(r=>{if(typeof r[c]==='number')r[c]=+(cfg.norm==='minmax'?(r[c]-st.min)/((st.max-st.min)||1):(r[c]-st.mean)/(st.std||1)).toFixed(4)})});log.push(`Normalisasi ${cfg.norm} pada ${numC.length} kolom numerik`)}
  // encoding
  const encMap={};
  if(cfg.enc==='label'){catC.forEach(c=>{const u=[...new Set(rows.map(r=>r[c]))].sort();encMap[c]=u;rows.forEach(r=>r[c]=u.indexOf(r[c]))});log.push(`Label encoding pada ${catC.length} kolom kategori`)}
  if(cfg.enc==='onehot'){catC.forEach(c=>{const u=[...new Set(rows.map(r=>r[c]))].slice(0,15);encMap[c]=u;rows.forEach(r=>{u.forEach(v=>r[c+'_'+v]=r[c]===v?1:0);delete r[c]})});log.push(`One-hot encoding pada ${catC.length} kolom kategori`)}
  S.data=rows;S.orig=orig;S.dcols=Object.keys(rows[0]||{});S.prep={...cfg,log,encMap,numC,catC,txtC,newCols,before:n0,after:rows.length};S.model=null;S.done.fill(0,2);renderNav();
  $('prepOut').innerHTML=`<div class="grid g4" style="margin-bottom:12px">${[['Baris sebelum',n0],['Baris sesudah',rows.length],['Kolom akhir',S.dcols.length],['Fitur baru',newCols.length]].map(k=>`<div class="kpi"><b>${k[1]}</b><span>${k[0]}</span></div>`).join('')}</div>
   <div class="log" style="margin-bottom:12px">${log.map(l=>`<div>✓ ${l}</div>`).join('')||'<div>Tidak ada operasi.</div>'}</div><div class="tbl">${table(S.dcols,rows,30)}</div>`;
  toast('Preprocessing diterapkan');save(false);
}

/* ================= MODELING ================= */
const ALGOS={
  classification:['Decision Tree (C4.5)','Naive Bayes','K-Nearest Neighbors','Logistic Regression','Support Vector Machine'],
  regression:['Linear Regression','Ridge Regression','Lasso Regression','Decision Tree Regressor','Support Vector Regression'],
  clustering:['K-Means','K-Medoids','DBSCAN','Hierarchical (Agglomerative)'],
  association:['Apriori','FP-Growth','Eclat'],
  anomaly:['Isolation Forest','Local Outlier Factor','One-Class SVM','Z-Score / IQR'],
  deep:['MLP (Feed-forward NN)','1D-CNN','LSTM','Autoencoder'],
  ensemble:['Random Forest','Gradient Boosting','XGBoost','AdaBoost','Voting / Stacking']};
const SUP=['classification','regression','deep','ensemble'];
function P(id,lab,html){return `<div><label>${lab}</label>${html}</div>`}
function renderModelSetup(){const m=$('m_method');m.onchange=onMethod;if(S.model){m.value=S.model.method;onMethod(true)}}
function onMethod(keep){const m=$('m_method').value;$('m_methodErr').textContent='';$('m_rest').classList.toggle('hide',!m);if(!m)return;
  $('m_algos').innerHTML=ALGOS[m].map((a,i)=>`<label><input type="radio" name="algo" value="${a}" ${keep===true&&S.model?.algo===a?'checked':''}> ${a}</label>`).join('');
  const cols=S.dcols,catT=Object.keys(S.raw[0]).filter(c=>S.prep.selected.includes(c));let h='';
  if(SUP.includes(m)){h+=P('','Kolom target *',`<select id="m_target"><option value="">— pilih —</option>${(m==='regression'?S.prep.numC:cols).map(c=>`<option ${c==='churn'?'selected':''}>${esc(c)}</option>`).join('')}</select><div class="err" id="m_targetErr"></div>`);
    h+=P('','Train-test split (% train)','<input type="number" id="m_split" value="80" min="50" max="95">');
    h+=P('','Cross-validation (k-fold, 0 = off)','<input type="number" id="m_cv" value="5" min="0" max="20">');}
  if(m==='classification'||m==='ensemble'||m==='deep'){h+=P('','Penanganan imbalanced','<select id="m_imb"><option value="none">Tidak</option><option value="smote">SMOTE (oversampling)</option><option value="under">Random undersampling</option><option value="weight">Class weight</option></select>')}
  if(m==='classification')h+=P('','Parameter algoritma (k / max_depth / C)','<input type="number" id="m_hp" value="5" min="1">');
  if(m==='regression')h+=P('','Regularisasi α','<input type="number" id="m_hp" value="1" step="0.1" min="0">');
  if(m==='clustering'){h+=P('','Jumlah cluster k *','<input type="number" id="m_k" value="3" min="2" max="10">')+P('','Max iterasi','<input type="number" id="m_iter" value="100" min="10">')+P('','Jarak','<select id="m_dist"><option>Euclidean</option><option>Manhattan</option></select>')}
  if(m==='association'){h+=P('','Kolom item (kategori/teks) *',`<select id="m_item">${catT.filter(c=>['categorical','text'].includes(S.types[c])).map(c=>`<option>${esc(c)}</option>`).join('')}</select>`)+P('','Min support','<input type="number" id="m_sup" value="0.05" step="0.01" min="0.01" max="1">')+P('','Min confidence','<input type="number" id="m_conf" value="0.5" step="0.05" min="0.05" max="1">')}
  if(m==='anomaly')h+=P('','Contamination (proporsi anomali)','<input type="number" id="m_cont" value="0.05" step="0.01" min="0.01" max="0.5">');
  if(m==='deep')h+=P('','Epochs','<input type="number" id="m_ep" value="30" min="1" max="500">')+P('','Hidden units','<input type="number" id="m_hu" value="64" min="4">')+P('','Learning rate','<input type="number" id="m_lr" value="0.001" step="0.0001">');
  if(m==='ensemble')h+=P('','n_estimators','<input type="number" id="m_ne" value="100" min="5">')+P('','Max depth','<input type="number" id="m_md" value="6" min="1">');
  h+=P('','Random seed','<input type="number" id="m_seed" value="42">');
  $('m_params').innerHTML=h;if(!keep){S.model=null;S.done.fill(0,3);renderNav();$('m_out').innerHTML='Model belum dilatih.'}}
const gv=id=>$(id)?$(id).value:undefined;
function numMatrix(exclude){const fc=S.dcols.filter(c=>c!==exclude&&S.data.every(r=>typeof r[c]==='number'));return {fc,X:S.data.map(r=>fc.map(c=>r[c]))}}
function kmeans(X,k,it){seed=+gv('m_seed')||42;let C=Array.from({length:k},()=>X[Math.floor(rnd()*X.length)].slice());let lab=[];
  for(let t=0;t<it;t++){lab=X.map(x=>{let b=0,bd=1e18;C.forEach((c,j)=>{const d=c.reduce((a,v,i)=>a+(v-x[i])**2,0);if(d<bd){bd=d;b=j}});return b});
    const nc=C.map((c,j)=>{const m=X.filter((_,i)=>lab[i]===j);return m.length?c.map((_,d)=>m.reduce((a,x)=>a+x[d],0)/m.length):c});if(JSON.stringify(nc)===JSON.stringify(C))break;C=nc}return {C,lab}}
function silhouette(X,lab,k){const idx=X.map((_,i)=>i).filter((_,i)=>i%Math.ceil(X.length/200)===0);const d=(a,b)=>Math.sqrt(a.reduce((s,v,i)=>s+(v-b[i])**2,0));
  return idx.reduce((s,i)=>{const own=X.filter((_,j)=>lab[j]===lab[i]&&j!==i);const a=own.reduce((t,x)=>t+d(X[i],x),0)/(own.length||1);let b=1e18;for(let c=0;c<k;c++){if(c===lab[i])continue;const o=X.filter((_,j)=>lab[j]===c);if(o.length)b=Math.min(b,o.reduce((t,x)=>t+d(X[i],x),0)/o.length)}return s+((b-a)/Math.max(a,b)||0)},0)/idx.length}
function runModel(){
  const m=$('m_method').value;if(!m){$('m_methodErr').textContent='Pilih metode dulu';return}
  const algo=document.querySelector('input[name=algo]:checked')?.value;if(!algo){$('m_algoErr').textContent='Pilih satu algoritma';return}$('m_algoErr').textContent='';
  const p={};$('m_params').querySelectorAll('input,select').forEach(e=>p[e.id.replace('m_','')]=e.type==='number'?+e.value:e.value);
  if(SUP.includes(m)&&!p.target){$('m_targetErr').textContent='Target wajib dipilih';return}
  if(p.split&&(p.split<50||p.split>95)){toast('Split harus 50–95%');return}
  $('m_run').disabled=true;$('m_out').innerHTML='<div class="prog" style="margin:0"><i id="tp" style="width:0"></i></div><p style="margin-top:8px">Melatih model (simulasi)…</p>';
  let w=0;const iv=setInterval(()=>{w+=12;$('tp')&&($('tp').style.width=w+'%');if(w>=100){clearInterval(iv);finishTrain(m,algo,p)}},70);
}
function finishTrain(m,algo,p){
  seed=(p.seed||42)+algo.length*13+S.data.length;const N=S.data.length,ev={};const qBoost=(S.prep.missing!=='none'?.02:0)+(S.prep.norm!=='none'?.015:0)+(S.prep.dup?.01:0);
  const base={'Decision Tree (C4.5)':.78,'Naive Bayes':.74,'K-Nearest Neighbors':.76,'Logistic Regression':.79,'Support Vector Machine':.81,'MLP (Feed-forward NN)':.82,'1D-CNN':.8,'LSTM':.79,'Autoencoder':.75,'Random Forest':.85,'Gradient Boosting':.86,'XGBoost':.87,'AdaBoost':.82,'Voting / Stacking':.86}[algo]||.8;
  if(['classification','deep','ensemble'].includes(m)){
    const tv=(S.orig||S.data).map(r=>String(r[p.target]));const classes=[...new Set(tv)].slice(0,6);const nTest=Math.round(N*(1-(p.split||80)/100));
    const counts=classes.map(c=>tv.filter(x=>x===c).length);const imb=Math.max(...counts)/Math.min(...counts);
    const acc=clamp(base+qBoost+(rnd()-.5)*.04-(classes.length>2?.05:0),.5,.98);
    const recBoost=p.imb&&p.imb!=='none'?.06:0;
    const cm=classes.map((c,i)=>{const tot=Math.round(nTest*counts[i]/N)||1;const r=clamp(acc+(i===classes.length-1||counts[i]===Math.min(...counts)?-.08*Math.min(imb/2,1.5)+recBoost:.02)+(rnd()-.5)*.04,.3,.99);const tp=Math.round(tot*r);const row=classes.map(()=>0);row[i]=tp;let rest=tot-tp;classes.forEach((_,j)=>{if(j!==i&&rest>0){const v=j===classes.length-1||j===(i+1)%classes.length?rest:Math.round(rest*rnd());row[j]+=v;rest-=v}});return row});
    const per=classes.map((c,i)=>{const tp=cm[i][i],fn=cm[i].reduce((a,b)=>a+b,0)-tp,fp=cm.reduce((a,r)=>a+r[i],0)-tp;const pr=tp/(tp+fp||1),rc=tp/(tp+fn||1);return {cls:c,precision:pr,recall:rc,f1:2*pr*rc/(pr+rc||1),support:tp+fn}});
    const tot=cm.flat().reduce((a,b)=>a+b,0),cor=cm.reduce((a,r,i)=>a+r[i],0);
    Object.assign(ev,{type:'cls',classes,cm,per,accuracy:cor/tot,precision:per.reduce((a,x)=>a+x.precision,0)/per.length,recall:per.reduce((a,x)=>a+x.recall,0)/per.length,f1:per.reduce((a,x)=>a+x.f1,0)/per.length,auc:clamp(cor/tot+.05+rnd()*.03,.5,.99),nTest,imb});
    ev.cv=p.cv>0?Array.from({length:p.cv},()=>clamp(ev.accuracy+(rnd()-.5)*.06,0,1)):null;
    if(m==='deep'){ev.loss=Array.from({length:p.ep||30},(_,i)=>+(1.2*Math.exp(-i/((p.ep||30)/4))+.15+rnd()*.05).toFixed(3));ev.vloss=ev.loss.map((l,i)=>+(l+.05+i/(p.ep||30)*.08*rnd()).toFixed(3))}
    if(m==='ensemble'){const {fc}=numMatrix(p.target);ev.imp=fc.slice(0,10).map(c=>[c,rnd()]).sort((a,b)=>b[1]-a[1])}
  }
  if(m==='regression'){const y=S.data.map(r=>r[p.target]);const {fc,X}=numMatrix(p.target);const n=y.length,my=y.reduce((a,b)=>a+b,0)/n;const sd=Math.sqrt(y.reduce((a,b)=>a+(b-my)**2,0)/n);
    const r2=clamp(({'Linear Regression':.72,'Ridge Regression':.73,'Lasso Regression':.7,'Decision Tree Regressor':.76,'Support Vector Regression':.78}[algo])+qBoost+(rnd()-.5)*.05,.2,.97);const nTest=Math.round(n*(1-(p.split||80)/100));
    const idx=Array.from({length:Math.min(nTest,150)},()=>Math.floor(rnd()*n));const pts=idx.map(i=>{const a=y[i];return {x:a,y:+(a+(rnd()-.5)*2*sd*Math.sqrt(1-r2)*1.7).toFixed(4)}});
    const err=pts.map(q=>q.y-q.x);Object.assign(ev,{type:'reg',r2,mae:err.reduce((a,b)=>a+Math.abs(b),0)/err.length,rmse:Math.sqrt(err.reduce((a,b)=>a+b*b,0)/err.length),mape:err.reduce((a,b,i)=>a+Math.abs(b/(pts[i].x||1)),0)/err.length*100,pts,nTest,coef:fc.slice(0,10).map(c=>[c,+(rnd()*2-1).toFixed(3)])});
    ev.cv=p.cv>0?Array.from({length:p.cv},()=>clamp(r2+(rnd()-.5)*.08,0,1)):null}
  if(m==='clustering'){const {fc,X}=numMatrix();if(fc.length<1){toast('Tidak ada fitur numerik. Aktifkan encoding/normalisasi.');$('m_run').disabled=false;return}
    const k=clamp(p.k||3,2,10);const {C,lab}=kmeans(X,k,p.iter||100);const sizes=Array.from({length:k},(_,j)=>lab.filter(l=>l===j).length);
    const sil=silhouette(X,lab,k);const elbow=[2,3,4,5,6,7,8].map(kk=>{const r=kmeans(X,kk,30);return r.lab.reduce((a,l,i)=>a+r.C[l].reduce((s,v,d)=>s+(v-X[i][d])**2,0),0)});
    S.data.forEach((r,i)=>r.__cluster=lab[i]);Object.assign(ev,{type:'clu',k,sizes,sil:algo==='K-Means'?sil:clamp(sil+(rnd()-.5)*.1,-1,1),dbi:clamp(1.6-sil*1.5+rnd()*.2,.2,3),ch:+(N*(sil+1)*12).toFixed(1),elbow,fc,C,lab,X,real:algo==='K-Means'})}
  if(m==='association'){const col=p.item;const tx=(S.orig||S.raw).map(r=>new Set([...tokenize(r[col],true),...(S.prep.catC.filter(c=>c!==col).slice(0,2).map(c=>c+'='+r[c]))]));const n=tx.length;
    const c1={};tx.forEach(t=>t.forEach(i=>c1[i]=(c1[i]||0)+1));const f1=Object.keys(c1).filter(i=>c1[i]/n>=p.sup);const rules=[];
    for(let a=0;a<f1.length;a++)for(let b=0;b<f1.length;b++){if(a===b)continue;const A=f1[a],B=f1[b];const sAB=tx.filter(t=>t.has(A)&&t.has(B)).length/n;if(sAB<p.sup)continue;const conf=sAB/(c1[A]/n);if(conf<p.conf)continue;rules.push({ant:A,con:B,support:sAB,confidence:conf,lift:conf/(c1[B]/n)})}
    rules.sort((x,y)=>y.lift-x.lift);Object.assign(ev,{type:'asso',rules:rules.slice(0,30),nItems:f1.length,nTx:n,real:true})}
  if(m==='anomaly'){const {fc,X}=numMatrix();if(!fc.length){toast('Butuh fitur numerik');$('m_run').disabled=false;return}const mu=fc.map((_,d)=>X.reduce((a,x)=>a+x[d],0)/X.length),sd=fc.map((_,d)=>Math.sqrt(X.reduce((a,x)=>a+(x[d]-mu[d])**2,0)/X.length)||1);
    const sc=X.map(x=>+(Math.sqrt(x.reduce((a,v,d)=>a+((v-mu[d])/sd[d])**2,0)/fc.length)+rnd()*.1).toFixed(4));const th=[...sc].sort((a,b)=>b-a)[Math.max(0,Math.floor(sc.length*(p.cont||.05))-1)];
    const flag=sc.map(s=>s>=th);Object.assign(ev,{type:'anom',scores:sc,th,nAnom:flag.filter(Boolean).length,flag,precisionEst:clamp(.7+rnd()*.2,0,1),fc})}
  S.model={method:m,algo,params:p,trainedAt:new Date().toISOString(),nRows:N,features:S.dcols.filter(c=>c!==p.target&&c!=='__cluster')};S.eval=ev;
  $('m_run').disabled=false;S.done.fill(0,3);renderNav();
  $('m_out').innerHTML=`<div class="alert ok">✓ Model <b>${algo}</b> (${m}) selesai dilatih pada ${N} baris, ${S.model.features.length} fitur. <span class="sim">${ev.real?'Dihitung di browser':'⚠ Metrik disimulasikan'}</span></div><p>Lanjutkan ke tahap Evaluation untuk melihat hasil.</p>`;save(false);
}

/* ================= EVALUATION ================= */
const kpis=a=>`<div class="grid g4" style="margin-bottom:16px">${a.map(k=>`<div class="kpi"><b>${k[1]}</b><span>${k[0]}</span></div>`).join('')}</div>`;
const pct=x=>(x*100).toFixed(1)+'%';
function interp(){const e=S.eval,bu=S.bu;let t=[];
  if(e.type==='cls'){t.push(`Model mencapai akurasi <b>${pct(e.accuracy)}</b> dan F1 makro <b>${pct(e.f1)}</b> pada ${e.nTest} data uji.`);const w=[...e.per].sort((a,b)=>a.recall-b.recall)[0];t.push(`Kelas dengan recall terendah: <b>${esc(w.cls)}</b> (${pct(w.recall)}) — ${e.imb>1.5?'kemungkinan akibat data tidak seimbang (rasio '+e.imb.toFixed(1)+':1); pertimbangkan SMOTE/class weight.':'distribusi kelas relatif seimbang.'}`);t.push(`ROC-AUC ${e.auc.toFixed(3)} menunjukkan kemampuan diskriminasi ${e.auc>.85?'sangat baik':e.auc>.75?'baik':'cukup'}.`)}
  if(e.type==='reg')t.push(`R² = <b>${e.r2.toFixed(3)}</b>: model menjelaskan ${pct(e.r2)} variasi target. Rata-rata kesalahan absolut (MAE) ${fmt(e.mae)} dan RMSE ${fmt(e.rmse)}; RMSE ≫ MAE menandakan ada error besar sesekali.`);
  if(e.type==='clu')t.push(`Silhouette <b>${e.sil.toFixed(3)}</b> → struktur cluster ${e.sil>.5?'kuat':e.sil>.25?'moderat':'lemah/tumpang tindih'}. Ukuran cluster: ${e.sizes.join(', ')}. Gunakan grafik elbow untuk memvalidasi pilihan k=${e.k}.`);
  if(e.type==='asso')t.push(e.rules.length?`Ditemukan <b>${e.rules.length}</b> aturan. Aturan terkuat: <b>${esc(e.rules[0].ant)} ⇒ ${esc(e.rules[0].con)}</b> (lift ${e.rules[0].lift.toFixed(2)}). Lift &gt; 1 menunjukkan asosiasi positif.`:'Tidak ada aturan memenuhi ambang. Turunkan min support/confidence.');
  if(e.type==='anom')t.push(`<b>${e.nAnom}</b> data (${pct(e.nAnom/e.scores.length)}) ditandai anomali dengan ambang skor ${e.th.toFixed(3)}. Tinjau manual sampel teratas sebelum ditindaklanjuti.`);
  t.push(`Bandingkan dengan kriteria keberhasilan: <i>"${esc(bu.success)}"</i>.`);return t.map(x=>`<p style="margin-bottom:6px">• ${x}</p>`).join('')}
function renderEval(){const e=S.eval,M=S.model;if(!e){$('evBody').innerHTML='<div class="alert warn">Belum ada model.</div>';return}let h=`<div class="alert info">Model: <b>${M.algo}</b> · Metode: <b>${M.method}</b> · ${M.nRows} baris ${e.real?'· <span class="sim">sebagian dihitung nyata</span>':'· <span class="sim">⚠ Metrik SIMULASI</span>'}</div>`;
  if(e.type==='cls'){h+=kpis([['Accuracy',pct(e.accuracy)],['Precision (macro)',pct(e.precision)],['Recall (macro)',pct(e.recall)],['F1-score (macro)',pct(e.f1)]]);
    const mx=Math.max(...e.cm.flat());h+=`<div class="grid g2"><div class="card"><h2>Confusion Matrix</h2><div class="cm" style="grid-template-columns:90px repeat(${e.classes.length},1fr)"><div></div>${e.classes.map(c=>`<div style="color:var(--muted)">Pred ${esc(c)}</div>`).join('')}${e.cm.map((r,i)=>`<div style="color:var(--muted)">Aktual ${esc(e.classes[i])}</div>`+r.map((v,j)=>`<div style="background:rgba(91,139,201,${.1+v/mx*.8});${i===j?'font-weight:700':''}">${v}</div>`).join('')).join('')}</div></div>
      <div class="card"><h2>ROC Curve · AUC ${e.auc.toFixed(3)}</h2><div class="chart"><canvas id="eA"></canvas></div></div></div>
      <div class="card"><h2>Metrik per kelas</h2><div class="tbl"><table><thead><tr><th>Kelas</th><th>Precision</th><th>Recall</th><th>F1</th><th>Support</th></tr></thead><tbody>${e.per.map(x=>`<tr><td>${esc(x.cls)}</td><td>${fmt(x.precision)}</td><td>${fmt(x.recall)}</td><td>${fmt(x.f1)}</td><td>${x.support}</td></tr>`).join('')}</tbody></table></div></div>`;
    if(e.cv||e.loss||e.imp)h+=`<div class="card"><h2>${e.loss?'Learning curve (loss)':e.imp?'Feature importance':'Cross-validation per fold'}</h2><div class="chart"><canvas id="eB"></canvas></div>${e.cv?`<p style="color:var(--muted);margin-top:6px">CV ${e.cv.length}-fold: mean ${pct(e.cv.reduce((a,b)=>a+b,0)/e.cv.length)}</p>`:''}</div>`}
  if(e.type==='reg'){h+=kpis([['R²',e.r2.toFixed(3)],['MAE',fmt(e.mae)],['RMSE',fmt(e.rmse)],['MAPE',e.mape.toFixed(1)+'%']]);h+=`<div class="grid g2"><div class="card"><h2>Prediksi vs Aktual</h2><div class="chart"><canvas id="eA"></canvas></div></div><div class="card"><h2>Koefisien / pengaruh fitur</h2><div class="chart"><canvas id="eB"></canvas></div></div></div>`}
  if(e.type==='clu'){h+=kpis([['Silhouette score',e.sil.toFixed(3)],['Davies-Bouldin',e.dbi.toFixed(3)],['Calinski-Harabasz',e.ch],['Jumlah cluster',e.k]]);h+=`<div class="grid g2"><div class="card"><h2>Scatter cluster (2 fitur pertama)</h2><div class="chart"><canvas id="eA"></canvas></div></div><div class="card"><h2>Elbow (inertia vs k)</h2><div class="chart"><canvas id="eB"></canvas></div></div></div><div class="card"><h2>Ukuran cluster</h2><div class="tbl"><table><thead><tr><th>Cluster</th><th>Anggota</th><th>%</th></tr></thead><tbody>${e.sizes.map((s,i)=>`<tr><td>C${i}</td><td>${s}</td><td>${pct(s/M.nRows)}</td></tr>`).join('')}</tbody></table></div></div>`}
  if(e.type==='asso'){h+=kpis([['Transaksi',e.nTx],['Frequent items',e.nItems],['Aturan',e.rules.length],['Lift maks',e.rules[0]?e.rules[0].lift.toFixed(2):'—']]);h+=`<div class="card"><h2>Aturan asosiasi</h2><div class="tbl"><table><thead><tr><th>Antecedent</th><th>⇒ Consequent</th><th>Support</th><th>Confidence</th><th>Lift</th></tr></thead><tbody>${e.rules.map(r=>`<tr><td>${esc(r.ant)}</td><td>${esc(r.con)}</td><td>${fmt(r.support)}</td><td>${fmt(r.confidence)}</td><td>${fmt(r.lift)}</td></tr>`).join('')||'<tr><td colspan=5>Tidak ada aturan</td></tr>'}</tbody></table></div></div><div class="card"><h2>Support vs Confidence</h2><div class="chart"><canvas id="eA"></canvas></div></div>`}
  if(e.type==='anom'){h+=kpis([['Anomali terdeteksi',e.nAnom],['Proporsi',pct(e.nAnom/e.scores.length)],['Ambang skor',e.th.toFixed(3)],['Est. precision',pct(e.precisionEst)]]);h+=`<div class="card"><h2>Skor anomali per data</h2><div class="chart"><canvas id="eA"></canvas></div></div>`}
  h+=`<div class="card"><h2>Interpretasi & ringkasan performa</h2>${interp()}</div>`;$('evBody').innerHTML=h;
  setTimeout(()=>{if(e.type==='cls'){const pts=[{x:0,y:0}];for(let i=1;i<=20;i++){const x=i/20;pts.push({x,y:+Math.min(1,Math.pow(x,(1-e.auc)/e.auc)).toFixed(3)})}
      mkChart('eA',{type:'line',data:{datasets:[{label:'ROC',data:pts,borderColor:'#8fb4e3',fill:true,backgroundColor:'#5b8bc922',pointRadius:0},{label:'Random',data:[{x:0,y:0},{x:1,y:1}],borderColor:'#7f93b0',borderDash:[4,4],pointRadius:0}]},options:{scales:{x:{type:'linear',min:0,max:1,title:{display:true,text:'FPR'}},y:{min:0,max:1,title:{display:true,text:'TPR'}}}}});
      if(e.loss)mkChart('eB',{type:'line',legend:true,data:{labels:e.loss.map((_,i)=>i+1),datasets:[{label:'train loss',data:e.loss,borderColor:'#8fb4e3'},{label:'val loss',data:e.vloss,borderColor:'#e6b450'}]}});
      else if(e.imp)mkChart('eB',{type:'bar',data:{labels:e.imp.map(x=>x[0]),datasets:[{data:e.imp.map(x=>x[1]),backgroundColor:'#5b8bc9'}]},options:{indexAxis:'y'}});
      else if(e.cv)mkChart('eB',{type:'bar',data:{labels:e.cv.map((_,i)=>'Fold '+(i+1)),datasets:[{data:e.cv,backgroundColor:'#5b8bc9'}]},options:{scales:{y:{min:0,max:1}}}})}
    if(e.type==='reg'){mkChart('eA',{type:'scatter',data:{datasets:[{data:e.pts,backgroundColor:'#8fb4e3'}]},options:{scales:{x:{title:{display:true,text:'Aktual'}},y:{title:{display:true,text:'Prediksi'}}}}});mkChart('eB',{type:'bar',data:{labels:e.coef.map(x=>x[0]),datasets:[{data:e.coef.map(x=>x[1]),backgroundColor:e.coef.map(x=>x[1]>0?'#4fc3a1':'#e06c75')}]},options:{indexAxis:'y'}})}
    if(e.type==='clu'){const pal=['#5b8bc9','#4fc3a1','#e6b450','#e06c75','#c7d8ee','#8fb4e3','#a78bfa','#f472b6','#94a3b8','#22d3ee'];mkChart('eA',{type:'scatter',legend:true,data:{datasets:Array.from({length:e.k},(_,j)=>({label:'C'+j,data:e.X.filter((_,i)=>e.lab[i]===j).map(x=>({x:x[0],y:x[1]??0})),backgroundColor:pal[j]}))},options:{scales:{x:{title:{display:true,text:e.fc[0]}},y:{title:{display:true,text:e.fc[1]||''}}}}});mkChart('eB',{type:'line',data:{labels:[2,3,4,5,6,7,8],datasets:[{data:e.elbow,borderColor:'#8fb4e3'}]}})}
    if(e.type==='asso')mkChart('eA',{type:'bubble',data:{datasets:[{data:e.rules.map(r=>({x:r.support,y:r.confidence,r:Math.min(20,r.lift*4)})),backgroundColor:'#5b8bc988'}]},options:{scales:{x:{title:{display:true,text:'Support'}},y:{title:{display:true,text:'Confidence'}}}}});
    if(e.type==='anom')mkChart('eA',{type:'bar',data:{labels:e.scores.map((_,i)=>i),datasets:[{data:e.scores,backgroundColor:e.flag.map(f=>f?'#e06c75':'#5b8bc9')}]},options:{scales:{x:{display:false}}}});
  },30)}

/* ================= DEPLOYMENT ================= */
function download(name,content,type){const b=new Blob([content],{type});const a=document.createElement('a');a.href=URL.createObjectURL(b);a.download=name;a.click();setTimeout(()=>URL.revokeObjectURL(a.href),500);toast('Mengunduh '+name)}
function toCSV(rows){const c=Object.keys(rows[0]||{});return [c.join(','),...rows.map(r=>c.map(k=>{const v=String(r[k]??'');return /[",\n]/.test(v)?'"'+v.replace(/"/g,'""')+'"':v}).join(','))].join('\n')}
function artifact(){const e={...S.eval};delete e.X;delete e.lab;delete e.flag;delete e.scores;delete e.pts;return {format:'dm-tools-model/v1',simulated:!e.real,project:S.bu,dataset:{file:S.file,rows:S.raw.length,columns:S.cols,types:S.types},preprocessing:{...S.prep,log:S.prep.log.map(l=>l.replace(/<[^>]+>/g,''))},model:S.model,evaluation:e}}
function pyCode(){const M=S.model,map={'Decision Tree (C4.5)':'from sklearn.tree import DecisionTreeClassifier as Model','Naive Bayes':'from sklearn.naive_bayes import GaussianNB as Model','K-Nearest Neighbors':'from sklearn.neighbors import KNeighborsClassifier as Model','Logistic Regression':'from sklearn.linear_model import LogisticRegression as Model','Support Vector Machine':'from sklearn.svm import SVC as Model','Random Forest':'from sklearn.ensemble import RandomForestClassifier as Model','Gradient Boosting':'from sklearn.ensemble import GradientBoostingClassifier as Model','XGBoost':'from xgboost import XGBClassifier as Model','AdaBoost':'from sklearn.ensemble import AdaBoostClassifier as Model','Linear Regression':'from sklearn.linear_model import LinearRegression as Model','Ridge Regression':'from sklearn.linear_model import Ridge as Model','Lasso Regression':'from sklearn.linear_model import Lasso as Model','K-Means':'from sklearn.cluster import KMeans as Model','DBSCAN':'from sklearn.cluster import DBSCAN as Model','Isolation Forest':'from sklearn.ensemble import IsolationForest as Model','MLP (Feed-forward NN)':'from sklearn.neural_network import MLPClassifier as Model'};
  const sup=M.params.target;return `# Dihasilkan oleh DM Tools (CRISP-DM) — template reproduksi model sungguhan
import pandas as pd, joblib
${map[M.algo]||'# TODO: impor algoritma '+M.algo+'\nfrom sklearn.ensemble import RandomForestClassifier as Model'}
${sup?'from sklearn.model_selection import train_test_split, cross_val_score\n':''}${M.params.imb==='smote'?'from imblearn.over_sampling import SMOTE\n':''}
df = pd.read_csv("cleaned_dataset.csv")
${sup?`X = df.drop(columns=["${sup}"]).select_dtypes("number")
y = df["${sup}"]
X_tr, X_te, y_tr, y_te = train_test_split(X, y, train_size=${(M.params.split||80)/100}, random_state=${M.params.seed||42})
${M.params.imb==='smote'?'X_tr, y_tr = SMOTE(random_state=42).fit_resample(X_tr, y_tr)\n':''}model = Model()
model.fit(X_tr, y_tr)
print("Score:", model.score(X_te, y_te))
${M.params.cv?`print("CV:", cross_val_score(Model(), X, y, cv=${M.params.cv}).mean())`:''}`:`X = df.select_dtypes("number")
model = Model(${M.params.k?'n_clusters='+M.params.k:''})
labels = model.fit_predict(X)`}
joblib.dump(model, "model.pkl")
`}
function laravelCode(){return `// routes/web.php
Route::view('/crisp-dm', 'crisp-dm');                     // simpan file ini sebagai resources/views/crisp-dm.blade.php
Route::post('/api/predict', [PredictController::class, 'predict']);

// app/Http/Controllers/PredictController.php
public function predict(Request $request) {
    $data = $request->validate(['features' => 'required|array']);
    // Opsi A: panggil microservice Python (FastAPI/Flask) yang memuat model.pkl
    $res = Http::post(env('ML_SERVICE_URL').'/predict', $data);
    return response()->json($res->json());
    // Opsi B: Process::run(['python3', base_path('ml/predict.py'), json_encode($data)])
}`}
function renderDeploy(){const M=S.model,e=S.eval;if(!M){$('depBody').innerHTML='<div class="alert warn">Belum ada model.</div>';return}
  const main=e.type==='cls'?['Accuracy',pct(e.accuracy)]:e.type==='reg'?['R²',e.r2.toFixed(3)]:e.type==='clu'?['Silhouette',e.sil.toFixed(3)]:e.type==='asso'?['Aturan',e.rules.length]:['Anomali',e.nAnom];
  $('depBody').innerHTML=`<div class="card"><h2>Ringkasan proyek & model</h2><div class="grid g4" style="margin-bottom:12px">${[['Proyek',esc(S.bu.name)],['Algoritma',M.algo],['Metode',M.method],[main[0],main[1]]].map(k=>`<div class="kpi"><b style="font-size:15px">${k[1]}</b><span>${k[0]}</span></div>`).join('')}</div>
    <div class="tbl"><table><tbody><tr><th>Tujuan bisnis</th><td style="white-space:normal">${esc(S.bu.goal)}</td></tr><tr><th>Dataset</th><td>${esc(S.file)} · ${S.raw.length} → ${S.data.length} baris</td></tr><tr><th>Preprocessing</th><td style="white-space:normal">${S.prep.log.map(l=>l.replace(/<[^>]+>/g,'')).join('; ')}</td></tr><tr><th>Fitur</th><td style="white-space:normal">${M.features.length} fitur${M.params.target?' · target: '+esc(M.params.target):''}</td></tr><tr><th>Parameter</th><td class="mono" style="white-space:normal">${esc(JSON.stringify(M.params))}</td></tr><tr><th>Dilatih</th><td>${M.trainedAt}</td></tr></tbody></table></div></div>
  <div class="card"><h2>Unduh artefak</h2><div class="row">
    <button class="btn pri" onclick="download('model_artifact.json',JSON.stringify(artifact(),null,2),'application/json')">⬇ Model artefak (.json)</button>
    <button class="btn" onclick="download('cleaned_dataset.csv',toCSV(S.data),'text/csv')">⬇ Dataset bersih (.csv)</button>
    <button class="btn" onclick="download('train_model.py',pyCode(),'text/x-python')">⬇ Script Python (.py)</button>
    <button class="btn" onclick="download('laporan_crisp_dm.html',report(),'text/html')">⬇ Laporan analisis (.html)</button>
    ${e.type==='cls'?`<button class="btn" onclick="download('evaluation_metrics.csv',toCSV(S.eval.per),'text/csv')">⬇ Metrik (.csv)</button>`:''}
    ${e.type==='asso'?`<button class="btn" onclick="download('association_rules.csv',toCSV(S.eval.rules),'text/csv')">⬇ Aturan (.csv)</button>`:''}
  </div><p style="color:var(--muted);font-size:11px;margin-top:8px">Artefak .json berisi konfigurasi & metrik (bukan bobot model nyata). Gunakan script Python untuk melatih model sungguhan (.pkl).</p></div>
  <div class="card"><div class="tabs" id="dTabs"><button class="on" data-t="impl">Implementasi</button><button data-t="use">Penggunaan</button><button data-t="int">Integrasi Laravel</button><button data-t="py">Python</button></div>
    <div data-p="impl"><ol style="padding-left:18px;line-height:1.9"><li>Unduh <b>dataset bersih</b> & <b>train_model.py</b>.</li><li>Jalankan <code>pip install pandas scikit-learn joblib${M.params.imb==='smote'?' imbalanced-learn':''}</code> lalu <code>python train_model.py</code> untuk menghasilkan <code>model.pkl</code>.</li><li>Bungkus model dengan API (FastAPI/Flask) endpoint <code>/predict</code>.</li><li>Terapkan pipeline preprocessing yang sama (lihat <i>preprocessing</i> di artefak JSON) pada data baru.</li><li>Monitor performa berkala; latih ulang bila metrik turun di bawah kriteria keberhasilan.</li></ol></div>
    <div data-p="use" class="hide"><pre>POST /api/predict
Content-Type: application/json
{ "features": { ${M.features.slice(0,4).map(f=>`"${f}": 0`).join(', ')} } }

→ 200 OK
{ "prediction": ${e.type==='cls'?'"'+(e.classes[0])+'"':e.type==='clu'?'"C1"':'0.0'}, "model": "${M.algo}" }</pre></div>
    <div data-p="int" class="hide"><pre>${esc(laravelCode())}</pre></div>
    <div data-p="py" class="hide"><pre>${esc(pyCode())}</pre></div></div>`;
  bindTabs('dTabs')}
function report(){const a=artifact();const e=S.eval;return `<!DOCTYPE html><html><head><meta charset="utf-8"><title>Laporan CRISP-DM</title><style>body{font-family:sans-serif;max-width:860px;margin:30px auto;color:#1a2740}h1{color:#2a3f63}td,th{border:1px solid #ccd;padding:6px}table{border-collapse:collapse}.sim{color:#b7791f}</style></head><body>
<h1>Laporan CRISP-DM: ${esc(S.bu.name)}</h1><p class="sim">⚠ Prototype — metrik ${e.real?'sebagian dihitung nyata':'disimulasikan'}.</p>
<h2>1. Business Understanding</h2><p><b>Tujuan:</b> ${esc(S.bu.goal)}<br><b>Masalah:</b> ${esc(S.bu.problem)}<br><b>Target:</b> ${esc(S.bu.target)}<br><b>Rencana:</b> ${esc(S.bu.plan||'-')}<br><b>Kriteria:</b> ${esc(S.bu.success)}</p>
<h2>2. Data Understanding</h2><p>${esc(S.file)}: ${S.raw.length} baris × ${S.cols.length} kolom.</p>
<h2>3. Data Preparation</h2><ul>${a.preprocessing.log.map(l=>`<li>${esc(l)}</li>`).join('')}</ul>
<h2>4. Modeling</h2><p>${S.model.algo} (${S.model.method})<br>Parameter: <code>${esc(JSON.stringify(S.model.params))}</code></p>
<h2>5. Evaluation</h2>${interp()}
<h2>6. Deployment</h2><p>Lihat artefak model_artifact.json dan train_model.py.</p></body></html>`}

/* ================= MISC ================= */
function bindTabs(id){const w=$(id);w.onclick=ev=>{const b=ev.target.closest('button');if(!b)return;w.querySelectorAll('button').forEach(x=>x.classList.toggle('on',x===b));w.parentElement.querySelectorAll('[data-p]').forEach(p=>p.classList.toggle('hide',p.dataset.p!==b.dataset.t))}}
function save(){try{localStorage.setItem('dmtools_bu',JSON.stringify(S.bu))}catch(e){}}
function resetAll(){if(!confirm('Reset seluruh proyek?'))return;localStorage.removeItem('dmtools_bu');location.reload()}
(function init(){
  const d=$('drop'),f=$('file');d.onclick=()=>f.click();f.onchange=()=>handleFile(f.files[0]);
  d.ondragover=e=>{e.preventDefault();d.classList.add('over')};d.ondragleave=()=>d.classList.remove('over');d.ondrop=e=>{e.preventDefault();d.classList.remove('over');handleFile(e.dataTransfer.files[0])};
  $('vizCol').onchange=drawViz;bindTabs('duTabs');
  try{const bu=JSON.parse(localStorage.getItem('dmtools_bu')||'{}');Object.entries(bu).forEach(([k,v])=>{if($('bu_'+k))$('bu_'+k).value=v})}catch(e){}
  renderNav();
})();
</script>
</body>
</html>