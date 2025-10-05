// Sidebar/Drawer
window.Drawer = (function(){
  function open(){ setState(true); }
  function close(){ setState(false); }
  function toggle(){
    const drawer = document.querySelector('.drawer');
    setState(!(drawer && drawer.classList.contains('active')));
  }
  function setState(active){
    const drawer = document.querySelector('.drawer');
    const overlay = document.querySelector('.drawer-overlay');
    if (!drawer || !overlay) return;
    drawer.classList.toggle('active', active);
    overlay.classList.toggle('active', active);
    document.body.style.overflow = active ? 'hidden' : '';
  }
  return { open, close, toggle };
})();
