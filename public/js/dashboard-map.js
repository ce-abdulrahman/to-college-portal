/* ============================
 * Dashboard Map – One-file Build
 * ============================ */

const PROVINCES_URL = window.PROVINCES_URL ?? '/dashboard/provinces/geojson';

const ROUTES = {
  universitiesByProvince: id => `/dashboard/provinces/${id}/universities`,
  collegesByUniversity:   id => `/dashboard/universities/${id}/colleges`,
  departmentsByCollege:   id => `/dashboard/colleges/${id}/departments`,
};

const HIGHLIGHT = { weightBoost: 2, durationMs: 700, color: null };

const HOTKEYS = {
  moveDown: ['ArrowDown'], moveUp: ['ArrowUp'], home: ['Home'], end: ['End'],
  zoom: ['Enter',' '], togglePoly: ['p','P'], focusPoint: ['g','G'],
  loadColleges: ['c','C'], loadDepartments: ['d','D'], routeTo: ['r','R']
};
const keyIs = (e, action)=> HOTKEYS[action]?.includes(e.key);

const ROUTE_STYLE = { color:'#7c3aed', weight:4, opacity:0.95, dashArray:null, showMarkers:true };
function setRouteDashed(isDashed=true){ ROUTE_STYLE.dashArray = isDashed ? '6 6' : null; }

/* Map */
const map = L.map('map').setView([34.7, 43.9], 6);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:19}).addTo(map);

/* Layers */
const provincesFG = L.featureGroup().addTo(map);
const uniPointsLG = L.layerGroup().addTo(map), uniPolysLG = L.layerGroup();
const colPointsLG = L.layerGroup().addTo(map), colPolysLG = L.layerGroup();
const depPointsLG = L.layerGroup().addTo(map), depPolysLG = L.layerGroup();

L.control.layers(null, {
  'University Polygons': uniPolysLG,
  'College Polygons'   : colPolysLG,
  'Department Polygons': depPolysLG,
}, {collapsed:true}).addTo(map);

/* Route & user */
let routeLayer = L.layerGroup().addTo(map), startEndLayer = L.layerGroup().addTo(map);
function clearRoute(){ routeLayer.clearLayers(); startEndLayer.clearLayers(); }

let userMarker=null, userAccCircle=null;
const userLayer = L.layerGroup().addTo(map);
function setUserLocation(lat,lng,acc){
  userLayer.clearLayers();
  userMarker = L.marker([lat,lng], {title:'You'});
  userAccCircle = L.circle([lat,lng], {radius:acc||0, color:'#2563eb', fillColor:'#60a5fa', fillOpacity:0.15, weight:1});
  userMarker.addTo(userLayer).bindTooltip('You are here', {direction:'top'});
  if (acc) userAccCircle.addTo(userLayer);
}
function locateUser(){
  if(!navigator.geolocation){ alert('Geolocation پشتیوانی ناکرێت'); return; }
  navigator.geolocation.getCurrentPosition(
    p => { const {latitude,longitude,accuracy}=p.coords; setUserLocation(latitude,longitude,accuracy);
           map.setView([latitude,longitude], Math.max(12,map.getZoom()), {animate:true}); },
    e => alert('نەتوانرا شوێنت بدۆزرێتەوە: '+e.message),
    { enableHighAccuracy:true, timeout:10000, maximumAge:30000 }
  );
}

/* Controls */
const MyLoc = L.Control.extend({
  onAdd(){ const b=L.DomUtil.create('button','leaflet-bar'); b.title='My Location';
    b.style.cssText='background:#fff;padding:8px;border:1px solid #ddd;cursor:pointer'; b.innerHTML='📍';
    L.DomEvent.on(b,'click',e=>{L.DomEvent.stop(e); locateUser();}); return b; }, onRemove(){}});
map.addControl(new MyLoc({position:'topleft'}));

