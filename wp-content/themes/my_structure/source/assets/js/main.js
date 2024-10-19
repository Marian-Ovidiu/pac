import Alpine from 'alpinejs';
import axios from 'axios';
import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';

window.axios = axios;
window.Swiper = Swiper;
import ApiService from './Classes/ApiService.js';

window.Alpine = Alpine;
window.ApiService = ApiService;

Alpine.start();
Swiper.use([Navigation, Pagination, Autoplay]);
