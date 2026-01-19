window.MapManager = (function () {
    return {
        init(cfg) {
            const el = document.getElementById(cfg.id);
            if (!el) return;

            const map = L.map(cfg.id).setView(cfg.center, cfg.zoom || 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
                attribution:'© OpenStreetMap'
            }).addTo(map);

            let marker = null;

            map.on('click', e => {
                if (marker) map.removeLayer(marker);
                marker = L.marker(e.latlng).addTo(map);
                if (cfg.lat) $(cfg.lat).val(e.latlng.lat);
                if (cfg.lng) $(cfg.lng).val(e.latlng.lng);
            });

            return map;
        }
    };
})();