const RouteCtrl = L.Control.extend({
  onAdd(){ const wrap=L.DomUtil.create('div','leaflet-bar'); wrap.style.cssText='background:#fff;display:grid;gap:6px;padding:6px;border:1px solid #ddd';
    const clearBtn=L.DomUtil.create('button','',wrap); clearBtn.textContent='Clear Route'; clearBtn.style.cssText='cursor:pointer;font-size:12px';
    clearBtn.onclick=(e)=>{ L.DomEvent.stop(e); clearRoute(); };
    const dashBtn=L.DomUtil.create('button','',wrap); dashBtn.textContent='Dashed On/Off'; dashBtn.style.cssText='cursor:pointer;font-size:12px';
    dashBtn.onclick=(e)=>{ L.DomEvent.stop(e); setRouteDashed(ROUTE_STYLE.dashArray==null); };
    return wrap; }, onRemove(){}});
map.addControl(new RouteCtrl({position:'topleft'}));

/* Sidebar DOM */
const $title = document.getElementById('province-title');
const $list  = document.getElementById('inst-list');

/* Indexes */
const idx = { uni:{point:new Map(), poly:new Map()}, col:{point:new Map(), poly:new Map()}, dep:{point:new Map(), poly:new Map()} };
function clearIdx(level){
  if(!level||level==='uni'){ idx.uni.point.clear(); idx.uni.poly.clear(); }
  if(!level||level==='col'){ idx.col.point.clear(); idx.col.poly.clear(); }
  if(!level||level==='dep'){ idx.dep.point.clear(); idx.dep.poly.clear(); }
}

/* Icons */
const SVG = {
  pin:`<svg viewBox="0 0 24 24" width="16" height="16"><path d="M12 22s7-5.33 7-12a7 7 0 1 0-14 0c0 6.67 7 12 7 12Z" fill="none" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="10" r="2.8" fill="currentColor"/></svg>`,
  polygon:`<svg viewBox="0 0 24 24" width="16" height="16"><path d="M7 4l10 2 3 6-4 6H8L4 12l3-8Z" fill="none" stroke="currentColor" stroke-width="1.8"/><circle cx="7" cy="4" r="1.2" fill="currentColor"/><circle cx="17" cy="6" r="1.2" fill="currentColor"/><circle cx="20" cy="12" r="1.2" fill="currentColor"/><circle cx="16" cy="18" r="1.2" fill="currentColor"/><circle cx="8" cy="18" r="1.2" fill="currentColor"/><circle cx="4" cy="12" r="1.2" fill="currentColor"/></svg>`,
  zoom:`<svg viewBox="0 0 24 24" width="16" height="16"><circle cx="11" cy="11" r="7" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M20 20l-3.5-3.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`,
  start:`<svg viewBox="0 0 24 24" width="20" height="20"><circle cx="12" cy="12" r="9" fill="#10b981" opacity="0.15"/><path d="M8 16V8l8 4-8 4Z" fill="#10b981"/><circle cx="12" cy="12" r="9" stroke="#10b981" fill="none" stroke-width="1.5"/></svg>`,
  end:`<svg viewBox="0 0 24 24" width="20" height="20"><circle cx="12" cy="12" r="9" fill="#ef4444" opacity="0.15"/><path d="M8 9l4 4 4-4" stroke="#ef4444" stroke-width="2" fill="none"/><path d="M8 13l4 4 4-4" stroke="#ef4444" stroke-width="2" fill="none"/><circle cx="12" cy="12" r="9" stroke="#ef4444" fill="none" stroke-width="1.5"/></svg>`
};
function svgDivIcon(svg,title=''){ return L.divIcon({className:'route-endpoint', html:`<div title="${title}" style="display:grid;place-items:center">${svg}</div>`, iconSize:[22,22], iconAnchor:[11,11]}); }

