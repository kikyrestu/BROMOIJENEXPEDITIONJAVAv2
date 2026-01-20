import './bootstrap';
import Alpine from 'alpinejs';
import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

// Initialize Alpine
window.Alpine = Alpine;
Alpine.start();

// Initialize Swiper (Globally available or initialized in components)
window.Swiper = Swiper;
window.SwiperModules = { Navigation, Pagination, Autoplay };
