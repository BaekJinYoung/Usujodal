import './bootstrap';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';

document.addEventListener('DOMContentLoaded', function () {
    var quill = new Quill('#editor', {
        theme: 'snow'
    });
});