/* Helpers */
function safeJSON(res){ return res.text().then(t=>{ try{return JSON.parse(t);}catch{ throw new Error(`Invalid JSON: ${t.slice(0,300)}`);} }); }
function focusLayer(layer){
  if(!layer) return;
  if(layer.getBounds) map.fitBounds(layer.getBounds(), {padding:[22,22]});
  else if(layer.getLatLng){ map.setView(layer.getLatLng(), Math.max(12,map.getZoom()), {animate:true}); layer.openPopup?.(); }
  if(layer.setStyle){
    const base={...(layer.options||{})}, w=base.weight??2;
    const hi={ weight:w+HIGHLIGHT.weightBoost }; if(HIGHLIGHT.color) hi.color = HIGHLIGHT.color;
    layer.setStyle(hi); setTimeout(()=> layer.setStyle({ weight:w, color:base.color ?? layer.options.color }), HIGHLIGHT.durationMs);
  }
}
const palette=['#1f77b4','#ff7f0e','#2ca02c','#d62728','#9467bd','#8c564b','#e377c2','#7f7f7f','#bcbd22','#17becf'];
let uniC=0,colC=0,depC=0; const nextUni=()=>palette[(uniC++)%palette.length], nextCol=()=>palette[(colC++)%palette.length], nextDep=()=>palette[(depC++)%palette.length];

function buildPoint(item,color){
  if(item.lat==null || item.lng==null) return null;
  const html=`<div style="min-width:220px"><strong>${item.name??'—'}</strong><br>${item.lat!=null?`<small>Lat: ${item.lat}, Lng: ${item.lng}</small><br>`:''}${item.image?`<img src="${item.image}" style="width:100%;border-radius:8px;margin-top:6px">`:''}</div>`;
  return L.circleMarker([item.lat,item.lng],{radius:7,color,fillOpacity:0.9}).bindPopup(html);
}
function buildPoly(geojson,item,color){
  if(!geojson) return null;
  const html=`<div style="min-width:220px"><strong>${item.name??'—'}</strong><br>${item.image?`<img src="${item.image}" style="width:100%;border-radius:8px;margin-top:6px">`:''}</div>`;
  return L.geoJSON(geojson,{style:{color,weight:2,fillOpacity:0.25}}).bindPopup(html);
}
function centroidOfGeoJSON(g){
  try{ const geom=g.geometry||g; if(!geom) return null; const {type,coordinates:c}=geom;
    if(type==='Point') return [c[1],c[0]];
    const ring = type==='Polygon'? c[0] : (type==='MultiPolygon'? c[0]?.[0] : null); if(!ring) return null;
    let x=0,y=0,n=0; for(const [lng,lat] of ring){ x+=lng; y+=lat; n++; } return n? [y/n, x/n] : null;
  }catch{ return null; }
}

/* Routing */
async function drawRoute(fromLL,toLL){
  const a=`${fromLL.lng},${fromLL.lat}`, b=`${toLL.lng},${toLL.lat}`;
  const url=`https://router.project-osrm.org/route/v1/driving/${a};${b}?overview=full&geometries=geojson`;
  try{
    const res=await fetch(url); if(!res.ok) throw new Error(await res.text());
    const data=await res.json(); const line=data.routes?.[0]?.geometry; if(!line) throw new Error('No route found');
    clearRoute();
    const pl=L.geoJSON(line,{style:{color:ROUTE_STYLE.color,weight:ROUTE_STYLE.weight,opacity:ROUTE_STYLE.opacity,dashArray:ROUTE_STYLE.dashArray}}).addTo(routeLayer);
    if(ROUTE_STYLE.showMarkers){
      const s=L.marker([fromLL.lat,fromLL.lng],{icon:svgDivIcon(SVG.start,'Start (You)')}).bindTooltip('Start (You)',{direction:'top'});
      const e=L.marker([toLL.lat,toLL.lng],{icon:svgDivIcon(SVG.end,'Destination')}).bindTooltip('Destination',{direction:'top'});
      s.addTo(startEndLayer); e.addTo(startEndLayer);
    }
    map.fitBounds(pl.getBounds().pad(0.2));
  }catch(err){ alert('نەتوانرا ڕێگا بدۆزرێتەوە: '+err.message); }
}
function routeToLatLng(targetLL){
  if(!userMarker){ alert('سەرەتا 📍 My Location داگرە'); return; }
  const from=userMarker.getLatLng(); drawRoute({lat:from.lat,lng:from.lng},{lat:targetLL.lat,lng:targetLL.lng});
}
function latLngOfItem(type,id){
  const pt=(type==='uni')?idx.uni.point.get(id):(type==='col')?idx.col.point.get(id):idx.dep.point.get(id);
  if(pt) return pt.getLatLng();
  const poly=(type==='uni')?idx.uni.poly.get(id):(type==='col')?idx.col.poly.get(id):idx.dep.poly.get(id);
  if(poly){ let cLL=null; poly.eachLayer?.(l=>{ if(!cLL && l.getBounds){ const c=l.getBounds().getCenter(); cLL={lat:c.lat,lng:c.lng}; }});
    if(!cLL){ const gj=poly.toGeoJSON?.(); const c=centroidOfGeoJSON(gj); if(c) cLL={lat:c[0],lng:c[1]}; } return cLL; }
  return null;
}

