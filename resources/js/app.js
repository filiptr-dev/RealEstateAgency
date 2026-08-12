import Alpine from 'alpinejs';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

// Fix default marker icon paths broken by Vite bundling
import markerIcon from 'leaflet/dist/images/marker-icon.png';
import markerIcon2x from 'leaflet/dist/images/marker-icon-2x.png';
import markerShadow from 'leaflet/dist/images/marker-shadow.png';

window.L = L;

delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconUrl: markerIcon,
    iconRetinaUrl: markerIcon2x,
    shadowUrl: markerShadow,
});

window.Alpine = Alpine;
Alpine.start();
