import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect';
import axios from 'axios';
import ApiService from './Classes/ApiService.js';
import Swiper from 'swiper';
import 'swiper/css';


window.axios = axios;
window.Alpine = Alpine;
window.ApiService = ApiService;
window.Swiper = Swiper;

Alpine.plugin(intersect);
Alpine.start();