/* Renderers */
function renderUniversitiesUI(title, items){
  $title.textContent = title || '—';
  const html = items.length ? items.map(u=>{
    const hasPoint=u.lat!=null && u.lng!=null, hasGeo=!!u.geojson;
    return `
      <li class="flex items-start justify-between gap-2 p-2 rounded hover:bg-gray-50" data-type="uni" data-id="${u.id}" tabindex="0" role="option">
        <div class="min-w-0">
          <div class="font-medium truncate">${u.name ?? 'University'}</div>
        </div>
        <div class="shrink-0 flex items-center gap-1">
          ${hasPoint? `<button class="inline-flex items-center gap-1 px-2 py-1 text-xs border rounded hover:bg-gray-50 text-blue-600" data-action="focus" data-type="uni" data-geo="point" data-id="${u.id}">${SVG.pin}<span class="hidden sm:inline">Point</span></button>`:''}
          ${hasGeo?   `<button class="inline-flex items-center gap-1 px-2 py-1 text-xs border rounded hover:bg-gray-50 text-emerald-700" data-action="toggle-poly" data-type="uni" data-id="${u.id}">${SVG.polygon}<span class="hidden sm:inline">Polygon</span></button>`:''}
          ${(hasPoint||hasGeo)? `<button class="inline-flex items-center gap-1 px-2 py-1 text-xs border rounded hover:bg-gray-50" data-action="zoom" data-type="uni" data-id="${u.id}">${SVG.zoom}<span class="hidden sm:inline">Zoom</span></button>`:''}
          ${(hasPoint||hasGeo)? `<button class="inline-flex items-center gap-1 px-2 py-1 text-xs border rounded hover:bg-gray-50" data-action="route" data-type="uni" data-id="${u.id}">🧭<span class="hidden sm:inline">Route</span></button>`:''}
          <button class="inline-flex items-center gap-1 px-2 py-1 text-xs border rounded hover:bg-gray-50" data-action="load-colleges" data-id="${u.id}">Colleges</button>
        </div>
      </li>`;
  }).join('') : `<li class="text-gray-400">هیچ زانکۆیەک نەدۆزرایەوە.</li>`;
  $list.innerHTML = html;
}
function renderCollegesUI(title, items){
  $title.textContent = title || '—';
  const html = items.length ? items.map(co=>{
    const hasPoint=co.lat!=null && co.lng!=null, hasGeo=!!co.geojson;
    return `
      <li class="flex items-start justify-between gap-2 p-2 rounded hover:bg-gray-50" data-type="col" data-id="${co.id}" tabindex="0" role="option">
        <div class="min-w-0">
          <div class="font-medium truncate">${co.name ?? 'College'}</div>
          ${hasPoint? `<div class="text-xs text-gray-500">(${co.lat}, ${co.lng})</div>`:''}
        </div>
        <div class="shrink-0 flex items-center gap-1">
          ${hasPoint? `<button class="inline-flex items-center gap-1 px-2 py-1 text-xs border rounded hover:bg-gray-50 text-blue-600" data-action="focus" data-type="col" data-geo="point" data-id="${co.id}">${SVG.pin}<span class="hidden sm:inline">Point</span></button>`:''}
          ${hasGeo?   `<button class="inline-flex items-center gap-1 px-2 py-1 text-xs border rounded hover:bg-gray-50 text-emerald-700" data-action="toggle-poly" data-type="col" data-id="${co.id}">${SVG.polygon}<span class="hidden sm:inline">Polygon</span></button>`:''}
          ${(hasPoint||hasGeo)? `<button class="inline-flex items-center gap-1 px-2 py-1 text-xs border rounded hover:bg-gray-50" data-action="zoom" data-type="col" data-id="${co.id}">${SVG.zoom}<span class="hidden sm:inline">Zoom</span></button>`:''}
          ${(hasPoint||hasGeo)? `<button class="inline-flex items-center gap-1 px-2 py-1 text-xs border rounded hover:bg-gray-50" data-action="route" data-type="col" data-id="${co.id}">🧭<span class="hidden sm:inline">Route</span></button>`:''}
          <button class="inline-flex items-center gap-1 px-2 py-1 text-xs border rounded hover:bg-gray-50" data-action="load-departments" data-id="${co.id}">Departments</button>
        </div>
      </li>`;
  }).join('') : `<li class="text-gray-400">هیچ کۆلێژێک نەدۆزرایەوە.</li>`;
  $list.innerHTML = html;
}
function renderDepartmentsUI(title, items){
  $title.textContent = title || '—';
  const html = items.length ? items.map(dp=>{
    const hasPoint=dp.lat!=null && dp.lng!=null, hasGeo=!!dp.geojson;
    return `
      <li class="flex items-start justify-between gap-2 p-2 rounded hover:bg-gray-50" data-type="dep" data-id="${dp.id}" tabindex="0" role="option">
        <div class="min-w-0">
          <div class="font-medium truncate">${dp.name ?? 'Department'}</div>
          ${hasPoint? `<div class="text-xs text-gray-500">(${dp.lat}, ${dp.lng})</div>`:''}
        </div>
        <div class="shrink-0 flex items-center gap-1">
          ${hasPoint? `<button class="inline-flex items-center gap-1 px-2 py-1 text-xs border rounded hover:bg-gray-50 text-blue-600" data-action="focus" data-type="dep" data-geo="point" data-id="${dp.id}">${SVG.pin}<span class="hidden sm:inline">Point</span></button>`:''}
          ${hasGeo?   `<button class="inline-flex items-center gap-1 px-2 py-1 text-xs border rounded hover:bg-gray-50 text-emerald-700" data-action="toggle-poly" data-type="dep" data-id="${dp.id}">${SVG.polygon}<span class="hidden sm:inline">Polygon</span></button>`:''}
          ${(hasPoint||hasGeo)? `<button class="inline-flex items-center gap-1 px-2 py-1 text-xs border rounded hover:bg-gray-50" data-action="zoom" data-type="dep" data-id="${dp.id}">${SVG.zoom}<span class="hidden sm:inline">Zoom</span></button>`:''}
          ${(hasPoint||hasGeo)? `<button class="inline-flex items-center gap-1 px-2 py-1 text-xs border rounded hover:bg-gray-50" data-action="route" data-type="dep" data-id="${dp.id}">🧭<span class="hidden sm:inline">Route</span></button>`:''}
        </div>
      </li>`;
  }).join('') : `<li class="text-gray-400">هیچ بەشێک نەدۆزرایەوە.</li>`;
  $list.innerHTML = html;
}

