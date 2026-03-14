import './bootstrap';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay, EffectFade } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/effect-fade';

// Make Swiper available globally BEFORE Alpine starts
// (Alpine init() in blade components needs these)
window.Swiper = Swiper;
window.SwiperModules = [Navigation, Pagination, Autoplay, EffectFade];

// Initialize Alpine with Collapse plugin
Alpine.plugin(collapse);
window.Alpine = Alpine;
Alpine.start();

