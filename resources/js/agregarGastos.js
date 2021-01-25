import Vue from 'vue'
import axios from "axios";

new Vue({
    el: '#app',
    data: {
        categorias: [],
        subcategorias: [],
        categoria_id: c_id,
        subcategoria_id: s_id
    },
    created: function() {

    },
    mounted: function() {
        axios.post("/gastos/getCategories").then(response => {
            this.categorias = response.data.categorias
            this.subcategorias = response.data.subcategorias
        });
    },
    methods: {
        changeCategory: function() {
            this.subcategoria_id = ""
        }
    }


});