/* Sidebar events */
$list.addEventListener('click', (e)=>{
  const b=e.target.closest('button[data-action]'); const li=e.target.closest('li[role="option"]');
  if(li){ const arr=items(); setSelection(arr.indexOf(li)); }
  if(!b) return;
  const action=b.dataset.action, type=b.dataset.type, id=+b.dataset.id;

  if(action==='focus'){
    const LAYER = type==='uni'? idx.uni.point.get(id) : type==='col'? idx.col.point.get(id) : idx.dep.point.get(id);
    if(LAYER) focusLayer(LAYER);
  }
  if(action==='toggle-poly'){
    let poly, group;
    if(type==='uni'){ poly=idx.uni.poly.get(id); group=uniPolysLG; }
    else if(type==='col'){ poly=idx.col.poly.get(id); group=colPolysLG; }
    else { poly=idx.dep.poly.get(id); group=depPolysLG; }
    if(!poly) return;
    const exists=group.getLayers().includes(poly);
    exists? group.removeLayer(poly) : poly.addTo(group);
    if(!exists) focusLayer(poly);
  }
  if(action==='zoom'){
    let poly, pt;
    if(type==='uni'){ poly=idx.uni.poly.get(id); pt=idx.uni.point.get(id); }
    else if(type==='col'){ poly=idx.col.poly.get(id); pt=idx.col.point.get(id); }
    else { poly=idx.dep.poly.get(id); pt=idx.dep.point.get(id); }
    focusLayer(poly || pt);
  }
  if(action==='route'){
    const ll = latLngOfItem(type,id);
    if(!ll){ alert('هیچ Point/Polygon بۆ ئەم ئایتەمە نەدۆزرایەوە'); return; }
    routeToLatLng(ll);
  }
  if(action==='load-colleges')   loadColleges(id);
  if(action==='load-departments') loadDepartments(id);
});

/* Keyboard navigation */
const LIST=$list; let selIndex=-1;
function items(){ return Array.from(LIST.querySelectorAll('li[role="option"]')); }
function setSelection(i){ const arr=items(); if(!arr.length){ selIndex=-1; return; }
  i=Math.max(0,Math.min(i,arr.length-1)); arr.forEach(el=>el.classList.remove('is-selected'));
  const el=arr[i]; el.classList.add('is-selected'); el.focus({preventScroll:false}); selIndex=i; }
function moveSelection(delta){ const arr=items(); if(!arr.length) return; if(selIndex===-1) selIndex=0; setSelection(selIndex+delta); }
function zoomItem(el){ if(!el) return; const type=el.dataset.type, id=+el.dataset.id;
  let poly,pt; if(type==='uni'){ poly=idx.uni.poly.get(id); pt=idx.uni.point.get(id); }
  else if(type==='col'){ poly=idx.col.poly.get(id); pt=idx.col.point.get(id); }
  else { poly=idx.dep.poly.get(id); pt=idx.dep.point.get(id); }
  focusLayer(poly||pt);
}
function togglePolygon(el){ if(!el) return; const type=el.dataset.type, id=+el.dataset.id;
  let poly,group; if(type==='uni'){ poly=idx.uni.poly.get(id); group=uniPolysLG; }
  else if(type==='col'){ poly=idx.col.poly.get(id); group=colPolysLG; }
  else { poly=idx.dep.poly.get(id); group=depPolysLG; }
  if(!poly) return; const exists=group.getLayers().includes(poly); exists? group.removeLayer(poly): poly.addTo(group); if(!exists) focusLayer(poly);
}
function focusPoint(el){ if(!el) return; const type=el.dataset.type, id=+el.dataset.id;
  const m = type==='uni'? idx.uni.point.get(id) : type==='col'? idx.col.point.get(id) : idx.dep.point.get(id); if(m) focusLayer(m);
}
function routeSelected(el){ if(!el) return; const type=el.dataset.type, id=+el.dataset.id;
  const ll=latLngOfItem(type,id); if(!ll){ alert('هیچ Point/Polygon بۆ ئەم ئایتەمە نەدۆزرایەوە'); return; } routeToLatLng(ll);
}
LIST.addEventListener('dblclick', e=>{ const el=e.target.closest('li[role="option"]'); if(!el) return; zoomItem(el); });
LIST.addEventListener('keydown', (e)=>{
  const arr=items(); if(!arr.length) return; if(selIndex===-1) selIndex=0;
  if (keyIs(e,'moveDown')) { e.preventDefault(); moveSelection(1); }
  else if (keyIs(e,'moveUp')) { e.preventDefault(); moveSelection(-1); }
  else if (keyIs(e,'home')) { e.preventDefault(); setSelection(0); }
  else if (keyIs(e,'end')) { e.preventDefault(); setSelection(arr.length-1); }
  else if (keyIs(e,'zoom')) { e.preventDefault(); zoomItem(arr[selIndex]); }
  else if (keyIs(e,'togglePoly')) { e.preventDefault(); togglePolygon(arr[selIndex]); }
  else if (keyIs(e,'focusPoint')) { e.preventDefault(); focusPoint(arr[selIndex]); }
  else if (keyIs(e,'loadColleges')) { const el=arr[selIndex]; if(el?.dataset.type==='uni'){ e.preventDefault(); loadColleges(+el.dataset.id); } }
  else if (keyIs(e,'loadDepartments')) { const el=arr[selIndex]; if(el?.dataset.type==='col'){ e.preventDefault(); loadDepartments(+el.dataset.id); } }
  else if (keyIs(e,'routeTo')) { e.preventDefault(); routeSelected(arr[selIndex]); }
});

/* Data Loading */
async function loadProvinces(){
  const res=await fetch(PROVINCES_URL); const gj=await res.json();
  L.geoJSON(gj, {
    style:{color:'#666',weight:1.5,fillOpacity:0.18},
    onEachFeature:(f,layer)=>{
      const p=f.properties||{};
      layer.bindTooltip(p.name||'Province',{sticky:true});
      layer.on('click', ()=>{
        $title.textContent = `${p.name ?? '—'} ${p.name_en ? `| ${p.name_en}` : ''}`;
        loadUniversities(p.id, p);
        try{ map.fitBounds(layer.getBounds(), {padding:[16,16]}); }catch{}
      });
    }
  }).addTo(provincesFG);
  try{ map.fitBounds(provincesFG.getBounds(), {padding:[22,22]}); }catch{}
}
async function loadUniversities(provinceId, props={}){
  uniPointsLG.clearLayers(); uniPolysLG.clearLayers();
  colPointsLG.clearLayers(); colPolysLG.clearLayers();
  depPointsLG.clearLayers(); depPolysLG.clearLayers();
  clearIdx('uni'); clearIdx('col'); clearIdx('dep'); uniC=colC=depC=0;

  const res=await fetch(ROUTES.universitiesByProvince(provinceId),{headers:{'Accept':'application/json'}});
  if(!res.ok){ alert('هەڵە لە وەرگرتنی زانیاری زانکۆکان'); return; }
  const data=await safeJSON(res); const items=data.items||[];
  renderUniversitiesUI(`${props.name??'Province'} ${props.name_en?`| ${props.name_en}`:''}`,items);

  items.forEach(it=>{
  const color = nextUni();
  const pt = buildPoint(it, color, 'uni'); if (pt){ pt.addTo(uniPointsLG); idx.uni.point.set(it.id, pt); }
  const poly = buildPoly(it.geojson, it, color, 'uni'); if (poly){ idx.uni.poly.set(it.id, poly); }
});
  if(uniPointsLG.getLayers().length){ try{ map.fitBounds(uniPointsLG.getBounds(), {padding:[22,22]}); }catch{} }
}
async function loadColleges(universityId){
  colPointsLG.clearLayers(); colPolysLG.clearLayers();
  depPointsLG.clearLayers(); depPolysLG.clearLayers();
  clearIdx('col'); clearIdx('dep'); colC=depC=0;

  const res=await fetch(ROUTES.collegesByUniversity(universityId),{headers:{'Accept':'application/json'}});
  if(!res.ok){ alert('هەڵە لە وەرگرتنی زانیاری کۆلێژەکان'); return; }
  const data=await safeJSON(res); const items=data.items||[];
  renderCollegesUI(`University #${universityId}`, items);

  items.forEach(it=>{
    const color = nextCol();
    const pt = buildPoint(it, color, 'col'); if (pt){ pt.addTo(colPointsLG); idx.col.point.set(it.id, pt); }
    const poly = buildPoly(it.geojson, it, color, 'col'); if (poly){ idx.col.poly.set(it.id, poly); }
});
  if(colPointsLG.getLayers().length){ try{ map.fitBounds(colPointsLG.getBounds(), {padding:[22,22]}); }catch{} }
}
async function loadDepartments(collegeId){
  depPointsLG.clearLayers(); depPolysLG.clearLayers();
  clearIdx('dep'); depC=0;

  try{
    const res = await fetch(ROUTES.departmentsByCollege(collegeId), { headers:{'Accept':'application/json'} });
    const txt = await res.text();
    if (!res.ok) {
      console.error('Departments fetch failed:', res.status, txt);
      alert('هەڵە لە وەرگرتنی زانیاری بەشەکان.');
      return;
    }
    const data = JSON.parse(txt);
    const items = data.items || [];
    renderDepartmentsUI(`College #${collegeId}`, items);

    items.forEach(it=>{
  const color = nextDep();
  const pt = buildPoint(it, color, 'dep'); if (pt){ pt.addTo(depPointsLG); idx.dep.point.set(it.id, pt); }
  const poly = buildPoly(it.geojson, it, color, 'dep'); if (poly){ idx.dep.poly.set(it.id, poly); }
});

    if (depPointsLG.getLayers().length){ try{ map.fitBounds(depPointsLG.getBounds(), {padding:[22,22]}); }catch{} }
  } catch (e) {
    console.error('Departments exception:', e);
    alert('هەڵەیەکی چاوسڕاوی لە وەرگرتنی بەشەکان ڕوویدا.');
  }
}


// ----------------------------------
function fmt(val){ return (val===null || val===undefined || val==='') ? '—' : val; }
function imgTag(src){ return src ? `<img src="${src}" style="width:100%;border-radius:10px;margin-top:8px">` : ''; }

function depPopupHTML(d){
  const breadcrumb = [
    fmt(d.province_name), fmt(d.university_name), fmt(d.college_name), fmt(d.name)
  ].filter(Boolean).join(' / ');

  // وێنەکان: پێشوویان بچووکرە (thumbnail)
  const thumbs = [d.province_image, d.university_image, d.college_image]
    .filter(Boolean)
    .map(src=>`<img src="${src}" style="width:54px;height:54px;object-fit:cover;border-radius:8px;border:1px solid #e5e7eb">`)
    .join('');

  return `
    <div style="min-width:260px">
      <div class="text-xs text-gray-500">${breadcrumb}</div>
      <div style="display:flex; align-items:center; gap:8px; margin:6px 0 8px;">
        ${thumbs}
      </div>

      <div style="font-weight:600; font-size:15px; margin-top:2px">${fmt(d.name)} ${d.name_en? `<span style="color:#6b7280;font-weight:500">| ${d.name_en}</span>`:''}</div>

      <div style="margin-top:6px; font-size:13px; line-height:1.4">
        <div><b>نمرەی ناو پاڕێزگا:</b> ${fmt(d.local_score)}</div>
        <div><b>نمرەی دەرەوەی پارێزگا:</b> ${fmt(d.external_score)}</div>
        <div><b>لق :</b> ${fmt(d.type)}</div>
        <div><b>ڕەگەز :</b> ${fmt(d.sex)}</div>
        ${d.lat!=null && d.lng!=null ? `<div style="color:#6b7280"><small>Lat: ${d.lat}, Lng: ${d.lng}</small></div>`:''}
      </div>

      ${d.description? `<div style="margin-top:8px; font-size:13px">${d.description}</div>`:''}
      ${imgTag(d.image)}
    </div>
  `;
}

function simplePopupHTML(item){
  return `
    <div style="min-width:220px">
      <strong>${fmt(item.name)}</strong>
      ${item.name_en? `<div style="color:#6b7280"><small>${item.name_en}</small></div>`:''}
      ${item.lat!=null? `<div style="color:#6b7280"><small>Lat: ${item.lat}, Lng: ${item.lng}</small></div>`:''}
      ${imgTag(item.image)}
    </div>
  `;
}

function buildPoint(item, color, kind){
  if (item.lat == null || item.lng == null) return null;
  const html = (kind==='dep') ? depPopupHTML(item) : simplePopupHTML(item);
  return L.circleMarker([item.lat, item.lng], {radius:7, color, fillOpacity:0.9}).bindPopup(html);
}

function buildPoly(geojson, item, color, kind){
  if (!geojson) return null;
  const html = (kind==='dep') ? depPopupHTML(item) : simplePopupHTML(item);
  return L.geoJSON(geojson, {style:{color, weight:2, fillOpacity:0.25}}).bindPopup(html);
}

// ----------------------------------


/* Boot */
(async function boot(){ await loadProvinces(); })();
